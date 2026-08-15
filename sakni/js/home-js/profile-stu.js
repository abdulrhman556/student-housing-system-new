function loadProfile() {
    let currentUser = null;
    try {
        currentUser = JSON.parse(localStorage.getItem('bayaty_current_user') || 'null');
    } catch(e) {
        console.error("Error parsing bayaty_current_user", e);
    }

    // Fallback من الحقول الفردية (للتسجيلة الأولى)
    const fName = localStorage.getItem('stu_firstName') || localStorage.getItem('firstName');
    const lName = localStorage.getItem('stu_lastName') || localStorage.getItem('lastName');
    const email = localStorage.getItem('stu_email') || localStorage.getItem('email') || localStorage.getItem('userEmail');
    const phone = localStorage.getItem('stu_phone') || localStorage.getItem('phone');
    const gender = localStorage.getItem('stu_gender') || localStorage.getItem('gender') || 'male';
    const university = localStorage.getItem('stu_university') || localStorage.getItem('university') || '—';
    const nationalId = localStorage.getItem('stu_nationalId') || localStorage.getItem('nationalId') || '—';

    let user = null;

    if (currentUser && (currentUser.userType === 'student' || currentUser.email)) {
        user = currentUser;
    } else if (fName || lName || email || phone) {
        user = {
            firstName: fName || 'طالب',
            lastName: lName || 'جديد',
            email: email || '—',
            phone: phone || '—',
            gender: gender,
            university: university,
            nationalId: nationalId
        };
        localStorage.setItem('bayaty_current_user', JSON.stringify(user));
    }

    if (!user) {
        window.location.href = 'regest.html';
        return;
    }

    renderProfile(user);
}

function renderProfile(user) {
    const firstName = user.firstName || user.name || 'طالب';
    const lastName = user.lastName || '';
    const fullName = (firstName + ' ' + lastName).trim();
    
    document.getElementById('welcomeName').innerText = fullName || 'طالب منصة بيتي';
    document.getElementById('welcomeEmail').innerText = user.email || '—';
    document.getElementById('avatarLetter').innerText = firstName.charAt(0) || 'ط';

    const genderText = (user.gender === 'female' || user.gender === 'أنثى') ? 'أنثى' : 'ذكر';

    // علامة "تم التعديل" لو فيه تعديل
    const modifiedBadge = document.getElementById('modifiedBadge');
    if (modifiedBadge) {
        if (user.modified) {
            modifiedBadge.classList.remove('hidden');
            modifiedBadge.innerText = '✏️ تم التعديل: ' + new Date(user.lastModified).toLocaleString('ar-EG');
        } else {
            modifiedBadge.classList.add('hidden');
        }
    }

    document.getElementById('infoGrid').innerHTML = `
        <div class="info-card"><div class="info-label">👤 الاسم الأول</div><div class="info-value" data-field="firstName">${firstName}</div></div>
        <div class="info-card"><div class="info-label">👤 اسم العائلة</div><div class="info-value" data-field="lastName">${lastName || '—'}</div></div>
        <div class="info-card"><div class="info-label">📧 البريد الإلكتروني</div><div class="info-value" data-field="email">${user.email || '—'}</div></div>
        <div class="info-card"><div class="info-label">📱 رقم الهاتف</div><div class="info-value" data-field="phone">${user.phone || '—'}</div></div>
        <div class="info-card"><div class="info-label">⚧ الجنس</div><div class="info-value" data-field="gender">${genderText}</div></div>
        <div class="info-card"><div class="info-label">🎓 الجامعة</div><div class="info-value" data-field="university">${user.university || '—'}</div></div>
        <div class="info-card sm:col-span-2"><div class="info-label">🆔 الرقم القومي</div><div class="info-value" data-field="nationalId">${user.nationalId || '—'}</div></div>
    `;
}

let isEditing = false;

function toggleEdit() {
    const btn = document.getElementById('editBtn');
    if (!isEditing) {
        enableEditMode();
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">save</span> حفظ التعديلات';
        btn.classList.remove('bg-blue-500/20', 'text-blue-300', 'border-blue-500/40');
        btn.classList.add('bg-green-500/20', 'text-green-300', 'border-green-500/40');
        isEditing = true;
    } else {
        saveProfile();
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">edit</span> تعديل البيانات';
        btn.classList.remove('bg-green-500/20', 'text-green-300', 'border-green-500/40');
        btn.classList.add('bg-blue-500/20', 'text-blue-300', 'border-blue-500/40');
        isEditing = false;
    }
}

function enableEditMode() {
    const fields = document.querySelectorAll('#infoGrid .info-value');
    fields.forEach(field => {
        const fieldName = field.getAttribute('data-field');
        const currentValue = field.innerText === '—' ? '' : field.innerText;
        
        if (fieldName === 'gender') {
            field.innerHTML = `
                <select class="edit-input w-full bg-white border border-slate-300 rounded px-2 py-1 text-sm text-slate-800" data-field="gender">
                    <option value="male" ${currentValue === 'ذكر' ? 'selected' : ''}>ذكر</option>
                    <option value="female" ${currentValue === 'أنثى' ? 'selected' : ''}>أنثى</option>
                </select>
            `;
        } else if (fieldName === 'nationalId') {
            field.innerHTML = `<input type="text" class="edit-input w-full bg-white border border-slate-300 rounded px-2 py-1 text-sm text-slate-800" data-field="nationalId" value="${currentValue}" maxlength="14" pattern="\\d{14}" placeholder="14 رقم">`;
        } else if (fieldName === 'email') {
            field.innerHTML = `<input type="email" class="edit-input w-full bg-white border border-slate-300 rounded px-2 py-1 text-sm text-slate-800" data-field="email" value="${currentValue}">`;
        } else {
            field.innerHTML = `<input type="text" class="edit-input w-full bg-white border border-slate-300 rounded px-2 py-1 text-sm text-slate-800" data-field="${fieldName}" value="${currentValue}">`;
        }
    });
}

function saveProfile() {
    const inputs = document.querySelectorAll('#infoGrid .edit-input');
    let user = {};
    
    try {
        user = JSON.parse(localStorage.getItem('bayaty_current_user') || '{}');
    } catch(e) {
        user = {};
    }

    inputs.forEach(input => {
        const fieldName = input.getAttribute('data-field');
        const value = input.value.trim();
        
        if (fieldName === 'firstName') {
            user.firstName = value || user.firstName;
            localStorage.setItem('stu_firstName', value);
        }
        if (fieldName === 'lastName') {
            user.lastName = value || user.lastName;
            localStorage.setItem('stu_lastName', value);
        }
        if (fieldName === 'email') {
            user.email = value || user.email;
            localStorage.setItem('stu_email', value);
        }
        if (fieldName === 'phone') {
            user.phone = value || user.phone;
            localStorage.setItem('stu_phone', value);
        }
        if (fieldName === 'gender') {
            user.gender = value || user.gender;
            localStorage.setItem('stu_gender', value);
        }
        if (fieldName === 'university') {
            user.university = value || user.university;
            localStorage.setItem('stu_university', value);
        }
        if (fieldName === 'nationalId') {
            // التحقق من الرقم القومي
            if (value && !/^\d{14}$/.test(value)) {
                alert('الرقم القومي يجب أن يكون 14 رقم بالضبط');
                return;
            }
            user.nationalId = value || user.nationalId;
            localStorage.setItem('stu_nationalId', value);
        }
    });

    // علامة التعديل + التاريخ
    user.modified = true;
    user.lastModified = new Date().toISOString();

    localStorage.setItem('bayaty_current_user', JSON.stringify(user));
    
    // إشعار للصفحات التانية (management) إن فيه تحديث
    localStorage.setItem('bayaty_profile_updated', Date.now().toString());

    renderProfile(user);
    showNotification('✓ تم حفظ التعديلات بنجاح');
}

function showNotification(msg) {
    const notif = document.createElement('div');
    notif.className = 'fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-full shadow-lg z-50 text-sm font-bold';
    notif.style.animation = 'fadeInDown 0.3s ease';
    notif.innerText = msg;
    document.body.appendChild(notif);
    setTimeout(() => {
        notif.style.animation = 'fadeOutUp 0.3s ease';
        setTimeout(() => notif.remove(), 300);
    }, 2500);
}

function openImage(src, title) {
    document.getElementById('imgModalSrc').src = src;
    document.getElementById('imgModalTitle').innerText = title;
    document.getElementById('imgModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeImgModal() {
    document.getElementById('imgModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImgModal();
});

loadProfile();