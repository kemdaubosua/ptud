<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
?>

<?php include 'header.php'; ?>

<style>
    /* --- CSS Giao diện Profile --- */
    .profile-tab {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .profile-tab .nav-link {
        color: #666;
        font-weight: 500;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        border-left: 3px solid transparent;
        transition: all 0.3s;
    }
    
    .profile-tab .nav-link:hover {
        color: #000;
        background-color: #f8f9fa;
    }
    
    .profile-tab .nav-link.active {
        color: #000;
        border-left: 3px solid #000;
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .profile-tab .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }

    .profile-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 30px;
        height: 100%;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* --- RESPONSIVE MOBILE --- */
    @media (max-width: 991px) {
        .profile-card {
            padding: 20px;
        }
        
        /* Ẩn menu mặc định trên mobile */
        .profile-sidebar-content {
            display: none;
            border-top: 1px solid #eee;
        }
        
        /* Class để JS toggle hiện menu */
        .profile-sidebar-content.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
        }
    }

    /* --- DESKTOP STICKY SIDEBAR --- */
    @media (min-width: 992px) {
        .sticky-sidebar {
            position: sticky;
            top: 100px; /* Cách top header */
            z-index: 90;
        }
    }

    /* Animation cho menu mobile */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Table Responsive Fix */
    .table-nowrap th, .table-nowrap td {
        white-space: nowrap;
    }

    /* Status Colors */
    .order-status { padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-confirmed { background: #d1ecf1; color: #0c5460; }
    .status-shipping { background: #d4edda; color: #155724; }
    .status-delivered { background: #cce5ff; color: #004085; }
    .status-cancelled { background: #f8d7da; color: #721c24; }
</style>

<main class="bg-light py-4 py-lg-5">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-3">
                <div class="sticky-sidebar">
                    <div class="profile-tab p-0">
                        <div class="p-3 text-center border-bottom bg-white">
                            <img src="https://via.placeholder.com/120" alt="Avatar" class="profile-avatar mb-3">
                            <h5 class="mb-1 text-truncate px-2"><?php echo htmlspecialchars($user['ho_ten'] ?? 'Người dùng'); ?></h5>
                            <small class="text-muted d-block text-truncate px-2"><?php echo htmlspecialchars($user['email'] ?? ''); ?></small>
                        </div>

                        <button class="btn btn-light w-100 d-lg-none py-3 px-3 fw-bold d-flex justify-content-between align-items-center" 
                                type="button" 
                                onclick="document.getElementById('profileMenu').classList.toggle('show')">
                            <span><i class="bi bi-list me-2"></i> Menu Tài khoản</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>

                        <div id="profileMenu" class="profile-sidebar-content d-lg-block">
                            <ul class="nav flex-column mb-0">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#profile" onclick="closeMenuOnMobile()">
                                        <i class="bi bi-person"></i> Thông tin tài khoản
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#orders" onclick="closeMenuOnMobile()">
                                        <i class="bi bi-receipt"></i> Đơn hàng của tôi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#address" onclick="closeMenuOnMobile()">
                                        <i class="bi bi-geo-alt"></i> Sổ địa chỉ
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#security" onclick="closeMenuOnMobile()">
                                        <i class="bi bi-shield-lock"></i> Bảo mật
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-danger border-bottom-0" href="logout.php">
                                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-9">
                <div class="tab-content">
                    
                    <div class="tab-pane fade show active" id="profile">
                        <div class="profile-card">
                            <h4 class="mb-4">Thông tin cá nhân</h4>
                            <form id="profileForm">
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold small text-uppercase">Họ</label>
                                        <input type="text" class="form-control" 
                                               value="<?php echo htmlspecialchars(explode(' ', $user['ho_ten'])[0] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold small text-uppercase">Tên</label>
                                        <input type="text" class="form-control" 
                                               value="<?php echo htmlspecialchars(explode(' ', $user['ho_ten'])[1] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold small text-uppercase">Ngày sinh</label>
                                        <input type="date" class="form-control" 
                                               value="<?php echo htmlspecialchars($user['ngay_sinh'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold small text-uppercase">Số điện thoại</label>
                                        <input type="tel" class="form-control phone-input" 
                                               value="<?php echo htmlspecialchars($user['so_dien_thoai'] ?? ''); ?>">
                                        <div class="invalid-feedback">SĐT không hợp lệ</div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase">Email</label>
                                        <input type="email" class="form-control email-input bg-light" 
                                               value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>
                                        <small class="text-muted">Email không thể thay đổi</small>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-uppercase d-block mb-2">Giới tính</label>
                                        <div class="btn-group w-100 w-md-auto" role="group">
                                            <input type="radio" class="btn-check" name="gender" id="male" autocomplete="off" <?php echo ($user['gioi_tinh'] ?? '') == 'Nam' ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-secondary" for="male">Nam</label>

                                            <input type="radio" class="btn-check" name="gender" id="female" autocomplete="off" <?php echo ($user['gioi_tinh'] ?? '') == 'Nữ' ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-secondary" for="female">Nữ</label>

                                            <input type="radio" class="btn-check" name="gender" id="other" autocomplete="off" <?php echo !in_array($user['gioi_tinh'] ?? '', ['Nam', 'Nữ']) ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-secondary" for="other">Khác</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-3 border-top text-end">
                                    <button type="submit" class="btn btn-dark px-4 py-2 w-100 w-md-auto">
                                        <i class="bi bi-check-lg me-2"></i>Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="orders">
                        <div class="profile-card">
                            <h4 class="mb-4">Lịch sử đơn hàng</h4>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã đơn</th>
                                            <th>Ngày đặt</th>
                                            <th>Sản phẩm</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <div class="spinner-border text-dark" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2">Đang tải dữ liệu...</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="address">
                        <div class="profile-card">
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                <h4 class="mb-0">Sổ địa chỉ</h4>
                                <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <i class="bi bi-plus-lg me-1"></i>Thêm mới
                                </button>
                            </div>
                            
                            <div class="row g-3" id="addressList">
                                <div class="col-12 text-center py-4">
                                    <div class="spinner-border text-dark" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="security">
                        <div class="profile-card">
                            <h4 class="mb-4">Bảo mật tài khoản</h4>
                            <form id="changePasswordForm">
                                <div class="mb-4 pb-3 border-bottom">
                                    <h6 class="mb-3 text-uppercase small fw-bold text-muted">Đổi mật khẩu</h6>
                                    <div class="row g-3">
                                        <div class="col-lg-4 col-12">
                                            <label class="form-label">Mật khẩu hiện tại</label>
                                            <input type="password" class="form-control" required>
                                        </div>
                                        <div class="col-lg-4 col-12">
                                            <label class="form-label">Mật khẩu mới</label>
                                            <input type="password" class="form-control" required minlength="6">
                                        </div>
                                        <div class="col-lg-4 col-12">
                                            <label class="form-label">Xác nhận MK</label>
                                            <input type="password" class="form-control" required minlength="6">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="mb-3 text-uppercase small fw-bold text-muted">Cài đặt nâng cao</h6>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <label class="form-check-label fw-bold" for="twoFactor">Xác thực 2 lớp (2FA)</label>
                                            <small class="text-muted d-block">Nhận mã OTP khi đăng nhập thiết bị lạ</small>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="twoFactor">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-dark px-4 py-2 w-100 w-md-auto">
                                        <i class="bi bi-shield-check me-2"></i>Cập nhật
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Tên người nhận</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" required>
                                <option value="">Chọn...</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label">Quận/Huyện</label>
                            <select class="form-select" required>
                                <option value="">Chọn...</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label">Phường/Xã</label>
                            <select class="form-select" required>
                                <option value="">Chọn...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ cụ thể</label>
                            <input type="text" class="form-control" placeholder="Số nhà, tên đường..." required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="defaultAddress">
                                <label class="form-check-label" for="defaultAddress">Đặt làm địa chỉ mặc định</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-dark" onclick="saveAddress()">Lưu địa chỉ</button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- UI HELPERS ---
    function closeMenuOnMobile() {
        if (window.innerWidth < 992) {
            document.getElementById('profileMenu').classList.remove('show');
        }
    }

    // --- VALIDATION ---
    function validatePhone(phone) {
        return /^(0|\+84)[0-9]{9}$/.test(phone);
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // --- PROFILE LOGIC ---
    const profileForm = document.getElementById('profileForm');
    
    if (profileForm) {
        const phoneInput = profileForm.querySelector('.phone-input');
        const emailInput = profileForm.querySelector('.email-input');

        profileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!validatePhone(phoneInput.value.trim())) {
                phoneInput.classList.add('is-invalid');
                return;
            } else {
                phoneInput.classList.remove('is-invalid');
            }
            
            // Xử lý giới tính
            let gender = '';
            const checkedGender = document.querySelector('input[name="gender"]:checked');
            if (checkedGender) {
                // Lấy text của label tương ứng
                gender = document.querySelector(`label[for="${checkedGender.id}"]`).innerText;
            }

            const formData = {
                ho_ten: `${profileForm.querySelector('input:nth-child(2) input').value} ${profileForm.querySelector('input:nth-child(1) input').value}`, // Logic ghép họ tên tùy thuộc vào API của bạn
                // Lưu ý: Đoạn trên đang lấy theo thứ tự DOM, bạn nên gán ID cho input để chính xác hơn
                // Ở đây mình lấy ví dụ cơ bản:
                ho_ten: profileForm.querySelectorAll('input[type="text"]')[0].value + ' ' + profileForm.querySelectorAll('input[type="text"]')[1].value,
                ngay_sinh: profileForm.querySelector('input[type="date"]').value,
                so_dien_thoai: phoneInput.value.trim(),
                gioi_tinh: gender
            };
            
            try {
                const response = await fetch('http://localhost/PTUD_Final/public/api/auth/cap-nhat-thong-tin', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    alert('Cập nhật thông tin thành công!');
                    location.reload();
                } else {
                    alert(data.error || 'Cập nhật thất bại');
                }
            } catch (error) {
                console.error(error);
                alert('Lỗi kết nối server');
            }
        });
    }

    // --- ORDER LOGIC ---
    async function loadOrders() {
        const tbody = document.querySelector('#orders tbody');
        try {
            // Giả lập delay để thấy spinner
            // await new Promise(r => setTimeout(r, 500)); 

            const response = await fetch('http://localhost/PTUD_Final/public/api/don-hang/lich-su', {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            });
            
            const orders = await response.json();
            
            if (orders && orders.length > 0) {
                tbody.innerHTML = '';
                const statusMap = {
                    'pending': {class: 'status-pending', text: 'Chờ xử lý'},
                    'confirmed': {class: 'status-confirmed', text: 'Đã xác nhận'},
                    'shipping': {class: 'status-shipping', text: 'Đang giao'},
                    'delivered': {class: 'status-delivered', text: 'Đã giao'},
                    'cancelled': {class: 'status-cancelled', text: 'Đã hủy'}
                };
                
                orders.forEach(order => {
                    const row = document.createElement('tr');
                    const statusInfo = statusMap[order.trang_thai] || {class: 'bg-secondary text-white', text: order.trang_thai};
                    
                    row.innerHTML = `
                        <td><strong>#${order.ma_don_hang}</strong></td>
                        <td>${new Date(order.ngay_dat).toLocaleDateString('vi-VN')}</td>
                        <td>${order.so_luong_san_pham} sản phẩm</td>
                        <td class="fw-bold">${parseInt(order.tong_tien).toLocaleString('vi-VN')}₫</td>
                        <td><span class="order-status ${statusInfo.class}">${statusInfo.text}</span></td>
                        <td>
                            <a href="order-detail.php?id=${order.id}" class="btn btn-sm btn-outline-dark">Chi tiết</a>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-receipt display-4 d-block mb-3 opacity-50"></i>
                                <p class="mb-3">Chưa có đơn hàng nào</p>
                                <a href="products.php" class="btn btn-outline-dark btn-sm">Mua sắm ngay</a>
                            </div>
                        </td>
                    </tr>`;
            }
        } catch (error) {
            console.error('Lỗi tải đơn hàng:', error);
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Lỗi tải dữ liệu</td></tr>`;
        }
    }

    // --- ADDRESS LOGIC ---
    async function loadAddresses() {
        const addressList = document.getElementById('addressList');
        try {
            const response = await fetch('http://localhost/PTUD_Final/public/api/dia-chi', {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            });
            
            const addresses = await response.json();
            
            if (addresses && addresses.length > 0) {
                addressList.innerHTML = '';
                addresses.forEach(address => {
                    const col = document.createElement('div');
                    col.className = 'col-md-6';
                    col.innerHTML = `
                        <div class="card h-100 shadow-sm border-0 bg-white">
                            <div class="card-body">
                                ${address.mac_dinh ? '<span class="badge bg-dark mb-2">Mặc định</span>' : ''}
                                <h6 class="card-title fw-bold">${address.ten_nguoi_nhan}</h6>
                                <p class="card-text small text-secondary mb-3">
                                    <i class="bi bi-telephone me-1"></i> ${address.so_dien_thoai}<br>
                                    <i class="bi bi-geo-alt me-1"></i> ${address.dia_chi_cu_the}, ${address.phuong_xa}, ${address.quan_huyen}, ${address.tinh_thanh}
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-dark" onclick="editAddress(${address.id})">Sửa</button>
                                    ${!address.mac_dinh ? `
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteAddress(${address.id})">Xóa</button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="setDefaultAddress(${address.id})">Đặt mặc định</button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    addressList.appendChild(col);
                });
            } else {
                addressList.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-light border text-center py-4">
                            <i class="bi bi-geo-alt display-6 d-block mb-2 text-muted"></i>
                            Chưa có địa chỉ nào được lưu
                        </div>
                    </div>`;
            }
        } catch (error) {
            console.error('Lỗi tải địa chỉ:', error);
        }
    }

    async function loadProvinces() {
        try {
            const response = await fetch('https://provinces.open-api.vn/api/p/');
            const provinces = await response.json();
            const select = document.querySelector('#addAddressModal select:nth-child(1)'); // Selector cần chính xác hơn nếu có nhiều select
            // Tốt nhất nên gán ID cho select tỉnh thành
            const provinceSelect = document.querySelector('#addressForm select:nth-of-type(1)');
            
            if(provinceSelect) {
                provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Lỗi tải tỉnh/thành:', error);
        }
    }

    function saveAddress() {
        const form = document.getElementById('addressForm');
        if (form.checkValidity()) {
            alert('Đã lưu địa chỉ! (Demo)');
            // Code gọi API lưu địa chỉ ở đây
            var myModal = bootstrap.Modal.getInstance(document.getElementById('addAddressModal'));
            myModal.hide();
            loadAddresses();
        } else {
            form.reportValidity();
        }
    }
    
    // --- SECURITY LOGIC ---
    const passForm = document.getElementById('changePasswordForm');
    if(passForm) {
        passForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const inputs = passForm.querySelectorAll('input[type="password"]');
            const currentPass = inputs[0].value;
            const newPass = inputs[1].value;
            const confirmPass = inputs[2].value;

            if (newPass !== confirmPass) {
                alert('Mật khẩu mới không khớp!');
                return;
            }

            try {
                const response = await fetch('http://localhost/PTUD_Final/public/api/auth/doi-mat-khau', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    body: JSON.stringify({
                        mat_khau_hien_tai: currentPass,
                        mat_khau_moi: newPass
                    })
                });
                
                const data = await response.json();
                if (response.ok) {
                    alert('Đổi mật khẩu thành công!');
                    passForm.reset();
                } else {
                    alert(data.error || 'Đổi mật khẩu thất bại');
                }
            } catch (error) {
                alert('Lỗi kết nối server');
            }
        });
    }

    // --- INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', () => {
        // Event listener cho tabs
        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                const target = e.target.getAttribute('href');
                if (target === '#orders') loadOrders();
                if (target === '#address') {
                    loadAddresses();
                    loadProvinces();
                }
            });
        });

        // Load data tab mặc định nếu active
        if (document.querySelector('#orders') && document.querySelector('#orders').classList.contains('active')) loadOrders();
        if (document.querySelector('#address') && document.querySelector('#address').classList.contains('active')) {
            loadAddresses();
            loadProvinces();
        }
    });

    // Placeholder functions
    function editAddress(id) { console.log('Edit', id); }
    function deleteAddress(id) { 
        if(confirm('Xóa địa chỉ này?')) console.log('Delete', id); 
    }
    function setDefaultAddress(id) { console.log('Set default', id); }
</script>

<?php include 'footer.php'; ?>