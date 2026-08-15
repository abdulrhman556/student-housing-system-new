// ===== SIDEBAR TOGGLE =====
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.querySelector('.sidebar-overlay').classList.toggle('active');
}

let currentFilter = 'all';
let currentProperties = [];
let selectedActionId = null;
let selectedDeleteId = null;

// ===== LABELS =====
const amenityLabels = {
    'wifi': '📶 واي فاي',
    'ac': '❄️ تكييف',
    'heater': '🔥 سخان مياه',
    'fridge': '🧊 ثلاجة',
    'washing': '👕 غسالة',
    'tv': '📺 تلفزيون',
    'furniture': '🛋️ مفروشة',
    'parking': '🚗 موقف سيارات'
};

const typeLabels = {
    'apartment': 'شقة',
    'room': 'غرفة',
    'studio': 'استوديو',
    'villa': 'فيلا',
    'duplex': 'دوبلكس'
};

const genderLabels = {
    'male': '👨‍🎓 طلاب',
    'female': '👩‍🎓 طالبات',
    'mixed': '👫 مشترك'
};

const genderIcons = {
    'male': '👨‍🎓',
    'female': '👩‍🎓',
    'mixed': '👫'
};

const statusLabels = {
    'pending': { text: 'قيد المراجعة', class: 'pending' },
    'approved': { text: 'تمت الموافقة', class: 'approved' },
    'rejected': { text: 'مرفوض', class: 'rejected' },
    'draft': { text: 'مسودة', class: 'draft' },
    'published': { text: 'منشور', class: 'approved' }
};

// ===== UPDATE STATS =====
function updateStats() {
    const properties = JSON.parse(localStorage.getItem('managementProperties')) || [];
    const pending = properties.filter(p => p.status === 'pending').length;
    const approved = properties.filter(p => p.status === 'published' || p.status === 'approved').length;
    const rejected = properties.filter(p => p.status === 'rejected').length;
    const draft = properties.filter(p => p.status === 'draft').length;

    document.getElementById('pendingCount').textContent = pending;
    document.getElementById('approvedCount').textContent = approved;
    document.getElementById('rejectedCount').textContent = rejected;
    document.getElementById('totalCount').textContent = properties.length;

    const pendingBadge = document.getElementById('pendingBadge');
    if (pendingBadge) pendingBadge.textContent = pending;
}

// ===== FILTER =====
function filterByStatus(status, btn) {
    currentFilter = status;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    renderTable();
}

// ===== RENDER TABLE =====
function renderTable() {
    const properties = JSON.parse(localStorage.getItem('managementProperties')) || [];
    let filtered = properties;

    if (currentFilter !== 'all') {
        if (currentFilter === 'approved') {
            filtered = properties.filter(p => p.status === 'published' || p.status === 'approved');
        } else {
            filtered = properties.filter(p => p.status === currentFilter);
        }
    }

    currentProperties = filtered;
    document.getElementById('tableCount').textContent = filtered.length + ' عقار';
    const container = document.getElementById('propertiesTable');

    if (filtered.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">🏠</div>
                <h3>لا توجد عقارات</h3>
                <p>لم يتم إضافة أي عقارات بعد</p>
            </div>
        `;
        return;
    }

    container.innerHTML = `
        <table>
            <thead>
                <tr>
                    <th>اسم العقار</th>
                    <th>النوع</th>
                    <th>المدينة / المنطقة</th>
                    <th>السكن</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                ${filtered.map(prop => {
                    const status = statusLabels[prop.status] || statusLabels['draft'];
                    const typeLabel = prop.typeLabel || typeLabels[prop.type] || prop.type || 'غير محدد';
                    const genderLabel = genderLabels[prop.gender] || 'غير محدد';
                    const genderIcon = genderIcons[prop.gender] || '👥';
                    return `
                        <tr>
                            <td><strong>${prop.name || 'بدون اسم'}</strong></td>
                            <td>${typeLabel}</td>
                            <td>${prop.city || '-'} / ${prop.area || '-'}</td>
                            <td>${genderIcon} ${genderLabel}</td>
                            <td><span class="status-badge ${status.class}">${status.text}</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view" onclick="viewProperty('${prop.id}')">👁️ عرض</button>
                                    ${prop.status === 'pending' ? `
                                        <button class="action-btn approve" onclick="openApproveModal('${prop.id}')">✅ موافقة</button>
                                        <button class="action-btn reject" onclick="openRejectModal('${prop.id}')">❌ رفض</button>
                                    ` : ''}
                                    ${prop.status === 'published' || prop.status === 'approved' ? `
                                        <button class="action-btn reject" onclick="unpublishProperty('${prop.id}')">🚫 إلغاء النشر</button>
                                    ` : ''}
                                    <button class="action-btn reject" onclick="openDeleteModal('${prop.id}')">🗑️ حذف</button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('')}
            </tbody>
        </table>
    `;
}

// ===== MODALS =====
function openApproveModal(propId) {
    selectedActionId = propId;
    document.getElementById('approveModal').classList.add('active');
}

function openRejectModal(propId) {
    selectedActionId = propId;
    document.getElementById('rejectModal').classList.add('active');
}

function openDeleteModal(propId) {
    selectedDeleteId = propId;
    document.getElementById('deleteModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function confirmApprove() {
    if (!selectedActionId) return;
    approveProperty(selectedActionId);
    closeModal('approveModal');
}

function confirmReject() {
    if (!selectedActionId) return;
    rejectProperty(selectedActionId);
    closeModal('rejectModal');
}

function confirmDelete() {
    if (!selectedDeleteId) return;
    deleteProperty(selectedDeleteId);
    closeModal('deleteModal');
}

// ===== VIEW DETAIL =====
function viewProperty(propId) {
    const properties = JSON.parse(localStorage.getItem('managementProperties')) || [];
    const prop = properties.find(p => p.id == propId);
    if (!prop) return;

    document.getElementById('modalTitle').textContent = prop.name || 'تفاصيل العقار';

    // Images
    const imagesContainer = document.getElementById('modalImages');
    if (prop.images && prop.images.length > 0) {
        imagesContainer.innerHTML = prop.images.slice(0, 6).map(img => {
            const src = img.base64 || img;
            return `<img src="${src}" alt="صورة" onclick="window.open('${src}', '_blank')" style="cursor: pointer;">`;
        }).join('');
    } else {
        imagesContainer.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted);font-size:3rem;">🏠 لا توجد صور</div>';
    }

    // Details
    const detailsContainer = document.getElementById('modalDetails');
    detailsContainer.innerHTML = `
        <div class="detail-item"><label>نوع العقار</label><span>${prop.typeLabel || typeLabels[prop.type] || prop.type || '-'}</span></div>
        <div class="detail-item"><label>المدينة</label><span>${prop.city || '-'}</span></div>
        <div class="detail-item"><label>المنطقة</label><span>${prop.area || '-'}</span></div>
        <div class="detail-item"><label>عدد الغرف</label><span>${prop.rooms || '-'}</span></div>
        <div class="detail-item"><label>عدد الحمامات</label><span>${prop.bathrooms || '-'}</span></div>
        <div class="detail-item"><label>الدور</label><span>${prop.floor || '-'}</span></div>
        <div class="detail-item"><label>عدد السرائر</label><span>${prop.beds || '-'}</span></div>
        <div class="detail-item"><label>نوع السكن</label><span>${genderLabels[prop.gender] || 'غير محدد'}</span></div>
        <div class="detail-item"><label>تاريخ الإضافة</label><span>${new Date(prop.createdAt).toLocaleDateString('ar-EG')}</span></div>
    `;

    // Pricing
    const pricingContainer = document.getElementById('modalPricing');
    if (prop.pricing) {
        if (prop.pricing.mode === 'single') {
            pricingContainer.innerHTML = `
                <div class="price-box">
                    <h4>السعر الشهري</h4>
                    <div class="price">${prop.pricing.price ? prop.pricing.price + ' ج.م' : '-'}</div>
                </div>
            `;
        } else if (prop.pricing.mode === 'per_bed') {
            const bedPrices = prop.pricing.bedPrices || [];
            if (bedPrices.length > 0) {
                pricingContainer.innerHTML = bedPrices.map(bp => `
                    <div class="price-box">
                        <h4>سعر السرير ${bp.bedNumber}</h4>
                        <div class="price">${bp.price ? bp.price + ' ج.م' : '-'}</div>
                    </div>
                `).join('');
            } else {
                pricingContainer.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:var(--text-muted);">لا توجد أسعار مسجلة</div>';
            }
        }
    } else {
        pricingContainer.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:var(--text-muted);">لا توجد أسعار مسجلة</div>';
    }

    // Amenities
    const amenitiesContainer = document.getElementById('modalAmenities');
    if (prop.amenities && prop.amenities.length > 0) {
        const labels = prop.amenitiesLabels || prop.amenities.map(a => amenityLabels[a] || a);
        amenitiesContainer.innerHTML = labels.map(label => `<span class="amenity-tag">${label}</span>`).join('');
    } else {
        amenitiesContainer.innerHTML = '<span style="color: var(--text-muted);">لا توجد مرافق مسجلة</span>';
    }

    // Description
    document.getElementById('modalDescription').innerHTML = prop.description || 'لا يوجد وصف مفصل للعقار.';

    // Actions
    const actionsContainer = document.getElementById('modalActions');
    let actionsHtml = '';
    if (prop.status === 'pending') {
        actionsHtml += `<button class="action-btn approve" onclick="openApproveModal('${propId}'); closeDetailModal();">✅ موافقة ونشر</button>`;
        actionsHtml += `<button class="action-btn reject" onclick="openRejectModal('${propId}'); closeDetailModal();">❌ رفض</button>`;
    } else if (prop.status === 'published' || prop.status === 'approved') {
        actionsHtml += `<span style="color: var(--success); font-weight: 700;">✅ تم نشر هذا العقار</span>`;
        actionsHtml += `<button class="action-btn reject" onclick="unpublishProperty('${propId}'); closeDetailModal();">🚫 إلغاء النشر</button>`;
    } else if (prop.status === 'rejected') {
        actionsHtml += `<span style="color: var(--danger); font-weight: 700;">❌ تم رفض هذا العقار</span>`;
        actionsHtml += `<button class="action-btn approve" onclick="openApproveModal('${propId}'); closeDetailModal();">✅ إعادة الموافقة</button>`;
    } else {
        actionsHtml += `<span style="color: var(--text-muted);">مسودة - لم يُرسل للمراجعة</span>`;
    }
    actionsHtml += `<button class="action-btn reject" onclick="openDeleteModal('${propId}'); closeDetailModal();">🗑️ حذف</button>`;
    actionsContainer.innerHTML = actionsHtml;

    document.getElementById('detailModal').classList.add('active');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.remove('active');
}

// ===== APPROVE =====
function approveProperty(propId) {
    let properties = JSON.parse(localStorage.getItem('managementProperties')) || [];
    const propIndex = properties.findIndex(p => p.id == propId);

    if (propIndex !== -1) {
        const prop = properties[propIndex];
        prop.status = 'published';
        prop.updatedAt = new Date().toISOString();
        prop.approvedAt = new Date().toISOString();
        localStorage.setItem('managementProperties', JSON.stringify(properties));

        // Sync to owner
        let ownerProps = JSON.parse(localStorage.getItem('ownerProperties')) || [];
        const ownerIndex = ownerProps.findIndex(p => p.id == propId);
        if (ownerIndex !== -1) {
            ownerProps[ownerIndex].status = 'published';
            ownerProps[ownerIndex].updatedAt = prop.updatedAt;
            localStorage.setItem('ownerProperties', JSON.stringify(ownerProps));
        }

        // Publish to home page
        let publishedProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
        publishedProps = publishedProps.filter(p => p.id != propId);

        let homePrice = 0;
        if (prop.pricing) {
            if (prop.pricing.mode === 'single') {
                homePrice = parseInt(prop.pricing.price) || 0;
            } else if (prop.pricing.mode === 'per_bed' && prop.pricing.bedPrices && prop.pricing.bedPrices.length > 0) {
                homePrice = parseInt(prop.pricing.bedPrices[0].price) || 0;
            }
        }

        const homeProp = {
            id: prop.id,
            title: prop.name,
            price: homePrice,
            bedsLeft: parseInt(prop.beds) || parseInt(prop.rooms) || 1,
            distance: 2.5,
            region: prop.area,
            gender: prop.gender || 'male',
            services: prop.amenities || [],
            img: prop.images && prop.images.length > 0 ? (prop.images[0].base64 || prop.images[0]) : 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=500',
            city: prop.city,
            status: 'published'
        };
        publishedProps.push(homeProp);
        localStorage.setItem('mangment_flutter_properties', JSON.stringify(publishedProps));

        updateStats();
        renderTable();
        showToast('success', '✅ تمت الموافقة', 'تم نشر العقار "' + (prop.name || '') + '" بنجاح!');
    }
}

// ===== REJECT =====
function rejectProperty(propId) {
    let properties = JSON.parse(localStorage.getItem('managementProperties')) || [];
    const propIndex = properties.findIndex(p => p.id == propId);

    if (propIndex !== -1) {
        const prop = properties[propIndex];
        prop.status = 'rejected';
        prop.updatedAt = new Date().toISOString();
        localStorage.setItem('managementProperties', JSON.stringify(properties));

        // Sync to owner
        let ownerProps = JSON.parse(localStorage.getItem('ownerProperties')) || [];
        const ownerIndex = ownerProps.findIndex(p => p.id == propId);
        if (ownerIndex !== -1) {
            ownerProps[ownerIndex].status = 'rejected';
            ownerProps[ownerIndex].updatedAt = prop.updatedAt;
            localStorage.setItem('ownerProperties', JSON.stringify(ownerProps));
        }

        // Remove from home
        let publishedProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
        publishedProps = publishedProps.filter(p => p.id != propId);
        localStorage.setItem('mangment_flutter_properties', JSON.stringify(publishedProps));

        updateStats();
        renderTable();
        showToast('error', '❌ تم الرفض', 'تم رفض العقار "' + (prop.name || '') + '" ونقله للمرفوض');
    }
}

// ===== UNPUBLISH =====
function unpublishProperty(propId) {
    let properties = JSON.parse(localStorage.getItem('managementProperties')) || [];
    const propIndex = properties.findIndex(p => p.id == propId);

    if (propIndex !== -1) {
        const prop = properties[propIndex];
        prop.status = 'draft';
        prop.updatedAt = new Date().toISOString();
        localStorage.setItem('managementProperties', JSON.stringify(properties));

        // Sync to owner
        let ownerProps = JSON.parse(localStorage.getItem('ownerProperties')) || [];
        const ownerIndex = ownerProps.findIndex(p => p.id == propId);
        if (ownerIndex !== -1) {
            ownerProps[ownerIndex].status = 'draft';
            ownerProps[ownerIndex].updatedAt = prop.updatedAt;
            localStorage.setItem('ownerProperties', JSON.stringify(ownerProps));
        }

        // Remove from home
        let publishedProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
        publishedProps = publishedProps.filter(p => p.id != propId);
        localStorage.setItem('mangment_flutter_properties', JSON.stringify(publishedProps));

        updateStats();
        renderTable();
        showToast('info', '🚫 تم إلغاء النشر', 'تم إلغاء نشر العقار "' + (prop.name || '') + '"');
    }
}

// ===== DELETE =====
function deleteProperty(propId) {
    let properties = JSON.parse(localStorage.getItem('managementProperties')) || [];
    const prop = properties.find(p => p.id == propId);
    const propName = prop ? prop.name : 'العقار';

    properties = properties.filter(p => p.id != propId);
    localStorage.setItem('managementProperties', JSON.stringify(properties));

    // Delete from owner
    let ownerProps = JSON.parse(localStorage.getItem('ownerProperties')) || [];
    ownerProps = ownerProps.filter(p => p.id != propId);
    localStorage.setItem('ownerProperties', JSON.stringify(ownerProps));

    // Delete from home
    let publishedProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
    publishedProps = publishedProps.filter(p => p.id != propId);
    localStorage.setItem('mangment_flutter_properties', JSON.stringify(publishedProps));

    updateStats();
    renderTable();
    showToast('error', '🗑️ تم الحذف', 'تم حذف العقار "' + propName + '" نهائياً');
}

// ===== TOAST =====
function showToast(type, title, text) {
    const toast = document.getElementById('toast');
    const titleEl = document.getElementById('toastTitle');
    const textEl = document.getElementById('toastText');
    const iconEl = toast.querySelector('.toast-icon');

    toast.className = 'toast ' + type;
    titleEl.textContent = title;
    textEl.textContent = text;

    if (type === 'success') iconEl.textContent = '✅';
    else if (type === 'error') iconEl.textContent = '❌';
    else iconEl.textContent = 'ℹ️';

    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 4000);
}

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
    updateStats();
    renderTable();
});

// Listen for storage changes
window.addEventListener('storage', function(e) {
    if (e.key === 'managementProperties' || e.key === 'ownerProperties') {
        updateStats();
        renderTable();
    }
});