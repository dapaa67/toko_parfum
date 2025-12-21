<!-- Modal Konfirmasi Setujui Pembayaran -->
<div id="approveModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 10001;" onclick="hideApproveModal()">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 28rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
        <div style="padding: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0 0 0.75rem 0;">Setujui Pembayaran?</h3>
            <p style="font-size: 0.875rem; color: #4B5563; margin: 0 0 1rem 0;">
                Apakah Anda yakin ingin menyetujui pembayaran pesanan ini? Status akan berubah menjadi <strong>Selesai</strong>.
            </p>
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <form action="process_payment.php" method="POST" style="display: flex; gap: 0.75rem; width: 100%; justify-content: flex-end;">
                    <input type="hidden" name="order_id" id="approveOrderId">
                    <input type="hidden" name="action" value="approve">
                    <button type="button" onclick="hideApproveModal()" 
                            style="padding: 0.5rem 1rem; border: 1px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#F9FAFB';"
                            onmouseout="this.style.backgroundColor='white';">
                        Batal
                    </button>
                    <button type="submit" 
                            style="padding: 0.5rem 1rem; background-color: #059669; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#047857';"
                            onmouseout="this.style.backgroundColor='#059669';">
                        Ya, Setujui
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Tolak Pembayaran -->
<div id="rejectModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 10001;" onclick="hideRejectModal()">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 28rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
        <div style="padding: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0 0 0.75rem 0;">Tolak Pembayaran?</h3>
            <p style="font-size: 0.875rem; color: #4B5563; margin: 0 0 1rem 0;">
                Apakah Anda yakin ingin menolak pembayaran ini? Pesanan akan <strong>Dibatalkan</strong>.
            </p>
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <form action="process_payment.php" method="POST" style="display: flex; gap: 0.75rem; width: 100%; justify-content: flex-end;">
                    <input type="hidden" name="order_id" id="rejectOrderId">
                    <input type="hidden" name="action" value="reject">
                    <button type="button" onclick="hideRejectModal()" 
                            style="padding: 0.5rem 1rem; border: 1px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#F9FAFB';"
                            onmouseout="this.style.backgroundColor='white';">
                        Batal
                    </button>
                    <button type="submit" 
                            style="padding: 0.5rem 1rem; background-color: #DC2626; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#B91C1C';"
                            onmouseout="this.style.backgroundColor='#DC2626';">
                        Ya, Tolak
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Selesaikan COD -->
<div id="codModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 10001;" onclick="hideCodModal()">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 28rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
        <div style="padding: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0 0 0.75rem 0;">Selesaikan Pesanan COD?</h3>
            <p style="font-size: 0.875rem; color: #4B5563; margin: 0 0 1rem 0;">
                Apakah Anda yakin pesanan COD ini sudah selesai dan dibayar?
            </p>
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <form action="process_payment.php" method="POST" style="display: flex; gap: 0.75rem; width: 100%; justify-content: flex-end;">
                    <input type="hidden" name="order_id" id="codOrderId">
                    <input type="hidden" name="action" value="complete_cod">
                    <button type="button" onclick="hideCodModal()" 
                            style="padding: 0.5rem 1rem; border: 1px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#F9FAFB';"
                            onmouseout="this.style.backgroundColor='white';">
                        Batal
                    </button>
                    <button type="submit" 
                            style="padding: 0.5rem 1rem; background-color: #2563EB; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#1D4ED8';"
                            onmouseout="this.style.backgroundColor='#2563EB';">
                        Ya, Selesaikan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #approveModal.hidden, #rejectModal.hidden, #codModal.hidden {
        display: none !important;
    }
    #approveModal, #rejectModal, #codModal {
        display: flex !important;
    }
</style>
