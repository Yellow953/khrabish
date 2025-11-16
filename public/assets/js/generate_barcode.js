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

function showSuccess(message) {
    const alertBox = document.getElementById("errorAlert");
    alertBox.innerText = message;
    alertBox.classList.remove("d-none", "alert-danger");
    alertBox.classList.add("alert-success");
    setTimeout(() => {
        alertBox.classList.add("d-none");
        alertBox.classList.remove("alert-success");
        alertBox.classList.add("alert-danger");
    }, 3000);
}

async function saveBarcodesToServer(barcodesData) {
    try {
        const response = await fetch("/app/products/barcodes/save", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify(barcodesData),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(
                result.message ||
                    "Server responded with error: " + response.status
            );
        }

        return result;
    } catch (error) {
        console.error("Error saving barcodes:", error);
        throw error;
    }
}

let generatedBarcodes = [];
let savedBarcodes = JSON.parse(localStorage.getItem("savedBarcodes")) || [];

function displaySavedBarcodes() {
    const container = document.getElementById("savedBarcodes");

    if (savedBarcodes.length === 0) {
        container.innerHTML =
            '<div class="text-muted text-center">No saved barcodes yet</div>';
        return;
    }

    container.innerHTML = "";

    savedBarcodes.forEach((item, index) => {
        const barcodeItem = document.createElement("div");
        barcodeItem.className = "saved-barcode-item";

        barcodeItem.innerHTML = `
                    <div>
                        <div><strong>${item.productName}</strong></div>
                        <div class="small text-muted">Barcode: ${
                            item.barcode
                        }</div>
                        <div class="small text-muted">${
                            item.note ? "Note: " + item.note : ""
                        }</div>
                        <div class="small text-muted">Saved: ${new Date(
                            item.timestamp
                        ).toLocaleString()}</div>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-danger delete-barcode" data-index="${index}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;

        container.appendChild(barcodeItem);
    });

    document.querySelectorAll(".delete-barcode").forEach((button) => {
        button.addEventListener("click", function () {
            const index = parseInt(this.getAttribute("data-index"));
            savedBarcodes.splice(index, 1);
            localStorage.setItem(
                "savedBarcodes",
                JSON.stringify(savedBarcodes)
            );
            displaySavedBarcodes();
            showSuccess("Barcode deleted successfully");
        });
    });
}

document.getElementById("generateBtn").addEventListener("click", function () {
    const select = document.getElementById("productSelect");
    const quantity = parseInt(document.getElementById("quantity").value);
    const showName = document.getElementById("showName").checked;
    const showPrice = document.getElementById("showPrice").checked;
    const alignment = document.getElementById("alignmentSelect").value;

    const option = select.options[select.selectedIndex];

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

    generatedBarcodes = [];

    for (let i = 0; i < quantity; i++) {
        const randomBarcode = generateRandomBarcode();

        generatedBarcodes.push({
            barcode: randomBarcode,
            productName: name,
            price: price,
        });

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

document.getElementById("saveBtn").addEventListener("click", function () {
    if (generatedBarcodes.length === 0) {
        showError("Please generate barcodes first before saving.");
        return;
    }

    const selectionContainer = document.getElementById("barcodeSelection");
    selectionContainer.innerHTML = "";

    generatedBarcodes.forEach((barcode, index) => {
        const checkboxDiv = document.createElement("div");
        checkboxDiv.className = "form-check mb-3";
        checkboxDiv.innerHTML = `
            <input class="form-check-input" type="checkbox" value="${index}" id="barcodeCheck${index}" checked>
            <label class="form-check-label" for="barcodeCheck${index}">
                ${barcode.barcode}
            </label>
        `;
        selectionContainer.appendChild(checkboxDiv);
    });

    const assignModal = new bootstrap.Modal(
        document.getElementById("assignModal")
    );
    assignModal.show();
});

document
    .getElementById("confirmSave")
    .addEventListener("click", async function () {
        const selectedIndices = [];
        const checkboxes = document.querySelectorAll(
            '#barcodeSelection input[type="checkbox"]:checked'
        );

        checkboxes.forEach((checkbox) => {
            selectedIndices.push(parseInt(checkbox.value));
        });

        if (selectedIndices.length === 0) {
            showError("Please select at least one barcode to save.");
            return;
        }

        const productId = document.getElementById("productSelect").value;

        const barcodesToSave = selectedIndices.map((index) => {
            return generatedBarcodes[index].barcode;
        });

        try {
            const saveButton = document.getElementById("confirmSave");
            const originalText = saveButton.innerHTML;
            saveButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
            saveButton.disabled = true;

            const result = await saveBarcodesToServer({
                product_id: productId,
                barcodes: barcodesToSave,
            });

            showSuccess(
                `Successfully saved ${result.count} barcode(s) to product`
            );

            bootstrap.Modal.getInstance(
                document.getElementById("assignModal")
            ).hide();
        } catch (error) {
            showError("Failed to save barcodes: " + error.message);
            console.error("Save error:", error);
        } finally {
            const saveButton = document.getElementById("confirmSave");
            saveButton.innerHTML = "Save Selected";
            saveButton.disabled = false;
        }
    });

displaySavedBarcodes();
