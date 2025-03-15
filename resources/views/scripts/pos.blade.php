<script>
    "use strict";

    var KTPosSystem = function() {
        var form;
        var orderItems = [];
        var taxRate = 0;
        var discount = 0;
        var grandTotal = 0;
        var orderNumber = {{ $last_order ? (int)$last_order->order_number : 0 }}

        var moneyFormat = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: '{{ $currency->code }}',
            minimumFractionDigits: 2
        });

        var calculateTotals = function() {
            var total = orderItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
            grandTotal = total - discount;

            if (discount > total) {
                discount = total;
            }

            grandTotal = total - discount;

            form.querySelector('[data-kt-pos-element="total"]').innerHTML = moneyFormat.format(total);
            form.querySelector('[data-kt-pos-element="discount"]').innerHTML = moneyFormat.format(discount);
            form.querySelector('[data-kt-pos-element="grant-total"]').innerHTML = moneyFormat.format(
            grandTotal);

            form.querySelector('input[name="total"]').value = total;
            form.querySelector('input[name="discount"]').value = discount;
            form.querySelector('input[name="grand_total"]').value = grandTotal;
        }

        var updateOrderTable = function() {
            var orderTable = document.getElementById('order_items');
            orderTable.innerHTML = '';

            orderItems.forEach((item, index) => {
                var row = orderTable.insertRow();
                row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-3">
                            <img src="${item.image}" class="w-100" alt="${item.name}">
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold">${item.name}</span>
                            <span class="text-gray-400 fw-semibold">${moneyFormat.format(item.price)}</span>
                        </div>
                    </div>
                </td>
                <td class="text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <button type="button" class="btn btn-sm btn-icon btn-light-primary me-2 quantity-decrease" data-index="${index}">
                            <i class="bi bi-dash-lg"></i>
                        </button>
                        <span class="text-gray-800 fw-bold mx-2" data-kt-pos-element="item-quantity">${item.quantity}</span>
                        <button type="button" class="btn btn-sm btn-icon btn-light-primary ms-2 quantity-increase" data-index="${index}">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </td>
                <td class="text-end">
                    <span class="text-gray-800 fw-bold" data-kt-pos-element="item-total">${moneyFormat.format(item.price * item.quantity)}</span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-icon btn-light-danger delete-item" data-index="${index}">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            `;
            });

            form.querySelector('input[name="order_items"]').value = JSON.stringify(orderItems);

            attachQuantityListeners();
            attachDeleteListeners();
            calculateTotals();
        }

        var attachQuantityListeners = function() {
            document.querySelectorAll('.quantity-decrease').forEach(btn => {
                btn.addEventListener('click', function() {
                    var index = parseInt(this.getAttribute('data-index'));
                    if (orderItems[index].quantity > 1) {
                        orderItems[index].quantity -= 1;
                        updateOrderTable();
                    }
                });
            });

            document.querySelectorAll('.quantity-increase').forEach(btn => {
                btn.addEventListener('click', function() {
                    var index = parseInt(this.getAttribute('data-index'));
                    orderItems[index].quantity += 1;
                    updateOrderTable();
                });
            });
        }

        var attachDeleteListeners = function() {
            document.querySelectorAll('.delete-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    var index = parseInt(this.getAttribute('data-index'));
                    orderItems.splice(index, 1);
                    updateOrderTable();
                });
            });
        }

        var handleProductSelection = function() {
            var productItems = document.querySelectorAll('.product-item');
            productItems.forEach(item => {
                item.addEventListener('click', function() {
                    var productId = this.getAttribute('data-product-id');
                    var productName = this.querySelector('.fw-bold').textContent;
                    var productPrice = parseFloat(this.querySelector('.text-success')
                        .textContent.replace(/[^0-9.-]+/g, ""));
                    var productImage = this.querySelector('img').src;

                    var existingItem = orderItems.find(item => item.id === productId);
                    if (existingItem) {
                        existingItem.quantity += 1;
                    } else {
                        orderItems.push({
                            id: productId,
                            name: productName,
                            price: productPrice,
                            quantity: 1,
                            image: productImage
                        });
                    }

                    updateOrderTable();
                });
            });
        }

        var handleClearAll = function() {
            var clearAllBtn = document.getElementById('clear_all');
            clearAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                orderItems = [];
                updateOrderTable();

                document.getElementById('note').value = '';
            });
        }

        var handleDiscountInput = function() {
            var discountElement = form.querySelector('[data-kt-pos-element="discount"]');
            var discountInput = document.getElementById('discount_input');

            discountElement.addEventListener('click', function() {
                discountInput.value = discount;
                discountElement.classList.add('d-none');
                discountInput.classList.remove('d-none');
                discountInput.focus();
            });

            discountInput.addEventListener('blur', function() {
                var discountValue = parseFloat(discountInput.value) || 0;

                if (discountValue > form.querySelector('input[name="total"]').value) {
                    discountValue = form.querySelector('input[name="total"]').value;
                }

                discount = discountValue;

                discountElement.classList.remove('d-none');
                discountInput.classList.add('d-none');

                calculateTotals();
            });

            discountInput.addEventListener('input', function() {
                var discountValue = parseFloat(discountInput.value) || 0;

                if (discountValue > form.querySelector('input[name="total"]').value) {
                    discountValue = form.querySelector('input[name="total"]').value;
                }

                discount = discountValue;
                calculateTotals();
            });
        }

        var handleProductSearch = function() {
            var searchInput = document.getElementById('product_search');

            searchInput.addEventListener('input', function() {
                var searchTerm = searchInput.value.toLowerCase();
                var productItems = document.querySelectorAll('.product-item');

                productItems.forEach(item => {
                    var productName = item.querySelector('.fw-bold').textContent.toLowerCase();
                    if (productName.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        function printReceipt() {
            var receiptWindow = window.open('', '', 'width=300,height=500');

            if (!receiptWindow) {
                console.error('Failed to open receipt window. Check popup blocker settings.');
                return;
            }

            var receiptContent = `
            <html>
                <head>
                    <title>Receipt</title>
                    <style>
                        @media print {
                            body { font-size: 12px; font-family: Arial, sans-serif; }
                            .receipt-header { text-align: center; margin-bottom: 20px; }
                            .receipt-details, .receipt-footer { margin-top: 20px; }
                            .text-right { text-align: right; }
                            .text-center { text-align: center; }
                        }
                    </style>
                </head>
                <body>
                    <div class="receipt-header">
                        <h2>Khrabish</h2>
                        <p>Jdeideh, Sagesse street, Al Arzeh Building</p>
                        <p>Date: ${new Date().toLocaleString()}</p>
                    </div>
                    <div class="receipt-details">
                        <table>
                            ${orderItems.map(item => `
                                <tr>
                                    <td>${item.name} x${item.quantity}</td>
                                    <td class="text-right">${moneyFormat.format(item.price * item.quantity)}</td>
                                </tr>
                            `).join('')}
                        </table>
                    </div>
                    <div class="receipt-footer">
                        <table>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td class="text-right">${moneyFormat.format(grandTotal)}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount Paid:</strong></td>
                                <td class="text-right">${moneyFormat.format(amountPaid)}</td>
                            </tr>
                            <tr>
                                <td><strong>Change Due:</strong></td>
                                <td class="text-right">${moneyFormat.format(changeDue)}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="text-center">Thank you for your purchase!</div>
                </body>
            </html>
            `;

            receiptWindow.document.write(receiptContent);
            receiptWindow.document.close();

            receiptWindow.onload = function () {
                receiptWindow.print();
                receiptWindow.close();
            };
        }

        var handleCompleteOrder = function() {
            var completeOrderBtn = document.getElementById('complete_order');
            var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            var amountPaidInput = document.getElementById('amountPaid');
            var changeDueElement = document.getElementById('changeDue');
            var modalGrandTotal = document.getElementById('modalGrandTotal');
            var confirmPaymentBtn = document.getElementById('confirmPayment');
            var clearPaymentBtn = document.getElementById('payment-clear');

            var isProcessing = false;

            completeOrderBtn.addEventListener('click', function(e) {
                e.preventDefault();

                if (orderItems.length > 0) {
                    calculateTotals();
                    modalGrandTotal.textContent = moneyFormat.format(grandTotal);
                    paymentModal.show();
                } else {
                    Swal.fire({
                        text: "Please add items to your order before completing.",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });

            amountPaidInput.addEventListener('input', function() {
                var amountPaid = parseFloat(amountPaidInput.value) || 0;
                var changeDue = amountPaid - grandTotal;
                changeDueElement.textContent = moneyFormat.format(changeDue > 0 ? changeDue : 0);
            });

            document.querySelectorAll('.card.bg-primary').forEach(function(card) {
                card.addEventListener('click', function() {
                    var bankNoteValue = parseFloat(this.querySelector('b').textContent.replace(
                        /[^0-9.-]+/g, ""));
                    var currentAmountPaid = parseFloat(amountPaidInput.value) || 0;
                    amountPaidInput.value = (currentAmountPaid + bankNoteValue).toFixed(2);

                    var amountPaid = parseFloat(amountPaidInput.value) || 0;
                    var changeDue = amountPaid - grandTotal;
                    changeDueElement.textContent = moneyFormat.format(changeDue > 0 ?
                        changeDue : 0);
                });
            });

            confirmPaymentBtn.addEventListener('click', function() {
                if (isProcessing) return;

                var amountPaid = parseFloat(amountPaidInput.value) || 0;

                if (amountPaid >= grandTotal) {
                    isProcessing = true;
                    orderNumber++;
                    var formData = new FormData(form);
                    var orderData = {
                        orderItems: orderItems,
                        total: grandTotal,
                        amountPaid: amountPaid,
                        changeDue: amountPaid - grandTotal,
                        note: document.getElementById('note').value,
                        cashier: '{{ ucwords(auth()->user()->name) }}',
                        orderNumber: orderNumber
                    };

                    addNewOrderToUI(orderData);

                    if (!navigator.onLine) {
                        saveOrderOffline(orderData);
                        Swal.fire({
                            text: "You are offline. Your order has been saved and will be submitted once you're back online.",
                            icon: "info",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function() {
                            resetOrderForm();
                        });
                        paymentModal.hide();
                        isProcessing = false;
                        return;
                    }

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    text: "Order completed successfully!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(result) {
                                    if (result.isConfirmed) {
                                        printReceipt();

                                        orderItems = [];
                                        updateOrderTable();
                                        document.getElementById('note').value = '';
                                        amountPaidInput.value = 0;
                                    }
                                });
                            } else {
                                Swal.fire({
                                    text: data.message ||
                                        "An error occurred while processing your order.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                text: "An unexpected error occurred. Please try again.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        })
                        .finally(() => {
                            isProcessing = false;
                            paymentModal.hide();
                        });
                } else {
                    Swal.fire({
                        text: "The amount paid is less than the grand total.",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });

            clearPaymentBtn.addEventListener('click', function() {
                amountPaidInput.value = '';
                changeDueElement.textContent = moneyFormat.format(0);
            });
        };

        var barcodeInput = document.getElementById('barcode_input');
        var productFetchUrl = "{{ route('products.barcode', ['barcode' => 'test']) }}";
        var scannedBarcode = "";
        var debounceTimeout;

        var handleBarcodeInput = function() {
            document.addEventListener('keydown', function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();

                    var barcode = scannedBarcode.trim();
                    if (barcode.length === 0) return;

                    clearTimeout(debounceTimeout); // Clear any previous request
                    debounceTimeout = setTimeout(() => {
                        var url = productFetchUrl.replace('test', barcode);

                        fetch(url)
                            .then(response => {
                                if (!response.ok) throw new Error("Product not found.");
                                return response.json();
                            })
                            .then(product => addProductToOrder(product))
                            .catch(error => console.error('Error fetching product:', error))
                            .finally(() => {
                                scannedBarcode = "";
                            });
                    }, 300); // Wait 300ms before making a request
                } else if (event.key.length === 1) {
                    scannedBarcode += event.key;
                }
            });
        };

        var addProductToOrder = function(product) {
            var existingItem = orderItems.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                orderItems.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1,
                    image: product.image
                });
            }
            updateOrderTable();
        }

        function resetOrderForm() {
            orderItems = [];
            updateOrderTable();
            document.getElementById('note').value = '';
            amountPaidInput.value = 0;
            changeDueElement.textContent = moneyFormat.format(0);
        }

        function saveOrderOffline(orderData) {
            let offlineOrders = JSON.parse(localStorage.getItem('offlineOrders')) || [];
            offlineOrders.push(orderData);
            localStorage.setItem('offlineOrders', JSON.stringify(offlineOrders));
        }

        function syncOfflineOrders() {
            let offlineOrders = JSON.parse(localStorage.getItem('offlineOrders'));

            if (offlineOrders && offlineOrders.length > 0) {
                offlineOrders.forEach(orderData => {
                    fetch("{{ route('sync') }}", {
                            method: 'POST',
                            body: JSON.stringify(orderData),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                offlineOrders = offlineOrders.filter(order => order !== orderData);
                                localStorage.setItem('offlineOrders', JSON.stringify(offlineOrders));
                            }
                        })
                        .catch(error => console.error('Error syncing order:', error));
                });
            }
        }

        function addNewOrderToUI(orderData) {
            var emptyMessage = document.querySelector('.last_order_empty');
            if (emptyMessage) {
                emptyMessage.style.display = 'none';
            }

            var lastOrdersContainer = document.querySelector('.last_orders');
            var newOrderHtml = `
                <div class="last_order rounded p-4 bg-primary text-white mb-3">
                    <div class="row">
                        <div class="col-6">
                            Order NO: ${orderData.orderNumber}
                        </div>
                        <div class="col-6 text-right">
                            Cashier: ${orderData.cashier}
                        </div>
                        <div class="col-12 my-2 text-center">
                            <b><u>Items:</u></b> <br>
                            ${orderData.orderItems.map(item => `${item.name} : ${item.quantity}`).join('<br>')}
                        </div>
                        <div class="col-6">
                            Sub Total: ${orderData.total ? (orderData.total + discount).toFixed(2) : (orderData.total).toFixed(2)}
                        </div>
                        <div class="col-6 text-right">
                            Total: ${orderData.total.toFixed(2)}
                        </div>
                    </div>
                </div>
            `;

            lastOrdersContainer.insertAdjacentHTML('afterbegin', newOrderHtml);
        }

        window.addEventListener('online', function() {
            syncOfflineOrders();
        });

        return {
            init: function() {
                form = document.querySelector('#kt_pos_form');
                handleProductSelection();
                handleClearAll();
                handleCompleteOrder();
                handleDiscountInput();
                handleProductSearch();
                handleBarcodeInput();
            }
        };
    }();

    KTUtil.onDOMContentLoaded(function() {
        KTPosSystem.init();
    });
</script>