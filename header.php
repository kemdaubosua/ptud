<?php
// 1. Khởi tạo Session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/PTUD_Final',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// 2. Logic lấy số lượng giỏ hàng (Chỉ chạy khi đã đăng nhập)
$cart_count = 0;
if (isset($_SESSION['nguoi_dung_id'])) {
    // Kết nối CSDL riêng cho header để đảm bảo trang nào cũng load được
    $conn_header = new mysqli("localhost", "root", "", "PTUD_Final");
    
    if (!$conn_header->connect_error) {
        $uid = (int)$_SESSION['nguoi_dung_id'];
        
        // Query tính tổng số lượng (SUM so_luong) từ chi tiết giỏ hàng
        $sql_count = "SELECT SUM(ct.so_luong) as total 
                      FROM chi_tiet_gio_hang ct 
                      JOIN gio_hang gh ON ct.gio_hang_id = gh.id 
                      WHERE gh.nguoi_dung_id = $uid";
        
        $result_count = $conn_header->query($sql_count);
        if ($result_count) {
            $row_count = $result_count->fetch_assoc();
            $cart_count = (int)$row_count['total'];
        }
        $conn_header->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sole Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar-brand { font-size: 1.8rem; font-weight: bold; }
        .nav-icons { display: flex; align-items: center; gap: 15px; }
        .nav-icons .nav-link { padding: 0.5rem; color: #333; }
        .dropdown-menu { min-width: 200px; }
        .nav-link.dropdown-toggle::after { display: none; }
        
        body { 
            padding-top: 20px;
            background-color: #f8f9fa; 
        }
        
        .breadcrumb {
            background-color: transparent !important;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            text-decoration: none;
            color: #6c757d;
            transition: color 0.3s;
        }
        .breadcrumb-item a:hover {
            color: #1a1a2e;
        }
        .breadcrumb-item.active {
            color: #ff6b6b;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sole Studio.</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutus.php">About us</a></li>
                </ul>
                
                <div class="nav-icons ms-3">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fas fa-search"></i>
                    </a>
                    
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            
                            <?php if (isset($_SESSION['nguoi_dung_id'])): ?>
                                <span class="position-absolute start-100 translate-middle p-1 bg-success border border-light rounded-circle" style="top: 5px;">
                                    <span class="visually-hidden">Đang online</span>
                                </span>
                            <?php endif; ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if (isset($_SESSION['nguoi_dung_id'])): ?>
                                <li>
                                    <a class="dropdown-item" href="userprofile.php">
                                        <i class="fas fa-user-circle me-2"></i>Tài khoản của tôi
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a class="dropdown-item" href="login.php">
                                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="register.php">
                                        <i class="fas fa-user-plus me-2"></i>Đăng ký
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <a class="nav-link position-relative" href="cart.php">
                        <i class="fas fa-shopping-cart"></i>
                        
                        <?php if ($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                <?php echo $cart_count > 99 ? '99+' : $cart_count; ?>
                                <span class="visually-hidden">sản phẩm trong giỏ</span>
                            </span>
                        <?php endif; ?>
                    </a>

                </div>
            </div>
        </div>
    </nav>


    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tìm kiếm sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nhập từ khóa tìm kiếm...">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i> Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($current_page) && $current_page != 'Trang chủ'): ?>
    <div class="container mt-4 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php echo $current_page; ?>
                </li>
            </ol>
        </nav>
    </div>
    <?php endif; ?>