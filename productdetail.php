<?php include 'header.php'; ?>

<style>
    /* Hiệu ứng active cho nút chọn size/màu */
    .btn-check:checked + .btn-outline-dark,
    .btn-check:active + .btn-outline-dark,
    .btn-outline-dark.active,
    .btn-outline-dark:active,
    .size-option.active {
        background-color: #000 !important;
        color: #fff !important;
    }

    /* Hiệu ứng active cho ảnh thumb */
    .thumbnail.active img {
        border: 2px solid #000;
        opacity: 1;
    }
    .thumbnail img {
        border: 2px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    /* Con trỏ chuột cho các nút màu */
    .color-option {
        cursor: pointer;
        transition: transform 0.2s;
    }
    .color-option:hover {
        transform: scale(1.1);
    }
    .color-option.active {
        ring: 2px solid #000;
        outline: 2px solid #000;
        outline-offset: 2px;
    }
</style>

<main class="container py-5">
    <div class="row g-5">
        <div class="col-lg-6">
            <div class="position-sticky" style="top: 2rem;">
                <div class="position-relative bg-light rounded overflow-hidden mb-3">
                    <img id="mainImage" src="..." class="w-100 h-auto object-fit-cover" alt="Product Image">
                    
                    <button class="btn btn-light rounded-circle shadow-sm position-absolute top-50 start-0 translate-middle-y ms-3" onclick="changeImage(-1)" style="width: 40px; height: 40px;">❮</button>
                    <button class="btn btn-light rounded-circle shadow-sm position-absolute top-50 end-0 translate-middle-y me-3" onclick="changeImage(1)" style="width: 40px; height: 40px;">❯</button>
                </div>
                <div class="d-flex gap-2 overflow-auto pb-2" id="thumbnails"></div>
            </div>
        </div>

        <div class="col-lg-6">
            <h1 class="fw-bold mb-3 display-6" id="pdName">...</h1>
            
            <div class="mb-4">
                <span class="fs-2 fw-bold text-dark" id="pdPrice">...</span>
                <!-- <span class="fs-4 text-muted text-decoration-line-through ms-3">550.000₫</span> -->
            </div>

            <div class="mb-3">
                <span id="pdStock" class="small text-muted"></span>
            </div>


            <div class="mb-4">
                <label class="fw-bold mb-2 d-block">Chọn size:</label>
                <div class="d-flex gap-2" id="pdSizes"></div>
            </div>

            <div class="mb-4">
                <label class="fw-bold mb-2 d-block">Chọn màu:</label>
                <div class="d-flex flex-wrap gap-2" id="pdColors"></div>
            </div>

            <div class="card bg-light border-0 mb-4">
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Với 07 option màu sắc đa dạng</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Phá cách trong thiết kế tay nhún</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bo chun dày dặn ôm vừa phải tinh tế</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Khóa kéo custom logo độc quyền</li>
                    </ul>
                </div>
            </div>

            <div class="d-flex gap-4 mb-4 border-bottom pb-4">
                <div class="d-flex align-items-center text-muted">
                    <div class="bg-light rounded-circle p-2 me-2">
                        <i class="fas fa-box"></i>
                    </div>
                    <span class="small">Giao hàng toàn quốc</span>
                </div>
                <div class="d-flex align-items-center text-muted">
                    <div class="bg-light rounded-circle p-2 me-2">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <span class="small">Thanh toán khi nhận</span>
                </div>
            </div>

            <div class="d-flex gap-3">
                <div class="input-group" style="width: 140px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
                    <input type="text" class="form-control text-center bg-white" id="quantity" value="1" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
                </div>
                
                <button class="btn btn-outline-dark" onclick="if(!validateBeforeBuy()) return;">THÊM VÀO GIỎ</button>
                <button class="btn btn-dark flex-grow-1 fw-bold py-2">MUA NGAY</button>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-dark fw-bold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-pane" type="button" role="tab">Thông tin sản phẩm</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-muted fw-bold" id="care-tab" data-bs-toggle="tab" data-bs-target="#care-pane" type="button" role="tab">Hướng dẫn bảo quản</button>
                </li>
            </ul>

            <div class="tab-content" id="productTabsContent">
                <div class="tab-pane fade show active" id="info-pane" role="tabpanel">
                    <table class="table table-borderless">
                        <tbody>
                            <tr><td class="fw-bold text-muted" style="width: 200px">Chất liệu:</td><td>Vải dù cao cấp, chống thấm nước nhẹ</td></tr>
                            <tr><td class="fw-bold text-muted">Kiểu dáng:</td><td>Oversized, phong cách bomber pilot</td></tr>
                            <tr><td class="fw-bold text-muted">Màu sắc:</td><td>7 màu: Đen, Xám, Be, Trắng, Xanh pastel, Hồng pastel, Đỏ gạch</td></tr>
                            <tr><td class="fw-bold text-muted">Size:</td><td>S, M, L (phù hợp với cả nam và nữ)</td></tr>
                            <tr><td class="fw-bold text-muted">Đặc điểm:</td><td>
                                <ul class="list-unstyled mb-0">
                                    <li>- Đường may tay nhún tạo điểm nhấn</li>
                                    <li>- Bo chun dày dặn ôm vừa phải</li>
                                    <li>- Khóa kéo custom logo độc quyền</li>
                                    <li>- Túi hai bên tiện dụng</li>
                                </ul>
                            </td></tr>
                            <tr><td class="fw-bold text-muted">Xuất xứ:</td><td>Thiết kế và sản xuất tại Việt Nam</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="care-pane" role="tabpanel">
                    <div class="alert alert-light border">
                        <p class="mb-2"><strong>1. Giặt:</strong> Giặt tay tốt nhất, nước dưới 30°C, không dùng chất tẩy mạnh.</p>
                        <p class="mb-2"><strong>2. Phơi/Sấy:</strong> Tránh nắng trực tiếp, không sấy nhiệt cao.</p>
                        <p class="mb-2"><strong>3. Ủi:</strong> Nhiệt độ thấp (dưới 110°C), dùng vải lót, tránh ủi lên logo.</p>
                        <p class="mb-0"><strong>4. Bảo quản:</strong> Treo móc, nơi khô thoáng.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const API_BASE = 'http://localhost/PTUD_Final/public';

    const MAX_BUY_PER_SKU = 10;

    function qs(name){
    return new URLSearchParams(window.location.search).get(name);
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

    /* ===== Images slider ===== */
    let images = [];
    let currentImageIndex = 0;

    function selectThumbnail(index) {
    currentImageIndex = index;
    const main = document.getElementById('mainImage');
    if (main) main.src = images[index] || '';

    document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
        thumb.classList.toggle('active', i === index);
    });
    }

    function changeImage(direction) {
    if (!images.length) return;
    currentImageIndex += direction;
    if (currentImageIndex < 0) currentImageIndex = images.length - 1;
    if (currentImageIndex >= images.length) currentImageIndex = 0;
    selectThumbnail(currentImageIndex);
    }

    /* ===== Quantity ===== */
    function changeQuantity(delta) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value || '1', 10) + delta;

        if (value < 1) value = 1;

        // Phải chọn SKU trước
        if (selectedSizeId !== null && selectedColorId !== null) {
            const v = getVariant(selectedSizeId, selectedColorId);
            if (v) {
                const ton = Number(v.so_luong_ton || 0);

                // Giới hạn tồn kho
                if (value > ton) {
                    value = ton;
                    alert(`Số lượng vượt quá tồn kho (${ton})`);
                }

                // Giới hạn mua lẻ
                if (value > MAX_BUY_PER_SKU) {
                    value = MAX_BUY_PER_SKU;
                    alert('Vui lòng liên hệ nhân viên hỗ trợ nếu mua trên 10 sản phẩm');
                }
            }
        }

        input.value = value;
    }

    /* ===== Variant State ===== */
    let sp = null;
    let variants = [];
    let selectedSizeId = null;
    let selectedColorId = null;

    let sizeToColors = new Map();
    let colorToSizes = new Map();
    let variantMap = new Map();

    function buildIndexes() {
    sizeToColors = new Map();
    colorToSizes = new Map();
    variantMap = new Map();

    variants.forEach(v => {
        const sId = v.kich_co_id ? Number(v.kich_co_id) : null;
        const cId = v.mau_sac_id ? Number(v.mau_sac_id) : null;
        const key = `${sId||0}|${cId||0}`;
        variantMap.set(key, v);

        if (sId) {
        if (!sizeToColors.has(sId)) sizeToColors.set(sId, new Set());
        if (cId) sizeToColors.get(sId).add(cId);
        }
        if (cId) {
        if (!colorToSizes.has(cId)) colorToSizes.set(cId, new Set());
        if (sId) colorToSizes.get(cId).add(sId);
        }
    });
    }

    function getVariant(sizeId, colorId) {
    return variantMap.get(`${sizeId||0}|${colorId||0}`) || null;
    }

    function renderSizes() {
    const wrap = document.getElementById('pdSizes');
    if (!wrap) return;

    const sizes = Array.isArray(sp?.sizes) ? sp.sizes : [];
    wrap.innerHTML = sizes.map(s => {
        const id = Number(s.id);
        const active = (selectedSizeId === id) ? ' active' : '';
        return `<button class="btn btn-outline-dark px-4 py-2 size-option${active}" data-size-id="${id}" type="button">
        ${escapeHtml(s.ten)}
        </button>`;
    }).join('');

    wrap.querySelectorAll('[data-size-id]').forEach(btn => {
        btn.addEventListener('click', () => {
        const id = Number(btn.getAttribute('data-size-id'));
        if (btn.disabled) return;
        selectedSizeId = (selectedSizeId === id) ? null : id;
        syncOptionStates();
        syncPriceAndStock();
        });
    });
    }

    function renderColors() {
    const wrap = document.getElementById('pdColors');
    if (!wrap) return;

    const colors = Array.isArray(sp?.colors) ? sp.colors : [];
    wrap.innerHTML = colors.map(c => {
        const id = Number(c.id);
        const hex = c.ma || '#ffffff';
        const active = (selectedColorId === id) ? ' active' : '';
        return `<div class="color-option rounded-circle border${active}" data-color-id="${id}"
        title="${escapeHtml(c.ten)}"
        style="width:35px;height:35px;background-color:${escapeHtml(hex)};"
        role="button" tabindex="0"></div>`;
    }).join('');

    wrap.querySelectorAll('[data-color-id]').forEach(div => {
        div.addEventListener('click', () => {
        if (div.classList.contains('disabled')) return;
        const id = Number(div.getAttribute('data-color-id'));
        selectedColorId = (selectedColorId === id) ? null : id;
        syncOptionStates();
        syncPriceAndStock();
        });
    });
    }

    function syncOptionStates() {
    document.querySelectorAll('.size-option').forEach(btn => {
        const id = Number(btn.getAttribute('data-size-id'));
        btn.classList.toggle('active', selectedSizeId === id);
    });
    document.querySelectorAll('.color-option').forEach(div => {
        const id = Number(div.getAttribute('data-color-id'));
        div.classList.toggle('active', selectedColorId === id);
    });

    // disable sizes if color selected
    document.querySelectorAll('.size-option').forEach(btn => {
        const sId = Number(btn.getAttribute('data-size-id'));
        let allowed = true;
        if (selectedColorId !== null) {
        const set = colorToSizes.get(selectedColorId);
        allowed = set ? set.has(sId) : false;
        }
        btn.disabled = !allowed;
        if (!allowed && selectedSizeId === sId) selectedSizeId = null;
    });

    // disable colors if size selected
    document.querySelectorAll('.color-option').forEach(div => {
        const cId = Number(div.getAttribute('data-color-id'));
        let allowed = true;
        if (selectedSizeId !== null) {
        const set = sizeToColors.get(selectedSizeId);
        allowed = set ? set.has(cId) : false;
        }
        div.classList.toggle('disabled', !allowed);
        div.style.opacity = allowed ? '1' : '0.35';
        div.style.pointerEvents = allowed ? 'auto' : 'none';
        if (!allowed && selectedColorId === cId) selectedColorId = null;
    });

    // refresh active after possible auto-unselect
    document.querySelectorAll('.size-option').forEach(btn => {
        const id = Number(btn.getAttribute('data-size-id'));
        btn.classList.toggle('active', selectedSizeId === id);
    });
    document.querySelectorAll('.color-option').forEach(div => {
        const id = Number(div.getAttribute('data-color-id'));
        div.classList.toggle('active', selectedColorId === id);
    });
    }

    function syncPriceAndStock() {
        const priceEl = document.getElementById('pdPrice');
        const stockEl = document.getElementById('pdStock');
        const qtyInput = document.getElementById('quantity');

        if (!priceEl || !stockEl || !sp) return;

        // Chưa chọn đủ biến thể
        if (selectedSizeId === null || selectedColorId === null) {
            priceEl.textContent = formatVND(sp.gia_ban);
            stockEl.textContent = 'Vui lòng chọn size và màu';
            return;
        }

        const v = getVariant(selectedSizeId, selectedColorId);
        if (!v) {
            stockEl.textContent = 'Không có biến thể phù hợp';
            return;
        }

        // Giá
        priceEl.textContent = formatVND(v.gia_ban);

        const ton = Number(v.so_luong_ton || 0);

        // Hiển thị tồn kho
        if (ton <= 0) {
            stockEl.textContent = 'Hết hàng';
            stockEl.className = 'small text-danger fw-bold';
        } else {
            stockEl.textContent = `Còn ${ton} sản phẩm`;
            stockEl.className = 'small text-muted';
        }
    }

    function validateBeforeBuy() {
        if (selectedSizeId === null || selectedColorId === null) {
            alert('Vui lòng chọn size và màu');
            return false;
        }

        const v = getVariant(selectedSizeId, selectedColorId);
        if (!v) {
            alert('Biến thể không hợp lệ');
            return false;
        }

        const qty = Number(document.getElementById('quantity').value || 1);

        if (qty > v.so_luong_ton) {
            alert('Số lượng vượt tồn kho');
            return false;
        }

        if (qty > MAX_BUY_PER_SKU) {
            alert('Đơn hàng lớn, vui lòng liên hệ nhân viên hỗ trợ');
            return false;
        }

        return true;
    }

    function autoPickDefaults() {
    if (!variants.length) return;
    const v0 = variants[0];
    selectedSizeId = v0.kich_co_id ? Number(v0.kich_co_id) : null;
    selectedColorId = v0.mau_sac_id ? Number(v0.mau_sac_id) : null;
    }

    async function loadDetail(){
    const id = Number(qs('id') || 0);
    if(!id){
        alert('Thiếu id sản phẩm');
        window.location.href = 'shop.php';
        return;
    }

    const res = await fetch(`${API_BASE}/api/san-pham/${id}`, { credentials: 'include' });
    const data = await res.json().catch(()=> ({}));

    if(!res.ok || !data.ok){
        alert(data.error || 'Không tìm thấy sản phẩm');
        window.location.href = 'shop.php';
        return;
    }

    sp = data.san_pham;
    variants = Array.isArray(sp?.variants) ? sp.variants : [];

    const nameEl = document.getElementById('pdName');
    const priceEl = document.getElementById('pdPrice');
    if (nameEl) nameEl.textContent = sp.ten_san_pham || 'Sản phẩm';
    if (priceEl) priceEl.textContent = formatVND(sp.gia_ban);

    // images
    const list = [];
    if(Array.isArray(sp.anh_phu) && sp.anh_phu.length){
        sp.anh_phu.forEach(a => { if (a?.url_anh) list.push(a.url_anh); });
    }
    if(!list.length){
        list.push(sp.anh_dai_dien_url || 'https://placehold.co/600x800?text=No+Image');
    }
    images = list;

    const wrap = document.getElementById('thumbnails');
    if (wrap) {
        wrap.innerHTML = images.map((url, idx)=>`
        <div class="thumbnail ${idx===0?'active':''}" onclick="selectThumbnail(${idx})" style="width:80px;height:80px;">
            <img src="${url}" class="w-100 h-100 rounded object-fit-cover">
        </div>
        `).join('');
    }

    const main = document.getElementById('mainImage');
    if (main) main.src = images[0];

    buildIndexes();
    autoPickDefaults();

    renderSizes();
    renderColors();

    syncOptionStates();
    syncPriceAndStock();
    }

    loadDetail().catch(() => {
    alert('Không kết nối được API');
    window.location.href = 'shop.php';
    });
</script>


<?php include 'footer.php'; ?>