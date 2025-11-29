<!-- Modal Konfirmasi Setujui Pembayaran -->
<div class="modal fade" id="approvePaymentModal" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success bg-gradient text-white border-0">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="approvePaymentModalLabel">
                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                    <span>Konfirmasi Setujui Pembayaran</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-credit-card-2-front-fill text-success" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-2 text-muted">Apakah Anda yakin ingin menyetujui pembayaran pesanan ini?</p>
                <div class="alert alert-success border-success d-flex align-items-center mb-0">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>
                        <strong>Informasi:</strong> Status pesanan akan berubah menjadi "Selesai" dan stok produk akan dikurangi.
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <form action="process_payment.php" method="POST" class="w-100 d-flex justify-content-end gap-2">
                    <input type="hidden" name="order_id" id="approveOrderId">
                    <input type="hidden" name="action" value="approve">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Ya, Setujui Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Tolak Pembayaran -->
<div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger bg-gradient text-white border-0">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="rejectPaymentModalLabel">
                    <i class="bi bi-x-circle-fill me-2 fs-4"></i>
                    <span>Konfirmasi Tolak Pembayaran</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-2 text-muted">Apakah Anda yakin ingin menolak pembayaran pesanan ini?</p>
                <div class="alert alert-warning border-warning d-flex align-items-center mb-0">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>
                        <strong>Perhatian:</strong> Status pesanan akan berubah menjadi "Dibatalkan".
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <form action="process_payment.php" method="POST" class="w-100 d-flex justify-content-end gap-2">
                    <input type="hidden" name="order_id" id="rejectOrderId">
                    <input type="hidden" name="action" value="reject">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i> Ya, Tolak Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Selesaikan COD -->
<div class="modal fade" id="completeCodModal" tabindex="-1" aria-labelledby="completeCodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary bg-gradient text-white border-0">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="completeCodModalLabel">
                    <i class="bi bi-check2-circle me-2 fs-4"></i>
                    <span>Konfirmasi Selesaikan Pesanan COD</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-cash-coin text-primary" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-2 text-muted">Apakah Anda yakin pesanan COD ini sudah selesai dan telah dibayar?</p>
                <div class="alert alert-info border-info d-flex align-items-center mb-0">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>
                        <strong>Informasi:</strong> Status pesanan akan berubah menjadi "Selesai".
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <form action="process_payment.php" method="POST" class="w-100 d-flex justify-content-end gap-2">
                    <input type="hidden" name="order_id" id="codOrderId">
                    <input type="hidden" name="action" value="complete_cod">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Ya, Selesaikan Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
