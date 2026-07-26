
        let currentUserId = null;
        const PLATFORM_FEE_PERCENT = 2; // 2%

        // ===== SYNC USER DATA =====
        function syncUserData() {
            let userData = JSON.parse(localStorage.getItem('userData')) || {};
            
            if (!userData.id) {
                const firstName = localStorage.getItem('owner_firstName') || '';
                const lastName = localStorage.getItem('owner_lastName') || '';
                const fullName = (firstName + ' ' + lastName).trim() || 'مالك العقار';
                
                userData = {
                    id: 'owner_' + Date.now(),
                    name: fullName,
                    role: 'owner'
                };
                localStorage.setItem('userData', JSON.stringify(userData));
            }
            
            currentUserId = userData.id;
            document.getElementById('userName').textContent = userData.name || 'مالك العقار';
            document.getElementById('userAvatar').textContent = (userData.name || 'م').charAt(0);
        }

        // ===== CALCULATE REVENUE =====
        function calculateRevenue() {
            const payments = JSON.parse(localStorage.getItem('payments')) || [];
            const ownerPayments = payments.filter(p => p.ownerId === currentUserId && p.status === 'completed');
            
            const totalRevenue = ownerPayments.reduce((sum, p) => sum + p.amount, 0);
            const platformFee = Math.round(totalRevenue * PLATFORM_FEE_PERCENT / 100);
            const netRevenue = totalRevenue - platformFee;
            
            // Get withdrawals
            const withdrawals = JSON.parse(localStorage.getItem('withdrawals')) || [];
            const ownerWithdrawals = withdrawals.filter(w => w.ownerId === currentUserId && w.status === 'completed');
            const totalWithdrawn = ownerWithdrawals.reduce((sum, w) => sum + w.amount, 0);
            
            const availableBalance = netRevenue - totalWithdrawn;

            // Update stats
            document.getElementById('totalRevenue').textContent = totalRevenue.toLocaleString() + ' ج.م';
            document.getElementById('netRevenue').textContent = netRevenue.toLocaleString() + ' ج.م';
            document.getElementById('platformFee').textContent = platformFee.toLocaleString() + ' ج.م';
            document.getElementById('availableBalance').textContent = availableBalance.toLocaleString() + ' ج.م';

            // Update split cards
            document.getElementById('platformAmount').textContent = platformFee.toLocaleString() + ' ج.م';
            document.getElementById('ownerAmount').textContent = netRevenue.toLocaleString() + ' ج.م';
            document.getElementById('totalAmount').textContent = totalRevenue.toLocaleString() + ' ج.م';

            // Update withdrawal max
            document.getElementById('withdrawAmount').max = availableBalance;
            
            return { totalRevenue, netRevenue, platformFee, availableBalance };
        }

        // ===== RENDER PAYMENTS TABLE =====
        function renderPaymentsTable() {
            const container = document.getElementById('paymentsTable');
            const payments = JSON.parse(localStorage.getItem('payments')) || [];
            const ownerPayments = payments.filter(p => p.ownerId === currentUserId);
            
            if (ownerPayments.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">💳</div>
                        <h3>لا توجد مدفوعات بعد</h3>
                        <p>ستظهر هنا المدفوعات التي يقوم بها الطلاب لحجز عقاراتك</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>العقار</th>
                            <th>الطالب</th>
                            <th>المبلغ</th>
                            <th>طريقة الدفع</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${ownerPayments.sort((a,b) => new Date(b.date) - new Date(a.date)).map(p => `
                            <tr>
                                <td><strong>${p.propertyName}</strong></td>
                                <td>${p.studentName}</td>
                                <td style="color: var(--gold-bright); font-weight: 700;">${p.amount} ج.م</td>
                                <td><span class="payment-method ${p.method}">${getMethodLabel(p.method)}</span></td>
                                <td>${new Date(p.date).toLocaleDateString('ar-EG')}</td>
                                <td><span class="status-badge ${p.status}">${getStatusLabel(p.status)}</span></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        // ===== RENDER WITHDRAWALS TABLE =====
        function renderWithdrawalsTable() {
            const container = document.getElementById('withdrawalsTable');
            const withdrawals = JSON.parse(localStorage.getItem('withdrawals')) || [];
            const ownerWithdrawals = withdrawals.filter(w => w.ownerId === currentUserId);
            
            if (ownerWithdrawals.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📤</div>
                        <h3>لا توجد طلبات سحب</h3>
                        <p>ستظهر هنا طلبات السحب التي قمت بها</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>المبلغ</th>
                            <th>طريقة السحب</th>
                            <th>رقم الحساب</th>
                            <th>تاريخ الطلب</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${ownerWithdrawals.sort((a,b) => new Date(b.date) - new Date(a.date)).map(w => `
                            <tr>
                                <td style="color: var(--gold-bright); font-weight: 700;">${w.amount} ج.م</td>
                                <td>${getMethodLabel(w.method)}</td>
                                <td>${w.phone}</td>
                                <td>${new Date(w.date).toLocaleDateString('ar-EG')}</td>
                                <td><span class="status-badge ${w.status}">${getStatusLabel(w.status)}</span></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        function getMethodLabel(method) {
            const labels = {
                'vodafone': 'فودافون كاش',
                'fawry': 'فوري',
                'bank': 'تحويل بنكي',
                'whatsapp': 'واتساب'
            };
            return labels[method] || method;
        }

        function getStatusLabel(status) {
            const labels = {
                'completed': 'مكتمل',
                'pending': 'قيد المعالجة',
                'failed': 'فاشل',
                'processing': 'جاري التحويل'
            };
            return labels[status] || status;
        }

        // ===== WITHDRAWAL FORM =====
        document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const amount = parseFloat(document.getElementById('withdrawAmount').value);
            const method = document.getElementById('withdrawMethod').value;
            const phone = document.getElementById('withdrawPhone').value;
            
            const revenue = calculateRevenue();
            
            if (amount > revenue.availableBalance) {
                alert('❌ المبلغ المطلوب أكبر من الرصيد المتاح!');
                return;
            }
            
            if (amount < 100) {
                alert('❌ الحد الأدنى للسحب هو 100 ج.م');
                return;
            }

            const withdrawal = {
                id: 'wd_' + Date.now(),
                ownerId: currentUserId,
                amount: amount,
                method: method,
                phone: phone,
                date: new Date().toISOString(),
                status: 'pending'
            };

            let withdrawals = JSON.parse(localStorage.getItem('withdrawals')) || [];
            withdrawals.push(withdrawal);
            localStorage.setItem('withdrawals', JSON.stringify(withdrawals));

            // Add to management queue for approval
            let withdrawalQueue = JSON.parse(localStorage.getItem('withdrawalQueue')) || [];
            withdrawalQueue.push({
                id: withdrawal.id,
                ownerId: currentUserId,
                ownerName: document.getElementById('userName').textContent,
                amount: amount,
                method: method,
                phone: phone,
                date: new Date().toISOString(),
                status: 'pending'
            });
            localStorage.setItem('withdrawalQueue', JSON.stringify(withdrawalQueue));

            alert('✅ تم تقديم طلب السحب بنجاح! سيتم معالجته خلال 24-72 ساعة.');
            this.reset();
            calculateRevenue();
            renderWithdrawalsTable();
        });

        // ===== SIDEBAR TOGGLE =====
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        // ===== INITIALIZE =====
        syncUserData();
        calculateRevenue();
        renderPaymentsTable();
        renderWithdrawalsTable();

        // Listen for storage changes
        window.addEventListener('storage', function(e) {
            if (e.key === 'payments' || e.key === 'withdrawals') {
                calculateRevenue();
                renderPaymentsTable();
                renderWithdrawalsTable();
            }
        });
