// Sales Report JavaScript
document.addEventListener('DOMContentLoaded', function () {
    // Order Details Modal Handler
    var orderDetailsModal = document.getElementById('orderDetailsModal');
    orderDetailsModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var orderId = button.getAttribute('data-order-id');
        var modalBody = document.getElementById('modal-body-content');

        // Tampilkan spinner saat loading
        modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        // Ambil detail pesanan via AJAX
        fetch(`ajax_get_order_details.php?order_id=${orderId}`)
            .then(response => response.text())
            .then(data => {
                modalBody.innerHTML = data;
            })
            .catch(error => {
                modalBody.innerHTML = '<div class="alert alert-danger m-3">Gagal memuat detail pesanan.</div>';
                console.error('Error:', error);
            });
    });

    // Helper functions untuk show modals dari AJAX content
    window.showApproveModal = function (orderId) {
        document.getElementById('approveOrderId').value = orderId;
        var modal = new bootstrap.Modal(document.getElementById('approvePaymentModal'));
        modal.show();
    };

    window.showRejectModal = function (orderId) {
        document.getElementById('rejectOrderId').value = orderId;
        var modal = new bootstrap.Modal(document.getElementById('rejectPaymentModal'));
        modal.show();
    };

    window.showCompleteCodModal = function (orderId) {
        document.getElementById('codOrderId').value = orderId;
        var modal = new bootstrap.Modal(document.getElementById('completeCodModal'));
        modal.show();
    };
});
