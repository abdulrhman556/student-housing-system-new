  
        let currentTab = 'all';
        let deletePropertyId = null;
        let publishPropertyId = null;
        let currentUserId = null;
        let currentUserName = 'مالك العقار';

        // ===== SYNC USER DATA =====
        // This ensures registration form data is converted to userData format
        function syncUserData() {
            let userData = JSON.parse(localStorage.getItem('userData')) || {};

            // If no userData.id, try to build from registration form keys
            if (!userData.id) {
                const firstName = localStorage.getItem('owner_firstName') || '';
                const lastName = localStorage.getItem('owner_lastName') || '';
                const email = localStorage.getItem('owner_email') || '';
                const phone = localStorage.getItem('owner_phone') || '';
                const whatsapp = localStorage.getItem('owner_whatsapp') || '';
                const gender = localStorage.getItem('owner_gender') || '';
                const address = localStorage.getItem('owner_address') || '';

                const fullName = (firstName + ' ' + lastName).trim() || 'مالك العقار';

                userData = {
                    id: 'owner_' + Date.now(),
                    name: fullName,
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    phone: phone,
                    whatsapp: whatsapp,
                    gender: gender,
                    address: address,
                    role: 'owner'
                };

                localStorage.setItem('userData', JSON.stringify(userData));
            }

            currentUserId = userData.id;
            currentUserName = userData.name || 'مالك العقار';

            // Update sidebar
            document.getElementById('userName').textContent = currentUserName;
            document.getElementById('userAvatar').textContent = currentUserName.charAt(0);
        }

        // ===== CHECK NOTIFICATIONS =====
        function checkNotifications() {
            const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
            const unread = notifications.filter(n => n.userId === currentUserId && !n.read);

            if (unread.length > 0) {
                const banner = document.getElementById('notificationBanner');
                const text = document.getElementById('notificationText');
                text.textContent = unread[unread.length - 1].message;
                banner.classList.add('show');

                unread.forEach(n => n.read = true);
                localStorage.setItem('notifications', JSON.stringify(notifications));

                setTimeout(() => {
                    banner.classList.remove('show');
                }, 5000);
            }
        }

        // ===== SIDEBAR TOGGLE =====
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        // ===== TAB SWITCHING =====
        function switchTab(tab) {
            currentTab = tab;
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            renderProperties();
        }

        // ===== RENDER PROPERTIES =====
        function renderProperties() {
            const grid = document.getElementById('propertiesGrid');
            const detailView = document.getElementById('detailView');
            detailView.classList.remove('active');
            grid.style.display = 'grid';
            document.querySelector('.tabs').style.display = 'flex';

            let properties = JSON.parse(localStorage.getItem('properties')) || [];

            // Filter by current user
            properties = properties.filter(p => p.ownerId === currentUserId);

            // Filter by tab
            if (currentTab !== 'all') {
                properties = properties.filter(p => p.status === currentTab);
            }

            if (properties.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">🏠</div>
                        <h3>لا توجد عقارات</h3>
                        <p>لم تقم بإضافة أي عقارات بعد. ابدأ بإضافة عقارك الأول!</p>
                    </div>
                `;
                return;
            }

            grid.innerHTML = properties.map(prop => {
                const statusLabels = {
                    'draft': 'مسودة',
                    'pending': 'قيد المراجعة',
                    'published': 'منشور'
                };

                const hasImage = prop.images && prop.images.length > 0;

                return `
                    <div class="property-card">
                        <div class="property-image">
                            ${hasImage ? `<img src="${prop.images[0]}" alt="${prop.propertyName}">` : '🏠'}
                        </div>
                        <div class="property-content">
                            <div class="property-header">
                                <div>
                                    <div class="property-name" onclick="viewProperty('${prop.id}')">${prop.propertyName}</div>
                                    <div class="property-type">${getPropertyTypeLabel(prop.propertyType)} · ${prop.city}</div>
                                </div>
                                <span class="status-badge ${prop.status}">${statusLabels[prop.status]}</span>
                            </div>
                            <div class="property-details">
                                <div class="detail-item">🛏️ ${prop.rooms} غرف</div>
                                <div class="detail-item">🚿 ${prop.bathrooms} حمام</div>
                                <div class="detail-item">🏢 الدور ${prop.floor || '-'}</div>
                            </div>
                            <div class="property-price">
                                ${prop.priceSingle ? 'فردي: ' + prop.priceSingle + ' ج.م' : ''}
                                ${prop.priceDouble ? ' · مزدوج: ' + prop.priceDouble + ' ج.م' : ''}
                            </div>
                            <div class="property-actions">
                                <button class="action-btn primary" onclick="viewProperty('${prop.id}')">عرض التفاصيل</button>
                                ${prop.status === 'draft' ? `<button class="action-btn secondary" onclick="editProperty('${prop.id}')">تعديل</button>` : ''}
                                ${prop.status === 'draft' ? `<button class="action-btn primary" onclick="showPublishModal('${prop.id}')">رفع</button>` : ''}
                                ${prop.status === 'published' ? `<button class="action-btn secondary" onclick="editProperty('${prop.id}')">تعديل</button>` : ''}
                                <button class="action-btn danger" onclick="showDeleteModal('${prop.id}')">حذف</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getPropertyTypeLabel(type) {
            const labels = {
                'apartment': 'شقة',
                'room': 'غرفة',
                'studio': 'استوديو',
                'villa': 'فيلا',
                'duplex': 'دوبلكس'
            };
            return labels[type] || type;
        }

        // ===== VIEW PROPERTY DETAIL =====
        function viewProperty(propId) {
            const properties = JSON.parse(localStorage.getItem('properties')) || [];
            const prop = properties.find(p => p.id === propId);
            if (!prop || prop.ownerId !== currentUserId) return;

            document.getElementById('propertiesGrid').style.display = 'none';
            document.querySelector('.tabs').style.display = 'none';
            document.getElementById('detailView').classList.add('active');

            document.getElementById('detailTitle').textContent = prop.propertyName;

            const imagesContainer = document.getElementById('detailImages');
            if (prop.images && prop.images.length > 0) {
                imagesContainer.innerHTML = prop.images.slice(0, 5).map(img => 
                    `<img src="${img}" alt="${prop.propertyName}">`
                ).join('');
            } else {
                imagesContainer.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-muted);font-size:4rem;">🏠 لا توجد صور</div>';
            }

            const infoContainer = document.getElementById('detailInfo');
            infoContainer.innerHTML = `
                <div class="detail-info-item">
                    <label>نوع العقار</label>
                    <span>${getPropertyTypeLabel(prop.propertyType)}</span>
                </div>
                <div class="detail-info-item">
                    <label>المدينة</label>
                    <span>${prop.city}</span>
                </div>
                <div class="detail-info-item">
                    <label>المنطقة</label>
                    <span>${prop.area}</span>
                </div>
                <div class="detail-info-item">
                    <label>عدد الغرف</label>
                    <span>${prop.rooms}</span>
                </div>
                <div class="detail-info-item">
                    <label>عدد الحمامات</label>
                    <span>${prop.bathrooms}</span>
                </div>
                <div class="detail-info-item">
                    <label>الدور</label>
                    <span>${prop.floor || '-'}</span>
                </div>
                <div class="detail-info-item">
                    <label>الحالة</label>
                    <span>${prop.status === 'draft' ? 'مسودة' : prop.status === 'pending' ? 'قيد المراجعة' : 'منشور'}</span>
                </div>
                <div class="detail-info-item">
                    <label>تاريخ الإضافة</label>
                    <span>${new Date(prop.createdAt).toLocaleDateString('ar-EG')}</span>
                </div>
            `;

            const pricingContainer = document.getElementById('detailPricing');
            pricingContainer.innerHTML = `
                <div class="price-card">
                    <h4>غرفة فردية</h4>
                    <div class="price">${prop.priceSingle || '-'}</div>
                    <div class="price-unit">ج.م / طالب</div>
                </div>
                <div class="price-card">
                    <h4>غرفة مزدوجة</h4>
                    <div class="price">${prop.priceDouble || '-'}</div>
                    <div class="price-unit">ج.م / طالب</div>
                </div>
                <div class="price-card">
                    <h4>غرفة ثلاثية</h4>
                    <div class="price">${prop.priceTriple || '-'}</div>
                    <div class="price-unit">ج.م / طالب</div>
                </div>
            `;

            const actionsContainer = document.getElementById('detailActions');
            let actionsHtml = '';

            if (prop.status === 'draft') {
                actionsHtml += `
                    <button class="action-btn primary" onclick="editProperty('${prop.id}')">تعديل العقار</button>
                    <button class="action-btn primary" onclick="showPublishModal('${prop.id}')">رفع للمراجعة</button>
                `;
            } else if (prop.status === 'published') {
                actionsHtml += `
                    <button class="action-btn secondary" onclick="editProperty('${prop.id}')">تعديل (سيتم المراجعة)</button>
                `;
            } else if (prop.status === 'pending') {
                actionsHtml += `
                    <span style="color: var(--info); font-weight: 600;">⏳ العقار قيد المراجعة من الإدارة</span>
                `;
            }

            actionsHtml += `
                <button class="action-btn danger" onclick="showDeleteModal('${prop.id}')">حذف العقار</button>
            `;

            actionsContainer.innerHTML = actionsHtml;
        }

        function closeDetail() {
            document.getElementById('detailView').classList.remove('active');
            document.getElementById('propertiesGrid').style.display = 'grid';
            document.querySelector('.tabs').style.display = 'flex';
        }

        // ===== EDIT PROPERTY =====
        function editProperty(propId) {
            window.location.href = 'add-property.html?edit=' + propId;
        }

        // ===== DELETE PROPERTY =====
        function showDeleteModal(propId) {
            deletePropertyId = propId;
            document.getElementById('deleteModal').classList.add('active');
        }

        function confirmDelete() {
            if (!deletePropertyId) return;

            let properties = JSON.parse(localStorage.getItem('properties')) || [];
            properties = properties.filter(p => p.id !== deletePropertyId);
            localStorage.setItem('properties', JSON.stringify(properties));

            let managementQueue = JSON.parse(localStorage.getItem('managementQueue')) || [];
            managementQueue = managementQueue.filter(item => item.propertyId !== deletePropertyId);
            localStorage.setItem('managementQueue', JSON.stringify(managementQueue));

            closeModal('deleteModal');
            deletePropertyId = null;

            if (document.getElementById('detailView').classList.contains('active')) {
                closeDetail();
            }
            renderProperties();
        }

        // ===== PUBLISH PROPERTY =====
        function showPublishModal(propId) {
            publishPropertyId = propId;
            document.getElementById('publishModal').classList.add('active');
        }

        function confirmPublish() {
            if (!publishPropertyId) return;

            let properties = JSON.parse(localStorage.getItem('properties')) || [];
            const propIndex = properties.findIndex(p => p.id === publishPropertyId);

            if (propIndex !== -1) {
                properties[propIndex].status = 'pending';
                properties[propIndex].updatedAt = new Date().toISOString();
                localStorage.setItem('properties', JSON.stringify(properties));

                let managementQueue = JSON.parse(localStorage.getItem('managementQueue')) || [];
                managementQueue = managementQueue.filter(q => q.propertyId !== publishPropertyId);
                managementQueue.push({
                    propertyId: properties[propIndex].id,
                    ownerId: properties[propIndex].ownerId,
                    ownerName: properties[propIndex].ownerName,
                    propertyName: properties[propIndex].propertyName,
                    submittedAt: new Date().toISOString(),
                    status: 'pending'
                });
                localStorage.setItem('managementQueue', JSON.stringify(managementQueue));
            }

            closeModal('publishModal');
            publishPropertyId = null;

            if (document.getElementById('detailView').classList.contains('active')) {
                closeDetail();
            }
            renderProperties();
        }

        // ===== MODAL FUNCTIONS =====
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // ===== INITIALIZE =====
        syncUserData();
        renderProperties();
        checkNotifications();

        // Listen for storage changes
        window.addEventListener('storage', function(e) {
            if (e.key === 'properties' || e.key === 'notifications') {
                renderProperties();
                checkNotifications();
            }
        });
