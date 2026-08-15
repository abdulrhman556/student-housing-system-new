const STORAGE_KEYS = {
    STUDENTS: 'bayaty_students',
    OWNERS: 'bayaty_owners',
    BANNED_USERS: 'bayaty_banned_users'
};

let currentTab = 'students';
let selectedUser = null;

function initStorage() {
    if (!localStorage.getItem(STORAGE_KEYS.STUDENTS)) localStorage.setItem(STORAGE_KEYS.STUDENTS, JSON.stringify([]));
    if (!localStorage.getItem(STORAGE_KEYS.OWNERS)) localStorage.setItem(STORAGE_KEYS.OWNERS, JSON.stringify([]));
    if (!localStorage.getItem(STORAGE_KEYS.BANNED_USERS)) localStorage.setItem(STORAGE_KEYS.BANNED_USERS, JSON.stringify([]));
}

function getStudents() { return JSON.parse(localStorage.getItem(STORAGE_KEYS.STUDENTS)) || []; }
function getOwners() { return JSON.parse(localStorage.getItem(STORAGE_KEYS.OWNERS)) || []; }
function getBannedUsers() { return JSON.parse(localStorage.getItem(STORAGE_KEYS.BANNED_USERS)) || []; }
function saveStudents(s) { localStorage.setItem(STORAGE_KEYS.STUDENTS, JSON.stringify(s)); }
function saveOwners(o) { localStorage.setItem(STORAGE_KEYS.OWNERS, JSON.stringify(o)); }
function saveBannedUsers(b) { localStorage.setItem(STORAGE_KEYS.BANNED_USERS, JSON.stringify(b)); }
function isBanned(email) { return getBannedUsers().includes(email); }

function toggleSidebar() { document.getElementById('sidebar').classList.toggle('open'); document.querySelector('.sidebar-overlay').classList.toggle('active'); }

function switchTab(tab) {
    currentTab = tab;
    document.getElementById('tab-students').className = tab === 'students' ? 'tab-btn active' : 'tab-btn';
    document.getElementById('tab-owners').className = tab === 'owners' ? 'tab-btn active' : 'tab-btn';
    document.getElementById('tableTitle').textContent = tab === 'students' ? '👨‍🎓 قائمة الطلاب' : '🏠 قائمة المُلاك';
    renderTable();
}

function updateStats() {
    const students = getStudents(), owners = getOwners(), banned = getBannedUsers();
    document.getElementById('totalStudents').textContent = students.length;
    document.getElementById('totalOwners').textContent = owners.length;
    document.getElementById('totalBanned').textContent = banned.length;
    document.getElementById('totalUsers').textContent = students.length + owners.length;
}

// ============ طلبات التعديل ============

function getEditRequest(type) {
    const key = type === 'student' ? 'bayaty_edit_request_student' : 'bayaty_edit_request_owner';
    try { return JSON.parse(localStorage.getItem(key) || 'null'); } catch(e) { return null; }
}

function renderEditRequests() {
    const section = document.getElementById('editRequestsSection');
    const list = document.getElementById('editRequestsList');
    const studentReq = getEditRequest('student');
    const ownerReq = getEditRequest('owner');
    
    let html = '';
    
    if (studentReq && studentReq.status === 'pending') {
        html += createRequestCard(studentReq, 'student');
    }
    if (ownerReq && ownerReq.status === 'pending') {
        html += createRequestCard(ownerReq, 'owner');
    }
    
    if (html) {
        section.style.display = 'block';
        list.innerHTML = html;
    } else {
        section.style.display = 'none';
        list.innerHTML = '';
    }
}

function createRequestCard(req, type) {
    const typeLabel = type === 'student' ? '👨‍🎓 طالب' : '🏠 صاحب سكن';
    const changes = [];
    const old = req.oldData || {};
    const neu = req.newData || {};
    
    for (let key in neu) {
        if (old[key] !== neu[key] && neu[key]) {
            const labels = {
                firstName: 'الاسم الأول', lastName: 'اسم العائلة', email: 'الإيميل',
                phone: 'الهاتف', whatsapp: 'واتساب', gender: 'الجنس',
                university: 'الجامعة', nationalId: 'الرقم القومي', address: 'العنوان'
            };
            changes.push(`<span style="display:inline-block; background:#f1f5f9; padding:2px 8px; border-radius:4px; margin:2px; font-size:0.8rem;">${labels[key] || key}: <s style="color:#94a3b8;">${old[key] || '—'}</s> → <b style="color:#0f172a;">${neu[key]}</b></span>`);
        }
    }
    
    return `
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
                <div>
                    <div style="font-weight:700; color:#0f172a; margin-bottom:4px;">${req.userName || 'مستخدم'}</div>
                    <div style="font-size:0.8rem; color:#64748b;">${typeLabel} | ${req.userEmail || ''} | ${req.requestDate || ''}</div>
                </div>
                <span style="background:#fef3c7; color:#92400e; padding:2px 10px; border-radius:20px; font-size:0.75rem; font-weight:600;">⏳ قيد المراجعة</span>
            </div>
            <div style="margin-bottom:12px;">${changes.join('') || '<span style="color:#94a3b8; font-size:0.85rem;">لا يوجد تغييرات ظاهرة</span>'}</div>
            <div style="display:flex; gap:8px;">
                <button onclick="approveEditRequest('${type}')" style="background:#22c55e; color:#fff; border:none; padding:6px 16px; border-radius:8px; cursor:pointer; font-weight:600; font-size:0.85rem;">✅ موافقة</button>
                <button onclick="rejectEditRequest('${type}')" style="background:#ef4444; color:#fff; border:none; padding:6px 16px; border-radius:8px; cursor:pointer; font-weight:600; font-size:0.85rem;">❌ رفض</button>
            </div>
        </div>
    `;
}

function approveEditRequest(type) {
    const key = type === 'student' ? 'bayaty_edit_request_student' : 'bayaty_edit_request_owner';
    const request = getEditRequest(type);
    if (!request || request.status !== 'pending') return;
    
    // تطبيق التعديلات
    if (type === 'student') {
        let students = getStudents();
        const idx = students.findIndex(s => s.email === request.userEmail);
        if (idx !== -1) {
            students[idx] = { ...students[idx], ...request.newData, modified: true, lastModified: new Date().toISOString() };
            saveStudents(students);
        }
    } else {
        let owners = getOwners();
        const idx = owners.findIndex(o => o.email === request.userEmail);
        if (idx !== -1) {
            owners[idx] = { ...owners[idx], ...request.newData, modified: true, lastModified: new Date().toISOString() };
            saveOwners(owners);
        }
    }
    
    // تحديث bayaty_current_user لو هو المستخدم الحالي
    const current = JSON.parse(localStorage.getItem('bayaty_current_user') || 'null');
    if (current && current.email === request.userEmail) {
        localStorage.setItem('bayaty_current_user', JSON.stringify({
            ...current, ...request.newData, modified: true, lastModified: new Date().toISOString()
        }));
    }
    
    // تحديث الحقول الفردية في localStorage
    const prefix = type === 'student' ? 'stu_' : 'owner_';
    for (let key in request.newData) {
        if (request.newData[key]) {
            localStorage.setItem(prefix + key, request.newData[key]);
        }
    }
    
    // تنظيف الطلب
    localStorage.removeItem(key);
    localStorage.setItem('bayaty_profile_updated', Date.now().toString());
    
    showToast('✅ تمت الموافقة على طلب التعديل وتطبيقه', 'green');
    renderEditRequests();
    renderTable();
}

function rejectEditRequest(type) {
    const key = type === 'student' ? 'bayaty_edit_request_student' : 'bayaty_edit_request_owner';
    const request = getEditRequest(type);
    if (!request) return;
    
    request.status = 'rejected';
    localStorage.setItem(key, JSON.stringify(request));
    
    // إشعار للمستخدم إن طلبه اترفض
    localStorage.setItem('bayaty_edit_rejected_notify', JSON.stringify({
        type: type, email: request.userEmail, time: Date.now()
    }));
    
    showToast('❌ تم رفض طلب التعديل', 'red');
    renderEditRequests();
}

// ============ الجدول ============

function renderTable() {
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const statusFilter = document.getElementById('statusFilter').value;
    const wrapper = document.getElementById('tableWrapper');
    let list = currentTab === 'students' ? getStudents() : getOwners();

    if (search) {
        list = list.filter(u => {
            const fullName = ((u.firstName || '') + ' ' + (u.lastName || '')).toLowerCase();
            return fullName.includes(search) || (u.email && u.email.toLowerCase().includes(search)) || (u.phone && u.phone.includes(search)) || (u.nationalId && u.nationalId.includes(search));
        });
    }
    if (statusFilter === 'active') list = list.filter(u => !isBanned(u.email));
    else if (statusFilter === 'banned') list = list.filter(u => isBanned(u.email));

    if (list.length === 0) {
        wrapper.innerHTML = `<div class="empty-state"><div class="empty-state-icon">📭</div><h3>لا يوجد مستخدمين</h3><p>لم يتم تسجيل أي ${currentTab === 'students' ? 'طلاب' : 'مُلاك'} بعد</p></div>`;
        return;
    }

    const rows = list.map((user, index) => {
        const fullName = (user.firstName || '') + ' ' + (user.lastName || '');
        const avatar = user.gender === 'female' ? '👩' : '👨';
        const banned = isBanned(user.email);
        const statusClass = banned ? 'status-banned' : 'status-active';
        const statusText = banned ? '🚫 محظور' : '✅ نشط';
        const typeLabel = currentTab === 'students' ? 'طالب' : 'صاحب سكن';
        const typeIcon = currentTab === 'students' ? '👨‍🎓' : '🏠';
        const modifiedBadge = user.modified ? '<span style="background:#f59e0b; color:#fff; padding:1px 6px; border-radius:10px; font-size:0.65rem; margin-right:4px;">✏️</span>' : '';
        const banBtn = banned
            ? `<button class="action-btn unban" onclick="event.stopPropagation(); toggleBan('${user.email}', '${currentTab}')">🔓 فك الحظر</button>`
            : `<button class="action-btn ban" onclick="event.stopPropagation(); toggleBan('${user.email}', '${currentTab}')">🚫 حظر</button>`;
        return `<tr style="cursor:pointer;" onclick="showDetails(${index}, '${currentTab}')">
            <td><div class="user-cell"><div class="user-avatar">${avatar}</div><div><p class="user-name">${modifiedBadge}${fullName.trim() || '—'}</p><p class="user-type">${typeIcon} ${typeLabel}</p></div></div></td>
            <td class="email-cell">${user.email || '—'}</td>
            <td class="phone-cell">${user.phone || '—'}</td>
            <td><span class="status-badge-table ${statusClass}">${statusText}</span></td>
            <td><div class="actions-cell" onclick="event.stopPropagation()">
                <button class="action-btn view" onclick="showDetails(${index}, '${currentTab}')">👁 عرض</button>${banBtn}
            </div></td>
        </tr>`;
    }).join('');

    wrapper.innerHTML = `<table><thead><tr><th>المستخدم</th><th>البريد الإلكتروني</th><th>رقم الهاتف</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody>${rows}</tbody></table>`;
}

function showDetails(index, type) {
    const list = type === 'students' ? getStudents() : getOwners();
    const user = list[index];
    if (!user) return;
    selectedUser = { ...user, type, index };

    const fullName = (user.firstName || '') + ' ' + (user.lastName || '');
    const avatar = user.gender === 'female' ? '👩' : '👨';
    const genderText = user.gender === 'female' ? 'أنثى' : 'ذكر';
    const typeLabel = type === 'students' ? 'طالب' : 'صاحب سكن';
    const typeIcon = type === 'students' ? '👨‍🎓' : '🏠';
    const banned = isBanned(user.email);

    const natIdFile = user.natIdFile || '';
    const hasNatId = natIdFile.startsWith('data:image') || natIdFile.startsWith('http');

    let extraFields = '';
    if (type === 'students') {
        extraFields = `<div class="info-item"><label>🎓 الجامعة / المعهد</label><p>${user.university || '—'}</p></div>
            <div class="info-item"><label>🆔 الرقم القومي</label><p class="mono">${user.nationalId || '—'}</p></div>`;
    } else {
        extraFields = `<div class="info-item"><label>💬 واتساب</label><p class="mono">${user.whatsapp || '—'}</p></div>
            <div class="info-item"><label>🆔 الرقم القومي</label><p class="mono">${user.nationalId || '—'}</p></div>
            <div class="info-item full-width"><label>📍 العنوان الشخصي</label><p>${user.address || '—'}</p></div>`;
    }

    const banBtnClass = banned ? 'btn-modal-unban' : 'btn-modal-ban';
    const banBtnText = banned ? '🔓 فك الحظر' : '🚫 حظر المستخدم';
    
    const modifiedInfo = user.modified && user.lastModified 
        ? `<div class="info-item full-width"><label>✏️ آخر تعديل</label><p>${new Date(user.lastModified).toLocaleString('ar-EG')}</p></div>` 
        : '';

    let natIdCard = '';
    if (hasNatId) {
        natIdCard = `<div class="doc-card" onclick="openImage(\`${natIdFile}\`, 'بطاقة الرقم القومي - ${fullName.trim()}')">
            <div class="doc-icon">🪪</div><div class="doc-name">بطاقة الرقم القومي</div><div class="doc-status">✓ تم الرفع - اضغط للعرض</div></div>`;
    } else {
        natIdCard = `<div class="doc-card no-file"><div class="doc-icon">🪪</div><div class="doc-name">بطاقة الرقم القومي</div><div class="doc-status">✗ لم يتم الرفع</div></div>`;
    }

    document.getElementById('modalBody').innerHTML = `
        <div class="modal-user-header">
            <div class="modal-avatar">${avatar}</div>
            <div class="modal-user-info">
                <h4>${fullName.trim() || '—'}</h4>
                <div class="modal-tags">
                    <span class="tag tag-gold">${typeIcon} ${typeLabel}</span>
                    ${banned ? '<span class="tag tag-red">🚫 محظور</span>' : '<span class="tag tag-green">✅ نشط</span>'}
                    ${user.modified ? '<span class="tag" style="background:#f59e0b; color:#fff;">✏️ تم التعديل</span>' : ''}
                </div>
            </div>
        </div>
        <div class="info-grid">
            <div class="info-item"><label>📧 البريد الإلكتروني</label><p>${user.email || '—'}</p></div>
            <div class="info-item"><label>🔑 كلمة المرور</label><p style="color:var(--gold-bright); font-family:monospace;">${user.password || '—'}</p></div>
            <div class="info-item"><label>📱 رقم الهاتف</label><p class="mono">${user.phone || '—'}</p></div>
            <div class="info-item"><label>⚧ الجنس</label><p>${genderText}</p></div>
            <div class="info-item full-width"><label>📅 تاريخ التسجيل</label><p>${user.registeredAt || '—'}</p></div>
            ${modifiedInfo}
            ${extraFields}
        </div>
        <div class="documents-section">
            <h5>📎 المستندات المرفقة</h5>
            <div class="documents-grid">${natIdCard}</div>
        </div>
        <div class="modal-actions">
            <button class="${banBtnClass}" onclick="toggleBan('${user.email}', '${type}'); closeModal();">${banBtnText}</button>
            <button class="btn-modal-close" onclick="closeModal()">إغلاق</button>
        </div>
    `;

    document.getElementById('detailsModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('detailsModal').classList.remove('active');
    document.body.style.overflow = '';
}

function openImage(src, title) {
    document.getElementById('imgModalSrc').src = src;
    document.getElementById('imgModalTitle').textContent = title;
    document.getElementById('imgModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeImgModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('imgModal').classList.remove('active');
    document.body.style.overflow = '';
}

function toggleBan(email, type) {
    let banned = getBannedUsers();
    const isCurrentlyBanned = banned.includes(email);
    if (isCurrentlyBanned) {
        banned = banned.filter(e => e !== email);
        showToast('🔓 تم فك الحظر بنجاح', 'green');
    } else {
        banned.push(email);
        showToast('🚫 تم حظر المستخدم بنجاح', 'red');
    }
    saveBannedUsers(banned);
    updateStats();
    renderTable();
}

function showToast(message, color) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    document.getElementById('toastIcon').textContent = color === 'red' ? '🚫' : '✅';
    toast.style.borderColor = color === 'red' ? 'rgba(239, 68, 68, 0.4)' : 'rgba(34, 197, 94, 0.4)';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// ============ مزامنة التسجيل ============

function syncFromRegistration() {
    let students = getStudents();
    let owners = getOwners();
    let changed = false;

    // مزامنة الطالب
    const stuEmail = localStorage.getItem('stu_email');
    if (stuEmail && stuEmail.trim() !== '') {
        const existingIndex = students.findIndex(s => s.email === stuEmail);
        const studentData = {
            firstName: localStorage.getItem('stu_firstName') || '',
            lastName: localStorage.getItem('stu_lastName') || '',
            email: stuEmail,
            password: localStorage.getItem('stu_password') || '',
            phone: localStorage.getItem('stu_phone') || '',
            gender: localStorage.getItem('stu_gender') || 'male',
            university: localStorage.getItem('stu_university') || '',
            nationalId: localStorage.getItem('stu_nationalId') || '',
            natIdFile: localStorage.getItem('stu_natIdFile') || '',
            userType: 'student',
            registeredAt: new Date().toISOString().split('T')[0]
        };
        if (existingIndex !== -1) students[existingIndex] = { ...students[existingIndex], ...studentData };
        else students.push(studentData);
        
        // تنظيف
        ['stu_firstName', 'stu_lastName', 'stu_email', 'stu_password', 'stu_phone', 'stu_gender', 'stu_university', 'stu_nationalId', 'stu_natIdFile'].forEach(k => localStorage.removeItem(k));
        changed = true;
    }

    // مزامنة المالك
    const ownEmail = localStorage.getItem('owner_email');
    if (ownEmail && ownEmail.trim() !== '') {
        const existingIndex = owners.findIndex(o => o.email === ownEmail);
        const ownerData = {
            firstName: localStorage.getItem('owner_firstName') || '',
            lastName: localStorage.getItem('owner_lastName') || '',
            email: ownEmail,
            password: localStorage.getItem('owner_password') || '',
            phone: localStorage.getItem('owner_phone') || '',
            whatsapp: localStorage.getItem('owner_whatsapp') || '',
            gender: localStorage.getItem('owner_gender') || 'male',
            nationalId: localStorage.getItem('owner_nationalId') || '',
            address: localStorage.getItem('owner_address') || '',
            natIdFile: localStorage.getItem('owner_natIdFile') || '',
            userType: 'owner',
            registeredAt: new Date().toISOString().split('T')[0]
        };
        if (existingIndex !== -1) owners[existingIndex] = { ...owners[existingIndex], ...ownerData };
        else owners.push(ownerData);
        
        // تنظيف
        ['owner_firstName', 'owner_lastName', 'owner_email', 'owner_password', 'owner_phone', 'owner_whatsapp', 'owner_gender', 'owner_nationalId', 'owner_address', 'owner_natIdFile'].forEach(k => localStorage.removeItem(k));
        changed = true;
    }

    if (changed) { saveStudents(students); saveOwners(owners); }
}

// ============ تهيئة ============

function init() {
    initStorage();
    syncFromRegistration();
    updateStats();
    renderTable();
    renderEditRequests();
    
    // فحص دوري لطلبات التعديل الجديدة
    setInterval(renderEditRequests, 3000);
}

document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeModal(); closeImgModal(); } });
init();