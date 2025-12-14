<?php
require_once '../models/AuthManager.php';
AuthManager::checkRole('admin');

require_once '../models/UserManager.php';

$userManager = new UserManager();

// Handle Ban/Unban Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $userId = $_POST['user_id'];
    $action = $_POST['action'];
    $newStatus = ($action === 'ban') ? 0 : 1;
    
    if ($userManager->updateUserStatus($userId, $newStatus)) {
        $msg = ($action === 'ban') ? 'User berhasil dibanned.' : 'User berhasil diaktifkan kembali.';
        header("Location: users.php?success=" . urlencode($msg));
    } else {
        header("Location: users.php?error=Gagal mengupdate status user.");
    }
    exit();
}

// Pagination
$usersPerPage = isset($_GET['per_page']) ? intval($_GET['per_page']) : 15;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$allUsers = $userManager->getAllUsers();
$totalUsers = count($allUsers);
$totalPages = ceil($totalUsers / $usersPerPage);
$offset = ($currentPage - 1) * $usersPerPage;
$users = array_slice($allUsers, $offset, $usersPerPage);
$pageTitle = "Manajemen User";

require_once 'includes/header.php';
?>

<main class="flex-1 px-2 pb-2">
    
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center shadow-sm">
            <i class="bi bi-check-circle-fill mr-2"></i> <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center shadow-sm">
            <i class="bi bi-exclamation-triangle-fill mr-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="pt-0 mt-0 pb-4 mb-1 border-b border-gray-200">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1"><?php echo $pageTitle; ?></h1>
            <p class="text-gray-600">Kelola pengguna terdaftar.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-2">
            <h5 class="font-bold text-lg">Daftar User</h5>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <label for="perPageSelect" class="text-sm text-gray-600">Tampilkan:</label>
                    <select id="perPageSelect" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500" onchange="changePerPage(this.value)">
                        <option value="5" <?php echo ($usersPerPage == 5) ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo ($usersPerPage == 10) ? 'selected' : ''; ?>>10</option>
                        <option value="15" <?php echo ($usersPerPage == 15) ? 'selected' : ''; ?>>15</option>
                        <option value="20" <?php echo ($usersPerPage == 20) ? 'selected' : ''; ?>>20</option>
                        <option value="50" <?php echo ($usersPerPage == 50) ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo ($usersPerPage == 100) ? 'selected' : ''; ?>>100</option>
                    </select>
                    <span class="text-sm text-gray-600">data</span>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full border border-gray-200"><?php echo $totalUsers; ?> Pengguna</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-8">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider pr-8 w-32"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="bi bi-people text-6xl block mb-4 opacity-50"></i>
                                <p>Belum ada user terdaftar.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $rowNumber = $offset + 1;
                        foreach ($users as $user): 
                        ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 pl-8"><?php echo $rowNumber++; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (isset($user['is_active']) && $user['is_active'] == 0): ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dibanned</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center pr-8">
                                    <div class="relative inline-block" x-data="{ open: false }">
                                        <button @click="open = !open" 
                                                @click.away="open = false"
                                                style="padding: 0.5rem; border-radius: 0.5rem; transition: all 0.2s; background-color: #F9FAFB; border: 1px solid #E5E7EB;"
                                                onmouseover="this.style.backgroundColor='#F3F4F6';"
                                                onmouseout="this.style.backgroundColor='#F9FAFB';">
                                            <i class="bi bi-three-dots-vertical" style="font-size: 1.125rem; color: #6B7280;"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             style="display: none; position: absolute; right: 100%; margin-right: 0.5rem; top: 0; width: 11rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); z-index: 50; border: 1px solid #E5E7EB;"
                                             @click.away="open = false">
                                            <div style="padding: 0.25rem;">
                                                <button onclick="showUserDetailsModal(<?php echo $user['id']; ?>)" 
                                                        style="width: 100%; display: flex; align-items: center; padding: 0.625rem 0.75rem; color: #1F2937; border-radius: 0.375rem; background: none; border: none; cursor: pointer; transition: all 0.15s; font-size: 0.875rem; text-align: left;"
                                                        onmouseover="this.style.backgroundColor='#EFF6FF'; this.style.color='#1D4ED8';"
                                                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1F2937';">
                                                    <i class="bi bi-info-circle" style="margin-right: 0.625rem; font-size: 0.875rem;"></i>
                                                    <span>Detail User</span>
                                                </button>
                                                <div style="height: 1px; background-color: #E5E7EB; margin: 0.25rem 0;"></div>
                                                <?php if (isset($user['is_active']) && $user['is_active'] == 0): ?>
                                                    <button onclick="showUnbanModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>')" 
                                                            style="width: 100%; display: flex; align-items: center; padding: 0.625rem 0.75rem; color: #059669; border-radius: 0.375rem; background: none; border: none; cursor: pointer; transition: all 0.15s; font-size: 0.875rem; text-align: left;"
                                                            onmouseover="this.style.backgroundColor='#D1FAE5'; this.style.color='#047857';"
                                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#059669';">
                                                        <i class="bi bi-check-circle" style="margin-right: 0.625rem; font-size: 0.875rem;"></i>
                                                        <span>Aktifkan User</span>
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="showBanModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>')" 
                                                            style="width: 100%; display: flex; align-items: center; padding: 0.625rem 0.75rem; color: #DC2626; border-radius: 0.375rem; background: none; border: none; cursor: pointer; transition: all 0.15s; font-size: 0.875rem; text-align: left;"
                                                            onmouseover="this.style.backgroundColor='#FEE2E2'; this.style.color='#B91C1C';"
                                                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#DC2626';">
                                                        <i class="bi bi-slash-circle" style="margin-right: 0.625rem; font-size: 0.875rem;"></i>
                                                        <span>Ban User</span>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="?page=<?php echo max(1, $currentPage - 1); ?>&per_page=<?php echo $usersPerPage; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo ($currentPage <= 1) ? 'pointer-events-none opacity-50' : ''; ?>">
                        <span class="sr-only">Previous</span>
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&per_page=<?php echo $usersPerPage; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo ($currentPage == $i) ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-500 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>&per_page=<?php echo $usersPerPage; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 <?php echo ($currentPage >= $totalPages) ? 'pointer-events-none opacity-50' : ''; ?>">
                        <span class="sr-only">Next</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- User Details Modal -->
    <div id="userDetailsModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideUserDetailsModal()">
        <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 48rem; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
            <div style="padding: 1.5rem; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0;">Detail User</h3>
                <button onclick="hideUserDetailsModal()" style="padding: 0.5rem; border-radius: 0.375rem; background: none; border: none; cursor: pointer; color: #6B7280; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='#111827';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#6B7280';">
                    <i class="bi bi-x-lg" style="font-size: 1.25rem;"></i>
                </button>
            </div>
            <div id="userDetailsContent" style="padding: 1.5rem;">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>

    <!-- Ban Modal -->
    <div id="banModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideBanModal()">
        <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 28rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
            <div style="padding: 1.5rem;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 50%; background-color: #FEE2E2;">
                        <i class="bi bi-slash-circle" style="font-size: 1.5rem; color: #DC2626;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">Konfirmasi Ban User</h3>
                        <p style="font-size: 0.875rem; color: #6B7280; margin: 0;">
                            Apakah Anda yakin ingin melakukan ban terhadap user <strong id="banUsername" style="color: #111827;"></strong>?
                        </p>
                        <div style="margin-top: 0.5rem; padding: 0.5rem; background-color: #FEF2F2; border-radius: 0.375rem; color: #991B1B; font-size: 0.75rem;">
                            <strong>Peringatan:</strong> User tidak akan dapat login ke sistem.
                        </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #F9FAFB; padding: 1rem 1.5rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; display: flex; gap: 0.75rem; justify-content: flex-end;">
                <form method="POST" style="display: flex; gap: 0.75rem; width: 100%; justify-content: flex-end;">
                    <input type="hidden" name="user_id" id="banUserId">
                    <input type="hidden" name="action" value="ban">
                    <button type="button" onclick="hideBanModal()" 
                            style="padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                            onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F3F4F6';"
                            onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='white';">
                        Batal
                    </button>
                    <button type="submit" 
                            style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #DC2626; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#B91C1C'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                            onmouseout="this.style.backgroundColor='#DC2626'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                        <i class="bi bi-slash-circle mr-2"></i> Ya, Ban User
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Unban Modal -->
    <div id="unbanModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideUnbanModal()">
        <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 28rem; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
            <div style="padding: 1.5rem;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 50%; background-color: #D1FAE5;">
                        <i class="bi bi-check-circle-fill" style="font-size: 1.5rem; color: #059669;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">Konfirmasi Aktifkan User</h3>
                        <p style="font-size: 0.875rem; color: #6B7280; margin: 0;">
                            Apakah Anda yakin ingin mengaktifkan kembali user <strong id="unbanUsername" style="color: #111827;"></strong>?
                        </p>
                        <div style="margin-top: 0.5rem; padding: 0.5rem; background-color: #ECFDF5; border-radius: 0.375rem; color: #065F46; font-size: 0.75rem;">
                            <strong>Informasi:</strong> User akan dapat login kembali ke sistem setelah diaktifkan.
                        </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #F9FAFB; padding: 1rem 1.5rem; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; display: flex; gap: 0.75rem; justify-content: flex-end;">
                <form method="POST" style="display: flex; gap: 0.75rem; width: 100%; justify-content: flex-end;">
                    <input type="hidden" name="user_id" id="unbanUserId">
                    <input type="hidden" name="action" value="unban">
                    <button type="button" onclick="hideUnbanModal()" 
                            style="padding: 0.5rem 1rem; border: 2px solid #E5E7EB; color: #4B5563; border-radius: 0.5rem; font-weight: 600; background: white; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;"
                            onmouseover="this.style.borderColor='#D1D5DB'; this.style.backgroundColor='#F3F4F6';"
                            onmouseout="this.style.borderColor='#E5E7EB'; this.style.backgroundColor='white';">
                        Batal
                    </button>
                    <button type="submit" 
                            style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #059669; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.875rem;"
                            onmouseover="this.style.backgroundColor='#047857'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.1)';"
                            onmouseout="this.style.backgroundColor='#059669'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';">
                        <i class="bi bi-check-circle mr-2"></i> Ya, Aktifkan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="hidden" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;" onclick="hideOrderDetailsModal()">
        <div class="modal-content" style="background: white; border-radius: 0.75rem; max-width: 48rem; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(0.95); opacity: 0; transition: all 0.2s ease-out;" onclick="event.stopPropagation()">
            <div style="padding: 1.5rem; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0;">Detail Pesanan</h3>
                <button onclick="hideOrderDetailsModal()" style="padding: 0.5rem; border-radius: 0.375rem; background: none; border: none; cursor: pointer; color: #6B7280; transition: all 0.2s;"
                        onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='#111827';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#6B7280';">
                    <i class="bi bi-x-lg" style="font-size: 1.25rem;"></i>
                </button>
            </div>
            <div id="orderDetailsContent" style="padding: 1.5rem;">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>

    <style>
    .modal-content.show {
        transform: scale(1) !important;
        opacity: 1 !important;
    }
    #userDetailsModal.hidden, #banModal.hidden, #unbanModal.hidden, #orderDetailsModal.hidden {
        display: none !important;
    }
    #userDetailsModal, #banModal, #unbanModal, #orderDetailsModal {
        display: flex !important;
    }
    </style>

    <?php include 'includes/payment_modals.php'; ?>

</main>

<?php include 'includes/footer.php'; ?>

<script>
    // Per-page selector function
    window.changePerPage = function(perPage) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1'); // Reset to page 1 when changing per_page
        window.location.href = url.toString();
    };

    // User Details Modal
    window.showUserDetailsModal = function(userId) {
        const modal = document.getElementById('userDetailsModal');
        const content = document.getElementById('userDetailsContent');
        
        // Show modal with loading
        content.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div><p class="mt-4 text-gray-600">Memuat detail user...</p></div>';
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.modal-content').classList.add('show');
        }, 10);
        
        // Fetch user details
        fetch(`ajax_get_user_details.php?user_id=${userId}`)
            .then(response => response.text())
            .then(data => {
                content.innerHTML = data;
            })
            .catch(error => {
                content.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Gagal memuat detail user.</div>';
                console.error('Error:', error);
            });
    };

    window.hideUserDetailsModal = function() {
        const modal = document.getElementById('userDetailsModal');
        modal.querySelector('.modal-content').classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };

    // Order Details Modal
    window.showOrderDetailsModal = function(orderId) {
        const modal = document.getElementById('orderDetailsModal');
        const content = document.getElementById('orderDetailsContent');
        
        // Show modal with loading
        content.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div><p class="mt-4 text-gray-600">Memuat detail pesanan...</p></div>';
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.modal-content').classList.add('show');
        }, 10);
        
        // Fetch order details
        fetch(`ajax_get_order_details.php?order_id=${orderId}`)
            .then(response => response.text())
            .then(data => {
                content.innerHTML = data;
            })
            .catch(error => {
                content.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Gagal memuat detail pesanan.</div>';
                console.error('Error:', error);
            });
    };

    window.hideOrderDetailsModal = function() {
        const modal = document.getElementById('orderDetailsModal');
        modal.querySelector('.modal-content').classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };

    // Ban Modal
    window.showBanModal = function(userId, username) {
        const modal = document.getElementById('banModal');
        document.getElementById('banUserId').value = userId;
        document.getElementById('banUsername').textContent = username;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.modal-content').classList.add('show');
        }, 10);
    };

    window.hideBanModal = function() {
        const modal = document.getElementById('banModal');
        modal.querySelector('.modal-content').classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };

    // Unban Modal
    window.showUnbanModal = function(userId, username) {
        const modal = document.getElementById('unbanModal');
        document.getElementById('unbanUserId').value = userId;
        document.getElementById('unbanUsername').textContent = username;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.modal-content').classList.add('show');
        }, 10);
    };

    window.hideUnbanModal = function() {
        const modal = document.getElementById('unbanModal');
        modal.querySelector('.modal-content').classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };

    // Approve Modal
    window.showApproveModal = function(orderId) {
        const modal = document.getElementById('approveModal');
        document.getElementById('approveOrderId').value = orderId;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.modal-content').classList.add('show');
        }, 10);
    };

    window.hideApproveModal = function() {
        const modal = document.getElementById('approveModal');
        modal.querySelector('.modal-content').classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };

    // Reject Modal
    window.showRejectModal = function(orderId) {
        const modal = document.getElementById('rejectModal');
        document.getElementById('rejectOrderId').value = orderId;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.modal-content').classList.add('show');
        }, 10);
    };

    window.hideRejectModal = function() {
        const modal = document.getElementById('rejectModal');
        modal.querySelector('.modal-content').classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };

    // COD Modal
    window.showCodModal = function(orderId) {
        const modal = document.getElementById('codModal');
        document.getElementById('codOrderId').value = orderId;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.modal-content').classList.add('show');
        }, 10);
    };

    window.hideCodModal = function() {
        const modal = document.getElementById('codModal');
        modal.querySelector('.modal-content').classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };
</script>
