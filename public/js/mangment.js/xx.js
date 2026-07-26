
        const PERSONS_KEY = 'my_persons_list';

        function getPersons() {
            return JSON.parse(localStorage.getItem(PERSONS_KEY) || '[]');
        }

        function savePersons(persons) {
            localStorage.setItem(PERSONS_KEY, JSON.stringify(persons));
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

        // Save handler
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

        // Sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        // Initialize
        renderList();
 