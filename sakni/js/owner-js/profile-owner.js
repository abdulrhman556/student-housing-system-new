function getCurrentUser() {
    return JSON.parse(localStorage.getItem('bayaty_current_user') || 'null');
}

function getBannedUsers() {
    return JSON.parse(localStorage.getItem('bayaty_banned_users') || '[]');
}

function isBanned(email) {
    return getBannedUsers().includes(email);
}

function logout() {
    localStorage.removeItem('bayaty_current_user');
    localStorage.removeItem('stu_email');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.querySelector('.sidebar-overlay').classList.toggle('active');
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

// ============ نظام طلبات التعديل ============

function getEditRequest() {
    try {
        return JSON.parse(localStorage.getItem('bayaty_edit_request_owner') || 'null');
    } catch(e) {
        return null;
    }
}

function saveEditRequest(request) {
    localStorage.setItem('bayaty_edit_request_owner', JSON.stringify(request));
    // إشعار للإدارة إن فيه طلب جديد
    localStorage.setItem('bayaty_edit_request_owner_updated', Date.now().toString());
}

function clearEditRequest() {
    localStorage.removeItem('bayaty_edit_request_owner');
}

function checkEditStatus() {
    const request = getEditRequest();
    const badge = document.getElementById('editStatusBadge');
    const btn = document.getElementById('editBtn');
    
    if (!request) {
        if (badge) badge.style.display = 'none';
        if (btn) {
            btn.innerHTML = '✏️ تعديل البيانات';
            btn.disabled = false;
            btn.style.opacity = '1';
        }
        return;
    }

    if (request.status === 'pending') {
        if (badge) {
            badge.style.display = 'inline-block';
            badge.innerHTML = '⏳ طلب تعديل قيد المراجعة';
        }
        if (btn) {
            btn.innerHTML = '⏳ طلبك قيد المراجعة';
            btn.disabled = true;
            btn.style.opacity = '0.6';
        }
    } 
    else if (request.status === 'approved') {
        // الإدارة وافقت → نطبق التعديلات
        applyApprovedEdit(request);
        if (badge) {
            badge.style.display = 'inline-block';
            badge.innerHTML = '✅ تمت الموافقة على التعديل';
            setTimeout(() => { badge.style.display = 'none'; }, 4000);
        }
        if (btn) {
            btn.innerHTML = '✏️ تعديل البيانات';
            btn.disabled = false;
            btn.style.opacity = '1';
        }
        clearEditRequest();
        showNotification('✓ تم تطبيق تعديلاتك بنجاح');
    } 
    else if (request.status === 'rejected') {
        if (badge) {
            badge.style.display = 'inline-block';
            badge.innerHTML = '❌ تم رفض طلب التعديل';
            setTimeout(() => { badge.style.display = 'none'; }, 4000);
        }
        if (btn) {
            btn.innerHTML = '✏️ تعديل البيانات';
            btn.disabled = false;
            btn.style.opacity = '1';
        }
        clearEditRequest();
        showNotification('✗ تم رفض طلب تعديل بياناتك');
    }
}

function applyApprovedEdit(request) {
    if (!request || !request.newData) return;
    
    const newData = request.newData;
    
    // تحديث الحقول الفردية
    localStorage.setItem('owner_firstName', newData.firstName || '');
    localStorage.setItem('owner_lastName', newData.lastName || '');
    localStorage.setItem('owner_email', newData.email || '');
    localStorage.setItem('owner_phone', newData.phone || '');
    localStorage.setItem('owner_whatsapp', newData.whatsapp || '');
    localStorage.setItem('owner_gender', newData.gender || 'male');
    localStorage.setItem('owner_nationalId', newData.nationalId || '');
    localStorage.setItem('owner_address', newData.address || '');
    
    // تحديث الكائن الموحد
    let user = getCurrentUser() || {};
    user.firstName = newData.firstName || user.firstName;
    user.lastName = newData.lastName || user.lastName;
    user.email = newData.email || user.email;
    user.phone = newData.phone || user.phone;
    user.whatsapp = newData.whatsapp || user.whatsapp;
    user.gender = newData.gender || user.gender;
    user.nationalId = newData.nationalId || user.nationalId;
    user.address = newData.address || user.address;
    user.userType = 'owner';
    user.modified = true;
    user.lastModified = new Date().toISOString();
    
    localStorage.setItem('bayaty_current_user', JSON.stringify(user));
    
    // إشعار للـ management
    localStorage.setItem('bayaty_profile_updated', Date.now().toString());
    
    // إعادة العرض
    renderProfile(user);
}

// ============ عرض البيانات ============

function loadProfile() {
    let user = getCurrentUser();

    // بناء من الحقول الفردية لو مفيش كائن موحد
    if (!user) {
        const fName = localStorage.getItem('owner_firstName') || '';
        const lName = localStorage.getItem('owner_lastName') || '';
        const email = localStorage.getItem('owner_email') || '';
        
        if (!email) {
            window.location.href = 'regest.html';
            return;
        }
        
        user = {
            firstName: fName,
            lastName: lName,
            email: email,
            phone: localStorage.getItem('owner_phone') || '',
            whatsapp: localStorage.getItem('owner_whatsapp') || '',
            gender: localStorage.getItem('owner_gender') || 'male',
            nationalId: localStorage.getItem('owner_nationalId') || '',
            address: localStorage.getItem('owner_address') || '',
            natIdFile: localStorage.getItem('owner_natIdFile') || '',
            userType: 'owner'
        };
        
        localStorage.setItem('bayaty_current_user', JSON.stringify(user));
    }

    // تأكد إنه مالك
    if (user.userType !== 'owner') {
        user.userType = 'owner';
        localStorage.setItem('bayaty_current_user', JSON.stringify(user));
    }

    renderProfile(user);
    checkEditStatus();
}

function renderProfile(user) {
    const firstName = user.firstName || '';
    const lastName = user.lastName || '';
    const fullName = (firstName + ' ' + lastName).trim();
    const displayName = fullName || 'مالك العقار';
    const firstChar = displayName.charAt(0) || 'م';
    const banned = isBanned(user.email);

    document.getElementById('userName').textContent = displayName;
    document.getElementById('userAvatar').textContent = firstChar;
    document.getElementById('profileAvatar').textContent = firstChar;
    document.getElementById('profileName').textContent = displayName;
    document.getElementById('typeBadge').textContent = '🏠 صاحب سكن';
    
    if (banned) {
        document.getElementById('statusBadge').style.display = 'inline-block';
    } else {
        document.getElementById('statusBadge').style.display = 'none';
    }

    const genderText = (user.gender === 'female' || user.gender === 'أنثى') ? 'أنثى' : 'ذكر';

    // البيانات الشخصية فقط (بدون نوع العقار)
    document.getElementById('infoGrid').innerHTML = `
        <div class="info-item"><label>👤 الاسم الأول</label><p data-field="firstName">${firstName || '—'}</p></div>
        <div class="info-item"><label>👤 اسم العائلة</label><p data-field="lastName">${lastName || '—'}</p></div>
        <div class="info-item"><label>📧 البريد الإلكتروني</label><p data-field="email">${user.email || '—'}</p></div>
        <div class="info-item"><label>📱 رقم الهاتف</label><p class="mono" data-field="phone">${user.phone || '—'}</p></div>
        <div class="info-item"><label>💬 رقم الواتساب</label><p class="mono" data-field="whatsapp">${user.whatsapp || '—'}</p></div>
        <div class="info-item"><label>⚧ الجنس</label><p data-field="gender">${genderText}</p></div>
        <div class="info-item"><label>🆔 الرقم القومي</label><p class="mono" data-field="nationalId">${user.nationalId || '—'}</p></div>
        <div class="info-item full-width"><label>📍 العنوان الشخصي</label><p data-field="address">${user.address || '—'}</p></div>
    `;

    // المستندات: بطاقة الرقم القومي بس (اختياري)
    const natIdFile = user.natIdFile || '';
    const hasNatId = natIdFile.startsWith('data:image') || natIdFile.startsWith('http');

    if (hasNatId) {
        document.getElementById('docsGrid').innerHTML = `
            <div class="doc-card" onclick="openImage('${natIdFile.replace(/'/g, "\\'")}', 'بطاقة الرقم القومي - ${displayName.replace(/'/g, "\\'")}')">
                <div class="doc-icon">🪪</div>
                <div class="doc-name">بطاقة الرقم القومي</div>
                <div class="doc-status" style="color:var(--success)">✓ تم الرفع — اضغط للعرض</div>
            </div>`;
    } else {
        document.getElementById('docsGrid').innerHTML = `
            <div class="doc-card no-file">
                <div class="doc-icon">🪪</div>
                <div class="doc-name">بطاقة الرقم القومي</div>
                <div class="doc-status" style="color:var(--danger)">✗ لم يتم الرفع (اختياري)</div>
            </div>`;
    }

    // إحصائيات العقارات
    const properties = JSON.parse(localStorage.getItem('properties') || '[]');
    const userProps = properties.filter(p => p.ownerId === user.id || p.ownerEmail === user.email);
    document.getElementById('statProperties').textContent = userProps.length;
    document.getElementById('statPublished').textContent = userProps.filter(p => p.status === 'published').length;
    document.getElementById('statPending').textContent = userProps.filter(p => p.status === 'pending').length;
}

// ============ نظام التعديل ============

let isEditing = false;

function toggleEdit() {
    const request = getEditRequest();
    if (request && request.status === 'pending') {
        showNotification('⏳ لديك طلب تعديل قيد المراجعة بالفعل');
        return;
    }

    const btn = document.getElementById('editBtn');
    
    if (!isEditing) {
        enableEditMode();
        btn.innerHTML = '📤 إرسال طلب تعديل';
        btn.style.background = '#f59e0b';
        isEditing = true;
    } else {
        submitEditRequest();
        btn.innerHTML = '✏️ تعديل البيانات';
        btn.style.background = '';
        isEditing = false;
    }
}

function enableEditMode() {
    const fields = document.querySelectorAll('#infoGrid p[data-field]');
    
    fields.forEach(field => {
        const fieldName = field.getAttribute('data-field');
        const currentValue = field.innerText === '—' ? '' : field.innerText;
        
        if (fieldName === 'gender') {
            field.innerHTML = `
                <select class="edit-input" data-field="gender" style="width:100%; padding:6px; border:1px solid #ccc; border-radius:6px; font-family:inherit;">
                    <option value="male" ${currentValue === 'ذكر' ? 'selected' : ''}>ذكر</option>
                    <option value="female" ${currentValue === 'أنثى' ? 'selected' : ''}>أنثى</option>
                </select>`;
        } 
        else if (fieldName === 'nationalId') {
            field.innerHTML = `<input type="text" class="edit-input" data-field="nationalId" value="${currentValue}" maxlength="14" pattern="\\d{14}" placeholder="14 رقم" style="width:100%; padding:6px; border:1px solid #ccc; border-radius:6px; font-family:inherit;">`;
        }
        else if (fieldName === 'email') {
            field.innerHTML = `<input type="email" class="edit-input" data-field="email" value="${currentValue}" style="width:100%; padding:6px; border:1px solid #ccc; border-radius:6px; font-family:inherit;">`;
        }
        else {
            field.innerHTML = `<input type="text" class="edit-input" data-field="${fieldName}" value="${currentValue}" style="width:100%; padding:6px; border:1px solid #ccc; border-radius:6px; font-family:inherit;">`;
        }
    });
}

function submitEditRequest() {
    const inputs = document.querySelectorAll('#infoGrid .edit-input');
    let user = getCurrentUser() || {};
    
    // البيانات القديمة
    const oldData = {
        firstName: user.firstName || localStorage.getItem('owner_firstName') || '',
        lastName: user.lastName || localStorage.getItem('owner_lastName') || '',
        email: user.email || localStorage.getItem('owner_email') || '',
        phone: user.phone || localStorage.getItem('owner_phone') || '',
        whatsapp: user.whatsapp || localStorage.getItem('owner_whatsapp') || '',
        gender: user.gender || localStorage.getItem('owner_gender') || 'male',
        nationalId: user.nationalId || localStorage.getItem('owner_nationalId') || '',
        address: user.address || localStorage.getItem('owner_address') || ''
    };

    const newData = { ...oldData };

    inputs.forEach(input => {
        const fieldName = input.getAttribute('data-field');
        const value = input.value.trim();
        if (fieldName) newData[fieldName] = value;
    });

    // التحقق من الرقم القومي
    if (newData.nationalId && !/^\d{14}$/.test(newData.nationalId)) {
        showNotification('⚠️ الرقم القومي يجب أن يكون 14 رقم بالضبط');
        renderProfile(user); // إرجاع العرض
        return;
    }

    // حفظ طلب التعديل
    const request = {
        userEmail: oldData.email,
        userName: (oldData.firstName + ' ' + oldData.lastName).trim(),
        userType: 'owner',
        oldData: oldData,
        newData: newData,
        status: 'pending',
        timestamp: Date.now(),
        requestDate: new Date().toLocaleString('ar-EG')
    };

    saveEditRequest(request);
    
    // إرجاع العرض للوضع الطبيعي
    renderProfile(user);
    checkEditStatus();
    
    showNotification('📤 تم إرسال طلب التعديل للإدارة');
}

function showNotification(msg) {
    const notif = document.createElement('div');
    notif.style.cssText = 'position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#0D162F; color:#F4D068; padding:12px 24px; border-radius:12px; border:1px solid #AA7C11; z-index:9999; font-weight:bold; font-size:0.9rem; box-shadow:0 4px 20px rgba(0,0,0,0.3);';
    notif.innerText = msg;
    document.body.appendChild(notif);
    setTimeout(() => {
        notif.style.opacity = '0';
        notif.style.transition = 'opacity 0.3s';
        setTimeout(() => notif.remove(), 300);
    }, 3000);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImgModal();
});

// فحص دوري لحالة الطلب (لو الإدارة وافقت من صفحة تانية)
setInterval(checkEditStatus, 3000);

loadProfile();