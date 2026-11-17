import JsBarcode from "jsbarcode";

const initial = window.barcodeLabelInitialSettings;

const settings = JSON.parse(JSON.stringify(initial));

const sheet = document.getElementById("labelSheet");

const fields = ["product_name", "sku", "pc", "date", "time"];

const dataElements = {
    product_name: document.getElementById("dataProduct"),
    sku: document.getElementById("dataSku"),
    barcode: document.getElementById("dataBarcode"),
    pc: document.getElementById("dataPc"),
    date: document.getElementById("dataDate"),
    time: document.getElementById("dataTime"),
};

function getData() {
    return {
        product_name: dataElements.product_name.value,
        sku: dataElements.sku.value,
        barcode: dataElements.barcode.value,
        pc: dataElements.pc.value,
        date: dataElements.date.value,
        time: dataElements.time.value,
    };
}

function setNestedValue(path, value) {
    const keys = path.split(".");
    let current = settings;

    for (let i = 0; i < keys.length - 1; i++) {
        current = current[keys[i]];
    }

    current[keys[keys.length - 1]] = value;
}

function readSettings() {
    document.querySelectorAll("[data-setting]").forEach((input) => {
        const path = input.dataset.setting;

        let value;

        if (input.type === "checkbox") {
            value = input.checked;
        } else if (input.type === "number") {
            value = Number(input.value);
        } else {
            value = input.value;
        }

        setNestedValue(path, value);
    });
}

function renderBarcode(data) {
    const container = document.getElementById("barcodePreview");

    container.innerHTML = "";

    if (!data.barcode) return;

    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");

    container.appendChild(svg);

    try {
        JsBarcode(svg, data.barcode, {
            format: settings.barcode.type,
            lineColor: "#000000",
            background: "#ffffff",
            displayValue: false,
            width: 2,
            height: 100,
            margin: 0,
        });

        const barcode = settings.barcode;

        container.style.left = `${barcode.x}mm`;
        container.style.top = `${barcode.y}mm`;
        container.style.width = `${barcode.width_mm}mm`;
        container.style.height = `${barcode.height_mm}mm`;

        svg.style.width = "100%";
        svg.style.height = "100%";

        svg.setAttribute("preserveAspectRatio", "xMidYMid meet");
    } catch (error) {
        container.innerHTML = "";
        console.error("Barcode generation failed:", error);
    }
}

function renderTextFields(data) {
    fields.forEach((key) => {
        const config = settings.fields[key];

        const element = document.getElementById(`preview-${key}`);

        if (!config.enabled) {
            element.style.display = "none";
            return;
        }

        element.style.display = "block";

        element.textContent = data[key] ?? "";

        element.style.left = `${config.x}mm`;
        element.style.top = `${config.y}mm`;

        element.style.width = `calc(100% - ${config.x + 1}mm)`;

        element.style.fontSize = `${config.font_size}px`;

        element.style.fontWeight = config.bold ? "bold" : "normal";

        element.style.textAlign = config.align;
    });
}

function renderPreview() {
    readSettings();

    const width = Number(document.getElementById("labelWidth").value);

    const height = Number(document.getElementById("labelHeight").value);

    if (
        !Number.isFinite(width) ||
        !Number.isFinite(height) ||
        width <= 0 ||
        height <= 0
    ) {
        return;
    }

    sheet.style.width = `${width}mm`;
    sheet.style.height = `${height}mm`;

    const data = getData();

    renderBarcode(data);
    renderTextFields(data);

    document.getElementById("settingsJson").value = JSON.stringify(settings);
}

function printLabel() {
    renderPreview();

    const width = Number(document.getElementById("labelWidth").value);

    const height = Number(document.getElementById("labelHeight").value);

    const barcodeValue = dataElements.barcode.value;

    if (!barcodeValue) {
        alert("Please enter a barcode value.");
        return;
    }

    const oldStyle = document.getElementById("dynamicPrintStyle");

    if (oldStyle) oldStyle.remove();

    const style = document.createElement("style");

    style.id = "dynamicPrintStyle";

    style.textContent = `
        @page {
            size: ${width}mm ${height}mm;
            margin: 0;
        }

        @media print {
            .label-sheet {
                width: ${width}mm !important;
                height: ${height}mm !important;
            }
        }
    `;

    document.head.appendChild(style);

    window.print();
}

window.printLabel = printLabel;

// Update preview whenever settings change.

document.querySelectorAll("[data-setting]").forEach((input) => {
    input.addEventListener("input", renderPreview);
    input.addEventListener("change", renderPreview);
});

document.querySelectorAll("#labelWidth, #labelHeight").forEach((input) => {
    input.addEventListener("input", renderPreview);
});

Object.values(dataElements).forEach((input) => {
    input.addEventListener("input", renderPreview);
});

// Initial preview.

renderPreview();
