<script>
    document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('kt_modal_new_client_form');
    var submitButton = document.getElementById('kt_modal_new_client_submit');
    var cancelButton = document.getElementById('kt_modal_new_client_cancel');
    var modalEl = document.getElementById('kt_modal_new_client');
    var clientSelect = document.getElementById('client_id');

    var closeModal = function() {
        var modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }
    };

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add the new client to the select dropdown
                if (data.client) {
                    const newOption = new Option(
                        data.client.name,
                        data.client.id,
                        false,
                        true // Make it selected
                    );
                    clientSelect.add(newOption);

                    // If using Select2, update it
                    if ($(clientSelect).data('select2')) {
                        $(clientSelect).trigger('change');
                    }
                }

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
                    text: data.message || "There was an error submitting the form. Please try again.",
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
});
</script>