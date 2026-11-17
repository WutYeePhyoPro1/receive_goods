<?php

namespace App\Http\Controllers;

use App\Models\BarcodeLabelTemplate;
use Illuminate\Http\Request;

class BarcodeLabelController extends Controller
{
    private function defaultSettings(): array
    {
        return [
            'fields' => [
                'product_name' => [
                    'enabled' => true,
                    'x' => 2,
                    'y' => 1,
                    'font_size' => 10,
                    'align' => 'center',
                    'bold' => true,
                ],
                'sku' => [
                    'enabled' => true,
                    'x' => 2,
                    'y' => 6,
                    'font_size' => 8,
                    'align' => 'center',
                    'bold' => false,
                ],
                'pc' => [
                    'enabled' => true,
                    'x' => 2,
                    'y' => 22,
                    'font_size' => 8,
                    'align' => 'center',
                    'bold' => false,
                ],
                'date' => [
                    'enabled' => true,
                    'x' => 2,
                    'y' => 26,
                    'font_size' => 7,
                    'align' => 'left',
                    'bold' => false,
                ],
                'time' => [
                    'enabled' => true,
                    'x' => 35,
                    'y' => 26,
                    'font_size' => 7,
                    'align' => 'right',
                    'bold' => false,
                ],
            ],
            'barcode' => [
                'x' => 5,
                'y' => 11,
                'width_mm' => 40,
                'height_mm' => 10,
                'type' => 'CODE128',
            ],
        ];
    }

    public function create()
    {
        return view('barcode-labels.editor', [
            'template' => null,
            'settings' => $this->defaultSettings(),
        ]);
    }

    public function edit(BarcodeLabelTemplate $template)
    {
        abort_unless(
            $template->user_id === auth()->id(),
            404
        );

        return view('barcode-labels.editor', [
            'template' => $template,
            'settings' => $template->settings,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateTemplate($request);

        $template = BarcodeLabelTemplate::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'width_mm' => $data['width_mm'],
            'height_mm' => $data['height_mm'],
            'settings' => $data['settings'],
        ]);

        return redirect()
            ->route('barcode-labels.edit', $template)
            ->with('success', 'Template saved successfully.');
    }

    public function update(
        Request $request,
        BarcodeLabelTemplate $template
    ) {
        abort_unless(
            $template->user_id === auth()->id(),
            404
        );

        $data = $this->validateTemplate($request);

        $template->update($data);

        return redirect()
            ->route('barcode-labels.edit', $template)
            ->with('success', 'Template updated successfully.');
    }

    private function validateTemplate(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],

            'width_mm' => [
                'required',
                'numeric',
                'min:10',
                'max:300'
            ],

            'height_mm' => [
                'required',
                'numeric',
                'min:10',
                'max:300'
            ],

            'settings' => ['required', 'array'],

            'settings.barcode.x' => ['required', 'numeric', 'min:0'],
            'settings.barcode.y' => ['required', 'numeric', 'min:0'],
            'settings.barcode.width_mm' => [
                'required',
                'numeric',
                'min:1',
                'max:300'
            ],
            'settings.barcode.height_mm' => [
                'required',
                'numeric',
                'min:1',
                'max:100'
            ],
            'settings.barcode.type' => [
                'required',
                'in:CODE128'
            ],

            'settings.fields' => ['required', 'array'],

            'settings.fields.*.enabled' => ['required', 'boolean'],
            'settings.fields.*.x' => [
                'required',
                'numeric',
                'min:0',
                'max:300'
            ],
            'settings.fields.*.y' => [
                'required',
                'numeric',
                'min:0',
                'max:300'
            ],
            'settings.fields.*.font_size' => [
                'required',
                'numeric',
                'min:4',
                'max:40'
            ],
            'settings.fields.*.align' => [
                'required',
                'in:left,center,right'
            ],
            'settings.fields.*.bold' => ['required', 'boolean'],
        ]);
    }
}
