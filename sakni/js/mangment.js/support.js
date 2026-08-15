const BOOKINGS_KEY = 'support_bookings';
const STATUSES_KEY = 'booking_statuses';

const amenityLabels = {
    'wifi': '📶 واي فاي',
    'ac': '❄️ تكييف',
    'heater': '🔥 سخان مياه',
    'fridge': '🧊 ثلاجة',
    'washing': '👕 غسالة',
    'tv': '📺 تلفزيون',
    'furniture': '🛋️ مفروشة',
    'parking': '🚗 موقف سيارات',
    'kitchen': '🍳 مطبخ متكامل'
};

function getBookings() {
    return JSON.parse(localStorage.getItem(BOOKINGS_KEY) || '[]');
}

function saveBookings(bookings) {
    localStorage.setItem(BOOKINGS_KEY, JSON.stringify(bookings));
}

function showToast(type, message) {
    try {
        var container = document.getElementById('toastContainer');
        if (!container) return;
        var toast = document.createElement('div');
        toast.className = 'toast ' + type;
        toast.innerHTML = message;
        container.appendChild(toast);
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-30px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(function() {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 300);
        }, 4000);
    } catch (e) {
        console.error('Toast error:', e);
    }
}

function updateStats() {
    const bookings = getBookings();
    const pending = bookings.filter(b => b.status === 'pending').length;
    const approved = bookings.filter(b => b.status === 'approved').length;

    document.getElementById('totalBookings').textContent = bookings.length;
    document.getElementById('pendingBookings').textContent = pending;
    document.getElementById('approvedBookings').textContent = approved;

    const badge = document.getElementById('bookingBadge');
    if (badge) badge.textContent = pending;
}

function renderBookings() {
    const bookings = getBookings();
    const list = document.getElementById('bookingsList');

    if (bookings.length === 0) {
        list.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h4>لا توجد طلبات حجز</h4>
                <p>الطلبات ستظهر هنا عندما يضغط الطلاب على "احجز الآن"</p>
            </div>
        `;
        updateStats();
        return;
    }

    // Sort: pending first, then by date
    bookings.sort((a, b) => {
        if (a.status === 'pending' && b.status !== 'pending') return -1;
        if (a.status !== 'pending' && b.status === 'pending') return 1;
        return new Date(b.createdAt) - new Date(a.createdAt);
    });

    list.innerHTML = bookings.map((b, index) => {
        const dateStr = b.createdAt ? new Date(b.createdAt).toLocaleDateString('ar-EG') : '';
        const timeStr = b.createdAt ? new Date(b.createdAt).toLocaleTimeString('ar-EG', {hour:'2-digit', minute:'2-digit'}) : '';
        
        let statusHtml = '';
        let actionsHtml = '';
        
        if (b.status === 'pending') {
            statusHtml = '<span class="status-badge status-pending">⏳ قيد الانتظار</span>';
            actionsHtml = `
                <button class="btn-approve" onclick="approveBooking('${b.id}')">✅ موافقة</button>
                <button class="btn-reject" onclick="rejectBooking('${b.id}')">❌ رفض</button>
            `;
        } else if (b.status === 'approved') {
            statusHtml = '<span class="status-badge status-approved">✅ تمت الموافقة</span>';
            actionsHtml = '<span style="color: #4ADE80; font-size: 0.9rem; font-weight: 700;">✓ تم تأكيد الحجز</span>';
        } else {
            statusHtml = '<span class="status-badge status-rejected">❌ مرفوض</span>';
            actionsHtml = '<span style="color: #F87171; font-size: 0.9rem; font-weight: 700;">✗ تم رفض الطلب</span>';
        }

        // ✅ تفاصيل المرافق
        let amenitiesHtml = '';
        if (b.amenities && b.amenities.length > 0) {
            amenitiesHtml = b.amenities.map(a => `<span class="amenity-tag">${amenityLabels[a] || a}</span>`).join('');
        } else {
            amenitiesHtml = '<span style="color: #64748B; font-size: 0.8rem;">لا توجد مرافق مسجلة</span>';
        }

        // ✅ تفاصيل التواصل
        let contactHtml = '';
        if (b.ownerName || b.contactPhone || b.contactWhatsapp || b.contactEmail) {
            contactHtml = `
                <div class="contact-box">
                    <h4>📞 بيانات التواصل مع صاحب السكن</h4>
                    ${b.ownerName ? `<p>👤 اسم المالك: <strong style="color:#F4D068;">${b.ownerName}</strong></p>` : ''}
                    ${b.contactPhone ? `<p>📱 الهاتف: <strong style="color:#F4D068;">${b.contactPhone}</strong></p>` : ''}
                    ${b.contactWhatsapp ? `<p>💬 واتساب: <strong style="color:#F4D068;">${b.contactWhatsapp}</strong></p>` : ''}
                    ${b.contactEmail ? `<p>📧 البريد: <strong style="color:#F4D068;">${b.contactEmail}</strong></p>` : ''}
                </div>
            `;
        }

        return `
            <div class="booking-card">
                <div class="booking-header">
                    <img src="${b.propertyImage || 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=200'}" class="booking-img" alt="العقار">
                    <div class="booking-title">
                        <h3>${b.propertyName || 'عقار بدون اسم'}</h3>
                        <p>📍 ${b.city || 'بني سويف'} - ${b.region || 'غير محدد'}</p>
                        <p>🏠 نوع العقار: ${b.propertyType || 'شقة'} | 🚻 السكن: ${b.gender === 'male' ? 'شباب' : b.gender === 'female' ? 'بنات' : 'غير محدد'}</p>
                        <p>🕐 ${dateStr} ${timeStr}</p>
                        ${statusHtml}
                    </div>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <label>🛏️ نوع السرير</label>
                        <span>${b.bedType}</span>
                    </div>
                    <div class="detail-item">
                        <label>💰 السعر الشهري</label>
                        <span>${b.price}</span>
                    </div>
                    <div class="detail-item">
                        <label>🚪 عدد الغرف</label>
                        <span>${b.rooms || '-'}</span>
                    </div>
                    <div class="detail-item">
                        <label>🛁 عدد الحمامات</label>
                        <span>${b.bathrooms || '-'}</span>
                    </div>
                    <div class="detail-item">
                        <label>🏢 الدور</label>
                        <span>${b.floor || '-'}</span>
                    </div>
                    <div class="detail-item">
                        <label>📏 المسافة من الكلية</label>
                        <span>${b.distance || '-'} كم</span>
                    </div>
                </div>

                <div class="amenities-box">
                    <h4>✨ المرافق والخدمات</h4>
                    ${amenitiesHtml}
                </div>

                <div class="description-box">
                    <h4>📝 وصف العقار</h4>
                    <p>${b.description || 'لا يوجد وصف مفصل.'}</p>
                </div>

                ${contactHtml}

                <div class="booking-actions">
                    ${actionsHtml}
                </div>
            </div>
        `;
    }).join('');

    updateStats();
}

function approveBooking(bookingId) {
    let bookings = getBookings();
    const booking = bookings.find(b => b.id === bookingId);
    if (!booking) return;

    booking.status = 'approved';
    saveBookings(bookings);

    let statuses = JSON.parse(localStorage.getItem(STATUSES_KEY) || '{}');
    statuses[booking.propertyId + '_' + booking.bedType] = 'approved';
    localStorage.setItem(STATUSES_KEY, JSON.stringify(statuses));

    renderBookings();
    showToast('success', '✅ تمت الموافقة على الحجز!');
}

function rejectBooking(bookingId) {
    let bookings = getBookings();
    const booking = bookings.find(b => b.id === bookingId);
    if (!booking) return;

    booking.status = 'rejected';
    saveBookings(bookings);

    let statuses = JSON.parse(localStorage.getItem(STATUSES_KEY) || '{}');
    statuses[booking.propertyId + '_' + booking.bedType] = 'rejected';
    localStorage.setItem(STATUSES_KEY, JSON.stringify(statuses));

    renderBookings();
    showToast('error', '❌ تم رفض الحجز');
}

// Sidebar toggle
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.querySelector('.sidebar-overlay').classList.toggle('active');
}

// Listen for new bookings from other tabs
window.addEventListener('storage', function(e) {
    if (e.key === BOOKINGS_KEY) {
        renderBookings();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', renderBookings);