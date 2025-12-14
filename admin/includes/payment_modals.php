<!-- Modal Konfirmasi Setujui Pembayaran -->
<div id="approveModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideApproveModal()">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 32rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
        <div style="padding: 1.5rem; background-color: #ECFDF5; border-bottom: 1px solid #D1FAE5; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 50%; background-color: #D1FAE5;">
                    <i class="bi bi-check-circle-fill" style="font-size: 1.5rem; color: #059669;"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">Konfirmasi Setujui Pembayaran</h3>
                    <p style="font-size: 0.875rem; color: #6B7280; margin: 0;">
                        Apakah Anda yakin ingin menyetujui pembayaran pesanan ini?
                    </p>
                    <div style="margin-top: 0.5rem; padding: 0.5rem; background-color: #D1FAE5; border-radius: 0.375rem; color: #065F46; font-size: 0.75rem;">
                        <strong>Informasi:</strong> Status pesanan akan berubah menjadi "Selesai" dan stok produk akan dikurangi.
                    </div>
                </div>
            </div>
        </div>
        <div style="background-color: #F9FAFB; padding: 1rem 1.5rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; display: flex; gap: 0.75rem; justify-content: flex-end;">
            <form action="process_payment.php" method="POST" style="display: flex; gap: 0.75rem; width: 100%; justify-content: flex-end;">
                <input type="hidden" name="order_id" id="approveOrderId">
                <input type="hidden" name="action" value="approve">
                <button type="button" onclick="hideApproveModal()" 
                        style="padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                        onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F3F4F6';"
                        onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='white';">
                    Batal
                </button>
                <button type="submit" 
                        style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #059669; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.875rem;"
                        onmouseover="this.style.backgroundColor='#047857'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.backgroundColor='#059669'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                    <i class="bi bi-check-circle mr-2"></i> Ya, Setujui
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Tolak Pembayaran -->
<div id="rejectModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideRejectModal()">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 32rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
        <div style="padding: 1.5rem; background-color: #FEF2F2; border-bottom: 1px solid #FEE2E2; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 50%; background-color: #FEE2E2;">
                    <i class="bi bi-x-circle-fill" style="font-size: 1.5rem; color: #DC2626;"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">Konfirmasi Tolak Pembayaran</h3>
                    <p style="font-size: 0.875rem; color: #6B7280; margin: 0;">
                        Apakah Anda yakin ingin menolak pembayaran pesanan ini?
                    </p>
                    <div style="margin-top: 0.5rem; padding: 0.5rem; background-color: #FEE2E2; border-radius: 0.375rem; color: #991B1B; font-size: 0.75rem;">
                        <strong>Perhatian:</strong> Status pesanan akan berubah menjadi "Dibatalkan".
                    </div>
                </div>
            </div>
        </div>
        <div style="background-color: #F9FAFB; padding: 1rem 1.5rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; display: flex; gap: 0.75rem; justify-content: flex-end;">
            <form action="process_payment.php" method="POST" style="display: flex; gap: 0.75rem; width: 100%; justify-content: flex-end;">
                <input type="hidden" name="order_id" id="rejectOrderId">
                <input type="hidden" name="action" value="reject">
                <button type="button" onclick="hideRejectModal()" 
                        style="padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                        onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F3F4F6';"
                        onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='white';">
                    Batal
                </button>
                <button type="submit" 
                        style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #DC2626; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.875rem;"
                        onmouseover="this.style.backgroundColor='#B91C1C'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.backgroundColor='#DC2626'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                    <i class="bi bi-x-circle mr-2"></i> Ya, Tolak
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Selesaikan COD -->
<div id="codModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideCodModal()">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 32rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
        <div style="padding: 1.5rem; background-color: #EFF6FF; border-bottom: 1px solid #DBEAFE; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
            <div style="display: flex; align-items: start; gap: 1rem;">
                <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 50%; background-color: #DBEAFE;">
                    <i class="bi bi-check2-circle" style="font-size: 1.5rem; color: #2563EB;"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">Konfirmasi Selesaikan Pesanan COD</h3>
                    <p style="font-size: 0.875rem; color: #6B7280; margin: 0;">
                        Apakah Anda yakin pesanan COD ini sudah selesai dan telah dibayar?
                    </p>
                    <div style="margin-top: 0.5rem; padding: 0.5rem; background-color: #DBEAFE; border-radius: 0.375rem; color: #1E40AF; font-size: 0.75rem;">
                        <strong>Informasi:</strong> Status pesanan akan berubah menjadi "Selesai".
                    </div>
                </div>
            </div>
        </div>
        <div style="background-color: #F9FAFB; padding: 1rem 1.5rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; display: flex; gap: 0.75rem; justify-content: flex-end;">
            <form action="process_payment.php" method="POST" style="display: flex; gap: 0.75rem; width: 100%; justify-content: flex-end;">
                <input type="hidden" name="order_id" id="codOrderId">
                <input type="hidden" name="action" value="complete_cod">
                <button type="button" onclick="hideCodModal()" 
                        style="padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                        onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F3F4F6';"
                        onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='white';">
                    Batal
                </button>
                <button type="submit" 
                        style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #2563EB; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.875rem;"
                        onmouseover="this.style.backgroundColor='#1D4ED8'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.backgroundColor='#2563EB'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                    <i class="bi bi-check2-circle mr-2"></i> Ya, Selesaikan
                </button>
            </form>
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
