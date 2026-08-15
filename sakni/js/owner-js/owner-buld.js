/* ============================================
    BAYATY - Owner Properties Page JS
    Reads from localStorage 'ownerProperties'
    Displays cards + full detail view
    Supports: delete, send to review
    ============================================ */

let properties = [];
let currentTab = 'all';
let selectedPropertyId = null;

try {
    const stored = localStorage.getItem('ownerProperties');
    if (stored) properties = JSON.parse(stored);
} catch(e) { console.error('Error loading properties:', e); }

function init() {
    renderProperties();
}

function renderProperties() {
    const grid = document.getElementById('propertiesGrid');
    const filtered = currentTab === 'all'
        ? properties
        : properties.filter(p => p.status === currentTab);

    if (filtered.length === 0) {
        grid.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">🏠</div>
                <h3>لا توجد عقارات</h3>
                <p>لم تقم بإضافة أي عقارات بعد</p>
            </div>
        `;
        return;
    }

    grid.innerHTML = filtered.map(p => createPropertyCard(p)).join('');
}

function createPropertyCard(p) {
    const mainImage = p.images && p.images.length > 0
        ? p.images[0].base64
        : '';
    const statusClass = p.status;
    const statusText = p.status === 'published' ? 'منشور'
        : p.status === 'draft' ? 'مسودة'
        : p.status === 'pending' ? 'قيد المراجعة'
        : p.status === 'approved' ? 'موافق عليه'
        : p.status === 'rejected' ? 'مرفوض'
        : p.status;

    let priceDisplay = '';
    if (p.pricing) {
        if (p.pricing.mode === 'single') {
            priceDisplay = p.pricing.price ? p.pricing.price + ' ج.م/شهر' : 'غير محدد';
        } else if (p.pricing.mode === 'per_bed') {
            const prices = p.pricing.bedPrices || [];
            if (prices.length > 0) {
                priceDisplay = prices.map(bp => 'سرير ' + bp.bedNumber + ': ' + bp.price + ' ج.م').join(' | ');
            } else {
                priceDisplay = 'غير محدد';
            }
        }
    }

    return `
        <div class="property-card" data-id="${p.id}">
            <div class="property-image">
                ${mainImage ? `<img src="${mainImage}" alt="${p.name}">` : '🏠'}
            </div>
            <div class="property-content">
                <div class="property-header">
                    <div>
                        <div class="property-name" onclick="showDetail('${p.id}')">${p.name}</div>
                        <div class="property-type">${p.typeLabel || p.type} · ${p.city} · ${p.area}</div>
                    </div>
                    <span class="status-badge ${statusClass}">${statusText}</span>
                </div>
                <div class="property-details">
                    <span class="detail-item">🛏️ ${p.beds} سرير</span>
                    <span class="detail-item">🚪 ${p.rooms} غرفة</span>
                    <span class="detail-item">🚿 ${p.bathrooms} حمام</span>
                </div>
                <div class="property-price">${priceDisplay}</div>
                <div class="property-actions">
                    <button class="action-btn primary" onclick="showDetail('${p.id}')">عرض التفاصيل</button>
                    ${p.status === 'draft' ? `<button class="action-btn secondary" onclick="sendToReview('${p.id}')">رفع للمراجعة</button>` : ''}
                    <button class="action-btn danger" onclick="deleteProperty('${p.id}')">حذف</button>
                </div>
            </div>
        </div>
    `;
}

function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    renderProperties();
}

function showDetail(id) {
    const p = properties.find(prop => String(prop.id) === String(id));
    if (!p) return;
    selectedPropertyId = id;

    document.getElementById('propertiesGrid').style.display = 'none';
    document.querySelector('.tabs').style.display = 'none';
    document.getElementById('detailView').classList.add('active');

    document.getElementById('detailTitle').textContent = p.name;

    // Images
    const imagesContainer = document.getElementById('detailImages');
    if (p.images && p.images.length > 0) {
        imagesContainer.innerHTML = p.images.map(img => `<img src="${img.base64}" alt="صورة عقار">`).join('');
    } else {
        imagesContainer.innerHTML = '<div style="padding:40px;text-align:center;color:var(--text-muted);">لا توجد صور</div>';
    }

    // Info grid
    const infoContainer = document.getElementById('detailInfo');
    infoContainer.innerHTML = `
        <div class="detail-info-item"><label>النوع</label><span>${p.typeLabel || p.type}</span></div>
        <div class="detail-info-item"><label>المدينة</label><span>${p.city}</span></div>
        <div class="detail-info-item"><label>المنطقة</label><span>${p.area}</span></div>
        <div class="detail-info-item"><label>الغرف</label><span>${p.rooms}</span></div>
        <div class="detail-info-item"><label>السراير</label><span>${p.beds}</span></div>
        <div class="detail-info-item"><label>الحمامات</label><span>${p.bathrooms}</span></div>
        <div class="detail-info-item"><label>الدور</label><span>${p.floor || 'غير محدد'}</span></div>
        <div class="detail-info-item"><label>نوع السكن</label><span>${p.genderLabel || p.gender}</span></div>
        <div class="detail-info-item"><label>الحالة</label><span>${getStatusText(p.status)}</span></div>
        <div class="detail-info-item" style="grid-column: span 2;"><label>المرافق</label><span>${p.amenitiesLabels && p.amenitiesLabels.length > 0 ? p.amenitiesLabels.join(' · ') : 'لا توجد مرافق محددة'}</span></div>
    `;

    // Pricing
    const pricingContainer = document.getElementById('detailPricing');
    if (p.pricing) {
        if (p.pricing.mode === 'single') {
            pricingContainer.innerHTML = `
                <div class="price-card">
                    <h4>السعر الشهري</h4>
                    <div class="price">${p.pricing.price || '0'} <span class="price-unit">ج.م/شهر</span></div>
                </div>
            `;
        } else if (p.pricing.mode === 'per_bed') {
            const prices = p.pricing.bedPrices || [];
            pricingContainer.innerHTML = prices.map(bp => `
                <div class="price-card">
                    <h4>سعر السرير ${bp.bedNumber}</h4>
                    <div class="price">${bp.price || '0'} <span class="price-unit">ج.م/شهر</span></div>
                </div>
            `).join('');
        }
    } else {
        pricingContainer.innerHTML = '';
    }

    // Description
    const descHtml = p.description
        ? `<div class="detail-info-item" style="grid-column: span 2; margin-top: 10px;"><label>الوصف</label><span style="line-height: 1.7;">${p.description}</span></div>`
        : '';
    if (descHtml) infoContainer.insertAdjacentHTML('beforeend', descHtml);

    // Actions
    const actionsContainer = document.getElementById('detailActions');
    let actionsHtml = '';
    if (p.status === 'draft') {
        actionsHtml += `<button class="action-btn primary" onclick="sendToReview('${p.id}')">رفع للمراجعة</button>`;
    }
    actionsHtml += `<button class="action-btn danger" onclick="deleteProperty('${p.id}')">حذف العقار</button>`;
    actionsContainer.innerHTML = actionsHtml;
}

function getStatusText(status) {
    const map = {
        'published': 'منشور',
        'draft': 'مسودة',
        'pending': 'قيد المراجعة',
        'approved': 'موافق عليه',
        'rejected': 'مرفوض'
    };
    return map[status] || status;
}

function closeDetail() {
    document.getElementById('detailView').classList.remove('active');
    document.getElementById('propertiesGrid').style.display = 'grid';
    document.querySelector('.tabs').style.display = 'flex';
    selectedPropertyId = null;
}

function deleteProperty(id) {
    selectedPropertyId = id;
    openModal('deleteModal');
}

function confirmDelete() {
    if (!selectedPropertyId) return;
    properties = properties.filter(p => String(p.id) !== String(selectedPropertyId));
    localStorage.setItem('ownerProperties', JSON.stringify(properties));
    closeModal('deleteModal');
    closeDetail();
    renderProperties();
    showNotification('تم حذف العقار بنجاح');
}

function sendToReview(id) {
    selectedPropertyId = id;
    openModal('publishModal');
}

function confirmPublish() {
    if (!selectedPropertyId) return;
    const p = properties.find(prop => String(prop.id) === String(selectedPropertyId));
    if (p) {
        p.status = 'pending';
        localStorage.setItem('ownerProperties', JSON.stringify(properties));

        // Sync with management storage
        let mgmtProps = [];
        try {
            const stored = localStorage.getItem('managementProperties');
            if (stored) mgmtProps = JSON.parse(stored);
        } catch(e) {}
        const mgmtIndex = mgmtProps.findIndex(mp => String(mp.id) === String(selectedPropertyId));
        if (mgmtIndex >= 0) {
            mgmtProps[mgmtIndex].status = 'pending';
        } else {
            mgmtProps.push({...p});
        }
        localStorage.setItem('managementProperties', JSON.stringify(mgmtProps));
    }
    closeModal('publishModal');
    closeDetail();
    renderProperties();
    showNotification('تم إرسال العقار للمراجعة بنجاح');
}

function showNotification(msg) {
    const banner = document.getElementById('notificationBanner');
    document.getElementById('notificationText').textContent = msg;
    banner.classList.add('show');
    setTimeout(() => banner.classList.remove('show'), 4000);
}

function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
}

document.addEventListener('DOMContentLoaded', init);