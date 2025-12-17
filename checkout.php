<?php
session_start(); // Bắt buộc phải có để lấy giỏ hàng

// --- PHẦN 1: GIẢ LẬP DỮ LIỆU GIỎ HÀNG (XÓA KHI CHẠY THỰC TẾ) ---
// Nếu chưa có giỏ hàng, tạo dữ liệu mẫu để bạn test giao diện
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        [
            'id' => 1,
            'name' => 'Symbolic® Steel Shoulder Bag',
            'color' => 'BLACK',
            'price' => 360000,
            'quantity' => 1
        ],
        [
            'id' => 2,
            'name' => 'Symbolic® Basic T-Shirt', // Thêm sản phẩm thứ 2 để test vòng lặp
            'color' => 'WHITE',
            'price' => 150000,
            'quantity' => 2
        ]
    ];
}
// ------------------------------------------------------------------

// --- PHẦN 2: TÍNH TOÁN TIỀN ---
$cart_items = $_SESSION['cart'] ?? [];
$subtotal = 0;
$shipping_fee = 30000; // Phí ship mặc định

// Tính tổng tiền hàng
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$total_payment = $subtotal + $shipping_fee;

// --- PHẦN 3: XỬ LÝ SUBMIT FORM ---
$order_success = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu post
    $fullname = $_POST['fullname'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $street_address = $_POST['address'] ?? '';
    
    $province = $_POST['province_text'] ?? '';
    $district = $_POST['district_text'] ?? '';
    $ward = $_POST['ward_text'] ?? '';
    
    $full_address_db = "$street_address, $ward, $district, $province";

    if (empty($fullname) || empty($phone) || empty($street_address) || empty($province) || empty($district) || empty($ward)) {
        $error_message = 'Vui lòng điền đầy đủ thông tin địa chỉ và người nhận (*)';
    } else {
        $order_success = true;
        // LƯU Ý: Tại đây bạn sẽ viết code lưu đơn hàng vào Database
        // Lưu thông tin người nhận, loop qua $cart_items để lưu chi tiết đơn hàng
        
        // Sau khi lưu thành công, thường sẽ xóa giỏ hàng:
        // unset($_SESSION['cart']); 
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - Symbolic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        /* GIỮ NGUYÊN CSS CŨ */
        :root { --primary-color: #3a86ff; --secondary-color: #6c757d; --light-gray: #f8f9fa; --border-color: #dee2e6; }
        body { background-color: var(--light-gray); font-family: 'Segoe UI', system-ui, sans-serif; padding-top: 20px; padding-bottom: 50px; }
        .checkout-container { max-width: 1200px; margin: 0 auto; }
        .logo-header { text-align: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid var(--border-color); }
        .logo-header h1 { font-weight: 700; font-size: 2.5rem; color: #222; }
        .checkout-card { background: white; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); margin-bottom: 25px; border: none; overflow: hidden; }
        .card-header-custom { background-color: white; border-bottom: 3px solid var(--primary-color); padding: 20px 25px; font-weight: 700; font-size: 1.4rem; }
        .card-body-custom { padding: 25px; }
        .form-label { font-weight: 600; color: #444; margin-bottom: 8px; }
        .required::after { content: " *"; color: #e63946; }
        .form-control, .form-select { padding: 12px 15px; border-radius: 8px; border: 1px solid var(--border-color); }
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(58,134,255,0.25); }
        .product-item { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid var(--border-color); }
        .product-info { flex: 1; }
        .product-name { font-weight: 700; margin-bottom: 3px; font-size: 1rem; }
        .product-meta { color: var(--secondary-color); font-size: 0.9rem; }
        .product-price { font-weight: 700; color: #222; font-size: 1.1rem; }
        .order-summary-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed var(--border-color); }
        .order-total { font-weight: 800; font-size: 1.4rem; color: #222; border-top: 2px solid var(--border-color); margin-top: 10px; padding-top: 15px; border-bottom: none; }
        .btn-place-order { background-color: var(--primary-color); color: white; font-weight: 700; font-size: 1.2rem; padding: 16px; border-radius: 8px; width: 100%; border: none; transition: 0.3s; }
        .btn-place-order:hover { background-color: #2667cc; }
        .discount-section { display: flex; gap: 10px; margin: 20px 0; }
        .btn-apply { background-color: var(--secondary-color); color: white; border: none; font-weight: 600; padding: 0 20px; border-radius: 8px; }
        .shipping-info, .payment-info { background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-top: 10px; border-left: 4px solid var(--primary-color); }
        .alert-success-custom { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .city-badge { background-color: #e3f2fd; color: var(--primary-color); padding: 8px 15px; border-radius: 20px; font-weight: 600; display: inline-block; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container checkout-container">
        <div class="logo-header">
            <h1>Symbolic.</h1>
            <p>Hoàn tất đơn hàng của bạn</p>
        </div>
        
        <?php if ($order_success): ?>
            <div class="alert-success-custom">
                <i class="fas fa-check-circle me-2"></i>
                Đặt hàng thành công! Tổng tiền: <b><?php echo number_format($total_payment, 0, ',', '.'); ?>₫</b><br>
                Địa chỉ: <?php echo $full_address_db; ?>
            </div>
        <?php elseif ($error_message): ?>
            <div class="alert alert-danger mb-4"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="checkout.php">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="checkout-card">
                        <div class="card-header-custom"><i class="fas fa-user-circle me-2"></i>Thông tin mua hàng</div>
                        <div class="card-body-custom">
                             <div class="city-badge" id="location-badge"><i class="fas fa-map-marker-alt me-2"></i>Vui lòng chọn khu vực giao hàng</div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label required">Họ và tên</label>
                                    <input type="text" class="form-control" name="fullname" required value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label required">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="phone" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label required">Địa chỉ (Số nhà/Đường)</label>
                                    <input type="text" class="form-control" name="address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                                </div>

                                <input type="hidden" name="province_text" id="province_text">
                                <input type="hidden" name="district_text" id="district_text">
                                <input type="hidden" name="ward_text" id="ward_text">

                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Tỉnh / Thành</label>
                                    <select class="form-select" id="province" required><option value="">Chọn Tỉnh/Thành</option></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Quận / Huyện</label>
                                    <select class="form-select" id="district" required disabled><option value="">Chọn Quận/Huyện</option></select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Phường / Xã</label>
                                    <select class="form-select" id="ward" required disabled><option value="">Chọn Phường/Xã</option></select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Ghi chú</label>
                                    <textarea class="form-control" name="note" rows="2"></textarea>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            <h5 class="mb-3">Vận chuyển & Thanh toán</h5>
                            <div class="shipping-info mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Giao hàng tận nơi</span>
                                    <span><?php echo number_format($shipping_fee, 0, ',', '.'); ?>₫</span>
                                </div>
                            </div>
                            <div class="payment-info">Thanh toán khi nhận hàng (COD)</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="checkout-card">
                        <div class="card-header-custom">
                            <i class="fas fa-shopping-bag me-2"></i>Đơn hàng (<?php echo count($cart_items); ?> sản phẩm)
                        </div>
                        <div class="card-body-custom">
                            
                            <?php if (!empty($cart_items)): ?>
                                <?php foreach ($cart_items as $item): ?>
                                    <div class="product-item">
                                        <div class="product-info">
                                            <div class="product-name"><?php echo $item['name']; ?></div>
                                            <div class="product-meta">
                                                Màu: <?php echo $item['color'] ?? 'N/A'; ?> <br>
                                                SL: x<?php echo $item['quantity']; ?>
                                            </div>
                                        </div>
                                        <div class="product-price">
                                            <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center text-muted my-3">Giỏ hàng trống</p>
                            <?php endif; ?>
                            <hr class="my-4">
                            
                            <div class="mb-3">
                                <label class="form-label">Nhập mã giảm giá</label>
                                <div class="discount-section">
                                    <input type="text" class="form-control" id="discountInput" placeholder="Mã giảm giá">
                                    <button type="button" class="btn btn-apply" id="applyDiscount">Áp dụng</button>
                                </div>
                            </div>
                            
                            <div class="order-summary-row">
                                <span>Tạm tính</span>
                                <span><?php echo number_format($subtotal, 0, ',', '.'); ?>₫</span>
                            </div>
                            
                            <div class="order-summary-row">
                                <span>Phí vận chuyển</span>
                                <span><?php echo number_format($shipping_fee, 0, ',', '.'); ?>₫</span>
                            </div>
                            
                            <div class="order-summary-row order-total">
                                <span>Tổng cộng</span>
                                <span id="final-total"><?php echo number_format($total_payment, 0, ',', '.'); ?>₫</span>
                            </div>
                            
                            <button type="submit" class="btn btn-place-order mt-4">
                                <i class="fas fa-shopping-cart me-2"></i>ĐẶT HÀNG
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- 1. SCRIPT API ĐỊA CHỈ (Giữ nguyên) ---
        $(document).ready(function() {
            $.getJSON('https://provinces.open-api.vn/api/?depth=1', function(data) {
                $.each(data, function(key, val) {
                    $('#province').append('<option value="' + val.code + '" data-name="' + val.name + '">' + val.name + '</option>');
                });
            });
            $('#province').change(function() {
                var provinceCode = $(this).val();
                var provinceName = $(this).find('option:selected').data('name');
                $('#province_text').val(provinceName);
                if(provinceName) $('#location-badge').html('<i class="fas fa-map-marker-alt me-2"></i>Giao tới: ' + provinceName);
                
                $('#district').html('<option value="">Chọn Quận/Huyện</option>').prop('disabled', true);
                $('#ward').html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
                if (provinceCode) {
                    $.getJSON('https://provinces.open-api.vn/api/p/' + provinceCode + '?depth=2', function(data) {
                        $.each(data.districts, function(key, val) {
                            $('#district').append('<option value="' + val.code + '" data-name="' + val.name + '">' + val.name + '</option>');
                        });
                        $('#district').prop('disabled', false);
                    });
                }
            });
            $('#district').change(function() {
                var districtCode = $(this).val();
                $('#district_text').val($(this).find('option:selected').data('name'));
                $('#ward').html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
                if (districtCode) {
                    $.getJSON('https://provinces.open-api.vn/api/d/' + districtCode + '?depth=2', function(data) {
                        $.each(data.wards, function(key, val) {
                            $('#ward').append('<option value="' + val.code + '" data-name="' + val.name + '">' + val.name + '</option>');
                        });
                        $('#ward').prop('disabled', false);
                    });
                }
            });
            $('#ward').change(function() {
                $('#ward_text').val($(this).find('option:selected').data('name'));
            });
        });

        // --- 2. SCRIPT TÍNH TIỀN (Dynamic) ---
        // Lấy giá trị tổng tiền từ PHP truyền xuống JS
        var initialTotal = <?php echo $total_payment; ?>; 

        document.getElementById('applyDiscount').addEventListener('click', function() {
            const discountCode = document.getElementById('discountInput').value.trim().toUpperCase();
            
            if (discountCode === 'SYMBOLIC10') {
                // Giảm 10%
                let newTotal = initialTotal * 0.9;
                updateTotalDisplay(newTotal);
                alert('Đã áp dụng mã giảm giá 10%');
            } else if (discountCode === 'FREESHIP') {
                // Trừ phí ship (giả sử ship là 30k)
                let newTotal = initialTotal - 30000;
                updateTotalDisplay(newTotal);
                alert('Đã áp dụng miễn phí vận chuyển');
            } else {
                alert('Mã giảm giá không hợp lệ');
                updateTotalDisplay(initialTotal); // Reset về giá gốc
            }
        });

        function updateTotalDisplay(amount) {
            // Format tiền Việt Nam bằng JS
            const formatted = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
            document.getElementById('final-total').textContent = formatted.replace('₫', '') + '₫'; // Fix format cho giống giao diện
        }
    </script>
</body>
</html>