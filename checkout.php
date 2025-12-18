<?php
declare(strict_types=1);

session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/PTUD_Final',
  'httponly' => true,
  'samesite' => 'Lax',
]);
session_start();

// Nếu chưa login mà vào thẳng checkout -> đá về login
if (!isset($_SESSION['nguoi_dung_id'])) {
  header('Location: login.php');
  exit;
}

$shipping_fee = 30000; // phí ship mặc định (FE)
$current_page = "Hoàn tất đơn hàng";
include 'header.php'; 
?>

<style>
    .form-label { font-weight: 600; font-size: 0.9rem; }
    .required::after { content: " *"; color: #dc3545; }
    
    /* Product Item trong Summary */
    .product-item { 
        display: flex; 
        align-items: center; 
        padding: 10px 0; 
        border-bottom: 1px dashed #dee2e6; 
    }
    .product-item:last-child { border-bottom: none; }
    .product-name { font-weight: 600; font-size: 0.95rem; line-height: 1.2; }
    .product-meta { font-size: 0.85rem; color: #6c757d; margin-top: 4px; }
    .product-price { font-weight: 600; font-size: 0.95rem; }

    /* Summary Rows */
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem; }
    .summary-total { 
        border-top: 2px solid #000; 
        padding-top: 15px; 
        margin-top: 15px; 
        font-weight: 700; 
        font-size: 1.2rem; 
        display: flex; 
        justify-content: space-between; 
    }

    /* Badge địa chỉ */
    .location-badge {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #000;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 20px;
    }
</style>

<main class="container py-5">
    
    <div class="row">
        <div class="col-12 mb-4 text-center">
            <h2 class="fw-bold text-uppercase">Hoàn tất đơn hàng</h2>
        </div>
    </div>

    <div id="alertSuccess" class="alert alert-success shadow-sm" style="display:none;"></div>
    <div id="alertError" class="alert alert-danger shadow-sm" style="display:none;"></div>

    <form id="checkoutForm">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4"><i class="fas fa-map-marker-alt me-2"></i>Thông tin vận chuyển</h5>
                        
                        <div class="location-badge" id="location-badge">
                            <i class="fas fa-info-circle me-1"></i>Vui lòng chọn khu vực giao hàng bên dưới
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Họ và tên</label>
                                <input type="text" class="form-control" name="fullname" placeholder="Nhập họ tên" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Số điện thoại</label>
                                <input type="tel" class="form-control" name="phone" placeholder="Nhập số điện thoại" required>
                            </div>
                            
                            <input type="hidden" name="province_text" id="province_text">
                            <input type="hidden" name="district_text" id="district_text">
                            <input type="hidden" name="ward_text" id="ward_text">

                            <div class="col-md-4">
                                <label class="form-label required">Tỉnh / Thành</label>
                                <select class="form-select" id="province" required>
                                    <option value="">Chọn Tỉnh/Thành</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Quận / Huyện</label>
                                <select class="form-select" id="district" required disabled>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Phường / Xã</label>
                                <select class="form-select" id="ward" required disabled>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label required">Địa chỉ chi tiết</label>
                                <input type="text" class="form-control" name="address" placeholder="Số nhà, tên đường..." required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ghi chú đơn hàng</label>
                                <textarea class="form-control" name="note" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <h5 class="fw-bold mb-3">Phương thức thanh toán</h5>
                        <div class="form-check p-3 border rounded mb-2 bg-light">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cod" checked>
                            <label class="form-check-label fw-bold" for="cod">
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Đơn hàng của bạn (<span id="orderCount">0</span>)</h5>
                        
                        <div id="orderItems" class="mb-4" style="max-height: 400px; overflow-y: auto;">
                            <p class="text-center text-muted my-3">Đang tải giỏ hàng...</p>
                        </div>

                        <div class="input-group mb-4">
                            <input type="text" class="form-control" id="discountInput" placeholder="Mã giảm giá">
                            <button class="btn btn-dark" type="button" id="applyDiscount">Áp dụng</button>
                        </div>

                        <div class="summary-row">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold" id="subtotalText">0₫</span>
                        </div>
                        <div class="summary-row">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span class="fw-bold" id="shippingText"><?php echo number_format($shipping_fee, 0, ',', '.'); ?>₫</span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Tổng cộng</span>
                            <span class="text-danger" id="final-total">0₫</span>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-3 mt-4 fw-bold text-uppercase" id="btnPlaceOrder" disabled>
                            Đặt hàng ngay
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="cart.php" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left me-1"></i>Quay lại giỏ hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // --- 1. SCRIPT API ĐỊA CHỈ (Giữ nguyên logic cũ) ---
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
            if(provinceName) $('#location-badge').html('<i class="fas fa-map-marker-alt me-2"></i>Giao tới: <strong>' + provinceName + '</strong>');

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

    // ==========================================================
    // ✅ 2. CHECKOUT LOGIC
    // ==========================================================
    const API_BASE = 'http://localhost/PTUD_Final/public';
    const DEFAULT_SHIPPING = <?php echo (int)$shipping_fee; ?>;

    let cart = null;           // dữ liệu /api/gio-hang
    let subtotal = 0;
    let shippingFee = DEFAULT_SHIPPING;
    let discountAmount = 0;    // số tiền giảm (VND)
    let discountCodeApplied = ''; 

    function vnd(amount) {
        return Number(amount || 0).toLocaleString('vi-VN') + '₫';
    }

    function showError(msg) {
        const el = document.getElementById('alertError');
        el.textContent = msg || 'Có lỗi xảy ra';
        el.style.display = 'block';
        document.getElementById('alertSuccess').style.display = 'none';
        el.scrollIntoView({behavior: "smooth", block: "center"});
    }

    function showSuccess(html) {
        const el = document.getElementById('alertSuccess');
        el.innerHTML = html;
        el.style.display = 'block';
        document.getElementById('alertError').style.display = 'none';
        el.scrollIntoView({behavior: "smooth", block: "center"});
    }

    function hideAlerts(){
        document.getElementById('alertSuccess').style.display = 'none';
        document.getElementById('alertError').style.display = 'none';
    }

    function calcTotal() {
        return Math.max(0, Number(subtotal) + Number(shippingFee) - Number(discountAmount));
    }

    function updateTotalsUI() {
        document.getElementById('subtotalText').textContent = vnd(subtotal);
        document.getElementById('shippingText').textContent = vnd(shippingFee);
        document.getElementById('final-total').textContent = vnd(calcTotal());
    }

    async function loadCartForCheckout() {
        hideAlerts();

        const res = await fetch(`${API_BASE}/api/gio-hang`, { credentials: 'include' });
        const data = await res.json().catch(() => ({}));

        if (res.status === 401) {
            window.location.href = 'login.php';
            return;
        }
        if (!res.ok || !data.ok) {
            showError(data.error || 'Không tải được giỏ hàng');
            return;
        }

        cart = data;
        const items = Array.isArray(cart.items) ? cart.items : [];

        // subtotal từ backend
        subtotal = Number(cart.tam_tinh || 0);

        // reset discount khi reload cart
        discountAmount = 0;
        discountCodeApplied = '';
        shippingFee = DEFAULT_SHIPPING;

        renderItems(items);
        updateTotalsUI();

        // enable/disable nút đặt hàng
        document.getElementById('btnPlaceOrder').disabled = items.length === 0;
    }

    function renderItems(items) {
        const wrap = document.getElementById('orderItems');
        document.getElementById('orderCount').textContent = String(items.length || 0);

        if (!items.length) {
            wrap.innerHTML = `<p class="text-center text-muted my-3">Giỏ hàng trống</p>`;
            return;
        }

        wrap.innerHTML = items.map(it => {
            const name = it.ten_san_pham || 'Sản phẩm';
            const qty  = Number(it.so_luong || 0);
            const line = Number(it.thanh_tien || (it.don_gia || 0) * qty);

            // optional meta
            const sku  = it.ma_sku ? `<span class="badge bg-light text-dark border">SKU: ${it.ma_sku}</span> ` : '';
            const size = it.ten_kich_co ? `Size: ${it.ten_kich_co} ` : '';
            const color= it.ten_mau ? `Màu: ${it.ten_mau}` : '';

            return `
                <div class="product-item">
                    <div class="flex-grow-1">
                        <div class="product-name">${name} <span class="text-muted fw-normal">x${qty}</span></div>
                        <div class="product-meta">
                            ${sku} ${size} ${color}
                        </div>
                    </div>
                    <div class="product-price text-end">${vnd(line)}</div>
                </div>
            `;
        }).join('');
    }

    // --- Discount Logic
    document.getElementById('applyDiscount').addEventListener('click', function() {
        if (!cart || !Array.isArray(cart.items) || cart.items.length === 0) {
            showError('Giỏ hàng trống, không thể áp mã.');
            return;
        }

        hideAlerts();
        const code = (document.getElementById('discountInput').value || '').trim().toUpperCase();

        // reset
        discountAmount = 0;
        discountCodeApplied = '';
        shippingFee = DEFAULT_SHIPPING;

        if (code === 'SYMBOLIC10') {
            discountCodeApplied = 'SYMBOLIC10';
            discountAmount = Math.round(subtotal * 0.10);
            showSuccess(`<i class="fas fa-tag me-2"></i>Đã áp dụng mã giảm giá 10%`);
        } else if (code === 'FREESHIP') {
            discountCodeApplied = 'FREESHIP';
            shippingFee = 0;
            discountAmount = 0;
            showSuccess(`<i class="fas fa-truck me-2"></i>Đã áp dụng miễn phí vận chuyển`);
        } else if (code === '') {
            showError('Vui lòng nhập mã giảm giá');
        } else {
            showError('Mã giảm giá không hợp lệ hoặc đã hết hạn');
        }

        updateTotalsUI();
    });

    // --- Submit Order
    document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        hideAlerts();

        if (!cart || !Array.isArray(cart.items) || cart.items.length === 0) {
            showError('Giỏ hàng trống, không thể đặt hàng.');
            return;
        }

        const fullname = document.querySelector('[name="fullname"]').value.trim();
        const phone    = document.querySelector('[name="phone"]').value.trim();
        const street   = document.querySelector('[name="address"]').value.trim();
        const note     = document.querySelector('[name="note"]').value.trim();

        const province = document.getElementById('province_text').value.trim();
        const district = document.getElementById('district_text').value.trim();
        const ward     = document.getElementById('ward_text').value.trim();

        if (!fullname || !phone || !street || !province || !district || !ward) {
            showError('Vui lòng điền đầy đủ thông tin địa chỉ và người nhận (*)');
            return;
        }

        const fullAddress = `${street}, ${ward}, ${district}, ${province}`;

        const payload = {
            nguoi_nhan: fullname,
            sdt_nguoi_nhan: phone,
            dia_chi_giao_hang: fullAddress,
            ghi_chu: note,
            phi_van_chuyen: shippingFee,
            giam_gia: discountAmount
        };

        const btn = document.getElementById('btnPlaceOrder');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';

        try {
            const res = await fetch(`${API_BASE}/api/don-hang`, {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json().catch(() => ({}));

            if (res.status === 401) {
                window.location.href = 'login.php';
                return;
            }

            if (!res.ok || !data.ok) {
                showError(data.error || 'Đặt hàng thất bại');
                btn.disabled = false;
                btn.innerHTML = originalText;
                return;
            }

            // Thành công
            const total = vnd(data.tong_tien ?? calcTotal());
            const maDon = data.ma_don_hang ? `<strong>${data.ma_don_hang}</strong>` : 'N/A';

            // Xóa nội dung form để tránh duplicate
            document.getElementById('checkoutForm').reset();
            document.getElementById('orderItems').innerHTML = '';
            document.getElementById('orderCount').textContent = '0';
            updateTotalsUI();

            showSuccess(`
                <h4 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Đặt hàng thành công!</h4>
                <p>Mã đơn hàng: ${maDon}</p>
                <p>Tổng tiền: <strong>${total}</strong></p>
                <hr>
                <p class="mb-0">Cảm ơn bạn đã mua sắm tại Sole Studio. Chúng tôi sẽ sớm liên hệ để xác nhận đơn hàng.</p>
                <div class="mt-3">
                    <a href="index.php" class="btn btn-sm btn-success fw-bold">Tiếp tục mua sắm</a>
                </div>
            `);

            // Ẩn nút đặt hàng sau khi thành công
            btn.style.display = 'none';

        } catch (err) {
            showError('Lỗi kết nối tới server, vui lòng thử lại');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });

    // Load cart lần đầu
    loadCartForCheckout();
</script>

<?php include 'footer.php'; ?>