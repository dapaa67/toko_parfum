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

$users = $userManager->getAllUsers();
$pageTitle = "Manajemen User";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Admin ParfumMy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-flower2 me-2 text-warning"></i>
            <span class="fw-bold">ParfumMy Admin</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../index.php" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($_GET['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1"><?php echo $pageTitle; ?></h1>
                    <p class="text-muted">Kelola pengguna terdaftar.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="bi bi-people display-4 d-block mb-3 opacity-50"></i>
                                            Belum ada user terdaftar.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="ps-4">#<?php echo $user['id']; ?></td>
                                            <td class="fw-medium"><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
                                            <td>
                                                <?php if (isset($user['is_active']) && $user['is_active'] == 0): ?>
                                                    <span class="badge bg-danger bg-opacity-75 rounded-pill px-3">Banned</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success bg-opacity-75 rounded-pill px-3">Active</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#userDetailsModal" data-user-id="<?php echo $user['id']; ?>">
                                                    <i class="bi bi-info-circle"></i> Detail
                                                </button>
                                                <?php if (isset($user['is_active']) && $user['is_active'] == 0): ?>
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('Aktifkan kembali user ini?');">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="action" value="unban">
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-check-circle"></i> Unban
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin mem-banned user ini?');">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="action" value="ban">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-slash-circle"></i> Ban
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Modal Detail User -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="userDetailsModalLabel">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4" id="user-modal-body">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pesanan (Nested) -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="orderDetailsModalLabel">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4" id="order-modal-body">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../sidebar.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handler untuk Modal User Details
    var userDetailsModal = document.getElementById('userDetailsModal');
    userDetailsModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var userId = button.getAttribute('data-user-id');
        var modalBody = document.getElementById('user-modal-body');
        
        modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        fetch(`ajax_get_user_details.php?user_id=${userId}`)
            .then(response => response.text())
            .then(data => {
                modalBody.innerHTML = data;
                
                // Re-attach event listeners for nested modals if needed, 
                // but Bootstrap 5 handles data-bs-toggle automatically for static elements.
                // However, since the content is dynamic, we rely on event delegation or just the fact that 
                // the buttons have data-bs-toggle attributes which Bootstrap observes.
            })
            .catch(error => {
                modalBody.innerHTML = '<div class="alert alert-danger m-3">Gagal memuat detail user.</div>';
                console.error('Error:', error);
            });
    });

    // Handler untuk Modal Order Details (Nested)
    // Perlu penanganan khusus agar saat modal order ditutup, modal user tetap terbuka atau setidaknya UX-nya enak.
    // Bootstrap 5 mendukung multiple modals tapi scrollbar kadang glitchy.
    
    var orderDetailsModal = document.getElementById('orderDetailsModal');
    orderDetailsModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var orderId = button.getAttribute('data-order-id');
        var modalBody = document.getElementById('order-modal-body');
        
        modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

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
});
</script>
</body>
</html>
