        // ===== SIDEBAR TOGGLE =====
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        let currentFilter = 'all';
        let currentProperties = [];

        // ===== AMENITIES LABELS =====
        const amenityLabels = {
            'wifi': '📶 واي فاي',
            'ac': '❄️ تكييف',
            'heater': '🔥 سخان مياه',
            'fridge': '🧊 ثلاجة',
            'washing': '👕 غسالة',
            'tv': '📺 تلفزيون',
            'furniture': '🛋️ مفروشة',
            'parking': '🚗 موقف سيارات'
        };

        const typeLabels = {
            'apartment': 'شقة',
            'room': 'غرفة',
            'studio': 'استوديو',
            'villa': 'فيلا',
            'duplex': 'دوبلكس'
        };

        const genderLabels = {
            'male': '👨‍🎓 طلاب',
            'female': '👩‍🎓 طالبات',
            'mixed': '👫 مشترك'
        };

        const genderIcons = {
            'male': '👨‍🎓',
            'female': '👩‍🎓',
            'mixed': '👫'
        };

        const statusLabels = {
            'pending': { text: 'قيد المراجعة', class: 'pending' },
            'approved': { text: 'تمت الموافقة', class: 'approved' },
            'rejected': { text: 'مرفوض', class: 'rejected' },
            'draft': { text: 'مسودة', class: 'draft' },
            'published': { text: 'منشور', class: 'approved' }
        };

        // ===== UPDATE STATS =====
        function updateStats() {
            const properties = JSON.parse(localStorage.getItem('properties')) || [];
            const pending = properties.filter(p => p.status === 'pending').length;
            const approved = properties.filter(p => p.status === 'published' || p.status === 'approved').length;
            const rejected = properties.filter(p => p.status === 'rejected').length;
            const draft = properties.filter(p => p.status === 'draft').length;

            document.getElementById('pendingCount').textContent = pending;
            document.getElementById('approvedCount').textContent = approved;
            document.getElementById('rejectedCount').textContent = rejected;
            document.getElementById('totalCount').textContent = properties.length;
        }

        // ===== FILTER BY STATUS =====
        function filterByStatus(status, btn) {
            currentFilter = status;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderTable();
        }

        // ===== RENDER TABLE =====
        function renderTable() {
            const properties = JSON.parse(localStorage.getItem('properties')) || [];
            let filtered = properties;

            if (currentFilter !== 'all') {
                if (currentFilter === 'approved') {
                    filtered = properties.filter(p => p.status === 'published' || p.status === 'approved');
                } else {
                    filtered = properties.filter(p => p.status === currentFilter);
                }
            }

            currentProperties = filtered;
            document.getElementById('tableCount').textContent = filtered.length + ' عقار';
            const container = document.getElementById('propertiesTable');

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">🏠</div>
                        <h3>لا توجد عقارات</h3>
                        <p>لم يتم إضافة أي عقارات بعد</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>اسم العقار</th>
                            <th>النوع</th>
                            <th>المدينة / المنطقة</th>
                            <th>السكن</th>
                            <th>صاحب العقار</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${filtered.map(prop => {
                            const status = statusLabels[prop.status] || statusLabels['draft'];
                            const typeLabel = typeLabels[prop.propertyType] || prop.propertyType;
                            return `
                                <tr>
                                    <td><strong>${prop.propertyName}</strong></td>
                                    <td>${typeLabel}</td>
                                    <td>${prop.city} / ${prop.area}</td>
                                    <td>${genderIcons[prop.gender] || '👥'} ${genderLabels[prop.gender] || 'غير محدد'}</td>
                                    <td>${prop.ownerName}</td>
                                    <td><span class="status-badge ${status.class}">${status.text}</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view" onclick="viewProperty('${prop.id}')">👁️ عرض</button>
                                            ${prop.status === 'pending' ? `
                                                <button class="action-btn approve" onclick="approveProperty('${prop.id}')">✅ موافقة</button>
                                                <button class="action-btn reject" onclick="rejectProperty('${prop.id}')">❌ رفض</button>
                                            ` : ''}
                                            ${prop.status === 'published' || prop.status === 'approved' ? `
                                                <button class="action-btn reject" onclick="unpublishProperty('${prop.id}')">🚫 إلغاء النشر</button>
                                            ` : ''}
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            `;
        }

        // ===== VIEW PROPERTY DETAIL =====
        function viewProperty(propId) {
            const properties = JSON.parse(localStorage.getItem('properties')) || [];
            const prop = properties.find(p => p.id === propId);
            if (!prop) return;

            document.getElementById('modalTitle').textContent = prop.propertyName;

            // Images
            const imagesContainer = document.getElementById('modalImages');
            if (prop.images && prop.images.length > 0) {
                imagesContainer.innerHTML = prop.images.slice(0, 6).map(img => 
                    `<img src="${img}" alt="${prop.propertyName}" onclick="window.open('${img}', '_blank')" style="cursor: pointer;">`
                ).join('');
            } else {
                imagesContainer.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-muted);font-size:3rem;">🏠 لا توجد صور</div>';
            }

            // Details
            const detailsContainer = document.getElementById('modalDetails');
            detailsContainer.innerHTML = `
                <div class="detail-item">
                    <label>نوع العقار</label>
                    <span>${typeLabels[prop.propertyType] || prop.propertyType}</span>
                </div>
                <div class="detail-item">
                    <label>المدينة</label>
                    <span>${prop.city}</span>
                </div>
                <div class="detail-item">
                    <label>المنطقة</label>
                    <span>${prop.area}</span>
                </div>
                <div class="detail-item">
                    <label>عدد الغرف</label>
                    <span>${prop.rooms}</span>
                </div>
                <div class="detail-item">
                    <label>عدد الحمامات</label>
                    <span>${prop.bathrooms}</span>
                </div>
                <div class="detail-item">
                    <label>الدور</label>
                    <span>${prop.floor || '-'}</span>
                </div>
                <div class="detail-item">
                    <label>عدد السرائر</label>
                    <span>${prop.beds || '-'}</span>
                </div>
                <div class="detail-item">
                    <label>نوع السكن</label>
                    <span>${genderLabels[prop.gender] || 'غير محدد'}</span>
                </div>
                <div class="detail-item">
                    <label>صاحب العقار</label>
                    <span>${prop.ownerName}</span>
                </div>
                <div class="detail-item">
                    <label>تاريخ الإضافة</label>
                    <span>${new Date(prop.createdAt).toLocaleDateString('ar-EG')}</span>
                </div>
            `;

            // Pricing
            const pricingContainer = document.getElementById('modalPricing');
            pricingContainer.innerHTML = `
                <div class="price-box">
                    <h4>غرفة فردية</h4>
                    <div class="price">${prop.priceSingle ? prop.priceSingle + ' ج.م' : '-'}</div>
                </div>
                <div class="price-box">
                    <h4>غرفة مزدوجة</h4>
                    <div class="price">${prop.priceDouble ? prop.priceDouble + ' ج.م' : '-'}</div>
                </div>
                <div class="price-box">
                    <h4>غرفة ثلاثية</h4>
                    <div class="price">${prop.priceTriple ? prop.priceTriple + ' ج.م' : '-'}</div>
                </div>
            `;

            // Amenities
            const amenitiesContainer = document.getElementById('modalAmenities');
            if (prop.amenities && prop.amenities.length > 0) {
                amenitiesContainer.innerHTML = prop.amenities.map(a => 
                    `<span class="amenity-tag">${amenityLabels[a] || a}</span>`
                ).join('');
            } else {
                amenitiesContainer.innerHTML = '<span style="color: var(--text-muted);">لا توجد مرافق مسجلة</span>';
            }

            // Description
            const descContainer = document.getElementById('modalDescription');
            descContainer.innerHTML = prop.description || 'لا يوجد وصف مفصل للعقار.';

            // Contact info in modal
            if (prop.contactPhone || prop.contactWhatsapp || prop.contactEmail || prop.mapLink) {
                detailsContainer.innerHTML += `
                    <div class="detail-item" style="grid-column: span 2;">
                        <label>معلومات التواصل</label>
                        <span>
                            ${prop.contactPhone ? '📞 ' + prop.contactPhone + ' | ' : ''}
                            ${prop.contactWhatsapp ? '💬 ' + prop.contactWhatsapp + ' | ' : ''}
                            ${prop.contactEmail ? '📧 ' + prop.contactEmail : ''}
                        </span>
                    </div>
                `;
                if (prop.mapLink) {
                    detailsContainer.innerHTML += `
                        <div class="detail-item" style="grid-column: span 2;">
                            <label>الموقع على الخريطة</label>
                            <span><a href="${prop.mapLink}" target="_blank" style="color: var(--gold-bright);">🗺️ فتح الموقع على Google Maps</a></span>
                        </div>
                    `;
                }
            }

            // Actions
            const actionsContainer = document.getElementById('modalActions');
            if (prop.status === 'pending') {
                actionsContainer.innerHTML = `
                    <button class="action-btn approve" onclick="approveProperty('${propId}'); closeModal();">✅ موافقة ونشر</button>
                    <button class="action-btn reject" onclick="rejectProperty('${propId}'); closeModal();">❌ رفض</button>
                `;
            } else if (prop.status === 'published' || prop.status === 'approved') {
                actionsContainer.innerHTML = `
                    <span style="color: var(--success); font-weight: 700;">✅ تم نشر هذا العقار</span>
                    <button class="action-btn reject" onclick="unpublishProperty('${propId}'); closeModal();">🚫 إلغاء النشر</button>
                `;
            } else if (prop.status === 'rejected') {
                actionsContainer.innerHTML = `
                    <span style="color: var(--danger); font-weight: 700;">❌ تم رفض هذا العقار</span>
                    <button class="action-btn approve" onclick="approveProperty('${propId}'); closeModal();">✅ إعادة الموافقة</button>
                `;
            } else {
                actionsContainer.innerHTML = `
                    <span style="color: var(--text-muted);">مسودة - لم يُرسل للمراجعة</span>
                `;
            }

            document.getElementById('detailModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        // ===== APPROVE PROPERTY =====
        function approveProperty(propId) {
            let properties = JSON.parse(localStorage.getItem('properties')) || [];
            const propIndex = properties.findIndex(p => p.id === propId);

            if (propIndex !== -1) {
                const prop = properties[propIndex];
                prop.status = 'published';
                prop.updatedAt = new Date().toISOString();
                prop.approvedAt = new Date().toISOString();
                localStorage.setItem('properties', JSON.stringify(properties));

                // Update management queue
                let queue = JSON.parse(localStorage.getItem('managementQueue')) || [];
                const queueIndex = queue.findIndex(q => q.propertyId === propId);
                if (queueIndex !== -1) {
                    queue[queueIndex].status = 'approved';
                    queue[queueIndex].reviewedAt = new Date().toISOString();
                    localStorage.setItem('managementQueue', JSON.stringify(queue));
                }

                // Add to published properties for home page
                let publishedProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
                // Remove if already exists
                publishedProps = publishedProps.filter(p => p.id !== propId);

                // Format property for home page
                const homeProp = {
                    id: prop.id,
                    title: prop.propertyName,
                    price: parseInt(prop.priceSingle) || parseInt(prop.priceDouble) || parseInt(prop.priceTriple) || 0,
                    bedsLeft: parseInt(prop.beds) || parseInt(prop.rooms) || 1,
                    distance: 2.5, // Default distance, can be updated
                    region: prop.area,
                    gender: prop.gender || 'male',
                    services: prop.amenities || [],
                    img: prop.images && prop.images.length > 0 ? prop.images[0] : 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=500',
                    city: prop.city,
                    ownerId: prop.ownerId,
                    ownerName: prop.ownerName,
                    status: 'published'
                };
                publishedProps.push(homeProp);
                localStorage.setItem('mangment_flutter_properties', JSON.stringify(publishedProps));

                // Send notification to owner
                sendNotification(prop.ownerId, 'approved', 
                    'تم نشر عقارك "' + prop.propertyName + '" بنجاح! 🎉',
                    'تمت الموافقة على عقارك ونشره على المنصة. يمكنك الآن استقبال طلبات الحجز.'
                );

                updateStats();
                renderTable();
                showToast('success', '✅ تمت الموافقة', 'تم نشر العقار "' + prop.propertyName + '" بنجاح!');
            }
        }

        // ===== REJECT PROPERTY =====
        function rejectProperty(propId) {
            let properties = JSON.parse(localStorage.getItem('properties')) || [];
            const propIndex = properties.findIndex(p => p.id === propId);

            if (propIndex !== -1) {
                const prop = properties[propIndex];
                prop.status = 'rejected';
                prop.updatedAt = new Date().toISOString();
                localStorage.setItem('properties', JSON.stringify(properties));

                // Update management queue
                let queue = JSON.parse(localStorage.getItem('managementQueue')) || [];
                const queueIndex = queue.findIndex(q => q.propertyId === propId);
                if (queueIndex !== -1) {
                    queue[queueIndex].status = 'rejected';
                    queue[queueIndex].reviewedAt = new Date().toISOString();
                    localStorage.setItem('managementQueue', JSON.stringify(queue));
                }

                // Remove from published if exists
                let publishedProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
                publishedProps = publishedProps.filter(p => p.id !== propId);
                localStorage.setItem('mangment_flutter_properties', JSON.stringify(publishedProps));

                // Send notification to owner
                sendNotification(prop.ownerId, 'rejected',
                    'تم رفض عقارك "' + prop.propertyName + '"',
                    'يرجى مراجعة بيانات العقار وإعادة الإرسال بعد التعديل.'
                );

                updateStats();
                renderTable();
                showToast('error', '❌ تم الرفض', 'تم رفض العقار "' + prop.propertyName + '" وإعادته للمسودات');
            }
        }

        // ===== UNPUBLISH PROPERTY =====
        function unpublishProperty(propId) {
            let properties = JSON.parse(localStorage.getItem('properties')) || [];
            const propIndex = properties.findIndex(p => p.id === propId);

            if (propIndex !== -1) {
                const prop = properties[propIndex];
                prop.status = 'draft';
                prop.updatedAt = new Date().toISOString();
                localStorage.setItem('properties', JSON.stringify(properties));

                // Remove from published
                let publishedProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
                publishedProps = publishedProps.filter(p => p.id !== propId);
                localStorage.setItem('mangment_flutter_properties', JSON.stringify(publishedProps));

                // Update queue
                let queue = JSON.parse(localStorage.getItem('managementQueue')) || [];
                const queueIndex = queue.findIndex(q => q.propertyId === propId);
                if (queueIndex !== -1) {
                    queue[queueIndex].status = 'rejected';
                    localStorage.setItem('managementQueue', JSON.stringify(queue));
                }

                sendNotification(prop.ownerId, 'rejected',
                    'تم إلغاء نشر عقارك "' + prop.propertyName + '"',
                    'تم إلغاء نشر العقار من المنصة.'
                );

                updateStats();
                renderTable();
                showToast('info', '🚫 تم إلغاء النشر', 'تم إلغاء نشر العقار "' + prop.propertyName + '"');
            }
        }

        // ===== SEND NOTIFICATION =====
        function sendNotification(userId, type, title, message) {
            let notifications = JSON.parse(localStorage.getItem('notifications')) || [];
            notifications.push({
                id: 'notif_' + Date.now(),
                userId: userId,
                type: type,
                title: title,
                message: message,
                createdAt: new Date().toISOString(),
                read: false
            });
            localStorage.setItem('notifications', JSON.stringify(notifications));
        }

        // ===== TOAST =====
        function showToast(type, title, text) {
            const toast = document.getElementById('toast');
            const titleEl = document.getElementById('toastTitle');
            const textEl = document.getElementById('toastText');
            const iconEl = toast.querySelector('.toast-icon');

            toast.className = 'toast ' + type;
            titleEl.textContent = title;
            textEl.textContent = text;

            if (type === 'success') iconEl.textContent = '✅';
            else if (type === 'error') iconEl.textContent = '❌';
            else iconEl.textContent = 'ℹ️';

            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 4000);
        }

        // ===== CLOSE MODAL ON OVERLAY CLICK =====
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ===== INITIAL RENDER =====
        document.addEventListener('DOMContentLoaded', () => {
            updateStats();
            renderTable();
        });

        // Listen for storage changes
        window.addEventListener('storage', function(e) {
            if (e.key === 'properties' || e.key === 'managementQueue') {
                updateStats();
                renderTable();
            }
        });
  