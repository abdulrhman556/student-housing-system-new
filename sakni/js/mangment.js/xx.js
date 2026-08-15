const PERSONS_KEY = 'my_persons_list';
const CONFIG_KEY = 'booking_config';

function getPersons() {
    return JSON.parse(localStorage.getItem(PERSONS_KEY) || '[]');
}

function savePersons(persons) {
    localStorage.setItem(PERSONS_KEY, JSON.stringify(persons));
}

function getConfig() {
    return JSON.parse(localStorage.getItem(CONFIG_KEY) || '{}');
}

function saveConfig(config) {
    localStorage.setItem(CONFIG_KEY, JSON.stringify(config));
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
    const persons = getPersons();
    document.getElementById('totalPersons').textContent = persons.length;
    if (persons.length > 0) {
        const last = persons[persons.length - 1];
        document.getElementById('lastAdded').textContent = last.name || '—';
    } else {
        document.getElementById('lastAdded').textContent = '—';
    }
    const today = new Date().toDateString();
    const todayCount = persons.filter(p => {
        if (!p.dateAdded) return false;
        return new Date(p.dateAdded).toDateString() === today;
    }).length;
    document.getElementById('todayCount').textContent = todayCount;
}

function renderList() {
    const persons = getPersons();
    const list = document.getElementById('personsList');

    if (persons.length === 0) {
        list.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h4>لا يوجد أشخاص مسجلين</h4>
                <p>أضف أول شخص ليظهر هنا</p>
            </div>
        `;
        updateStats();
        return;
    }

    list.innerHTML = persons.map((p, index) => {
        const dateStr = p.dateAdded ? new Date(p.dateAdded).toLocaleDateString('ar-SA') : '';
        const timeStr = p.dateAdded ? new Date(p.dateAdded).toLocaleTimeString('ar-SA', {hour:'2-digit', minute:'2-digit'}) : '';
        return `<div class="person-item">
            <div class="person-info">
                <div class="person-icon">👤</div>
                <div class="person-details">
                    <h4>${p.name || '—'}</h4>
                    <p>📧 ${p.email || '—'} &nbsp;|&nbsp; 📱 ${p.phone || '—'} &nbsp;|&nbsp; 🔒 ${p.password ? '••••••' : '—'}
                    ${dateStr ? ' &nbsp;|&nbsp; 🕐 ' + dateStr + ' ' + timeStr : ''}</p>
                </div>
            </div>
            <div class="person-actions">
                <button class="btn-icon" onclick="deletePerson(${index})" title="حذف">🗑️</button>
            </div>
        </div>`;
    }).join('');

    updateStats();
}

function deletePerson(index) {
    if (!confirm('هل أنت متأكد من حذف هذا الشخص؟')) return;
    const persons = getPersons();
    persons.splice(index, 1);
    savePersons(persons);
    renderList();
    showToast('success', '✅ تم الحذف بنجاح!');
}

function clearForm() {
    document.getElementById('personForm').reset();
}

// ✅ تحميل الإعدادات المحفوظة تلقائياً في الفورم
function loadConfigIntoForm() {
    const config = getConfig();
    
    const phoneInput = document.getElementById('configPhone');
    const preview = document.getElementById('barcodePreview');
    const img = document.getElementById('barcodePreviewImg');
    
    if (phoneInput && config.contactPhone) {
        phoneInput.value = config.contactPhone;
    }
    
    if (preview && img && config.barcodeImage) {
        img.src = config.barcodeImage;
        preview.style.display = 'block';
    }
}

// Person form handler
document.getElementById('personForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('personName').value.trim();
    const phone = document.getElementById('personPhone').value.trim();
    const email = document.getElementById('personEmail').value.trim().toLowerCase();
    const password = document.getElementById('personPassword').value;

    if (!name || !phone || !email || !password) {
        showToast('error', '❌ يرجى ملء جميع الحقول!');
        return;
    }
    if (!email.includes('@') || !email.includes('.')) {
        showToast('error', '❌ يرجى إدخال بريد إلكتروني صحيح!');
        return;
    }

    const persons = getPersons();
    if (persons.find(p => p.email === email)) {
        showToast('error', '❌ هذا البريد الإلكتروني مسجل بالفعل!');
        return;
    }

    persons.push({
        name: name,
        phone: phone,
        email: email,
        password: password,
        dateAdded: new Date().toISOString()
    });

    savePersons(persons);
    renderList();
    document.getElementById('personForm').reset();
    showToast('success', '✅ تم حفظ الشخص بنجاح!');
});

// Config form handler (barcode + phone) — ✅ يحافظ على القديم لو مفيش جديد
document.getElementById('configForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const phone = document.getElementById('configPhone').value.trim();
    const fileInput = document.getElementById('configBarcode');
    
    if (!phone) {
        showToast('error', '❌ يرجى إدخال رقم الهاتف!');
        return;
    }

    const existing = getConfig();
    const config = {
        contactPhone: phone,
        barcodeImage: existing.barcodeImage || null  // ✅ يحافظ على القديم افتراضياً
    };

    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            config.barcodeImage = evt.target.result;  // ✅ يستبدل بالجديد لو اختار صورة
            saveConfig(config);
            loadConfigIntoForm();
            showToast('success', '✅ تم حفظ إعدادات الحجز بنجاح!');
        };
        reader.readAsDataURL(fileInput.files[0]);
    } else {
        saveConfig(config);
        loadConfigIntoForm();
        showToast('success', '✅ تم حفظ الإعدادات بنجاح!');
    }
});

// Preview barcode image before upload
document.getElementById('configBarcode').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            const preview = document.getElementById('barcodePreview');
            const img = document.getElementById('barcodePreviewImg');
            img.src = evt.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Sidebar toggle
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.querySelector('.sidebar-overlay').classList.toggle('active');
}

// Update booking badge count
function updateBookingBadge() {
    const bookings = JSON.parse(localStorage.getItem('support_bookings')) || [];
    const pending = bookings.filter(b => b.status === 'pending').length;
    const badge = document.getElementById('bookingBadge');
    if (badge) badge.textContent = pending;
}

// ✅ Initialize — يحمل كل حاجة محفوظة
document.addEventListener('DOMContentLoaded', function() {
    renderList();
    loadConfigIntoForm();  // ✅ يحمل رقم الهاتف والباركود المحفوظين
    updateBookingBadge();
});