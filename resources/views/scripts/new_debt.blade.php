<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('kt_modal_new_debt_form');
        var submitButton = document.getElementById('kt_modal_new_debt_submit');
        var cancelButton = document.getElementById('kt_modal_new_debt_cancel');
        var modalEl = document.getElementById('kt_modal_new_debt');
        
        var closeModal = function() {
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        };

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(form);
            console.log(formData);
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        text: data.message,
                        icon: "success",
                        confirmButtonText: "Ok, got it!"
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            closeModal();
                            form.reset();
                        }
                    });
                } else {
                    Swal.fire({
                        text: "There was an error submitting the form. Please try again.",
                        icon: "error",
                        confirmButtonText: "Ok, got it!"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    text: "There was an error submitting the form. Please try again.",
                    icon: "error",
                    confirmButtonText: "Ok, got it!"
                });
            });
        });

        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();
            closeModal();
            form.reset();
        });

        modalEl.addEventListener('shown.bs.modal', function () {
            var creditorContainer = document.getElementById('creditor');
            creditorContainer.innerHTML = '';

            fetch("{{ route('clients.fetch') }}")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.clients && data.clients.length > 0) {
                        const labelElement = document.createElement('label');
                        labelElement.classList.add('form-label', 'required');
                        labelElement.textContent = 'Client';

                        const selectElement = document.createElement('select');
                        selectElement.name = 'client_id';
                        selectElement.classList.add('form-select');
                        selectElement.required = true;
                        selectElement.setAttribute('data-control', 'select2');

                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Select an option';
                        selectElement.appendChild(defaultOption);

                        data.clients.forEach(client => {
                            const option = document.createElement('option');
                            option.value = client.id;
                            option.textContent = client.name;
                            selectElement.appendChild(option);
                        });

                        creditorContainer.appendChild(labelElement);
                        creditorContainer.appendChild(selectElement);

                        if (typeof $ !== 'undefined' && $.fn.select2) {
                            $(selectElement).select2({
                                placeholder: 'Select an option',
                                allowClear: true,
                                dropdownParent: $(modalEl)
                            }).on('select2:open', function () {
                                var select2Dropdown = $('.select2-container .select2-dropdown');
                                select2Dropdown.css('z-index', 1055); 
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching clients:', error);
                });
        });

    });
</script>