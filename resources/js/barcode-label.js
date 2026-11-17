import JsBarcode from "jsbarcode";

document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById("labelCanvas");
    const form = document.getElementById("templateForm");
    const settingsInput = document.getElementById("settingsInput");

    if (!canvas || !form) return;

    let settings = JSON.parse(
        JSON.stringify(window.initialLabelSettings || { elements: [] }),
    );

    if (!Array.isArray(settings.elements)) {
        settings.elements = [];
    }

    settings.elements.forEach((element) => {
        if (element.type !== "barcode" || element.displayValue === false) {
            return;
        }

        const valueId = `${element.id}_value`;
        const valueExists = settings.elements.some((item) => item.id === valueId);

        if (!valueExists) {
            const originalHeight = getFiniteDimension(element.height, 4);
            const valueHeight = Math.min(4, Math.max(2, originalHeight * 0.3));
            const barcodeHeight = Math.max(3, originalHeight - valueHeight);

            settings.elements.push({
                id: valueId,
                type: "text",
                label: `${element.label || "Barcode"} Value`,
                value_key: element.value_key || "barcode",
                text: element.text || "",
                x: Number(element.x) || 0,
                y: (Number(element.y) || 0) + barcodeHeight,
                width: Number(element.width) || 20,
                height: valueHeight,
                fontSize: 7,
                fontFamily: "sans-serif",
                fontWeight: 400,
                align: "center",
                visible: element.visible !== false,
            });

            element.height = barcodeHeight;
        }

        element.displayValue = false;
    });

    let selectedId = null;

    const $ = (id) => document.getElementById(id);

    const widthInput = $("labelWidth");
    const heightInput = $("labelHeight");

    const sampleInputs = {
        product_name: $("sample_product_name"),
        sku: $("sample_sku"),
        barcode: $("sample_barcode"),
        quantity: $("sample_quantity"),
        date: $("sample_date"),
        time: $("sample_time"),
    };

    const editor = {
        label: $("editLabel"),
        text: $("editText"),
        x: $("editX"),
        y: $("editY"),
        width: $("editWidth"),
        height: $("editHeight"),
        fontSize: $("editFontSize"),
        fontFamily: $("editFontFamily"),
        fontWeight: $("editFontWeight"),
        align: $("editAlign"),
        barWidth: $("editBarWidth"),
        format: $("editFormat"),
        visible: $("editVisible"),
    };

    function getSelectedElement() {
        return settings.elements.find((item) => item.id === selectedId);
    }

    function getLabelWidth() {
        return Math.max(10, Number(widthInput.value) || 50);
    }

    function getLabelHeight() {
        return Math.max(10, Number(heightInput.value) || 30);
    }

    function getScale() {
        const rect = canvas.getBoundingClientRect();

        return {
            x: rect.width / getLabelWidth(),
            y: rect.height / getLabelHeight(),
        };
    }

    function sampleValue(element) {
        if (element.value_key && sampleInputs[element.value_key]) {
            return sampleInputs[element.value_key].value;
        }

        return element.text ?? "";
    }

    function getFiniteDimension(value, fallback) {
        const dimension = Number(value);

        return Number.isFinite(dimension) && dimension > 0 ? dimension : fallback;
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function updateSettingsInput() {
        settingsInput.value = JSON.stringify(settings);
    }

    function updateCanvasSize() {
        canvas.style.width = `${getLabelWidth()}mm`;
        canvas.style.height = `${getLabelHeight()}mm`;
        fitCanvasToPreview();
    }

    function fitCanvasToPreview() {
        const preview = canvas.parentElement;

        if (!preview || !canvas.offsetWidth || !canvas.offsetHeight) return;

        const previewStyle = window.getComputedStyle(preview);
        const availableWidth =
            preview.clientWidth -
            parseFloat(previewStyle.paddingLeft) -
            parseFloat(previewStyle.paddingRight);
        const availableHeight =
            preview.clientHeight -
            parseFloat(previewStyle.paddingTop) -
            parseFloat(previewStyle.paddingBottom);
        const scale = Math.min(
            availableWidth / canvas.offsetWidth,
            availableHeight / canvas.offsetHeight,
        );

        canvas.style.transform = `translate(-50%, -50%) scale(${scale})`;
    }

    function createElementNode(element) {
        const wrapper = document.createElement("div");

        wrapper.className = "label-element";
        wrapper.dataset.elementId = element.id;

        if (element.type === "text") {
            wrapper.classList.add("text-element");
        } else {
            wrapper.classList.add("barcode-element");
        }

        wrapper.style.left = `${element.x}mm`;
        wrapper.style.top = `${element.y}mm`;
        wrapper.style.width = `${element.width}mm`;
        wrapper.style.height = `${element.height}mm`;

        if (!element.visible) {
            wrapper.style.display = "none";
        }

        const content = document.createElement("div");
        content.className = "element-content";

        if (element.type === "text") {
            content.textContent = sampleValue(element);

            content.style.fontSize = `${element.fontSize || 8}px`;
            content.style.fontFamily = element.fontFamily || "sans-serif";
            content.style.fontWeight =
                element.fontWeight || (element.bold ? "700" : "400");
            content.style.textAlign = element.align || "left";

            content.style.display = "block";
            content.style.whiteSpace = "normal";
            content.style.overflowWrap = "anywhere";
            content.style.wordBreak = "normal";
            content.style.lineHeight = "1.2";

            content.style.width = "100%";
            content.style.height = "100%";
            content.style.boxSizing = "border-box";
            content.style.overflow = "hidden";
        } else if (element.type === "barcode") {
            const svg = document.createElementNS(
                "http://www.w3.org/2000/svg",
                "svg",
            );

            content.appendChild(svg);

            try {
                JsBarcode(svg, sampleValue(element) || "123456", {
                    format: element.format || "CODE128",
                    displayValue: false,
                    width: Number(element.barWidth) || 2,
                    height: 45,
                    margin: 4,
                    background: "#ffffff",
                    lineColor: "#000000",
                });

                svg.setAttribute("width", "100%");
                svg.setAttribute("height", "100%");
                svg.setAttribute("preserveAspectRatio", "none");
                svg.style.maxWidth = "100%";
                svg.style.maxHeight = "100%";
            } catch (error) {
                content.textContent = "Invalid barcode value";
                content.style.color = "red";
                console.error("Barcode generation error:", error);
            }
        }

        wrapper.appendChild(content);

        const resizeHandle = document.createElement("div");
        resizeHandle.className = "resize-handle";
        wrapper.appendChild(resizeHandle);

        wrapper.addEventListener("pointerdown", (event) => {
            if (event.target.classList.contains("resize-handle")) {
                return;
            }

            startMove(event, element, wrapper);
        });

        resizeHandle.addEventListener("pointerdown", (event) => {
            startResize(event, element, wrapper);
        });

        wrapper.addEventListener("click", (event) => {
            event.stopPropagation();
            selectElement(element.id);
        });

        if (selectedId === element.id) {
            wrapper.classList.add("selected");
        }

        return wrapper;
    }

    function renderCanvas() {
        updateCanvasSize();

        canvas.replaceChildren();

        settings.elements.forEach((element) => {
            canvas.appendChild(createElementNode(element));
        });

        updateSettingsInput();
        renderElementList();
    }

    function renderElementList() {
        const list = $("elementList");
        list.replaceChildren();

        settings.elements.forEach((element) => {
            const button = document.createElement("button");

            button.type = "button";
            button.textContent = element.label || element.id;

            if (element.id === selectedId) {
                button.classList.add("active");
            }

            button.addEventListener("click", () => {
                selectElement(element.id);
            });

            list.appendChild(button);
        });
    }

    function selectElement(id) {
        selectedId = id;

        const element = getSelectedElement();

        document.querySelectorAll(".label-element").forEach((node) => {
            node.classList.toggle("selected", node.dataset.elementId === id);
        });

        $("noSelection").style.display = element ? "none" : "block";
        $("elementEditor").style.display = element ? "block" : "none";

        if (!element) {
            renderElementList();
            return;
        }

        editor.label.value = element.label || "";
        editor.text.value = element.text || "";
        $("editText").previousElementSibling.textContent =
            element.type === "barcode" ? "Barcode Value" : "Text";
        $("editWidth").previousElementSibling.textContent =
            element.type === "barcode" ? "Barcode Width (mm)" : "Width (mm)";
        $("editHeight").previousElementSibling.textContent =
            element.type === "barcode" ? "Barcode Height (mm)" : "Height (mm)";
        editor.x.value = element.x;
        editor.y.value = element.y;
        editor.width.value = element.width;
        editor.height.value = element.height;

        const isText = element.type === "text";
        const isBarcode = element.type === "barcode";

        $("textOptions").style.display = isText ? "block" : "none";
        $("barcodeOptions").style.display = isBarcode ? "block" : "none";

        if (isText) {
            editor.fontSize.value = element.fontSize || 8;
            editor.fontFamily.value = element.fontFamily || "sans-serif";
            editor.fontWeight.value =
                element.fontWeight || (element.bold ? "700" : "400");
            editor.align.value = element.align || "left";
        }

        if (isBarcode) {
            editor.barWidth.value = element.barWidth || 2;
            editor.format.value = element.format || "CODE128";
        }

        editor.visible.checked = element.visible !== false;

        renderElementList();
    }

    function startMove(event, element, wrapper) {
        if (event.button !== 0) return;

        event.preventDefault();
        selectElement(element.id);

        const scale = getScale();
        const startX = event.clientX;
        const startY = event.clientY;
        const originalX = Number(element.x);
        const originalY = Number(element.y);

        wrapper.setPointerCapture(event.pointerId);

        function move(pointerEvent) {
            const dx = (pointerEvent.clientX - startX) / scale.x;
            const dy = (pointerEvent.clientY - startY) / scale.y;

            element.x = Number(
                clamp(
                    originalX + dx,
                    0,
                    getLabelWidth() - element.width,
                ).toFixed(2),
            );

            element.y = Number(
                clamp(
                    originalY + dy,
                    0,
                    getLabelHeight() - element.height,
                ).toFixed(2),
            );

            wrapper.style.left = `${element.x}mm`;
            wrapper.style.top = `${element.y}mm`;

            editor.x.value = element.x;
            editor.y.value = element.y;

            updateSettingsInput();
        }

        function stop() {
            wrapper.removeEventListener("pointermove", move);
            wrapper.removeEventListener("pointerup", stop);
            wrapper.removeEventListener("pointercancel", stop);

            updateSettingsInput();
        }

        wrapper.addEventListener("pointermove", move);
        wrapper.addEventListener("pointerup", stop);
        wrapper.addEventListener("pointercancel", stop);
    }

    function startResize(event, element, wrapper) {
        if (event.button !== 0) return;

        event.preventDefault();
        event.stopPropagation();

        selectElement(element.id);

        const scale = getScale();
        const startX = event.clientX;
        const startY = event.clientY;
        const originalWidth = Number(element.width);
        const originalHeight = Number(element.height);

        wrapper.setPointerCapture(event.pointerId);

        function resize(pointerEvent) {
            const dx = (pointerEvent.clientX - startX) / scale.x;
            const dy = (pointerEvent.clientY - startY) / scale.y;

            element.width = Number(
                clamp(
                    originalWidth + dx,
                    3,
                    getLabelWidth() - element.x,
                ).toFixed(2),
            );

            element.height = Number(
                clamp(
                    originalHeight + dy,
                    3,
                    getLabelHeight() - element.y,
                ).toFixed(2),
            );

            wrapper.style.width = `${element.width}mm`;
            wrapper.style.height = `${element.height}mm`;

            editor.width.value = element.width;
            editor.height.value = element.height;

            updateSettingsInput();

            // Keep the barcode SVG at its new dimensions.
            const svg = wrapper.querySelector("svg");

            if (svg) {
                svg.style.width = "100%";
                svg.style.height = "100%";
            }
        }

        function stop() {
            wrapper.removeEventListener("pointermove", resize);
            wrapper.removeEventListener("pointerup", stop);
            wrapper.removeEventListener("pointercancel", stop);

            updateSettingsInput();
        }

        wrapper.addEventListener("pointermove", resize);
        wrapper.addEventListener("pointerup", stop);
        wrapper.addEventListener("pointercancel", stop);
    }

    function updateSelectedElementFromEditor() {
        const element = getSelectedElement();

        if (!element) return;

        element.label = editor.label.value;
        element.text = editor.text.value;

        if (
            document.activeElement === editor.text &&
            element.value_key &&
            sampleInputs[element.value_key]
        ) {
            sampleInputs[element.value_key].value = editor.text.value;
        }

        element.x = clamp(Number(editor.x.value) || 0, 0, getLabelWidth() - 1);

        element.y = clamp(Number(editor.y.value) || 0, 0, getLabelHeight() - 1);

        element.width = clamp(
            Number(editor.width.value) || 1,
            1,
            getLabelWidth() - element.x,
        );

        element.height = clamp(
            Number(editor.height.value) || 1,
            1,
            getLabelHeight() - element.y,
        );

        element.visible = editor.visible.checked;

        if (element.type === "text") {
            element.fontSize = Number(editor.fontSize.value) || 8;
            element.fontFamily = editor.fontFamily.value;
            element.fontWeight = Number(editor.fontWeight.value) || 400;
            element.align = editor.align.value;
            element.bold = element.fontWeight >= 600;
        }

        if (element.type === "barcode") {
            element.barWidth = clamp(
                Number(editor.barWidth.value) || 2,
                0.1,
                10,
            );
            element.format = editor.format.value;
            element.displayValue = false;
        }

        renderCanvas();
        selectElement(element.id);
    }

    // Update element properties when the right-side inputs change.
    Object.values(editor).forEach((input) => {
        if (!input) return;

        input.addEventListener("input", updateSelectedElementFromEditor);
        input.addEventListener("change", updateSelectedElementFromEditor);
    });

    // Update preview when sample data changes.
    Object.values(sampleInputs).forEach((input) => {
        if (!input) return;

        input.addEventListener("input", renderCanvas);
    });

    // Update label dimensions.
    [widthInput, heightInput].forEach((input) => {
        input.addEventListener("input", () => {
            settings.elements.forEach((element) => {
                element.x = clamp(
                    Number(element.x) || 0,
                    0,
                    getLabelWidth() - 1,
                );

                element.y = clamp(
                    Number(element.y) || 0,
                    0,
                    getLabelHeight() - 1,
                );

                element.width = clamp(
                    Number(element.width) || 1,
                    1,
                    getLabelWidth() - element.x,
                );

                element.height = clamp(
                    Number(element.height) || 1,
                    1,
                    getLabelHeight() - element.y,
                );
            });

            renderCanvas();

            if (selectedId) {
                selectElement(selectedId);
            }
        });
    });

    // Add a new text element.
    $("addTextButton").addEventListener("click", () => {
        const id = `text_${Date.now()}`;

        const element = {
            id,
            type: "text",
            label: "New Text",
            text: "New Text",
            x: 2,
            y: 2,
            width: Math.min(25, getLabelWidth() - 2),
            height: 6,
            fontSize: 9,
            bold: false,
            align: "left",
            visible: true,
        };

        settings.elements.push(element);

        renderCanvas();
        selectElement(id);
    });

    // Delete selected element.
    $("deleteElementButton").addEventListener("click", () => {
        if (!selectedId) return;

        settings.elements = settings.elements.filter(
            (element) => element.id !== selectedId,
        );

        selectedId = null;

        renderCanvas();
        selectElement(null);
    });

    // Clicking the empty canvas deselects elements.
    canvas.addEventListener("click", (event) => {
        if (event.target === canvas) {
            selectElement(null);
        }
    });

    // Save the latest settings before submitting.
    form.addEventListener("submit", () => {
        updateSettingsInput();
    });

    // Print the label.
    $("printButton").addEventListener("click", () => {
        updateSettingsInput();

        const style = document.createElement("style");

        style.id = "dynamic-print-style";

        style.textContent = `
            @media print {
                @page {
                    size: ${getLabelWidth()}mm ${getLabelHeight()}mm;
                    margin: 0;
                }
            }
        `;

        document.getElementById("dynamic-print-style")?.remove();
        document.head.appendChild(style);

        window.print();
    });

    const previewObserver = new ResizeObserver(fitCanvasToPreview);
    previewObserver.observe(canvas.parentElement);
    window.addEventListener("resize", fitCanvasToPreview);
    window.addEventListener("beforeprint", () => {
        canvas.style.transform = "none";
    });
    window.addEventListener("afterprint", fitCanvasToPreview);

    renderCanvas();
    selectElement(null);
});
