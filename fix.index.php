<?php
// Kết nối database
require_once 'config/database.php';

// Lấy banner từ database
$banner_query = "SELECT * FROM banners WHERE page = 'home' AND status = 1 ORDER BY created_at DESC LIMIT 1";
$banner_result = mysqli_query($conn, $banner_query);
$banner = mysqli_fetch_assoc($banner_result);

// Lấy danh mục sản phẩm
$category_query = "SELECT * FROM categories WHERE status = 1 AND slug != 'giay-dep' ORDER BY display_order ASC";
$category_result = mysqli_query($conn, $category_query);

// Lấy sản phẩm mới về
$new_products_query = "SELECT p.*, c.name as category_name FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      WHERE p.status = 1 AND p.is_new = 1 
                      ORDER BY p.created_at DESC LIMIT 8";
$new_products_result = mysqli_query($conn, $new_products_query);

// Lấy sản phẩm bán chạy
$bestseller_query = "SELECT p.*, c.name as category_name FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.status = 1 AND p.is_bestseller = 1 
                    ORDER BY p.sold DESC LIMIT 8";
$bestseller_result = mysqli_query($conn, $bestseller_query);
?>

<style>
    /* Banner chính */
    .hero-banner {
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        padding: 80px 20px;
        position: relative;
        overflow: hidden;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        border-radius: 12px;
    }
    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
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
        color: #212529;
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
    .new-tag {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #28a745;
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
    
    /* Giá sản phẩm */
    .product-price {
        color: #dc3545;
        font-weight: bold;
    }
    .product-old-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.9rem;
        margin-right: 5px;
    }
</style>

<main>
    
    <section class="py-4">
        <div class="container">
            <div class="hero-banner" style="background-image: url('<?php echo isset($banner['image_url']) ? $banner['image_url'] : 'https://via.placeholder.com/1200x400?text=Banner+Ch%C3%ADnh'; ?>');">
                <div class="hero-content">
                    <?php if(isset($banner['title'])): ?>
                        <h1 class="text-white fw-bold mb-4"><?php echo $banner['title']; ?></h1>
                    <?php endif; ?>
                    <?php if(isset($banner['description'])): ?>
                        <p class="text-white mb-4 lead"><?php echo $banner['description']; ?></p>
                    <?php endif; ?>
                    <button class="btn btn-light px-5 py-3 rounded-pill fw-bold text-uppercase" onclick="goAllProducts()">
                        <i class="fa-solid fa-bag-shopping me-2"></i>Mua ngay
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h3 class="text-center fw-bold mb-4">Danh mục sản phẩm</h3>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 justify-content-center">
                <?php while($category = mysqli_fetch_assoc($category_result)): ?>
                <div class="col" onclick="goCategory('<?php echo $category['slug']; ?>')">
                    <div class="card category-card border-0 shadow-sm h-100 py-3 text-center text-dark">
                        <div class="category-icon">
                            <i class="<?php echo $category['icon'] ? $category['icon'] : 'fa-solid fa-tag'; ?>"></i>
                        </div>
                        <div class="fw-bold"><?php echo $category['name']; ?></div>
                    </div>
                </div>
                <?php endwhile; ?>
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
                <?php if(mysqli_num_rows($new_products_result) > 0): ?>
                    <?php while($product = mysqli_fetch_assoc($new_products_result)): ?>
                    <div class="col">
                        <a href="productdetail.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <div class="product-image-container">
                                    <div class="new-tag">MỚI</div>
                                    <img src="<?php echo $product['image_url'] ? $product['image_url'] : 'https://via.placeholder.com/300x400?text=Sản+phẩm'; ?>" 
                                         alt="<?php echo $product['name']; ?>"
                                         onerror="this.src='https://via.placeholder.com/300x400?text=Ảnh+Lỗi'">
                                </div>
                                <div class="card-body text-center p-3">
                                    <h6 class="card-title mb-1"><?php echo $product['name']; ?></h6>
                                    <div class="fw-bold product-price">
                                        <?php if($product['discount_price'] && $product['discount_price'] < $product['price']): ?>
                                            <span class="product-old-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                            <?php echo number_format($product['discount_price'], 0, ',', '.'); ?>đ
                                        <?php else: ?>
                                            <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Chưa có sản phẩm mới</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5">
                <button class="btn btn-outline-dark rounded-pill px-5 py-2" onclick="goAllProducts()">
                    <i class="fa-solid fa-eye me-2"></i>Xem tất cả
                </button>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold text-uppercase mb-5">Sản phẩm bán chạy</h2>
            
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
                <?php if(mysqli_num_rows($bestseller_result) > 0): ?>
                    <?php $count = 1; ?>
                    <?php while($product = mysqli_fetch_assoc($bestseller_result)): ?>
                    <div class="col">
                        <a href="productdetail.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <div class="product-image-container">
                                    <?php if($count == 1): ?>
                                        <div class="best-seller-tag">TOP 1</div>
                                    <?php elseif($count <= 3): ?>
                                        <div class="best-seller-tag" style="background-color: #fd7e14;">TOP <?php echo $count; ?></div>
                                    <?php endif; ?>
                                    <img src="<?php echo $product['image_url'] ? $product['image_url'] : 'https://via.placeholder.com/300x400?text=Sản+phẩm'; ?>" 
                                         alt="<?php echo $product['name']; ?>"
                                         onerror="this.src='https://via.placeholder.com/300x400?text=Ảnh+Lỗi'">
                                </div>
                                <div class="card-body text-center p-3">
                                    <h6 class="card-title mb-1"><?php echo $product['name']; ?></h6>
                                    <div class="fw-bold product-price">
                                        <?php if($product['discount_price'] && $product['discount_price'] < $product['price']): ?>
                                            <span class="product-old-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                            <?php echo number_format($product['discount_price'], 0, ',', '.'); ?>đ
                                        <?php else: ?>
                                            <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted">Đã bán: <?php echo $product['sold'] ? $product['sold'] : 0; ?></small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php $count++; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Chưa có sản phẩm bán chạy</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-5">
                <button class="btn btn-outline-dark rounded-pill px-5 py-2" onclick="goAllProducts()">
                    <i class="fa-solid fa-chart-line me-2"></i>Xem thêm
                </button>
            </div>
        </div>
    </section>

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
                        <div class="value-icon"><i class="fa-solid fa-shield-halved"></i></div>
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
        window.location.href = "shop.php";
    }

    function goCategory(category) {
        window.location.href = "shop.php?danh_muc=" + encodeURIComponent(category);
    }
</script>

<?php 
// Đóng kết nối
mysqli_close($conn);
include 'footer.php'; 
?>