<?php 
    $current_page = "Cửa hàng"; 
    include 'header.php'; 
?>

<style>
    .sidebar-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    
    .filter-section {
        margin-bottom: 1.8rem;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 1.8rem;
    }
    
    .filter-section:last-child {
        border-bottom: none;
    }
    
    .filter-title {
        font-size: 1rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .filter-title .clear-filter {
        font-size: 0.85rem;
        font-weight: 500;
        color: #777;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .filter-title .clear-filter:hover {
        color: #000;
    }
    
    .filter-link {
        font-size: 0.95rem;
        color: #555;
        transition: all 0.2s;
        text-decoration: none;
        display: block;
        padding: 8px 0;
        border-radius: 8px;
        padding-left: 12px;
        position: relative;
    }
    
    .filter-link:hover, .filter-link.active {
        color: #000;
        background-color: #f8f9fa;
        font-weight: 500;
    }
    
    .filter-link.active:before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 16px;
        background-color: #000;
        border-radius: 2px;
    }
    
    .price-filter-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 8px;
        padding-left: 12px;
    }
    
    .price-filter-item:hover {
        background-color: #f8f9fa;
    }
    
    .price-filter-item.active {
        background-color: #f8f9fa;
        font-weight: 500;
    }
    
    .price-filter-item .price-range {
        font-size: 0.9rem;
        color: #666;
    }
    
    .price-filter-item.active .price-range {
        color: #000;
        font-weight: 500;
    }
    
    .price-filter-item .checkmark {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        transition: all 0.2s;
    }
    
    .price-filter-item.active .checkmark {
        border-color: #000;
        background-color: #000;
    }
    
    .price-filter-item.active .checkmark:after {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #fff;
    }
    
    .product-count {
        font-size: 0.85rem;
        color: #888;
        font-weight: 400;
    }
    
    .btn-filter-toggle {
        background: #fff;
        border: 2px solid #222;
        color: #222;
        font-weight: 500;
        border-radius: 10px;
        padding: 10px 20px;
        transition: all 0.2s;
    }
    
    .btn-filter-toggle:hover {
        background: #222;
        color: #fff;
    }
    
    .mobile-filter-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1050;
        display: none;
    }
    
    .mobile-filter-sidebar {
        position: fixed;
        top: 0;
        left: -320px;
        width: 300px;
        height: 100%;
        background: #fff;
        z-index: 1060;
        padding: 25px;
        overflow-y: auto;
        transition: left 0.3s ease;
        box-shadow: 5px 0 25px rgba(0,0,0,0.1);
    }
    
    .mobile-filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .close-filter-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #666;
        cursor: pointer;
    }
    
    .btn-size {
        min-width: 45px;
        border-radius: 20px;
    }

    .product-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 35px rgba(0,0,0,0.1) !important;
    }

    .product-image-container {
        height: 280px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
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

    .old-price {
        font-size: 0.9rem;
        text-decoration: line-through;
        color: #999;
    }
</style>

<main class="bg-light py-5">
    <div class="container">
        <div class="row">
            
            <!-- Desktop Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0">Bộ lọc</h4>
                        <a href="shop.php" class="clear-filter">Xóa tất cả</a>
                    </div>

                    <div class="filter-section">
                        <h6 class="filter-title">Danh mục</h6>
                        <ul class="list-unstyled">
                            <li><a href="shop.php?danh_muc_id=1" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 1) ? 'active' : ''; ?>">Áo thun</a></li>
                            <li><a href="shop.php?danh_muc_id=2" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 2) ? 'active' : ''; ?>">Hoodie</a></li>
                            <li><a href="shop.php?danh_muc_id=3" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 3) ? 'active' : ''; ?>">Quần</a></li>
                            <li><a href="shop.php?danh_muc_id=4" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 4) ? 'active' : ''; ?>">Áo khoác</a></li>
                            <li><a href="shop.php?danh_muc_id=5" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 5) ? 'active' : ''; ?>">Áo sơ mi</a></li>
                            <li><a href="shop.php?danh_muc_id=6" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 6) ? 'active' : ''; ?>">Phụ kiện</a></li>
                            <li><a href="shop.php" class="filter-link <?php echo (!isset($_GET['danh_muc_id'])) ? 'active' : ''; ?>">Tất cả</a></li>
                        </ul>
                    </div>

                    <div class="filter-section">
                        <h6 class="filter-title">Khoảng giá</h6>
                        <div id="priceFilters">
                            <div class="price-filter-item <?php echo (!isset($_GET['gia_tu']) && !isset($_GET['gia_den'])) ? 'active' : ''; ?>" data-price-range="all">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>Tất cả giá</span>
                                </div>
                                <span class="product-count">-</span>
                            </div>
                            <div class="price-filter-item <?php echo (isset($_GET['gia_tu']) && $_GET['gia_tu'] == 0 && isset($_GET['gia_den']) && $_GET['gia_den'] == 300000) ? 'active' : ''; ?>" data-price-range="0-300000">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>Dưới 300.000đ</span>
                                </div>
                                <span class="product-count">-</span>
                            </div>
                            <div class="price-filter-item <?php echo (isset($_GET['gia_tu']) && $_GET['gia_tu'] == 300000 && isset($_GET['gia_den']) && $_GET['gia_den'] == 500000) ? 'active' : ''; ?>" data-price-range="300000-500000">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>300.000đ - 500.000đ</span>
                                </div>
                                <span class="product-count">-</span>
                            </div>
                            <div class="price-filter-item <?php echo (isset($_GET['gia_tu']) && $_GET['gia_tu'] == 500000 && isset($_GET['gia_den']) && $_GET['gia_den'] == 1000000) ? 'active' : ''; ?>" data-price-range="500000-1000000">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>500.000đ - 1.000.000đ</span>
                                </div>
                                <span class="product-count">-</span>
                            </div>
                            <div class="price-filter-item <?php echo (isset($_GET['gia_tu']) && $_GET['gia_tu'] == 1000000 && !isset($_GET['gia_den'])) ? 'active' : ''; ?>" data-price-range="1000000">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>Trên 1.000.000đ</span>
                                </div>
                                <span class="product-count">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h6 class="filter-title">Sắp xếp</h6>
                        <div id="sortFilters">
                            <div class="price-filter-item <?php echo (!isset($_GET['sap_xep'])) ? 'active' : ''; ?>" data-sort="">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>Mặc định</span>
                                </div>
                            </div>
                            <div class="price-filter-item <?php echo (isset($_GET['sap_xep']) && $_GET['sap_xep'] == 'gia_tang') ? 'active' : ''; ?>" data-sort="gia_tang">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>Giá: Thấp đến cao</span>
                                </div>
                            </div>
                            <div class="price-filter-item <?php echo (isset($_GET['sap_xep']) && $_GET['sap_xep'] == 'gia_giam') ? 'active' : ''; ?>" data-sort="gia_giam">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>Giá: Cao đến thấp</span>
                                </div>
                            </div>
                            <div class="price-filter-item <?php echo (isset($_GET['sap_xep']) && $_GET['sap_xep'] == 'moi_nhat') ? 'active' : ''; ?>" data-sort="moi_nhat">
                                <div class="d-flex align-items-center">
                                    <div class="checkmark"></div>
                                    <span>Mới nhất</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Filter Overlay -->
            <div class="mobile-filter-overlay" id="mobileFilterOverlay"></div>
            
            <!-- Mobile Filter Sidebar -->
            <div class="mobile-filter-sidebar" id="mobileFilterSidebar">
                <div class="mobile-filter-header">
                    <h4 class="fw-bold m-0">Bộ lọc</h4>
                    <button class="close-filter-btn" id="closeFilterBtn">&times;</button>
                </div>
                
                <div class="filter-section">
                    <h6 class="filter-title">Danh mục</h6>
                    <ul class="list-unstyled">
                        <li><a href="shop.php?danh_muc_id=1" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 1) ? 'active' : ''; ?>">Áo thun</a></li>
                        <li><a href="shop.php?danh_muc_id=2" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 2) ? 'active' : ''; ?>">Hoodie</a></li>
                        <li><a href="shop.php?danh_muc_id=3" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 3) ? 'active' : ''; ?>">Quần</a></li>
                        <li><a href="shop.php?danh_muc_id=4" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 4) ? 'active' : ''; ?>">Áo khoác</a></li>
                        <li><a href="shop.php?danh_muc_id=5" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 5) ? 'active' : ''; ?>">Áo sơ mi</a></li>
                        <li><a href="shop.php?danh_muc_id=6" class="filter-link <?php echo (isset($_GET['danh_muc_id']) && $_GET['danh_muc_id'] == 6) ? 'active' : ''; ?>">Phụ kiện</a></li>
                        <li><a href="shop.php" class="filter-link <?php echo (!isset($_GET['danh_muc_id'])) ? 'active' : ''; ?>">Tất cả</a></li>
                    </ul>
                </div>

                <div class="filter-section">
                    <h6 class="filter-title">Khoảng giá</h6>
                    <div id="mobilePriceFilters">
                        <div class="price-filter-item <?php echo (!isset($_GET['gia_tu']) && !isset($_GET['gia_den'])) ? 'active' : ''; ?>" data-price-range="all">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>Tất cả giá</span>
                            </div>
                            <span class="product-count">-</span>
                        </div>
                        <div class="price-filter-item <?php echo (isset($_GET['gia_tu']) && $_GET['gia_tu'] == 0 && isset($_GET['gia_den']) && $_GET['gia_den'] == 300000) ? 'active' : ''; ?>" data-price-range="0-300000">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>Dưới 300.000đ</span>
                            </div>
                            <span class="product-count">-</span>
                        </div>
                        <div class="price-filter-item <?php echo (isset($_GET['gia_tu']) && $_GET['gia_tu'] == 300000 && isset($_GET['gia_den']) && $_GET['gia_den'] == 500000) ? 'active' : ''; ?>" data-price-range="300000-500000">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>300.000đ - 500.000đ</span>
                            </div>
                            <span class="product-count">-</span>
                        </div>
                        <div class="price-filter-item <?php echo (isset($_GET['gia_tu']) && $_GET['gia_tu'] == 500000 && isset($_GET['gia_den']) && $_GET['gia_den'] == 1000000) ? 'active' : ''; ?>" data-price-range="500000-1000000">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>500.000đ - 1.000.000đ</span>
                            </div>
                            <span class="product-count">-</span>
                        </div>
                        <div class="price-filter-item <?php echo (isset($_GET['gia_tu']) && $_GET['gia_tu'] == 1000000 && !isset($_GET['gia_den'])) ? 'active' : ''; ?>" data-price-range="1000000">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>Trên 1.000.000đ</span>
                            </div>
                            <span class="product-count">-</span>
                        </div>
                    </div>
                </div>

                <div class="filter-section">
                    <h6 class="filter-title">Sắp xếp</h6>
                    <div id="mobileSortFilters">
                        <div class="price-filter-item <?php echo (!isset($_GET['sap_xep'])) ? 'active' : ''; ?>" data-sort="">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>Mặc định</span>
                            </div>
                        </div>
                        <div class="price-filter-item <?php echo (isset($_GET['sap_xep']) && $_GET['sap_xep'] == 'gia_tang') ? 'active' : ''; ?>" data-sort="gia_tang">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>Giá: Thấp đến cao</span>
                            </div>
                        </div>
                        <div class="price-filter-item <?php echo (isset($_GET['sap_xep']) && $_GET['sap_xep'] == 'gia_giam') ? 'active' : ''; ?>" data-sort="gia_giam">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>Giá: Cao đến thấp</span>
                            </div>
                        </div>
                        <div class="price-filter-item <?php echo (isset($_GET['sap_xep']) && $_GET['sap_xep'] == 'moi_nhat') ? 'active' : ''; ?>" data-sort="moi_nhat">
                            <div class="d-flex align-items-center">
                                <div class="checkmark"></div>
                                <span>Mới nhất</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-3">
                    <button class="btn btn-dark w-100 py-3" id="applyMobileFilter">Áp dụng bộ lọc</button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold text-uppercase m-0">Tất cả sản phẩm</h1>
                    <button class="btn btn-filter-toggle d-lg-none" id="openFilterBtn">
                        <i class="fas fa-filter me-2"></i>Bộ lọc
                    </button>
                </div>

                <div id="productGrid" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4"></div>

                <div class="d-flex justify-content-between align-items-center mt-4" id="pagingBar" style="display:none;">
                    <button class="btn btn-outline-dark" id="btnPrev">Trang trước</button>
                    <div class="text-muted small" id="pagingText"></div>
                    <button class="btn btn-outline-dark" id="btnNext">Trang sau</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
const API_BASE = 'http://localhost/PTUD_Final/public';

function qs(name){
  return new URLSearchParams(window.location.search).get(name);
}

function setQs(params){
  const url = new URL(window.location.href);
  Object.entries(params).forEach(([k,v])=>{
    if(v === null || v === '' || typeof v === 'undefined') url.searchParams.delete(k);
    else url.searchParams.set(k, v);
  });
  window.location.href = url.toString();
}

function formatVND(n){
  const num = Number(n || 0);
  return num.toLocaleString('vi-VN') + '₫';
}

function escapeHtml(s){
  return String(s ?? '')
    .replaceAll('&','&amp;')
    .replaceAll('<','&lt;')
    .replaceAll('>','&gt;')
    .replaceAll('"','&quot;')
    .replaceAll("'","&#039;");
}

function renderProductCard(sp){
  const img = sp.anh_dai_dien_url || 'https://placehold.co/600x800?text=No+Image';
  const ten = escapeHtml(sp.ten_san_pham);
  const gia = formatVND(sp.gia_ban);
  const giaGoc = sp.gia_goc ? formatVND(sp.gia_goc) : null;

  const href = `productdetail.php?id=${encodeURIComponent(sp.id)}`;

  return `
    <div class="col">
      <a href="${href}" class="text-decoration-none text-dark">
        <div class="card product-card h-100 border-0 shadow-sm">
          <div class="product-image-container">
            <img src="${img}" alt="${ten}">
          </div>
          <div class="card-body p-3 text-center">
            <h6 class="card-title mb-1 fw-normal">${ten}</h6>
            <div class="fw-bold">${gia}</div>
            ${giaGoc ? `<div class="old-price">${giaGoc}</div>` : ''}
          </div>
        </div>
      </a>
    </div>
  `;
}

// Price filter handler
function setupPriceFilters(containerId) {
  const container = document.getElementById(containerId);
  if (!container) return;
  
  container.querySelectorAll('.price-filter-item').forEach(item => {
    item.addEventListener('click', () => {
      // Remove active class from all items
      container.querySelectorAll('.price-filter-item').forEach(i => {
        i.classList.remove('active');
      });
      
      // Add active class to clicked item
      item.classList.add('active');
      
      // Get price range
      const priceRange = item.getAttribute('data-price-range');
      
      // Prepare query params
      const params = { trang: 1 };
      
      // Clear previous price filters
      delete params.gia_tu;
      delete params.gia_den;
      
      // Set new price filters based on range
      if (priceRange !== 'all') {
        if (priceRange === '1000000') {
          params.gia_tu = 1000000;
        } else {
          const [gia_tu, gia_den] = priceRange.split('-');
          params.gia_tu = gia_tu;
          params.gia_den = gia_den;
        }
      }
      
      // Apply filters
      setQs(params);
    });
  });
}

// Sort filter handler
function setupSortFilters(containerId) {
  const container = document.getElementById(containerId);
  if (!container) return;
  
  container.querySelectorAll('.price-filter-item').forEach(item => {
    item.addEventListener('click', () => {
      // Remove active class from all items
      container.querySelectorAll('.price-filter-item').forEach(i => {
        i.classList.remove('active');
      });
      
      // Add active class to clicked item
      item.classList.add('active');
      
      // Get sort value
      const sortValue = item.getAttribute('data-sort');
      
      // Prepare query params
      const params = { trang: 1 };
      
      // Set sort parameter if not empty
      if (sortValue) {
        params.sap_xep = sortValue;
      } else {
        // Remove sort parameter if empty
        delete params.sap_xep;
      }
      
      // Apply sort
      setQs(params);
    });
  });
}

// Mobile filter functionality
function setupMobileFilters() {
  const openFilterBtn = document.getElementById('openFilterBtn');
  const closeFilterBtn = document.getElementById('closeFilterBtn');
  const mobileFilterOverlay = document.getElementById('mobileFilterOverlay');
  const mobileFilterSidebar = document.getElementById('mobileFilterSidebar');
  const applyMobileFilterBtn = document.getElementById('applyMobileFilter');
  
  if (openFilterBtn) {
    openFilterBtn.addEventListener('click', () => {
      mobileFilterOverlay.style.display = 'block';
      mobileFilterSidebar.style.left = '0';
      document.body.style.overflow = 'hidden';
    });
  }
  
  if (closeFilterBtn) {
    closeFilterBtn.addEventListener('click', closeMobileFilters);
  }
  
  if (mobileFilterOverlay) {
    mobileFilterOverlay.addEventListener('click', closeMobileFilters);
  }
  
  if (applyMobileFilterBtn) {
    applyMobileFilterBtn.addEventListener('click', () => {
      // Get selected price filter
      const activePriceFilter = document.querySelector('#mobilePriceFilters .price-filter-item.active');
      const priceRange = activePriceFilter ? activePriceFilter.getAttribute('data-price-range') : 'all';
      
      // Get selected sort filter
      const activeSortFilter = document.querySelector('#mobileSortFilters .price-filter-item.active');
      const sortValue = activeSortFilter ? activeSortFilter.getAttribute('data-sort') : '';
      
      // Get selected category (if any)
      const activeCategoryFilter = document.querySelector('#mobileFilterSidebar .filter-link.active');
      let danh_muc_id = null;
      
      if (activeCategoryFilter && activeCategoryFilter.href) {
        const url = new URL(activeCategoryFilter.href);
        const categoryId = url.searchParams.get('danh_muc_id');
        if (categoryId) danh_muc_id = categoryId;
      }
      
      // Prepare query params
      const params = { trang: 1 };
      
      // Set category
      if (danh_muc_id) {
        params.danh_muc_id = danh_muc_id;
      } else {
        delete params.danh_muc_id;
      }
      
      // Set price filter
      if (priceRange !== 'all') {
        if (priceRange === '1000000') {
          params.gia_tu = 1000000;
        } else {
          const [gia_tu, gia_den] = priceRange.split('-');
          params.gia_tu = gia_tu;
          params.gia_den = gia_den;
        }
      } else {
        delete params.gia_tu;
        delete params.gia_den;
      }
      
      // Set sort
      if (sortValue) {
        params.sap_xep = sortValue;
      } else {
        delete params.sap_xep;
      }
      
      // Apply filters and close mobile sidebar
      closeMobileFilters();
      setQs(params);
    });
  }
  
  function closeMobileFilters() {
    mobileFilterOverlay.style.display = 'none';
    mobileFilterSidebar.style.left = '-320px';
    document.body.style.overflow = 'auto';
  }
}

async function loadProducts(){
  const tu_khoa = qs('tu_khoa') || '';
  const danh_muc_id = qs('danh_muc_id');
  const trang = Number(qs('trang') || 1);
  const gioi_han = Number(qs('gioi_han') || 12);
  const gia_tu = qs('gia_tu');
  const gia_den = qs('gia_den');
  const sap_xep = qs('sap_xep');
  
  const url = new URL(`${API_BASE}/api/san-pham`);
  url.searchParams.set('trang', String(trang));
  url.searchParams.set('gioi_han', String(gioi_han));
  
  if(tu_khoa) url.searchParams.set('tu_khoa', tu_khoa);
  if(danh_muc_id) url.searchParams.set('danh_muc_id', danh_muc_id);
  if(gia_tu) url.searchParams.set('gia_tu', gia_tu);
  if(gia_den) url.searchParams.set('gia_den', gia_den);
  if(sap_xep) url.searchParams.set('sap_xep', sap_xep);

  const grid = document.getElementById('productGrid');
  const pagingBar = document.getElementById('pagingBar');
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');
  const pagingText = document.getElementById('pagingText');

  grid.innerHTML = `<div class="col-12 text-center text-muted py-5"><div class="spinner-border text-dark" role="status"></div><div class="mt-2">Đang tải sản phẩm...</div></div>`;

  try {
    const res = await fetch(url.toString(), { credentials: 'include' });
    const data = await res.json();

    if(!res.ok || !data.ok){
      grid.innerHTML = `<div class="col-12 text-center text-danger py-5">Không tải được danh sách sản phẩm</div>`;
      if(pagingBar) pagingBar.style.display = 'none';
      return;
    }

    const items = data.items || [];
    if(items.length === 0){
      grid.innerHTML = `<div class="col-12 text-center text-muted py-5">
        <i class="fas fa-box-open fa-2x mb-3"></i>
        <p class="mb-2">Không có sản phẩm phù hợp</p>
        <a href="shop.php" class="btn btn-outline-dark btn-sm">Xóa bộ lọc</a>
      </div>`;
      if(pagingBar) pagingBar.style.display = 'none';
      return;
    }

    grid.innerHTML = items.map(renderProductCard).join('');

    // Update product counts in filters (sample - in real app you'd get counts from API)
    updateProductCounts(data.tong || 0);

    // Phân trang
    if(pagingBar){
      const p = data.paging || {};
      const tong_trang = Number(p.tong_trang || 1);
      pagingBar.style.display = 'flex';

      pagingText.textContent = `Trang ${p.trang || 1} / ${tong_trang} (Tổng: ${p.tong || 0})`;

      btnPrev.disabled = (trang <= 1);
      btnNext.disabled = (trang >= tong_trang);

      btnPrev.onclick = ()=> setQs({ trang: trang - 1 });
      btnNext.onclick = ()=> setQs({ trang: trang + 1 });
    }
  } catch (error) {
    grid.innerHTML = `<div class="col-12 text-center text-danger py-5">Lỗi: không kết nối được API</div>`;
    if(pagingBar) pagingBar.style.display = 'none';
  }
}

// Sample function to update product counts in filters
function updateProductCounts(total) {
  // In a real app, you would get counts for each filter from the API
  const counts = {
    'all': total,
    '0-300000': Math.floor(total * 0.2),
    '300000-500000': Math.floor(total * 0.3),
    '500000-1000000': Math.floor(total * 0.35),
    '1000000': Math.floor(total * 0.15)
  };
  
  // Update desktop filters
  document.querySelectorAll('#priceFilters .product-count').forEach(el => {
    const item = el.closest('.price-filter-item');
    const range = item.getAttribute('data-price-range');
    el.textContent = `(${counts[range] || 0})`;
  });
  
  // Update mobile filters
  document.querySelectorAll('#mobilePriceFilters .product-count').forEach(el => {
    const item = el.closest('.price-filter-item');
    const range = item.getAttribute('data-price-range');
    el.textContent = `(${counts[range] || 0})`;
  });
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  // Setup price filters
  setupPriceFilters('priceFilters');
  setupPriceFilters('mobilePriceFilters');
  
  // Setup sort filters
  setupSortFilters('sortFilters');
  setupSortFilters('mobileSortFilters');
  
  // Setup mobile filters
  setupMobileFilters();
  
  // Load products
  loadProducts().catch(err => {
    const grid = document.getElementById('productGrid');
    if(grid) grid.innerHTML = `<div class="col-12 text-center text-danger py-5">Lỗi: không kết nối được API</div>`;
  });
});
</script>

<?php include 'footer.php'; ?>