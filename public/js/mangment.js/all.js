
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

        function renderTable() {
            const search = document.getElementById('searchInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('statusFilter').value;
            const wrapper = document.getElementById('tableWrapper');
            let list = currentTab === 'students' ? getStudents() : getOwners();

            if (search) {
                list = list.filter(u => {
                    const fullName = (u.firstName + ' ' + u.lastName).toLowerCase();
                    return fullName.includes(search) || (u.email && u.email.toLowerCase().includes(search)) || (u.phone && u.phone.includes(search));
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
                const banBtn = banned
                    ? `<button class="action-btn unban" onclick="event.stopPropagation(); toggleBan('${user.email}', '${currentTab}')">🔓 فك الحظر</button>`
                    : `<button class="action-btn ban" onclick="event.stopPropagation(); toggleBan('${user.email}', '${currentTab}')">🚫 حظر</button>`;
                return `<tr style="cursor:pointer;" onclick="showDetails(${index}, '${currentTab}')">
                    <td><div class="user-cell"><div class="user-avatar">${avatar}</div><div><p class="user-name">${fullName.trim() || '—'}</p><p class="user-type">${typeIcon} ${typeLabel}</p></div></div></td>
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
            const uniCardFile = user.uniCardFile || '';
            const hasNatId = natIdFile.startsWith('data:image') || natIdFile.startsWith('http');
            const hasUniCard = uniCardFile.startsWith('data:image') || uniCardFile.startsWith('http');

            let extraFields = '';
            if (type === 'students') {
                const yearMap = { '1': 'الفرقة الأولى', '2': 'الفرقة الثانية', '3': 'الفرقة الثالثة', '4': 'الفرقة الرابعة' };
                extraFields = `<div class="info-item"><label>🎓 الجامعة</label><p>${user.university || '—'}</p></div>
                    <div class="info-item"><label>📚 الفرقة الدراسية</label><p>${yearMap[user.academicYear] || user.academicYear || '—'}</p></div>`;
            } else {
                const propMap = { 'apartment': 'شقة', 'room': 'غرفة', 'studio': 'استوديو', 'villa': 'فيلا' };
                extraFields = `<div class="info-item"><label>🏠 نوع العقار</label><p>${propMap[user.propertyType] || user.propertyType || '—'}</p></div>
                    <div class="info-item full-width"><label>📍 العنوان</label><p>${user.address || '—'}</p></div>
                    <div class="info-item"><label>💬 واتساب</label><p class="mono">${user.whatsapp || '—'}</p></div>`;
            }

            const banBtnClass = banned ? 'btn-modal-unban' : 'btn-modal-ban';
            const banBtnText = banned ? '🔓 فك الحظر' : '🚫 حظر المستخدم';

            let natIdCard = '';
            let uniCardCard = '';
            if (hasNatId) {
                natIdCard = `<div class="doc-card" onclick="openImage(\`${natIdFile}\`, 'بطاقة الرقم القومي - ${fullName.trim()}')">
                    <div class="doc-icon">🪪</div><div class="doc-name">بطاقة الرقم القومي</div><div class="doc-status">✓ تم الرفع - اضغط للعرض</div></div>`;
            } else {
                natIdCard = `<div class="doc-card no-file"><div class="doc-icon">🪪</div><div class="doc-name">بطاقة الرقم القومي</div><div class="doc-status">✗ لم يتم الرفع</div></div>`;
            }

            if (type === 'students' && hasUniCard) {
                uniCardCard = `<div class="doc-card" onclick="openImage(\`${uniCardFile}\`, 'الكارنيه الجامعي - ${fullName.trim()}')">
                    <div class="doc-icon">🎓</div><div class="doc-name">الكارنيه الجامعي</div><div class="doc-status">✓ تم الرفع - اضغط للعرض</div></div>`;
            }

            document.getElementById('modalBody').innerHTML = `
                <div class="modal-user-header">
                    <div class="modal-avatar">${avatar}</div>
                    <div class="modal-user-info">
                        <h4>${fullName.trim() || '—'}</h4>
                        <div class="modal-tags">
                            <span class="tag tag-gold">${typeIcon} ${typeLabel}</span>
                            ${banned ? '<span class="tag tag-red">🚫 محظور</span>' : '<span class="tag tag-green">✅ نشط</span>'}
                        </div>
                    </div>
                </div>
                <div class="info-grid">
                    <div class="info-item"><label>📧 البريد الإلكتروني</label><p>${user.email || '—'}</p></div>
                    <div class="info-item"><label>🔑 كلمة المرور</label><p style="color:var(--gold-bright); font-family:monospace;">${user.password || '—'}</p></div>
                    <div class="info-item"><label>📱 رقم الهاتف</label><p class="mono">${user.phone || '—'}</p></div>
                    <div class="info-item"><label>⚧ الجنس</label><p>${genderText}</p></div>
                    <div class="info-item full-width"><label>📅 تاريخ التسجيل</label><p>${user.registeredAt || '—'}</p></div>
                    ${extraFields}
                </div>
                <div class="documents-section">
                    <h5>📎 المستندات المرفقة</h5>
                    <div class="documents-grid">${natIdCard}${uniCardCard}</div>
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

        function syncFromRegistration() {
            let students = getStudents();
            let owners = getOwners();
            let changed = false;

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
                    academicYear: localStorage.getItem('stu_year') || '1',
                    natIdFile: localStorage.getItem('stu_natIdFile') || '', 
                    uniCardFile: localStorage.getItem('stu_uniCardFile') || '',
                    registeredAt: new Date().toISOString().split('T')[0]
                };
                if (existingIndex !== -1) students[existingIndex] = { ...students[existingIndex], ...studentData };
                else students.push(studentData);
                
                // تنظيف لمنع التكرار
                ['stu_firstName', 'stu_lastName', 'stu_email', 'stu_password', 'stu_phone', 'stu_gender', 'stu_university', 'stu_year', 'stu_natIdFile', 'stu_uniCardFile'].forEach(k => localStorage.removeItem(k));
                changed = true;
            }

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
                    propertyType: localStorage.getItem('owner_propertyType') || 'apartment', 
                    address: localStorage.getItem('owner_address') || '',
                    natIdFile: localStorage.getItem('owner_natIdFile') || '',
                    registeredAt: new Date().toISOString().split('T')[0]
                };
                if (existingIndex !== -1) owners[existingIndex] = { ...owners[existingIndex], ...ownerData };
                else owners.push(ownerData);
                
                // تنظيف لمنع التكرار
                ['owner_firstName', 'owner_lastName', 'owner_email', 'owner_password', 'owner_phone', 'owner_whatsapp', 'owner_gender', 'owner_propertyType', 'owner_address', 'owner_natIdFile'].forEach(k => localStorage.removeItem(k));
                changed = true;
            }

            if (changed) { saveStudents(students); saveOwners(owners); }
        }

        function init() {
            initStorage();
            syncFromRegistration();
            updateStats();
            renderTable();
        }

        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeModal(); closeImgModal(); } });
        init();
