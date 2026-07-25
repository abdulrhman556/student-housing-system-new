
    function loadProfile() {
        // 1. محاولة جلب كائن المستخدم الموحد أولاً
        let currentUser = null;
        try {
            currentUser = JSON.parse(localStorage.getItem('bayaty_current_user') || 'null');
        } catch(e) {
            console.error("Error parsing bayaty_current_user", e);
        }

        // 2. جلب الحقول الفردية كخطة بديلة (Fallback) في حال كان الحساب مسجل لأول مرة بدون الكائن الموحد
        const fName = localStorage.getItem('stu_firstName') || localStorage.getItem('firstName');
        const lName = localStorage.getItem('stu_lastName') || localStorage.getItem('lastName');
        const email = localStorage.getItem('stu_email') || localStorage.getItem('email') || localStorage.getItem('userEmail');
        const phone = localStorage.getItem('stu_phone') || localStorage.getItem('phone');
        const gender = localStorage.getItem('stu_gender') || localStorage.getItem('gender') || 'male';
        const university = localStorage.getItem('stu_university') || localStorage.getItem('university') || '—';
        const academicYear = localStorage.getItem('stu_year') || localStorage.getItem('stu_academic_year') || localStorage.getItem('academicYear') || '—';
        const natIdFile = localStorage.getItem('stu_natIdFile') || localStorage.getItem('natIdFile') || '';
        const uniCardFile = localStorage.getItem('stu_uniCardFile') || localStorage.getItem('uniCardFile') || '';

        let user = null;

        if (currentUser && (currentUser.userType === 'student' || currentUser.email)) {
            user = currentUser;
        } else if (fName || lName || email || phone) {
            // إذا وُجدت أي بيانات فردية، نقوم ببناء كائن المستخدم منها فوراً دون عمل تحويل للصفحة الأخرى
            user = {
                firstName: fName || 'طالب',
                lastName: lName || 'جديد',
                email: email || '—',
                phone: phone || '—',
                gender: gender,
                university: university,
                academicYear: academicYear,
                natIdFile: natIdFile,
                uniCardFile: uniCardFile
            };
        }

        // إذا لم يتم العثور على أي بيانات إطلاقاً (المستخدم لم يسجل أبداً)
        if (!user) {
            window.location.href = 'regest.html';
            return;
        }

        // عرض البيانات في الصفحة
        renderProfile(user);
    }

    function renderProfile(user) {
        const firstName = user.firstName || user.name || 'طالب';
        const lastName = user.lastName || '';
        const fullName = (firstName + ' ' + lastName).trim();
        
        document.getElementById('welcomeName').innerText = fullName || 'طالب منصة بيتي';
        document.getElementById('welcomeEmail').innerText = user.email || '—';
        document.getElementById('avatarLetter').innerText = firstName.charAt(0) || 'ط';

        const yearMap = { '1': 'الفرقة الأولى', '2': 'الفرقة الثانية', '3': 'الفرقة الثالثة', '4': 'الفرقة الرابعة' };
        const genderText = (user.gender === 'female' || user.gender === 'أنثى') ? 'أنثى' : 'ذكر';

        document.getElementById('infoGrid').innerHTML = `
            <div class="info-card"><div class="info-label">👤 الاسم الأول</div><div class="info-value">${firstName}</div></div>
            <div class="info-card"><div class="info-label">👤 اسم العائلة</div><div class="info-value">${lastName || '—'}</div></div>
            <div class="info-card"><div class="info-label">📧 البريد الإلكتروني</div><div class="info-value">${user.email || '—'}</div></div>
            <div class="info-card"><div class="info-label">📱 رقم الهاتف</div><div class="info-value">${user.phone || '—'}</div></div>
            <div class="info-card"><div class="info-label">⚧ الجنس</div><div class="info-value">${genderText}</div></div>
            <div class="info-card"><div class="info-label">🎓 الجامعة</div><div class="info-value">${user.university || '—'}</div></div>
            <div class="info-card sm:col-span-2"><div class="info-label">📚 الفرقة الدراسية</div><div class="info-value">${yearMap[user.academicYear] || user.academicYear || '—'}</div></div>
        `;

        // Documents Rendering
        const natIdFile = user.natIdFile || '';
        const uniCardFile = user.uniCardFile || '';
        const hasNatId = natIdFile.startsWith('data:image') || natIdFile.startsWith('http');
        const hasUniCard = uniCardFile.startsWith('data:image') || uniCardFile.startsWith('http');

        let docsHtml = '';
        if (hasNatId) {
            docsHtml += `<div class="info-card doc-preview" onclick="openImage('${natIdFile.replace(/'/g, "\'")}', 'بطاقة الرقم القومي')">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🪪</span>
                    <div>
                        <div class="info-label">بطاقة الرقم القومي</div>
                        <div class="info-value text-green-600 text-xs">✓ تم الرفع — اضغط للعرض</div>
                    </div>
                </div>
            </div>`;
        } else {
            docsHtml += `<div class="info-card opacity-60">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🪪</span>
                    <div>
                        <div class="info-label">بطاقة الرقم القومي</div>
                        <div class="info-value text-red-500 text-xs">✗ لم يتم الرفع</div>
                    </div>
                </div>
            </div>`;
        }

        if (hasUniCard) {
            docsHtml += `<div class="info-card doc-preview" onclick="openImage('${uniCardFile.replace(/'/g, "\'")}', 'الكارنيه الجامعي')">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎓</span>
                    <div>
                        <div class="info-label">الكارنيه الجامعي</div>
                        <div class="info-value text-green-600 text-xs">✓ تم الرفع — اضغط للعرض</div>
                    </div>
                </div>
            </div>`;
        } else {
            docsHtml += `<div class="info-card opacity-60">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎓</span>
                    <div>
                        <div class="info-label">الكارنيه الجامعي</div>
                        <div class="info-value text-red-500 text-xs">✗ لم يتم الرفع</div>
                    </div>
                </div>
            </div>`;
        }

        document.getElementById('docsGrid').innerHTML = docsHtml;
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

    // تشغيل الفحص عند تحميل الملف الشخصي
    loadProfile();
