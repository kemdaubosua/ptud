<?php include 'header.php'; ?>

<style>
    /* Banner chính */
    .hero-banner {
        background-color: #e9ecef;
        border-radius: 12px;
        padding: 80px 20px;
        position: relative;
        overflow: hidden;
    }

    /* Card Danh mục */
    .category-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .category-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    /* Product Card (Đồng bộ với Shop) */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .product-image-container {
        height: 280px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .product-image-container img {
        transition: transform 0.5s ease;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-card:hover .product-image-container img {
        transform: scale(1.08);
    }
    
    /* Tag Top 1 */
    .best-seller-tag {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #dc3545;
        color: white;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: bold;
        border-radius: 20px;
        z-index: 2;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* Mini Banner */
    .mini-banner {
        height: 200px;
        background-color: #e2e3e5;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-style: italic;
        font-size: 1.5rem;
        color: #6c757d;
        transition: transform 0.3s;
        cursor: pointer;
    }
    .mini-banner:hover {
        transform: scale(1.02);
    }

    /* Value Props */
    .value-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        color: #212529;
    }
</style>

<main>
    
    <section class="py-4">
        <div class="container">
            <div class="hero-banner text-center text-muted">
                <button class="btn btn-dark px-5 py-3 rounded-pill fw-bold text-uppercase" onclick="goAllProducts()">Mua ngay</button>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 justify-content-center">
                <div class="col" onclick="goCategory('1')">
                    <div class="card category-card border-0 shadow-sm h-100 py-3 text-center text-dark">
                        <div class="category-icon"><i class="fa-solid fa-shirt"></i></div>
                        <div class="fw-bold">ÁO</div>
                    </div>
                </div>
                
                <div class="col" onclick="goCategory('quan')">
                    <div class="card category-card border-0 shadow-sm h-100 py-3 text-center text-dark">
                     <div class="category-icon"><i class="fa-solid fa-person-walking"></i></div>
                        <div class="fw-bold">QUẦN</div>
                    </div>
                </div>

                <div class="col" onclick="goCategory('phukien')">
                    <div class="card category-card border-0 shadow-sm h-100 py-3 text-center text-dark">
                        <div class="category-icon"><i class="fa-solid fa-glasses"></i></div>
                        <div class="fw-bold">PHỤ KIỆN</div>
                    </div>
                </div>
                
                <div class="col" onclick="goCategory('sale')">
                    <div class="card category-card border-0 shadow-sm h-100 py-3 text-center text-danger">
                        <div class="category-icon"><i class="fa-solid fa-tags"></i></div>
                        <div class="fw-bold">SALE</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold text-uppercase mb-5">Hàng mới về</h2>
            
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
                
                <div class="col">
                    <a href="productdetail.php" class="text-decoration-none text-dark">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <div class="product-image-container">
                                <img src="https://via.placeholder.com/300x400?text=Ao+Thun" alt="Product">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1">Áo thun basic</h6>
                                <div class="fw-bold">299.000đ</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="productdetail.php" class="text-decoration-none text-dark">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <div class="product-image-container">
                                <img src="https://via.placeholder.com/300x400?text=Hoodie" alt="Product">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1">Áo hoodie</h6>
                                <div class="fw-bold">499.000đ</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="productdetail.php" class="text-decoration-none text-dark">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <div class="product-image-container">
                                <img src="https://via.placeholder.com/300x400?text=Jeans" alt="Product">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1">Quần jeans</h6>
                                <div class="fw-bold">599.000đ</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="productdetail.php" class="text-decoration-none text-dark">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <div class="product-image-container">
                                <img src="https://via.placeholder.com/300x400?text=Jacket" alt="Product">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1">Áo khoác dù</h6>
                                <div class="fw-bold">799.000đ</div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            <div class="text-center mt-5">
                <button class="btn btn-outline-dark rounded-pill px-5 py-2" onclick="goAllProducts()">Xem tất cả</button>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold text-uppercase mb-5">Sản phẩm bán chạy</h2>
            
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
                
                <div class="col">
                    <a href="productdetail.php" class="text-decoration-none text-dark">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <div class="product-image-container">
                                <div class="best-seller-tag">TOP 1</div>
                                <img src="https://via.placeholder.com/300x400?text=Polo" alt="Product">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1">Áo Polo bán chạy</h6>
                                <div class="fw-bold">349.000đ</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="productdetail.php" class="text-decoration-none text-dark">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <div class="product-image-container">
                                <img src="https://via.placeholder.com/300x400?text=Vay" alt="Product">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1">Váy basic nữ</h6>
                                <div class="fw-bold">450.000đ</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="productdetail.php" class="text-decoration-none text-dark">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <div class="product-image-container">
                                <img src="https://via.placeholder.com/300x400?text=Giay" alt="Product">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1">Giày thể thao</h6>
                                <div class="fw-bold">1.200.000đ</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="productdetail.php" class="text-decoration-none text-dark">
                        <div class="card product-card border-0 shadow-sm h-100">
                            <div class="product-image-container">
                                <img src="https://via.placeholder.com/300x400?text=Tui" alt="Product">
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1">Túi xách da</h6>
                                <div class="fw-bold">890.000đ</div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
            
            <div class="text-center mt-5">
                <button class="btn btn-outline-dark rounded-pill px-5 py-2" onclick="goAllProducts()">Xem thêm</button>
            </div>
        </div>
    </section>

    <!-- <section class="py-4 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mini-banner shadow-sm" onclick="goCategory('collection-moi')">
                        Bộ Sưu Tập Mùa Đông
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mini-banner shadow-sm" onclick="goCategory('phu-kien-hot')">
                        Phụ Kiện Giảm Giá
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <section class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Tại sao chọn chúng tôi?</h2>
            
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 text-center">
                <div class="col">
                    <div class="p-3 h-100">
                        <div class="value-icon"><i class="fa-solid fa-truck-fast"></i></div>
                        <h5 class="fw-bold">Giao hàng nhanh</h5>
                        <p class="text-muted small">Miễn phí vận chuyển cho đơn hàng từ 500.000đ</p>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 h-100">
                        <div class="value-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                        <h5 class="fw-bold">Đổi trả dễ dàng</h5>
                        <p class="text-muted small">Đổi trả sản phẩm trong vòng 30 ngày</p>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 h-100">
                        <div class="value-icon"><i class="fa-solid fa-credit-card"></i></div>
                        <h5 class="fw-bold">Thanh toán an toàn</h5>
                        <p class="text-muted small">Bảo mật thông tin khách hàng tuyệt đối</p>
                    </div>
                </div>
                <div class="col">
                    <div class="p-3 h-100">
                        <div class="value-icon"><i class="fa-solid fa-handshake-angle"></i></div>
                        <h5 class="fw-bold">Hỗ trợ 24/7</h5>
                        <p class="text-muted small">Đội ngũ chăm sóc khách hàng luôn sẵn sàng</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<script>
    function goAllProducts() {
        window.location.href = "shop.php"; // Chuyển sang file PHP
    }

    function goCategory(category) {
        window.location.href = "shop.php?danh_muc_id=" + encodeURIComponent(category);
    }
    
    // Các thẻ <a> bao quanh product-card đã tự động lo việc chuyển trang
</script>

<?php include 'footer.php'; ?>