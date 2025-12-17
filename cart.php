<?php 
    $current_page = "Giỏ hàng"; 
    include 'header.php'; 
?>

<main class="container py-5">

    <div class="row">
        <div class="col-12">
            <div id="cartContent">
                </div>
        </div>
    </div>

</main>

<script>
    // Kiểm tra trạng thái đăng nhập từ PHP Session và gán vào biến JS
    // Lưu ý: Thay 'user_id' bằng tên key session bạn dùng khi xử lý đăng nhập (ví dụ: 'user', 'account', 'customer_id')
    const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    // -------------------------

    // Dữ liệu mẫu 
    let cartItems = [
        {
            id: 1,
            name: 'Symbolic®Street Shoulder Bag',
            variant: 'BLACK',
            price: 360000,
            quantity: 1,
            image: 'https://via.placeholder.com/150' 
        }
    ];

    function formatCurrency(amount) {
        return amount.toLocaleString('vi-VN') + 'đ';
    }

    function calculateTotal() {
        return cartItems.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    function updateQuantity(itemId, change) {
        const item = cartItems.find(i => i.id === itemId);
        if (item) {
            item.quantity += change;
            if (item.quantity < 1) item.quantity = 1;
            renderCart();
        }
    }

    function removeItem(itemId) {
        if(confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
            cartItems = cartItems.filter(i => i.id !== itemId);
            renderCart();
        }
    }

    function renderCart() {
        const cartContent = document.getElementById('cartContent');
        
        // TRƯỜNG HỢP GIỎ HÀNG TRỐNG
        if (cartItems.length === 0) {
            cartContent.innerHTML = `
                <div class="text-center py-5 bg-light rounded shadow-sm">
                    <i class="fas fa-shopping-cart fa-4x mb-3 text-secondary"></i>
                    <h3 class="text-secondary fw-bold">Giỏ hàng của bạn đang trống</h3>
                    <p class="text-muted">Hãy quay lại và chọn thêm sản phẩm yêu thích nhé!</p>
                    <a href="shop.php" class="btn btn-dark mt-3 px-4 py-2 text-uppercase fw-bold">Tiếp tục mua hàng</a>
                </div>
            `;
            return;
        }

        // RENDER BẢNG GIỎ HÀNG
        let cartHTML = `
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="py-3 ps-4 border-0">Sản phẩm</th>
                                    <th scope="col" class="py-3 border-0 text-center">Đơn giá</th>
                                    <th scope="col" class="py-3 border-0 text-center">Số lượng</th>
                                    <th scope="col" class="py-3 border-0 text-center">Thành tiền</th>
                                    <th scope="col" class="py-3 border-0 text-center">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
        `;

        cartItems.forEach(item => {
            const subtotal = item.price * item.quantity;
            cartHTML += `
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <img src="${item.image}" alt="${item.name}" class="rounded border" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="ms-3">
                                <h6 class="mb-0 fw-bold text-dark">${item.name}</h6>
                                <small class="text-muted d-block mt-1">Màu: ${item.variant}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center fw-semibold">${formatCurrency(item.price)}</td>
                    <td class="text-center">
                        <div class="input-group d-inline-flex align-items-center" style="width: 120px;">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <input type="text" class="form-control form-control-sm text-center border-secondary bg-white" value="${item.quantity}" readonly>
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(${item.id}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-center fw-bold text-danger fs-6">${formatCurrency(subtotal)}</td>
                    <td class="text-center">
                        <button class="btn btn-link text-muted p-0" onclick="removeItem(${item.id})" title="Xóa">
                            <i class="fas fa-trash-alt fa-lg hover-danger"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        cartHTML += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Tạm tính:</span>
                                <span class="fw-bold">${formatCurrency(calculateTotal())}</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between mb-4 align-items-center">
                                <span class="h5 mb-0 fw-bold">Tổng cộng:</span>
                                <span class="h4 mb-0 fw-bold text-danger">${formatCurrency(calculateTotal())}</span>
                            </div>
                            
                            <p class="small text-muted mb-4"><i class="fas fa-info-circle me-1"></i>Phí vận chuyển và mã giảm giá sẽ được áp dụng ở bước thanh toán.</p>

                            <div class="d-grid gap-2">
                                <button class="btn btn-dark py-2 text-uppercase fw-bold" onclick="checkout()">Tiến hành thanh toán</button>
                                <a href="index.php" class="btn btn-outline-secondary py-2">Tiếp tục mua hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        cartContent.innerHTML = cartHTML;
    }

    // --- HÀM CHECKOUT ĐÃ SỬA ---
    function checkout() {
        // Kiểm tra biến isLoggedIn đã khai báo ở đầu script
        if (!isLoggedIn) {
            alert("Bạn phải đăng nhập trước khi tiến hành thanh toán");
            // Tùy chọn: Chuyển hướng sang trang login sau khi thông báo
            // window.location.href = 'login.php'; 
            return;
        }

        // Nếu đã đăng nhập thì cho phép chuyển hướng
        window.location.href = 'checkout.php';
    }
    // ---------------------------

    // Initial render
    renderCart();
</script>

<style>
    .hover-danger:hover {
        color: #dc3545 !important;
        transition: color 0.2s;
    }
</style>

<?php include 'footer.php'; ?>