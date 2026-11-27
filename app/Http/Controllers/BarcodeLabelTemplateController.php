<?php

namespace App\Http\Controllers;

use App\Models\BarcodeLabelTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BarcodeLabelTemplateController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => BarcodeLabelTemplate::query()
                ->orderBy('name')
                ->get(['id', 'name', 'updated_at']),
        ]);
    }

    public function show(BarcodeLabelTemplate $barcodeTemplate): JsonResponse
    {
        return response()->json(['data' => $barcodeTemplate]);
    }

    public function store(Request $request): JsonResponse
    {
        $template = BarcodeLabelTemplate::create($this->validatedData($request));

        return response()->json(['data' => $template], 201);
    }

    public function update(Request $request, BarcodeLabelTemplate $barcodeTemplate): JsonResponse
    {
        $barcodeTemplate->update($this->validatedData($request, $barcodeTemplate));

        return response()->json(['data' => $barcodeTemplate->fresh()]);
    }

    public function destroy(BarcodeLabelTemplate $barcodeTemplate): JsonResponse
    {
        $barcodeTemplate->delete();

        return response()->json([], 204);
    }

    private function validatedData(Request $request, ?BarcodeLabelTemplate $template = null): array
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('barcode_label_templates', 'name')->ignore($template?->id),
            ],
            'layout' => ['required', 'array:width,height,elements'],
            'layout.width' => ['required', 'numeric', 'between:10,300'],
            'layout.height' => ['required', 'numeric', 'between:10,300'],
            'layout.elements' => ['required', 'array', 'min:1', 'max:100'],
            'layout.elements.*' => [
                'required',
                'array:id,type,text,x,y,width,height,align,verticalAlign,fontSize,fontWeight,fixedSize',
            ],
            'layout.elements.*.id' => ['required', 'string', 'max:100'],
            'layout.elements.*.type' => [
                'required',
                Rule::in(['productName', 'barcode', 'barcodeValue', 'date', 'time', 'text', 'rectangle']),
            ],
            'layout.elements.*.text' => ['present', 'nullable', 'string', 'max:500'],
            'layout.elements.*.x' => ['required', 'numeric', 'between:0,100'],
            'layout.elements.*.y' => ['required', 'numeric', 'between:0,100'],
            'layout.elements.*.width' => ['required', 'numeric', 'gt:0', 'lte:100'],
            'layout.elements.*.height' => ['required', 'numeric', 'gt:0', 'lte:100'],
            'layout.elements.*.align' => ['sometimes', Rule::in(['left', 'center', 'right'])],
            'layout.elements.*.verticalAlign' => ['sometimes', Rule::in(['top', 'center', 'bottom'])],
            'layout.elements.*.fontSize' => ['sometimes', 'numeric', 'between:1,72'],
            'layout.elements.*.fontWeight' => ['sometimes', Rule::in([300, 400, 500, 600, 700, 800, 900])],
            'layout.elements.*.fixedSize' => ['sometimes', 'boolean'],
        ]);

        $validator->after(function ($validator) use ($request): void {
            $elements = $request->input('layout.elements');
            if (!is_array($elements)) {
                return;
            }

            $ids = [];
            foreach ($elements as $index => $element) {
                if (!is_array($element)) {
                    continue;
                }

                if (isset($element['id'])) {
                    if (in_array($element['id'], $ids, true)) {
                        $validator->errors()->add("layout.elements.$index.id", 'Element IDs must be unique.');
                    }
                    $ids[] = $element['id'];
                }

                foreach (['x' => 'width', 'y' => 'height'] as $position => $size) {
                    if (
                        isset($element[$position], $element[$size]) &&
                        is_numeric($element[$position]) &&
                        is_numeric($element[$size]) &&
                        (float) $element[$position] + (float) $element[$size] > 100
                    ) {
                        $validator->errors()->add(
                            "layout.elements.$index.$position",
                            'The element must fit inside the label.'
                        );
                    }
                }

                if (
                    array_key_exists('fixedSize', $element) &&
                    ($element['type'] ?? null) !== 'productName'
                ) {
                    $validator->errors()->add(
                        "layout.elements.$index.fixedSize",
                        'Fixed-size text fitting is only available for product names.'
                    );
                }

                if (in_array(($element['type'] ?? null), ['barcode', 'rectangle'], true)) {
                    continue;
                }

                foreach (['align', 'fontSize', 'fontWeight'] as $key) {
                    if (!array_key_exists($key, $element)) {
                        $validator->errors()->add(
                            "layout.elements.$index.$key",
                            'This setting is required for text elements.'
                        );
                    }
                }
            }
        });

        $validated = $validator->validate();
        foreach ($validated['layout']['elements'] as &$element) {
            $element['text'] = $element['text'] ?? '';
            if (!in_array(($element['type'] ?? null), ['barcode', 'rectangle'], true)) {
                $element['verticalAlign'] = $element['verticalAlign'] ?? 'top';
            }
        }
        unset($element);

        return $validated;
    }
}
