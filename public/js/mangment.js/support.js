  // ===== GLOBAL =====
        let currentTab = 'all';
        let selectedMessageId = null;
        let selectedMessageSenderType = null;
        let selectedMessageSenderId = null;
        let contactNumbers = [];

        // ===== SIDEBAR TOGGLE =====
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        // ===== LOAD CONTACT NUMBERS =====
        function loadContactNumbers() {
            const stored = localStorage.getItem('mangment_connect_links');
            contactNumbers = stored ? JSON.parse(stored) : [];
            renderContactNumbers();
            updateStats();
        }

        function renderContactNumbers() {
            const list = document.getElementById('numbersList');
            if (contactNumbers.length === 0) {
                list.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <h4>لا توجد أرقام بعد</h4>
                        <p>أضف أول رقم تواصل ليظهر للمستخدمين</p>
                    </div>
                `;
                return;
            }

            list.innerHTML = '';
            contactNumbers.forEach((item, index) => {
                list.innerHTML += `
                    <div class="number-item">
                        <div class="number-info">
                            <div class="number-icon">📞</div>
                            <div class="number-details">
                                <h4>${escapeHtml(item.role)}</h4>
                                <p>${escapeHtml(item.phone)}</p>
                            </div>
                        </div>
                        <div class="number-actions">
                            <a href="https://wa.me/${item.phone.replace(/[^0-9]/g, '')}" target="_blank" class="btn-icon" title="فتح واتساب">💬</a>
                            <button class="btn-icon" onclick="deleteContact(${index})" title="حذف">🗑️</button>
                        </div>
                    </div>
                `;
            });
        }

        // ===== ADD CONTACT NUMBER =====
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const role = document.getElementById('contactRole').value.trim();
            let phone = document.getElementById('contactPhone').value.trim();

            phone = phone.replace(/[^0-9]/g, '');

            if (!phone.startsWith('2') && phone.length === 10) {
                phone = '2' + phone;
            }

            contactNumbers.push({ role, phone });
            localStorage.setItem('mangment_connect_links', JSON.stringify(contactNumbers));

            this.reset();
            renderContactNumbers();
            updateStats();
            showToast('success', '✅ تم إضافة رقم التواصل بنجاح');
        });

        function deleteContact(index) {
            if (confirm('هل أنت متأكد من حذف هذا الرقم؟')) {
                contactNumbers.splice(index, 1);
                localStorage.setItem('mangment_connect_links', JSON.stringify(contactNumbers));
                renderContactNumbers();
                updateStats();
                showToast('info', '🗑️ تم حذف الرقم');
            }
        }

        // ===== MESSAGES MANAGEMENT =====
        function loadMessages() {
            const stored = localStorage.getItem('management_support_messages');
            let messages = stored ? JSON.parse(stored) : [];

            // Filter by tab
            if (currentTab === 'pending') {
                messages = messages.filter(m => m.status === 'pending');
            } else if (currentTab === 'replied') {
                messages = messages.filter(m => m.status === 'replied');
            } else if (currentTab === 'students') {
                messages = messages.filter(m => m.senderType === 'student');
            } else if (currentTab === 'owners') {
                messages = messages.filter(m => m.senderType === 'owner');
            }

            renderMessages(messages);
            updateStats();
        }

        function renderMessages(messages) {
            const container = document.getElementById('messagesContainer');

            if (messages.length === 0) {
                let tabLabel = '';
                if (currentTab === 'pending') tabLabel = 'قيد الانتظار';
                else if (currentTab === 'replied') tabLabel = 'تم الرد عليها';
                else if (currentTab === 'students') tabLabel = 'من الطلاب';
                else if (currentTab === 'owners') tabLabel = 'من أصحاب العقارات';

                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <h4>لا توجد رسائل ${tabLabel}</h4>
                        <p>${currentTab === 'all' ? 'ستظهر هنا رسائل المستخدمين الواردة' : 'لا توجد رسائل في هذا القسم'}</p>
                    </div>
                `;
                return;
            }

            let html = `
                <table class="messages-table">
                    <thead>
                        <tr>
                            <th>المرسل</th>
                            <th>النوع</th>
                            <th>الرسالة</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            messages.forEach(msg => {
                const date = new Date(msg.createdAt || msg.date).toLocaleDateString('ar-EG');
                const time = new Date(msg.createdAt || msg.date).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
                const statusClass = msg.status === 'pending' ? 'status-pending' : 'status-replied';
                const statusText = msg.status === 'pending' ? '⏳ قيد الانتظار' : '✅ تم الرد';

                const senderType = msg.senderType || 'unknown';
                const senderBadgeClass = senderType === 'student' ? 'sender-student' : senderType === 'owner' ? 'sender-owner' : '';
                const senderBadgeText = senderType === 'student' ? '🎓 طالب' : senderType === 'owner' ? '🏠 صاحب عقار' : '❓ غير معروف';

                const replyCount = msg.replies ? msg.replies.length : 0;

                html += `
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--white);">${escapeHtml(msg.senderName || 'مستخدم')}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">${escapeHtml(msg.senderId || '')}</div>
                        </td>
                        <td>
                            <span class="sender-badge ${senderBadgeClass}">${senderBadgeText}</span>
                            <div style="margin-top: 4px;"><span class="type-badge">${escapeHtml(msg.typeLabel || msg.subject || 'عام')}</span></div>
                        </td>
                        <td><div class="message-preview" title="${escapeHtml(msg.message || msg.body || '')}">${escapeHtml(msg.message || msg.body || '')}</div></td>
                        <td style="font-size: 0.8rem;">${date}<br><span style="color: var(--text-muted);">${time}</span></td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="openReplyModal('${msg.id}')">
                                ${msg.status === 'pending' ? '📨 رد' : '📨 رد إضافي'}
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }

        // ===== TABS =====
        function switchTab(tab, btn) {
            currentTab = tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            loadMessages();
        }

        // ===== REPLY MODAL =====
        function openReplyModal(messageId) {
            selectedMessageId = messageId;
            const messages = JSON.parse(localStorage.getItem('management_support_messages')) || [];
            const msg = messages.find(m => m.id === messageId);

            if (!msg) return;

            selectedMessageSenderType = msg.senderType;
            selectedMessageSenderId = msg.senderId;

            const date = new Date(msg.createdAt || msg.date).toLocaleDateString('ar-EG');
            const time = new Date(msg.createdAt || msg.date).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
            const senderType = msg.senderType || 'unknown';
            const senderBadgeText = senderType === 'student' ? '🎓 طالب' : senderType === 'owner' ? '🏠 صاحب عقار' : '❓ غير معروف';

            document.getElementById('replyDetails').innerHTML = `
                <div class="detail-row">
                    <span class="detail-label">المرسل:</span>
                    <span class="detail-value">${escapeHtml(msg.senderName || 'مستخدم')}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">نوع المستخدم:</span>
                    <span class="detail-value">${senderBadgeText}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">معرف المستخدم:</span>
                    <span class="detail-value">${escapeHtml(msg.senderId || '—')}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">البريد:</span>
                    <span class="detail-value">${escapeHtml(msg.email || '—')}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">التليفون:</span>
                    <span class="detail-value">${escapeHtml(msg.phone || '—')}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">التاريخ:</span>
                    <span class="detail-value">${date} ${time}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">النوع:</span>
                    <span class="detail-value">${escapeHtml(msg.typeLabel || msg.subject || 'عام')}</span>
                </div>
                <div class="detail-message">
                    <strong style="color: var(--gold-bright);">📩 الرسالة:</strong><br>
                    ${escapeHtml(msg.message || msg.body || '')}
                </div>
                ${msg.replies && msg.replies.length > 0 ? `
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border-gold);">
                        <strong style="color: var(--success);">📨 الردود السابقة:</strong>
                        ${msg.replies.map(r => `<div style="margin-top: 8px; color: var(--text-light); font-size: 0.85rem; background: rgba(244,208,104,0.05); padding: 8px; border-radius: 8px;">• ${escapeHtml(r.text)} <span style="color: var(--text-muted); font-size: 0.7rem;">(${new Date(r.timestamp).toLocaleTimeString('ar-EG', {hour:'2-digit', minute:'2-digit'})})</span></div>`).join('')}
                    </div>
                ` : ''}
            `;

            document.getElementById('replyText').value = '';
            document.getElementById('replyModal').classList.add('active');
        }

        function closeReplyModal() {
            document.getElementById('replyModal').classList.remove('active');
            selectedMessageId = null;
            selectedMessageSenderType = null;
            selectedMessageSenderId = null;
        }

        function submitReply() {
            const replyText = document.getElementById('replyText').value.trim();
            if (!replyText) {
                showToast('error', '❌ الرجاء كتابة الرد أولاً');
                return;
            }

            let messages = JSON.parse(localStorage.getItem('management_support_messages')) || [];
            const msgIndex = messages.findIndex(m => m.id === selectedMessageId);

            if (msgIndex === -1) {
                showToast('error', '❌ لم يتم العثور على الرسالة');
                return;
            }

            const reply = {
                id: 'reply_' + Date.now(),
                text: replyText,
                timestamp: new Date().toISOString(),
                admin: 'مدير الدعم'
            };

            if (!messages[msgIndex].replies) {
                messages[msgIndex].replies = [];
            }
            messages[msgIndex].replies.push(reply);
            messages[msgIndex].status = 'replied';

            localStorage.setItem('management_support_messages', JSON.stringify(messages));

            // Also update the user's local storage based on sender type
            updateUserReply(selectedMessageId, selectedMessageSenderType, selectedMessageSenderId, replyText);

            closeReplyModal();
            loadMessages();
            showToast('success', '✅ تم إرسال الرد بنجاح! سيظهر للمستخدم إشعار بالرد.');
        }

        function updateUserReply(messageId, senderType, senderId, replyText) {
            if (!senderType || !senderId) return;

            try {
                if (senderType === 'student') {
                    // Update student chat
                    let studentChat = JSON.parse(localStorage.getItem('student_chat_' + senderId)) || [];
                    const chatMsg = studentChat.find(m => m.id === messageId);
                    if (chatMsg) {
                        chatMsg.status = 'replied';
                        studentChat.push({
                            id: 'reply_' + Date.now(),
                            text: replyText,
                            sender: 'received',
                            timestamp: new Date().toISOString()
                        });
                        localStorage.setItem('student_chat_' + senderId, JSON.stringify(studentChat));
                    }

                    // Update student support messages
                    let studentMessages = JSON.parse(localStorage.getItem('student_support_messages')) || [];
                    const studentMsg = studentMessages.find(m => m.id === messageId);
                    if (studentMsg) {
                        studentMsg.reply = replyText;
                        studentMsg.replyDate = new Date().toISOString();
                        studentMsg.status = 'replied';
                        localStorage.setItem('student_support_messages', JSON.stringify(studentMessages));
                    }
                } else if (senderType === 'owner') {
                    // Update owner chat
                    let ownerChat = JSON.parse(localStorage.getItem('owner_chat_' + senderId)) || [];
                    const chatMsg = ownerChat.find(m => m.id === messageId);
                    if (chatMsg) {
                        chatMsg.status = 'replied';
                        ownerChat.push({
                            id: 'reply_' + Date.now(),
                            text: replyText,
                            sender: 'received',
                            timestamp: new Date().toISOString()
                        });
                        localStorage.setItem('owner_chat_' + senderId, JSON.stringify(ownerChat));
                    }

                    // Update owner support messages
                    let ownerMessages = JSON.parse(localStorage.getItem('owner_support_messages')) || [];
                    const ownerMsg = ownerMessages.find(m => m.id === messageId);
                    if (ownerMsg) {
                        ownerMsg.reply = replyText;
                        ownerMsg.replyDate = new Date().toISOString();
                        ownerMsg.status = 'replied';
                        localStorage.setItem('owner_support_messages', JSON.stringify(ownerMessages));
                    }
                }
            } catch (e) {
                console.error('Update user reply error:', e);
            }
        }

        // ===== STATS =====
        function updateStats() {
            const messages = JSON.parse(localStorage.getItem('management_support_messages')) || [];
            const total = messages.length;
            const pending = messages.filter(m => m.status === 'pending').length;
            const replied = messages.filter(m => m.status === 'replied').length;
            const numbers = contactNumbers.length;

            document.getElementById('statTotal').textContent = total;
            document.getElementById('statPending').textContent = pending;
            document.getElementById('statReplied').textContent = replied;
            document.getElementById('statNumbers').textContent = numbers;
        }

        // ===== TOAST =====
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

        function escapeHtml(text) {
            if (!text) return '—';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ===== INIT =====
        loadContactNumbers();
        loadMessages();

        // Refresh every 5 seconds
        setInterval(() => {
            loadMessages();
            loadContactNumbers();
        }, 5000);
