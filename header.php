<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symbolic</title>
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
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="login.php"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</a></li>
                                <li><a class="dropdown-item" href="register.php"><i class="fas fa-user-plus me-2"></i>Đăng ký</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <a class="nav-link" href="cart.php">
                        <i class="fas fa-shopping-cart"></i>
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