<?php

namespace App\Http\Controllers;

use App\Models\BarcodeLabelTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BarcodeLabelController extends Controller
{
    private function defaultSettings(): array
    {
        return [
            'elements' => [
                [
                    'id' => 'product_name',
                    'type' => 'text',
                    'label' => 'Product Name',
                    'value_key' => 'product_name',
                    'text' => 'Sample Product',
                    'x' => 2,
                    'y' => 2,
                    'width' => 46,
                    'height' => 5,
                    'fontSize' => 10,
                    'bold' => true,
                    'align' => 'left',
                    'visible' => true,
                ],
                [
                    'id' => 'sku',
                    'type' => 'text',
                    'label' => 'SKU',
                    'value_key' => 'sku',
                    'text' => 'SKU: 10001',
                    'x' => 2,
                    'y' => 8,
                    'width' => 25,
                    'height' => 4,
                    'fontSize' => 8,
                    'bold' => false,
                    'align' => 'left',
                    'visible' => true,
                ],
                [
                    'id' => 'quantity',
                    'type' => 'text',
                    'label' => 'PC / Quantity',
                    'value_key' => 'quantity',
                    'text' => 'PC: 1',
                    'x' => 30,
                    'y' => 8,
                    'width' => 18,
                    'height' => 4,
                    'fontSize' => 8,
                    'bold' => false,
                    'align' => 'right',
                    'visible' => true,
                ],
                [
                    'id' => 'date',
                    'type' => 'text',
                    'label' => 'Date',
                    'value_key' => 'date',
                    'text' => 'Date: 2026-11-17',
                    'x' => 2,
                    'y' => 13,
                    'width' => 25,
                    'height' => 4,
                    'fontSize' => 7,
                    'bold' => false,
                    'align' => 'left',
                    'visible' => true,
                ],
                [
                    'id' => 'time',
                    'type' => 'text',
                    'label' => 'Time',
                    'value_key' => 'time',
                    'text' => 'Time: 10:30',
                    'x' => 28,
                    'y' => 13,
                    'width' => 20,
                    'height' => 4,
                    'fontSize' => 7,
                    'bold' => false,
                    'align' => 'right',
                    'visible' => true,
                ],
                [
                    'id' => 'barcode',
                    'type' => 'barcode',
                    'label' => 'Barcode',
                    'text' => '100012345678',
                    'x' => 5,
                    'y' => 18,
                    'width' => 40,
                    'height' => 8,
                    'format' => 'CODE128',
                    'barWidth' => 2,
                    'displayValue' => false,
                    'visible' => true,
                ],
                [
                    'id' => 'barcode_value',
                    'type' => 'text',
                    'label' => 'Barcode Value',
                    'value_key' => 'barcode',
                    'text' => '100012345678',
                    'x' => 5,
                    'y' => 26,
                    'width' => 40,
                    'height' => 4,
                    'fontSize' => 7,
                    'fontFamily' => 'sans-serif',
                    'fontWeight' => 400,
                    'align' => 'center',
                    'visible' => true,
                ],
            ],
        ];
    }

    public function index()
    {
        $templates = BarcodeLabelTemplate::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('barcode-labels.index', compact('templates'));
    }

    public function create()
    {
        $template = null;
        $settings = $this->defaultSettings();

        return view('barcode-labels.editor', compact('template', 'settings'));
    }

    public function edit(BarcodeLabelTemplate $template)
    {
        $this->authorizeTemplate($template);

        $settings = $template->settings ?: $this->defaultSettings();

        return view('barcode-labels.editor', compact('template', 'settings'));
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

    public function update(Request $request, BarcodeLabelTemplate $template)
    {
        $this->authorizeTemplate($template);

        $data = $this->validateTemplate($request);

        $template->update([
            'name' => $data['name'],
            'width_mm' => $data['width_mm'],
            'height_mm' => $data['height_mm'],
            'settings' => $data['settings'],
        ]);

        return back()->with('success', 'Template updated successfully.');
    }

    public function destroy(BarcodeLabelTemplate $template)
    {
        $this->authorizeTemplate($template);

        $template->delete();

        return redirect()
            ->route('barcode-labels.index')
            ->with('success', 'Template deleted.');
    }

    private function authorizeTemplate(BarcodeLabelTemplate $template): void
    {
        abort_unless(
            $template->user_id === auth()->id(),
            403
        );
    }

    private function validateTemplate(Request $request): array
    {
        $request->merge([
            'settings' => is_string($request->input('settings'))
                ? json_decode($request->input('settings'), true)
                : $request->input('settings'),
        ]);

        if (!is_array($request->input('settings'))) {
            throw ValidationException::withMessages([
                'settings' => 'The label settings are invalid.',
            ]);
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'width_mm' => ['required', 'numeric', 'min:10', 'max:300'],
            'height_mm' => ['required', 'numeric', 'min:10', 'max:300'],
            'settings' => ['required', 'array'],
            'settings.elements' => ['required', 'array'],
            'settings.elements.*.id' => ['required', 'string', 'max:100'],
            'settings.elements.*.type' => ['required', 'in:text,barcode'],
            'settings.elements.*.label' => ['nullable', 'string', 'max:100'],
            'settings.elements.*.text' => ['nullable', 'string', 'max:500'],
            'settings.elements.*.x' => ['required', 'numeric', 'min:0'],
            'settings.elements.*.y' => ['required', 'numeric', 'min:0'],
            'settings.elements.*.width' => ['required', 'numeric', 'min:1'],
            'settings.elements.*.height' => ['required', 'numeric', 'min:1'],
            'settings.elements.*.fontSize' => ['nullable', 'numeric', 'min:1', 'max:100'],
            'settings.elements.*.fontFamily' => ['nullable', 'string', 'max:50'],
            'settings.elements.*.fontWeight' => ['nullable', 'in:300,400,500,600,700,800,900'],
            'settings.elements.*.bold' => ['nullable', 'boolean'],
            'settings.elements.*.align' => ['nullable', 'in:left,center,right'],
            'settings.elements.*.visible' => ['required', 'boolean'],
            'settings.elements.*.format' => ['nullable', 'string', 'max:30'],
            'settings.elements.*.barWidth' => ['nullable', 'numeric', 'min:0.1', 'max:10'],
            'settings.elements.*.displayValue' => ['nullable', 'boolean'],
            'settings.elements.*.value_key' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
