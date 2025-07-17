function generateRandomBarcode(length = 12) {
    let barcode = "";
    for (let i = 0; i < length; i++) {
        barcode += Math.floor(Math.random() * 10);
    }
    return barcode;
}

function showError(message) {
    const alertBox = document.getElementById("errorAlert");
    alertBox.innerText = message;
    alertBox.classList.remove("d-none");
    setTimeout(() => alertBox.classList.add("d-none"), 3000);
}

document.getElementById("generateBtn").addEventListener("click", function () {
    const select = document.getElementById("productSelect");
    const quantity = parseInt(document.getElementById("quantity").value);
    const showName = document.getElementById("showName").checked;
    const showPrice = document.getElementById("showPrice").checked;
    const alignment = document.getElementById("alignmentSelect").value;

    const option = select.options[select.selectedIndex];

    // Validation
    if (!option.value) return showError("Please select a product.");
    if (isNaN(quantity) || quantity < 1)
        return showError("Please enter a valid quantity (1 or more).");

    const preview = document.getElementById("barcodePreview");
    preview.style.justifyContent =
        alignment === "left"
            ? "flex-start"
            : alignment === "right"
            ? "flex-end"
            : "center";

    preview.innerHTML = "";

    const name = option.dataset.name;
    const price = option.dataset.price;

    for (let i = 0; i < quantity; i++) {
        const randomBarcode = generateRandomBarcode();

        const wrapper = document.createElement("div");
        wrapper.className = "label-wrapper";
        wrapper.style.width = "160px";

        if (showName) {
            const label = document.createElement("div");
            label.className = "fw-bold mb-1 small";
            label.innerText = name;
            label.style.fontWeight = "bold";
            wrapper.appendChild(label);
        }

        const svg = document.createElement("svg");
        JsBarcode(svg, randomBarcode, {
            format: "CODE128",
            width: 2,
            height: 50,
            displayValue: false,
            fontSize: 12,
            margin: 0,
        });
        wrapper.appendChild(svg);

        const codeEl = document.createElement("div");
        codeEl.className = "small text-muted mt-1";
        codeEl.innerText = randomBarcode;
        wrapper.appendChild(codeEl);

        if (showPrice) {
            const label1 = document.createElement("div");
            label1.className = "fw-bold mb-1 small";
            label1.innerText = "$" + price;
            label1.style.fontWeight = "bold";
            wrapper.appendChild(label1);
        }

        preview.appendChild(wrapper);
    }
});

document.getElementById("printBtn").addEventListener("click", function () {
    const printContent = document.getElementById("barcodePreview").innerHTML;
    if (!printContent.trim())
        return showError("Please generate labels before printing.");

    const alignment = document.getElementById("alignmentSelect").value;

    const w = window.open("", "", "height=600,width=800");
    w.document.write(`
            <html>
            <head>
                <title>Print Labels</title>
                <style>
                    body {
                        margin: 0;
                        padding: 10px;
                        text-align: center;
                        font-family: Arial, sans-serif;
                        max-width: 600px;
                        margin-left: auto;
                        margin-right: auto;
                    }
                    .label-wrapper {
                        padding: 10px;
                        width: 160px;
                        height: 60mm;
                        ${alignment == "center" ? "margin: 10px auto" : ""};
                        ${
                            alignment == "right"
                                ? "margin: 10px 0 10px auto"
                                : ""
                        };
                        ${
                            alignment == "left"
                                ? "margin: 10px auto 10px 0"
                                : ""
                        };
                        border-radius: 6px;
                        page-break-inside: avoid;
                        page-break-after: always;
                        text-align: center;
                    }
                    svg {
                        width: 100%;
                    }
                </style>
            </head>
            <body>${printContent}</body>
            </html>
        `);
    w.document.close();
    w.focus();
    w.print();
    // w.close();
});
