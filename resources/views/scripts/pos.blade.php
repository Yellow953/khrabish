<script>
    "use strict";

    class PosSystem {
        constructor() {
            // Configuration
            this.taxRate = {{ $business->tax->rate ?? 0 }};
            this.usdToLbpRate = {{ $exchange_rate ?? 1 }};
            this.systemCurrency = '{{ $currency->code }}';
            this.systemCurrency = '{{ $currency->code }}';
            this.moneyFormat = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: this.systemCurrency,
                minimumFractionDigits: 2
            });

            // State
            this.orderItems = [];
            this.discount = 0;
            this.grandTotal = 0;
            this.orderNumber = {{ $last_order ? (int)$last_order->order_number : 0 }};
            this.amountPaid = 0;
            this.changeDue = 0;
            this.isProcessing = false;

            // DOM Elements
            this.cachedElements = {
                form: document.querySelector('#kt_pos_form'),
                orderTable: document.getElementById('order_items'),
                noteInput: document.getElementById('note'),
                clientSelect: document.getElementById('client_id'),
                amountPaidInput: document.getElementById('amountPaid'),
                grandTotalUSD: document.getElementById('grandTotalUSD'),
                grandTotalLBP: document.getElementById('grandTotalLBP'),
                changeDueUSD: document.getElementById('changeDueUSD'),
                changeDueLBP: document.getElementById('changeDueLBP'),
                clearPaymentBtn: document.getElementById('payment-clear'),
                usdNotesTab: document.querySelector('[href="#usd_notes"]'),
                lbpNotesTab: document.querySelector('[href="#lbp_notes"]'),
                productSearch: document.getElementById('product_search'),
                discountInput: document.getElementById('discount_input'),
                discountElement: document.querySelector('[data-kt-pos-element="discount"]'),
                completeOrderBtn: document.getElementById('complete_order'),
                confirmPaymentBtn: document.getElementById('confirmPayment'),
                clearPaymentBtn: document.getElementById('payment-clear'),
                clearAllBtn: document.getElementById('clear_all'),
                barcodeInput: document.getElementById('barcode_input'),
                draftsContainer: document.getElementById('drafts-container')
            };

            //Barcode
            this.scannedBarcode = "";
            this.barcodeDebounceTimeout = null;

            // Constants
            this.SELECTORS = {
                PRODUCT_ITEM: '.product-item',
                QUANTITY_DECREASE: '.quantity-decrease',
                QUANTITY_INCREASE: '.quantity-increase',
                DELETE_ITEM: '.delete-item',
                BANK_NOTE_CARD: '.bank-note'
            };

            // Caching
            this.drafts = [];
            this.currentDraftId = null;
            this.STORAGE_KEYS = {
                ACTIVE_ORDER: 'pos_active_order',
                DRAFTS: 'pos_drafts'
            };

            // Initialize
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.checkOfflineOrders();
            this.loadActiveOrder();
            this.loadDrafts();
        }

        setupEventListeners() {
            // Product selection
            document.querySelectorAll(this.SELECTORS.PRODUCT_ITEM).forEach(item => {
                item.addEventListener('click', () => this.handleProductClick(item));
            });

            // Order table events
            this.cachedElements.orderTable.addEventListener('click', (e) => {
                const target = e.target;
                if (target.closest(this.SELECTORS.QUANTITY_DECREASE)) {
                    this.handleQuantityChange(target.closest(this.SELECTORS.QUANTITY_DECREASE), -1);
                } else if (target.closest(this.SELECTORS.QUANTITY_INCREASE)) {
                    this.handleQuantityChange(target.closest(this.SELECTORS.QUANTITY_INCREASE), 1);
                } else if (target.closest(this.SELECTORS.DELETE_ITEM)) {
                    this.handleDeleteItem(target.closest(this.SELECTORS.DELETE_ITEM));
                }
            });

            // Discount handling
            this.cachedElements.discountElement.addEventListener('click', () => this.showDiscountInput());
            this.cachedElements.discountInput.addEventListener('blur', () => this.updateDiscount());
            this.cachedElements.discountInput.addEventListener('input', () => this.debounce(this.updateDiscount, 300));

            // Product search
            this.cachedElements.productSearch.addEventListener('input', () => this.debounce(this.filterProducts(), 300));

            // Complete order
            this.cachedElements.completeOrderBtn.addEventListener('click', (e) => this.handleCompleteOrder(e));

            // Payment handling
            this.cachedElements.amountPaidInput.addEventListener('input', (e) => {
                this.handleAmountPaidInput(e.target.value);
            });
            this.cachedElements.confirmPaymentBtn.addEventListener('click', () => this.confirmPayment());
            this.cachedElements.clearPaymentBtn.addEventListener('click', () => this.clearPayment());

            // Fields Caching handling
            if ($(this.cachedElements.clientSelect).data('select2')) {
                $(this.cachedElements.clientSelect).on('select2:select', () => {
                    this.saveActiveOrder();
                });
                $(this.cachedElements.clientSelect).on('select2:unselect', () => {
                    this.saveActiveOrder();
                });
            }
            this.cachedElements.noteInput.addEventListener('input', () => {
                this.saveActiveOrder();
            });

            // Clear all
            this.cachedElements.clearAllBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.resetOrderForm();
            });

            // Bank Notes Modal
            document.querySelectorAll(this.SELECTORS.BANK_NOTE_CARD).forEach(card => {
                card.addEventListener('click', () => this.addBankNoteValue(card));
            });
            this.cachedElements.usdNotesTab.addEventListener('click', () => {
                this.paymentCurrency = 'USD';
                this.calculateChangeDue();
            });
            this.cachedElements.lbpNotesTab.addEventListener('click', () => {
                this.paymentCurrency = 'LBP';
                this.calculateChangeDue();
            });

            // Barcode Scanning
            document.addEventListener('keydown', (e) => this.handleBarcodeInput(e));

            // Online/offline events
            window.addEventListener('online', () => this.syncOfflineOrders());
        }

        // Core POS Functions
        handleProductClick(item) {
            const productData = {
                id: item.getAttribute('data-product-id'),
                name: item.querySelector('.fw-bold').textContent.trim(),
                price: parseFloat(item.querySelector('.text-success').textContent.replace(/[^0-9.-]+/g, "")),
                image: item.querySelector('img').src,
                variants: JSON.parse(item.getAttribute('data-variants')),
                element: item
            };

            if (productData.variants.length > 0) {
                this.showVariantSelectionModal(productData);
            } else {
                this.addProductToOrder(productData);
            }
        }

        loadActiveOrder() {
            const savedOrder = localStorage.getItem(this.STORAGE_KEYS.ACTIVE_ORDER);
            if (savedOrder) {
                try {
                    const orderData = JSON.parse(savedOrder);
                    this.orderItems = orderData.items || [];
                    this.discount = orderData.discount || 0;
                    this.updateOrderTable();

                    if (orderData.clientId) {
                        this.cachedElements.clientSelect.value = orderData.clientId;
                        if ($('#client_id').data('select2')) {
                            $('#client_id').trigger('change');
                        }
                    }

                    if (orderData.note) {
                        this.cachedElements.noteInput.value = orderData.note;
                    }
                } catch (e) {
                    console.error('Failed to load active order:', e);
                }
            }
        }

        saveActiveOrder() {
            const orderData = {
                items: this.orderItems,
                discount: this.discount,
                clientId: this.cachedElements.clientSelect.value,
                note: this.cachedElements.noteInput.value,
                timestamp: new Date().getTime()
            };

            const currentOrder = localStorage.getItem(this.STORAGE_KEYS.ACTIVE_ORDER);
            if (!currentOrder || JSON.stringify(orderData) !== currentOrder) {
                localStorage.setItem(this.STORAGE_KEYS.ACTIVE_ORDER, JSON.stringify(orderData));
            }
        }

        clearActiveOrder() {
            localStorage.removeItem(this.STORAGE_KEYS.ACTIVE_ORDER);
            this.currentDraftId = null;
        }

        showDraftNameModal() {
            return Swal.fire({
                title: 'Save Order as Draft',
                input: 'text',
                inputLabel: 'Draft Name',
                inputValue: `Draft ${this.drafts.length + 1}`,
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please enter a draft name';
                    }
                }
            });
        }

        loadDrafts() {
            const savedDrafts = localStorage.getItem(this.STORAGE_KEYS.DRAFTS);
            this.drafts = savedDrafts ? JSON.parse(savedDrafts) : [];
            this.renderDraftsUI();
        }

        saveDraft(draftName = `Draft ${this.drafts.length + 1}`) {
            let finalName = draftName;
            let counter = 1;
            while (this.drafts.some(d => d.name === finalName)) {
                finalName = `${draftName} (${counter++})`;
            }

            const draft = {
                id: 'draft_' + new Date().getTime(),
                name: finalName,
                items: [...this.orderItems],
                discount: this.discount,
                clientId: this.cachedElements.clientSelect.value,
                note: this.cachedElements.noteInput.value,
                timestamp: new Date().getTime()
            };

            this.drafts = this.drafts.filter(d =>
                JSON.stringify(d.items) !== JSON.stringify(draft.items) ||
                d.clientId !== draft.clientId ||
                d.note !== draft.note
            );

            this.drafts.unshift(draft);
            this.saveDraftsToStorage();
            this.renderDraftsUI();

            return draft;
        }

        scrollToOrderForm() {
            const orderForm = document.getElementById('kt_pos_form');
            if (orderForm) {
                const offset = 100;
                const topPos = orderForm.getBoundingClientRect().top + window.pageYOffset - offset;

                window.scrollTo({
                    top: topPos,
                    behavior: 'smooth'
                });
            }
        }

        loadDraft(draftId) {
            const draft = this.drafts.find(d => d.id === draftId);
            if (draft) {
                this.orderItems = [...draft.items];
                this.discount = draft.discount || 0;
                this.currentDraftId = draftId;

                this.updateOrderTable();
                this.cachedElements.clientSelect.value = draft.clientId || '';
                this.cachedElements.noteInput.value = draft.note || '';

                if ($('#client_id').data('select2')) {
                    $('#client_id').trigger('change');
                }

                this.saveActiveOrder();

                this.scrollToOrderForm();
            }
        }

        deleteDraft(draftId) {
            this.drafts = this.drafts.filter(d => d.id !== draftId);
            this.saveDraftsToStorage();
            this.renderDraftsUI();

            if (this.currentDraftId === draftId) {
                this.currentDraftId = null;
            }
        }

        saveDraftsToStorage() {
            localStorage.setItem(this.STORAGE_KEYS.DRAFTS, JSON.stringify(this.drafts));
        }

        renderDraftsUI() {
            const draftsContainer = document.getElementById('drafts-container') || this.createDraftsUI();
            const draftsList = draftsContainer.querySelector('.drafts-list');

            if (this.drafts.length === draftsList.children.length &&
                this.drafts.length > 0 &&
                draftsList.querySelector('.draft-item')) {

                draftsList.querySelectorAll('.draft-item').forEach(el => {
                    const draftId = el.dataset.id;
                    el.classList.toggle('active-draft', this.currentDraftId === draftId);
                });
                return;
            }

            draftsList.innerHTML = '';

            if (this.drafts.length === 0) {
                draftsList.innerHTML = `
                    <div class="empty-drafts p-4 text-center">
                        <i class="bi bi-folder-x fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-0">No saved drafts yet</p>
                    </div>
                `;
                return;
            }

            this.drafts.forEach(draft => {
                const draftEl = document.createElement('div');
                draftEl.className = `draft-item p-3 border-bottom ${this.currentDraftId === draft.id ? 'active-draft bg-light-primary' : ''}`;
                draftEl.dataset.id = draft.id;
                draftEl.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="me-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                <strong class="draft-name">${draft.name}</strong>
                            </div>
                            <div class="d-flex small text-muted">
                                <span class="me-3">
                                    <i class="bi bi-clock-history me-1"></i>
                                    ${new Date(draft.timestamp).toLocaleTimeString()}
                                </span>
                                <span>
                                    <i class="bi bi-box-seam me-1"></i>
                                    ${draft.items.length} items
                                </span>
                            </div>
                            ${draft.note ? `<div class="mt-2 small text-muted"><i class="bi bi-pencil-square me-1"></i>${draft.note.substring(0, 30)}${draft.note.length > 30 ? '...' : ''}</div>` : ''}
                        </div>
                        <div class="draft-actions btn-group btn-group-sm">
                            <button class="btn btn-sm btn-primary load-draft" data-id="${draft.id}" title="Load Draft">
                                <i class="bi bi-arrow-up-circle"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-draft" data-id="${draft.id}" title="Delete Draft">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                draftsList.appendChild(draftEl);
            });

            draftsList.querySelectorAll('.load-draft').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.loadDraft(btn.dataset.id);
                });
            });

            draftsList.querySelectorAll('.delete-draft').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const { isConfirmed } = await Swal.fire({
                        title: 'Delete Draft?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    });

                    if (isConfirmed) {
                        this.deleteDraft(btn.dataset.id);
                        this.showAlert('Draft deleted successfully!', 'success');
                    }
                });
            });

            draftsList.querySelectorAll('.draft-item').forEach(item => {
                item.addEventListener('click', () => {
                    this.loadDraft(item.dataset.id);
                });
            });
        }

        createDraftsUI() {
            const draftsHTML = `
                <div class="card mt-4 border-custom" id="drafts-container">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Saved Drafts</h4>
                        <span>
                            <i class="fa fa-save"></i>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="drafts-list" style="max-height: 300px; overflow-y: auto;"></div>
                    </div>
                </div>
            `;

            this.cachedElements.form.insertAdjacentHTML('afterend', draftsHTML);

            document.getElementById('save-as-draft').addEventListener('click', async (e) => {
                e.preventDefault();

                if (this.orderItems.length === 0 && !this.cachedElements.noteInput.value && !this.cachedElements.clientSelect.value) {
                    this.showAlert('Cannot save an empty draft', 'warning');
                    return;
                }

                const { value: draftName } = await this.showDraftNameModal();

                if (draftName) {
                    const draft = this.saveDraft(draftName);
                    await this.showAlert('Draft saved successfully!', 'success');

                    this.resetOrderForm();

                    this.currentDraftId = draft.id;
                    this.renderDraftsUI();
                }
            });

            return document.getElementById('drafts-container');
        }

        addProductToOrder(productData, options = []) {
            for (const option of options) {
                const variantOption = this.findVariantOption(productData, option.variantId, option.optionId);
                const availableQuantity = variantOption ?
                    (variantOption.quantity === null ?
                        parseInt(productData.element.getAttribute('data-quantity')) || 0 :
                        variantOption.quantity) :
                    parseInt(productData.element.getAttribute('data-quantity')) || 0;

                if (option.quantity > availableQuantity) {
                    this.showAlert(`Not enough stock for "${variantOption?.value || productData.name}". Only ${availableQuantity} available.`, 'warning');
                    return false;
                }
            }

            const existingItem = this.findExistingOrderItem(productData.id, options);

            if (existingItem) {
                const wouldExceed = options.some(option => {
                    const variantOption = this.findVariantOption(productData, option.variantId, option.optionId);
                    const availableQuantity = variantOption ?
                        (variantOption.quantity === null ?
                            parseInt(productData.element.getAttribute('data-quantity')) || 0 :
                            variantOption.quantity) :
                        parseInt(productData.element.getAttribute('data-quantity')) || 0;

                    return (existingItem.quantity + 1) > availableQuantity;
                });

                if (wouldExceed) {
                    this.showAlert(`Cannot add more of "${productData.name}" due to stock limitations.`, 'warning');
                    return false;
                }

                existingItem.quantity += 1;
            } else {
                this.orderItems.push({
                    id: productData.id,
                    name: productData.name,
                    price: productData.price,
                    quantity: 1,
                    image: productData.image,
                    options: options.map(option => ({
                        ...option,
                        quantity: option.quantity
                    }))
                });
            }

            this.updateOrderTable();
            return true;
        }

        findVariantOption(productData, variantId, optionId) {
            const variant = productData.variants.find(v => v.id == variantId);
            if (variant) {
                return variant.options.find(o => o.id == optionId);
            }
            return null;
        }

        findExistingOrderItem(productId, options) {
            return this.orderItems.find(item =>
                item.id === productId &&
                JSON.stringify(item.options) === JSON.stringify(options)
            );
        }

        handleQuantityChange(button, change) {
            const index = parseInt(button.getAttribute('data-index'));
            const item = this.orderItems[index];
            const productElement = document.querySelector(`.product-item[data-product-id="${item.id}"]`);

            if (change === -1 && item.quantity > 1) {
                item.quantity += change;
                this.updateOrderTable();
            } else if (change === 1) {
                if (item.options && item.options.length > 0) {
                    const productData = {
                        id: item.id,
                        variants: JSON.parse(productElement.getAttribute('data-variants')),
                        element: productElement
                    };

                    const hasInsufficientQuantity = item.options.some(option => {
                        const variantOption = this.findVariantOption(productData, option.variantId, option.optionId);
                        const availableQuantity = variantOption ?
                            (variantOption.quantity === null ?
                                parseInt(productElement.getAttribute('data-quantity')) || 0 :
                                variantOption.quantity) :
                            parseInt(productElement.getAttribute('data-quantity')) || 0;

                        return (item.quantity + 1) > availableQuantity;
                    });

                    if (hasInsufficientQuantity) {
                        this.showAlert(`Cannot add more due to stock limitations.`, 'warning');
                        return;
                    }
                }
                else {
                    const availableQuantity = parseInt(productElement.getAttribute('data-quantity')) || 0;
                    if (item.quantity >= availableQuantity) {
                        this.showAlert(`Cannot add more of "${item.name}". Only ${availableQuantity} available.`, 'warning');
                        return;
                    }
                }

                item.quantity += change;
                this.updateOrderTable();
            }
        }

        handleDeleteItem(button) {
            const index = parseInt(button.getAttribute('data-index'));
            this.orderItems.splice(index, 1);
            this.updateOrderTable();
        }

        updateOrderTable() {
            const fragment = document.createDocumentFragment();

            this.orderItems.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = this.getOrderRowHtml(item, index);
                fragment.appendChild(row);
            });

            this.cachedElements.orderTable.innerHTML = '';
            this.cachedElements.orderTable.appendChild(fragment);
            this.cachedElements.form.querySelector('input[name="order_items"]').value = JSON.stringify(this.orderItems);
            this.calculateTotals();
            this.saveActiveOrder();
        }

        getOrderRowHtml(item, index) {
            return `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-3">
                            <img src="${item.image}" class="w-100" alt="${item.name}">
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold">${item.name}</span>
                            ${item.options?.length > 0 ? `
                                <div class="text-gray-400 fw-semibold">
                                    ${item.options.map(option => `
                                        ${option.value}${option.quantity > 1 ? ` (${option.quantity}x)` : ''}
                                    `).join(', ')}
                                </div>
                            ` : ''}
                            <span class="text-gray-400 fw-semibold">${this.moneyFormat.format(item.price)}</span>
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
                    <span class="text-gray-800 fw-bold" data-kt-pos-element="item-total">
                        ${this.moneyFormat.format(
                            (item.price * item.quantity) +
                            (item.options?.reduce((sum, option) => {
                                const optionPrice = option.optionPrice || 0;
                                return sum + (optionPrice * option.quantity * item.quantity);
                            }, 0) || 0)
                        )}
                    </span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-icon btn-light-danger delete-item" data-index="${index}">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            `;
        }

        handleCompleteOrder(e) {
            e.preventDefault();

            if (this.orderItems.length > 0) {
                this.calculateTotals();

                this.cachedElements.grandTotalUSD.textContent = `$${this.grandTotalUSD.toFixed(2)}`;
                this.cachedElements.grandTotalLBP.textContent = `${Math.round(this.grandTotalLBP)} LBP`;

                if (!this.paymentModal) {
                    this.paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                }

                this.paymentModal.show();
            } else {
                this.showAlert("Please add items to your order before completing.", "warning");
            }
        }

        // Calculation Functions
        calculateTotals() {
            const subtotal = this.orderItems.reduce((sum, item) => {
                const itemBaseTotal = item.price * item.quantity;

                const optionsTotal = item.options?.reduce((optSum, option) => {
                    const optionPrice = option.optionPrice || 0;
                    return optSum + (optionPrice * option.quantity * item.quantity);
                }, 0) || 0;

                return sum + itemBaseTotal + optionsTotal;
            }, 0);

            const tax = subtotal * (this.taxRate / 100);
            this.grandTotal = subtotal + tax - this.discount;

            if (this.discount > subtotal + tax) {
                this.discount = subtotal + tax;
                this.grandTotal = 0;
            }

            this.grandTotalUSD = this.systemCurrency === 'USD' ? this.grandTotal : this.convertCurrency(this.grandTotal, 'LBP', 'USD');
            this.grandTotalLBP = this.systemCurrency === 'LBP' ? this.grandTotal : this.convertCurrency(this.grandTotal, 'USD', 'LBP');

            this.updateTotalDisplay(subtotal, tax);
        }

        updateTotalDisplay(subtotal, tax) {
            this.cachedElements.form.querySelector('[data-kt-pos-element="total"]').innerHTML =
                this.moneyFormat.format(subtotal);
            this.cachedElements.form.querySelector('[data-kt-pos-element="discount"]').innerHTML =
                this.moneyFormat.format(this.discount);
            this.cachedElements.form.querySelector('[data-kt-pos-element="tax"]').innerHTML =
                this.moneyFormat.format(tax);
            this.cachedElements.form.querySelector('[data-kt-pos-element="grant-total"]').innerHTML =
                this.moneyFormat.format(this.grandTotal);

            this.cachedElements.form.querySelector('input[name="total"]').value = subtotal;
            this.cachedElements.form.querySelector('input[name="tax"]').value = tax;
            this.cachedElements.form.querySelector('input[name="discount"]').value = this.discount;
            this.cachedElements.form.querySelector('input[name="grand_total"]').value = this.grandTotal;
        }

        calculateChangeDue() {
            const amountPaid = this.cachedElements.amountPaidInput.value;
            const amountPaidNum = amountPaid ? parseFloat(amountPaid) : 0;

            if (this.paymentCurrency === 'USD') {
                this.amountPaid = amountPaidNum;
                this.changeDue = this.amountPaid - this.grandTotalUSD;

                this.cachedElements.changeDueUSD.textContent = `$${(this.changeDue).toFixed(2)}`;
                this.cachedElements.changeDueLBP.textContent = `${Math.round(this.convertCurrency(this.changeDue, 'USD', 'LBP'))} LBP`;
            } else {
                this.amountPaid = amountPaidNum;
                this.changeDue = this.amountPaid - this.grandTotalLBP;

                this.cachedElements.changeDueLBP.textContent = `${Math.round(this.changeDue)} LBP`;
                this.cachedElements.changeDueUSD.textContent = `$${(this.convertCurrency(this.changeDue, 'LBP', 'USD')).toFixed(2)}`;
            }
        }

        handleAmountPaidInput(value) {
            let cleanedValue = value.replace(/[^0-9.]/g, '');
            if ((cleanedValue.match(/\./g) || []).length > 1) {
                cleanedValue = cleanedValue.substring(0, cleanedValue.lastIndexOf('.'));
            }
            this.cachedElements.amountPaidInput.value = cleanedValue;
            this.calculateChangeDue();
        }

        // Payment Processing
        async confirmPayment() {
            if (this.isProcessing) return;

            try {
                this.validatePayment();
                this.isProcessing = true;

                this.updateStockQuantities();
                this.orderNumber++;

                const orderData = this.createOrderData();
                this.addNewOrderToUI(orderData);

                if (!navigator.onLine) {
                    await this.handleOfflineOrder(orderData);
                    return;
                }

                await this.submitOrderToServer(orderData);
                this.showAlert("Order completed successfully!", "success");
                this.printReceipt();

                this.clearActiveOrder();
                this.resetOrderForm();

            } catch (error) {
                this.showAlert(error.message, "error");
            } finally {
                this.isProcessing = false;
                this.paymentModal?.hide();
            }
        }

        validatePayment() {
            const amountPaid = parseFloat(this.cachedElements.amountPaidInput.value) || 0;

            if (this.orderItems.length === 0) {
                throw new Error("Please add items to your order before completing.");
            }

            if (this.paymentCurrency === 'USD') {
                if (amountPaid < this.grandTotalUSD) {
                    throw new Error("The amount paid is less than the grand total.");
                }
            } else {
                if (amountPaid < this.grandTotalLBP) {
                    throw new Error("The amount paid is less than the grand total.");
                }
            }

            this.paymentCurrency = this.paymentCurrency;
        }

        updateStockQuantities() {
            this.orderItems.forEach(item => {
                const productElement = document.querySelector(`.product-item[data-product-id="${item.id}"]`);
                if (productElement) {
                    const currentQuantity = parseInt(productElement.getAttribute('data-quantity')) || 0;
                    const newQuantity = currentQuantity - item.quantity;
                    productElement.setAttribute('data-quantity', newQuantity);

                    if (newQuantity <= 0) {
                        productElement.classList.add('out-of-stock');
                    }
                }
            });
        }

        createOrderData() {
            return {
                orderItems: this.orderItems,
                total: this.grandTotal,
                totalUSD: this.systemCurrency === 'USD' ? this.grandTotal : this.convertCurrency(this.grandTotal, 'LBP', 'USD'),
                totalLBP: this.systemCurrency === 'LBP' ? this.grandTotal : this.convertCurrency(this.grandTotal, 'USD', 'LBP'),
                amountPaid: this.amountPaid,
                amountPaidCurrency: this.paymentCurrency,
                amountPaidUSD: this.paymentCurrency === 'USD' ? this.amountPaid : this.convertCurrency(this.amountPaid, 'LBP', 'USD'),
                amountPaidLBP: this.paymentCurrency === 'LBP' ? this.amountPaid : this.convertCurrency(this.amountPaid, 'USD', 'LBP'),
                changeDue: this.changeDue,
                changeDueCurrency: this.paymentCurrency,
                changeDueUSD: this.paymentCurrency === 'USD' ? this.changeDue : this.convertCurrency(this.changeDue, 'LBP', 'USD'),
                changeDueLBP: this.paymentCurrency === 'LBP' ? this.changeDue : this.convertCurrency(this.changeDue, 'USD', 'LBP'),
                note: this.cachedElements.noteInput.value,
                cashier: '{{ ucwords(auth()->user()->name) }}',
                orderNumber: this.orderNumber,
                client_id: this.cachedElements.clientSelect.value,
                paymentCurrency: this.paymentCurrency,
                exchangeRate: this.paymentCurrency == 'USD' ? 1 : this.usdToLbpRate
            };
        }

        async submitOrderToServer(orderData) {
            const formData = new FormData(this.cachedElements.form);

            formData.append('amount_paid', orderData.amountPaid);
            formData.append('change_due', orderData.changeDue);
            formData.append('payment_currency', orderData.paymentCurrency);
            formData.append('exchange_rate', orderData.exchangeRate);

            const response = await fetch(this.cachedElements.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || "An error occurred while processing your order.");
            }
        }

        // Variant Selection Modal
        showVariantSelectionModal(productData) {
            if (this.variantModal && this.variantModal._element) {
                this.variantModal.hide();
                this.variantModal.dispose();
                this.variantModal._element.remove();
            }

            const modal = document.createElement('div');
            modal.classList.add('modal', 'fade');
            modal.innerHTML = this.getVariantModalHtml(productData);
            document.body.appendChild(modal);

            this.variantModal = new bootstrap.Modal(modal);
            this.variantModal.show();

            modal.querySelectorAll('.variant-option-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', (e) => {
                    const quantityInput = e.target.closest('.form-check').querySelector('.variant-quantity');
                    if (quantityInput) {
                        quantityInput.style.display = e.target.checked ? 'block' : 'none';
                    }
                });
            });

            modal.querySelector('#add-to-order').addEventListener('click', () => {
                const selectedOptions = this.getSelectedVariantOptions(modal);

                if (selectedOptions.length > 0) {
                    this.addProductToOrder(productData, selectedOptions);
                    this.variantModal.hide();
                } else {
                    this.showAlert('Please select at least one variant option.', 'warning');
                }
            });

            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
                this.variantModal = null;
            });
        }

        getVariantModalHtml(productData) {
            return `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Variant for ${productData.name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="variant-selection-form">
                                ${productData.variants.map(variant => `
                                    <div class="mb-3">
                                        <label class="form-label">${variant.title} (${variant.type === 'single' ? 'Select one' : 'Select multiple'})</label>
                                        ${variant.type === 'single' ? `
                                            <select class="form-select variant-option-select" data-variant-id="${variant.id}">
                                                <option value="">Select an option</option>
                                                ${variant.options.map(option => `
                                                    <option value="${option.id}"
                                                            data-price="${option.price}"
                                                            data-quantity="${option.quantity}">
                                                        ${option.value} - ${this.moneyFormat.format(option.price)}
                                                        ${option.quantity != null ? '(' + option.quantity + ' available)': ''}
                                                    </option>
                                                `).join('')}
                                            </select>
                                        ` : `
                                            <div class="variant-options-container" data-variant-id="${variant.id}">
                                                ${variant.options.map(option => `
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input variant-option-checkbox"
                                                            type="checkbox"
                                                            value="${option.id}"
                                                            id="option-${option.id}"
                                                            data-price="${option.price}"
                                                            data-quantity="${option.quantity}">
                                                        <label class="form-check-label" for="option-${option.id}">
                                                            ${option.value} - ${this.moneyFormat.format(option.price)}
                                                            ${option.quantity != null ? '(' + option.quantity + ')' : ''}
                                                        </label>
                                                        <input type="number"
                                                            class="form-control form-control-sm mt-1 variant-quantity"
                                                            value="1"
                                                            min="1"
                                                            max="${option.quantity}"
                                                            style="width: 80px; display: none;"
                                                            data-option-id="${option.id}">
                                                    </div>
                                                `).join('')}
                                            </div>
                                        `}
                                    </div>
                                `).join('')}
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="add-to-order">Add to Order</button>
                        </div>
                    </div>
                </div>
            `;
        }

        getSelectedVariantOptions(modal) {
            const selectedOptions = [];

            modal.querySelectorAll('.variant-option-select').forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption.value) {
                    const optionPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                    selectedOptions.push({
                        variantId: select.getAttribute('data-variant-id'),
                        optionId: selectedOption.value,
                        value: selectedOption.textContent.split(' - ')[0],
                        optionPrice: optionPrice === 0 ? null : optionPrice,
                        quantity: 1
                    });
                }
            });

            modal.querySelectorAll('.variant-options-container').forEach(container => {
                const variantId = container.getAttribute('data-variant-id');
                container.querySelectorAll('.variant-option-checkbox:checked').forEach(checkbox => {
                    const quantityInput = container.querySelector(`.variant-quantity[data-option-id="${checkbox.value}"]`);
                    const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
                    const optionPrice = parseFloat(checkbox.getAttribute('data-price')) || 0;

                    selectedOptions.push({
                        variantId: variantId,
                        optionId: checkbox.value,
                        value: checkbox.nextElementSibling.textContent.split(' - ')[0],
                        optionPrice: optionPrice === 0 ? null : optionPrice,
                        quantity: quantity
                    });
                });
            });

            return selectedOptions;
        }

        // Utility Functions
        debounce(func, delay) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        showAlert(message, type = 'success') {
            return Swal.fire({
                text: message,
                icon: type,
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }

        convertCurrency(amount, fromCurrency, toCurrency) {
            if (fromCurrency === toCurrency) return amount;

            if (fromCurrency === 'USD' && toCurrency === 'LBP') {
                return amount * this.usdToLbpRate;
            } else if (fromCurrency === 'LBP' && toCurrency === 'USD') {
                return amount / this.usdToLbpRate;
            }
            return amount;
        }

        resetOrderForm() {
            this.orderItems = [];
            this.updateOrderTable();
            this.cachedElements.noteInput.value = '';

            this.cachedElements.amountPaidInput.value = '';
            this.amountPaid = 0;
            this.changeDue = 0;
            this.paymentCurrency = this.systemCurrency;

            this.cachedElements.grandTotalUSD.textContent = '$0.00';
            this.cachedElements.grandTotalLBP.textContent = '0 LBP';
            this.cachedElements.changeDueUSD.textContent = '$0.00';
            this.cachedElements.changeDueLBP.textContent = '0 LBP';

            this.cachedElements.clientSelect.value = '';
            if ($('#client_id').data('select2')) {
                $('#client_id').trigger('change');
            }

            this.clearActiveOrder();

            const usdTab = document.querySelector('[href="#usd_notes"]');
            if (usdTab) {
                new bootstrap.Tab(usdTab).show();
            }
        }

        clearPayment() {
            this.cachedElements.amountPaidInput.value = '';
            this.amountPaid = 0;
            this.changeDue = 0;
            this.cachedElements.changeDueUSD.textContent = '$0.00';
            this.cachedElements.changeDueLBP.textContent = '0 LBP';
            this.paymentCurrency = this.systemCurrency;
        }

        addBankNoteValue(card) {
            const tabContent = card.closest('.tab-pane');
            const isUSDNote = tabContent && tabContent.id === 'usd_notes';

            const valueText = card.querySelector('div:last-child').textContent;
            const bankNoteValue = parseFloat(valueText.replace(/[^0-9.-]+/g, ""));

            const currentAmountPaid = parseFloat(this.cachedElements.amountPaidInput.value) || 0;

            if (isUSDNote) {
                this.cachedElements.amountPaidInput.value = (currentAmountPaid + bankNoteValue).toFixed(2);
                this.paymentCurrency = 'USD';
                this.cachedElements.usdNotesTab.click();
            } else {
                this.cachedElements.amountPaidInput.value = Math.round(currentAmountPaid + bankNoteValue);
                this.paymentCurrency = 'LBP';
                this.cachedElements.lbpNotesTab.click();
            }

            this.calculateChangeDue();
        }

        // Offline Functionality
        saveOrderOffline(orderData) {
            let offlineOrders = JSON.parse(localStorage.getItem('offlineOrders')) || [];
            offlineOrders.push(orderData);
            localStorage.setItem('offlineOrders', JSON.stringify(offlineOrders));
        }

        async syncOfflineOrders() {
            const offlineOrders = JSON.parse(localStorage.getItem('offlineOrders')) || [];

            if (offlineOrders.length > 0) {
                for (const orderData of offlineOrders) {
                    try {
                        await fetch("{{ route('sync') }}", {
                            method: 'POST',
                            body: JSON.stringify(orderData),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const updatedOrders = offlineOrders.filter(order => order !== orderData);
                        localStorage.setItem('offlineOrders', JSON.stringify(updatedOrders));
                    } catch (error) {
                        console.error('Error syncing order:', error);
                    }
                }
            }
        }

        async handleOfflineOrder(orderData) {
            this.saveOrderOffline(orderData);
            await this.showAlert(
                "You are offline. Your order has been saved and will be submitted once you're back online.",
                "info"
            );
            this.resetOrderForm();
        }

        checkOfflineOrders() {
            if (navigator.onLine && localStorage.getItem('offlineOrders')) {
                this.syncOfflineOrders();
            }
        }

        // Receipt Printing
        printReceipt() {
            const drawerKick = '\x1B\x70\x00\x19\xFA';
            const receiptWindow = window.open('', '', 'width=300,height=500');

            if (!receiptWindow) {
                console.error('Failed to open receipt window. Check popup blocker settings.');
                return;
            }

            receiptWindow.document.write(this.getReceiptHtml(drawerKick));
            receiptWindow.document.close();

            receiptWindow.onload = () => {
                receiptWindow.print();
                receiptWindow.close();
            };
        }

        getReceiptHtml(drawerKick) {
            const formatLBP = (amount) => {
                return Math.round(amount).toLocaleString() + ' L.L.';
            };

            const totalLBP = this.systemCurrency === 'USD'
                ? this.convertCurrency(this.grandTotal, 'USD', 'LBP')
                : this.grandTotal;

            const amountPaidLBP = this.paymentCurrency === 'USD'
                ? this.convertCurrency(this.amountPaid, 'USD', 'LBP')
                : this.amountPaid;

            const changeDueLBP = this.paymentCurrency === 'USD'
                ? this.convertCurrency(this.changeDue, 'USD', 'LBP')
                : this.changeDue;

            return `
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
                                .dual-currency { display: flex; flex-direction: column; }
                                .currency-item { flex: 1; }
                                .currency-label { font-size: 10px; color: #666; }
                                hr { border-top: 1px dashed #ccc; }
                                .receipt-footer table tr td { border-bottom: 1px dashed #ccc; }
                            }
                        </style>
                    </head>
                    <body>
                        <pre>${drawerKick}</pre>
                        <div class="receipt-header">
                            <h2>Khrabish</h2>
                            <p>Date: ${new Date().toLocaleString()}</p>
                            <p>Payment Currency: ${this.paymentCurrency}</p>
                        </div>
                        <hr>
                        <div class="receipt-details">
                            <table width="100%">
                                ${this.orderItems.map(item => `
                                    <tr>
                                        <td>${item.name} x${item.quantity}</td>
                                        <td class="text-right">${this.moneyFormat.format(item.price * item.quantity)}</td>
                                    </tr>
                                `).join('')}
                            </table>
                        </div>
                        <hr>
                        <div class="receipt-footer">
                            <table width="100%">
                                <tr>
                                    <td><strong>Total:</strong></td>
                                    <td class="text-right">
                                        <div class="dual-currency">
                                            <div class="currency-item">
                                                ${this.moneyFormat.format(this.grandTotal)}
                                            </div>
                                            <div class="currency-item">
                                                ${this.systemCurrency === 'USD' ? formatLBP(totalLBP) : '$' + this.convertCurrency(this.grandTotal, 'LBP', 'USD').toFixed(2)}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Amount Paid:</strong></td>
                                    <td class="text-right">
                                        <div class="dual-currency">
                                            <div class="currency-item">
                                                ${this.paymentCurrency === 'USD' ? '$' + this.amountPaid.toFixed(2) : formatLBP(this.amountPaid)}
                                            </div>
                                            <div class="currency-item">
                                                ${this.paymentCurrency === 'USD' ? formatLBP(amountPaidLBP) : '$' + this.convertCurrency(this.amountPaid, 'LBP', 'USD').toFixed(2)}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Change Due:</strong></td>
                                    <td class="text-right">
                                        <div class="dual-currency">
                                            <div class="currency-item">
                                                ${this.paymentCurrency === 'USD' ? '$' + (this.changeDue > 0 ? this.changeDue.toFixed(2) : '0.00') : formatLBP(this.changeDue > 0 ? this.changeDue : 0)}
                                            </div>
                                            <div class="currency-item">
                                                ${this.paymentCurrency === 'USD' ? formatLBP(changeDueLBP > 0 ? changeDueLBP : 0) : '$' + (this.convertCurrency(this.changeDue, 'LBP', 'USD') > 0 ? this.convertCurrency(this.changeDue, 'LBP', 'USD').toFixed(2) : '0.00')}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <br>
                        <div class="text-center">Thank you for your purchase!</div>
                        <br>
                        <div class="text-center">{!! QrCode::size(50)->generate(route('home')) !!}</div>
                        <br>
                        <div class="text-center">Visit our online menu...</div>
                    </body>
                </html>
            `;
        }

        openCashDrawer() {
            const drawerKick = '\x1B\x70\x00\x19\xFA';
            const popup = window.open('', '', 'width=100,height=100');

            if (!popup) {
                this.showAlert('Popup blocked. Please allow popups for this site.', 'error');
                return;
            }

            popup.document.write(`
                <html>
                    <head>
                        <style>
                            @media print {
                                body { margin: 0; }
                                pre { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        <pre>${drawerKick}</pre>
                    </body>
                </html>
            `);

            popup.document.close();
            popup.onload = () => {
                popup.print();
                popup.close();
            };
        }

        // Barcode Handling
        handleBarcodeInput(event) {
            if (['INPUT', 'TEXTAREA'].includes(event.target.tagName)) return;

            if (event.key === "Enter") {
                event.preventDefault();
                const barcode = this.scannedBarcode.trim();

                if (barcode.length === 0) return;

                clearTimeout(this.barcodeDebounceTimeout);
                this.barcodeDebounceTimeout = setTimeout(() => {
                    const productItem = this.findProductByBarcode(barcode);

                    if (productItem) {
                        productItem.click();
                    } else {
                        this.showAlert('Product not found for barcode: ' + barcode, 'warning');
                    }

                    this.scannedBarcode = "";
                }, 300);
            }else if (event.key.length === 1 && !event.ctrlKey && !event.altKey && !event.metaKey) {
                this.scannedBarcode += event.key;
            }
        }

        findProductByBarcode(barcode) {
            const items = document.querySelectorAll(this.SELECTORS.PRODUCT_ITEM);

            for (const item of items) {
                const barcodes = JSON.parse(item.getAttribute('data-barcodes') || '[]');
                if (barcodes.some(b => b.barcode === barcode)) {
                    return item;
                }
            }
            return null;
        }

        // Product Search
        filterProducts() {
            const searchTerm = this.cachedElements.productSearch.value.toLowerCase().trim();

            if (!searchTerm) {
                document.querySelectorAll(`${this.SELECTORS.PRODUCT_ITEM}`).forEach(item => {
                    item.style.display = '';
                });
                return;
            }

            let foundMatches = false;

            document.querySelectorAll(this.SELECTORS.PRODUCT_ITEM).forEach(item => {
                const productName = item.querySelector('.fw-bold').textContent.toLowerCase();

                const barcodesAttr = item.getAttribute('data-barcodes');
                let barcodeList = [];
                try {
                    barcodeList = JSON.parse(barcodesAttr);
                } catch (e) {
                    console.error('Invalid JSON in data-barcodes:', e);
                }

                const barcodeMatch = barcodeList.some(b =>
                    b.barcode && b.barcode.toLowerCase().includes(searchTerm)
                );

                const matchesSearch = productName.includes(searchTerm) || barcodeMatch;
                item.style.display = matchesSearch ? '' : 'none';

                if (matchesSearch) {
                    foundMatches = true;

                    const tabPane = item.closest('.tab-pane');
                    if (tabPane && !tabPane.classList.contains('active')) {
                        const tabId = tabPane.id;
                        const tabLink = document.querySelector(`[href="#${tabId}"]`);
                        if (tabLink) {
                            new bootstrap.Tab(tabLink).show();
                        }
                    }
                }
            });

            if (!foundMatches) {
                this.showAlert('No products found matching your search', 'info');
            }
        }

        // Discount Handling
        async showDiscountInput() {
            this.cachedElements.discountInput.value = this.discount;
            this.cachedElements.discountElement.classList.add('d-none');
            this.cachedElements.discountInput.classList.remove('d-none');
            this.cachedElements.discountInput.focus();
        }

        updateDiscount() {
            const discountValue = parseFloat(this.cachedElements.discountInput.value) || 0;
            const maxDiscount = parseFloat(this.cachedElements.form.querySelector('input[name="total"]').value);

            this.discount = Math.min(discountValue, maxDiscount);

            this.cachedElements.discountElement.classList.remove('d-none');
            this.cachedElements.discountInput.classList.add('d-none');
            this.calculateTotals();
            this.saveActiveOrder();
        }

        // UI Updates
        addNewOrderToUI(orderData) {
            const emptyMessage = document.querySelector('.last_order_empty');
            if (emptyMessage) emptyMessage.style.display = 'none';

            const lastOrdersContainer = document.querySelector('.last_orders');
            lastOrdersContainer.insertAdjacentHTML('afterbegin', this.getOrderSummaryHtml(orderData));
        }

        getOrderSummaryHtml(orderData) {
            return `
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
                            Sub Total: ${orderData.total ? (orderData.total + this.discount).toFixed(2) : orderData.total.toFixed(2)}
                        </div>
                        <div class="col-6 text-right">
                            Total: ${orderData.total.toFixed(2)}
                        </div>
                    </div>
                </div>
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const posSystem = new PosSystem();

        window.PosSystem = posSystem;

        document.getElementById('open_cash_drawer').addEventListener('click', () => posSystem.openCashDrawer());
    });
</script>