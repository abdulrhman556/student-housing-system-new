
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
            localStorage.removeItem('stu_email'); // لمسح جلسة الطالب أيضاً عند تسجيل الخروج
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

        function loadProfile() {
            let user = getCurrentUser();

            // فحص أولاً إذا كان الحساب المسجل هو طالب (من الكود الأول)
            if (!user && localStorage.getItem('stu_email')) {
                user = {
                    firstName: localStorage.getItem('stu_firstName') || '',
                    lastName: localStorage.getItem('stu_lastName') || '',
                    email: localStorage.getItem('stu_email') || '',
                    phone: localStorage.getItem('stu_phone') || '',
                    gender: localStorage.getItem('stu_gender') || 'male',
                    university: localStorage.getItem('stu_university') || '',
                    academicYear: localStorage.getItem('stu_year') || '',
                    natIdFile: localStorage.getItem('stu_natIdFile') || '',
                    userType: 'student'
                };
            }

            // فحص الحسابات القديمة للمالك (Legacy) إذا لم يكن طالباً
            if (!user) {
                const fName = localStorage.getItem('owner_firstName') || '';
                const lName = localStorage.getItem('owner_lastName') || '';
                const email = localStorage.getItem('owner_email') || '';
                if (!email) {
                    window.location.href = 'regest.html';
                    return;
                }
                user = {
                    firstName: fName, lastName: lName, email: email,
                    phone: localStorage.getItem('owner_phone') || '',
                    whatsapp: localStorage.getItem('owner_whatsapp') || '',
                    gender: localStorage.getItem('owner_gender') || 'male',
                    propertyType: localStorage.getItem('owner_propertyType') || '',
                    address: localStorage.getItem('owner_address') || '',
                    natIdFile: localStorage.getItem('owner_natIdFile') || '',
                    userType: 'owner'
                };
            }

            // تخصيص الشارات والعناوين بناءً على نوع الحساب المكتشف (طالب أو مالك)
            if (user.userType === 'student') {
                document.getElementById('typeBadge').textContent = '🎓 حساب طالب';
            } else {
                document.getElementById('typeBadge').textContent = '🏠 صاحب سكن';
            }

            const fullName = (user.firstName || '') + ' ' + (user.lastName || '');
            const displayName = fullName.trim() || (user.userType === 'student' ? 'الطالب المسجل' : 'مالك العقار');
            const firstChar = displayName.charAt(0);
            const banned = isBanned(user.email);

            document.getElementById('userName').textContent = displayName;
            document.getElementById('userAvatar').textContent = firstChar;
            document.getElementById('profileAvatar').textContent = firstChar;
            document.getElementById('profileName').textContent = displayName;

            if (banned) {
                document.getElementById('statusBadge').style.display = 'inline-block';
            }

            const genderText = user.gender === 'female' ? 'أنثى' : 'ذكر';

            // بناء شبكة المعلومات بشكل ديناميكي حسب نوع الحساب
            let infoHTML = `
                <div class="info-item"><label>👤 الاسم الكامل</label><p>${displayName}</p></div>
                <div class="info-item"><label>📧 البريد الإلكتروني</label><p>${user.email || '—'}</p></div>
                <div class="info-item"><label>📱 رقم الهاتف</label><p class="mono">${user.phone || '—'}</p></div>
                <div class="info-item"><label>⚧ الجنس</label><p>${genderText}</p></div>
            `;

            if (user.userType === 'student') {
                infoHTML += `
                    <div class="info-item"><label>🏫 الجامعة / المعهد</label><p>${user.university || '—'}</p></div>
                    <div class="info-item"><label>📚 الفرقة الدراسية</label><p>الفرقة ${user.academicYear || '—'}</p></div>
                `;
            } else {
                const propMap = { 'apartment': 'شقة', 'room': 'غرفة', 'studio': 'استوديو', 'villa': 'فيلا' };
                infoHTML += `
                    <div class="info-item"><label>💬 واتساب</label><p class="mono">${user.whatsapp || '—'}</p></div>
                    <div class="info-item"><label>🏠 نوع العقار</label><p>${propMap[user.propertyType] || user.propertyType || '—'}</p></div>
                    <div class="info-item full-width"><label>📍 العنوان</label><p>${user.address || '—'}</p></div>
                    <div class="info-item"><label>📅 تاريخ التسجيل</label><p>${user.registeredAt || '—'}</p></div>
                `;
            }

            document.getElementById('infoGrid').innerHTML = infoHTML;

            // عرض بطاقة الرقم القومي
            const natIdFile = user.natIdFile || '';
            const hasNatId = natIdFile.startsWith('data:image') || natIdFile.startsWith('http');

            if (hasNatId) {
                document.getElementById('docsGrid').innerHTML = `<div class="doc-card" onclick="openImage('${natIdFile.replace(/'/g, "\'")}', 'بطاقة الرقم القومي - ${displayName.replace(/'/g, "\'")}')">
                    <div class="doc-icon">🪪</div><div class="doc-name">بطاقة الرقم القومي</div><div class="doc-status" style="color:var(--success)">✓ تم الرفع — اضغط للعرض</div></div>`;
            } else {
                document.getElementById('docsGrid').innerHTML = `<div class="doc-card no-file"><div class="doc-icon">🪪</div><div class="doc-name">بطاقة الرقم القومي</div><div class="doc-status" style="color:var(--danger)">✗ لم يتم الرفع</div></div>`;
            }

            // إحصائيات العقارات (تظهر صفر تلقائياً إذا كان الحساب لطالب)
            const properties = JSON.parse(localStorage.getItem('properties') || '[]');
            const userProps = properties.filter(p => p.ownerId === user.id || p.ownerEmail === user.email);
            document.getElementById('statProperties').textContent = userProps.length;
            document.getElementById('statPublished').textContent = userProps.filter(p => p.status === 'published').length;
            document.getElementById('statPending').textContent = userProps.filter(p => p.status === 'pending').length;
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeImgModal();
        });

        loadProfile();
