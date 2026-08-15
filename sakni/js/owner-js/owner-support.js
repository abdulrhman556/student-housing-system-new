
        var currentUserId = null;
        var currentUserName = 'مالك العقار';
        var chatOpen = false;
        var unreadCount = 0;

        function init() {
            try {
                loadUserData();
                loadContacts();
                renderMessages();
                loadChatMessages();
                checkForReplies();
                setInterval(checkForReplies, 3000);
            } catch (e) {
                console.error('Init error:', e);
            }
        }

        function loadUserData() {
            try {
                var userData = JSON.parse(localStorage.getItem('userData')) || {};
                if (userData.name) {
                    currentUserName = userData.name;
                    var nameEl = document.getElementById('userName');
                    var avatarEl = document.getElementById('userAvatar');
                    if (nameEl) nameEl.textContent = userData.name;
                    if (avatarEl) avatarEl.textContent = userData.name.charAt(0);
                }
                currentUserId = userData.id || 'owner_' + Date.now();
            } catch (e) {}
        }

        // ===== LOAD CONTACTS FROM MANAGEMENT =====
        function loadContacts() {
            try {
                var container = document.getElementById('contactList');
                if (!container) return;

                var config = JSON.parse(localStorage.getItem('booking_config')) || {};
                var phone = config.contactPhone;

                if (!phone) {
                    container.innerHTML = '<p style="color: var(--text-muted); font-size: 0.85rem; text-align: center; padding: 20px;">لا يوجد رقم دعم متاح حالياً. سيتم إضافته من لوحة الإدارة.</p>';
                    return;
                }

                var cleanPhone = phone.replace(/[^0-9]/g, '');
                var waLink = 'https://wa.me/' + cleanPhone + '?text=' + encodeURIComponent('مرحباً، أنا ' + currentUserName + ' صاحب عقار في منصة بيتي وأحتاج مساعدة');

                var html = '<a href="' + waLink + '" target="_blank" class="contact-item">' +
                    '<div class="contact-icon-emoji">💬</div>' +
                    '<div class="contact-info">' +
                    '<span class="label">الدعم الفني</span>' +
                    '<span class="value">' + escapeHtml(phone) + '</span>' +
                    '</div>' +
                    '<span class="contact-arrow">◀</span>' +
                    '</a>';
                container.innerHTML = html;
            } catch (e) {
                console.error('Load contacts error:', e);
            }
        }

        function toggleFaq(item) {
            item.classList.toggle('open');
        }

        // ===== SEND MESSAGE TO MANAGEMENT =====
        function sendMessage(e) {
            e.preventDefault();
            try {
                var subjectEl = document.getElementById('msgSubject');
                var bodyEl = document.getElementById('msgBody');

                var subject = subjectEl ? subjectEl.value : '';
                var body = bodyEl ? bodyEl.value.trim() : '';

                if (!subject || !body) {
                    showToast('error', '❌ يرجى ملء جميع الحقول');
                    return;
                }

                var config = JSON.parse(localStorage.getItem('booking_config')) || {};
                var phone = config.contactPhone;

                if (!phone) {
                    showToast('error', '❌ لا يوجد رقم دعم متاح حالياً');
                    return;
                }

                var cleanPhone = phone.replace(/[^0-9]/g, '');
                var waText = '*موضوع:* ' + getSubjectLabel(subject) + '%0A%0A' + '*التفاصيل:* ' + encodeURIComponent(body) + '%0A%0A' + '*من:* ' + encodeURIComponent(currentUserName);
                var waLink = 'https://wa.me/' + cleanPhone + '?text=' + waText;

                window.open(waLink, '_blank');

                if (subjectEl) subjectEl.value = '';
                if (bodyEl) bodyEl.value = '';

                showToast('success', '✅ جاري فتح واتساب لإرسال رسالتك...');
            } catch (e) {
                console.error('Send message error:', e);
                showToast('error', '❌ حدث خطأ أثناء الإرسال');
            }
        }

        function renderMessages() {
            try {
                var container = document.getElementById('messagesList');
                if (!container) return;

                var messages = JSON.parse(localStorage.getItem('owner_support_messages')) || [];
                var myMessages = messages.filter(function(m) { return m.senderId === currentUserId; });

                myMessages.sort(function(a, b) { return new Date(b.date) - new Date(a.date); });

                if (myMessages.length === 0) {
                    container.innerHTML = '<p style="color: var(--text-muted); text-align: center; padding: 30px;">لا توجد رسائل مرسلة بعد</p>';
                    return;
                }

                var html = '';
                for (var i = 0; i < myMessages.length; i++) {
                    var m = myMessages[i];
                    var isReplied = m.reply && m.reply !== '';
                    var statusClass = isReplied ? 'replied' : 'pending';
                    var statusText = isReplied ? 'تم الرد' : 'قيد الانتظار';
                    var statusBadge = isReplied ? 'replied' : 'pending';

                    html += '<div class="message-item ' + statusClass + '">' +
                        '<div class="message-header">' +
                        '<span class="message-subject">' + escapeHtml(getSubjectLabel(m.subject)) + '</span>' +
                        '<span class="message-date">' + formatDate(m.date) + '</span>' +
                        '</div>' +
                        '<div class="message-body">' + escapeHtml(m.body) + '</div>' +
                        '<span class="message-status ' + statusBadge + '">' + statusText + '</span>';

                    if (isReplied) {
                        html += '<div class="reply-box">' +
                            '<strong>📢 رد الإدارة:</strong><br>' +
                            escapeHtml(m.reply) +
                            '<div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 5px;">' + formatDate(m.replyDate) + '</div>' +
                            '</div>';
                    }

                    html += '</div>';
                }
                container.innerHTML = html;
            } catch (e) {
                console.error('Render messages error:', e);
            }
        }

        // ===== CHAT WIDGET =====
        function toggleChat() {
            chatOpen = !chatOpen;
            var box = document.getElementById('chatBox');
            var badge = document.getElementById('chatBadge');

            if (chatOpen) {
                box.classList.add('active');
                unreadCount = 0;
                if (badge) badge.style.display = 'none';
                loadChatMessages();
            } else {
                box.classList.remove('active');
            }
        }

        function sendChatMessage() {
            var input = document.getElementById('chatInput');
            var text = input.value.trim();
            if (!text) return;

            var config = JSON.parse(localStorage.getItem('booking_config')) || {};
            var phone = config.contactPhone;

            if (!phone) {
                showToast('error', '❌ لا يوجد رقم دعم متاح حالياً');
                return;
            }

            var cleanPhone = phone.replace(/[^0-9]/g, '');
            var waLink = 'https://wa.me/' + cleanPhone + '?text=' + encodeURIComponent(text);

            window.open(waLink, '_blank');
            input.value = '';

            // Optional: add to local chat for UI feedback
            var msgId = 'chat_owner_' + Date.now();
            var timestamp = new Date().toISOString();
            var ownerMessages = JSON.parse(localStorage.getItem('owner_chat_' + currentUserId)) || [];
            ownerMessages.push({
                id: msgId,
                text: text,
                sender: 'student',
                timestamp: timestamp,
                status: 'pending'
            });
            localStorage.setItem('owner_chat_' + currentUserId, JSON.stringify(ownerMessages));
            loadChatMessages();
        }

        function loadChatMessages() {
            var container = document.getElementById('chatMessages');
            var messages = JSON.parse(localStorage.getItem('owner_chat_' + currentUserId)) || [];

            if (messages.length === 0) {
                container.innerHTML = '<div class="empty-chat"><div style="font-size: 2rem; margin-bottom: 10px;">👋</div><div>أهلاً بيك! أرسل رسالتك وسنرد عليك قريباً</div></div>';
                return;
            }

            container.innerHTML = '';
            for (var i = 0; i < messages.length; i++) {
                var msg = messages[i];
                var time = new Date(msg.timestamp).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
                var statusHtml = msg.sender === 'student' ? 
                    '<span class="msg-status"><span class="status-dot ' + msg.status + '"></span> ' + (msg.status === 'pending' ? 'قيد الانتظار' : 'تم الرد') + '</span>' : '';

                container.innerHTML += '<div class="message-bubble ' + msg.sender + '">' +
                    msg.text.replace(/\n/g, '<br>') +
                    '<span class="msg-time">' + time + '</span>' +
                    statusHtml +
                    '</div>';
            }

            container.scrollTop = container.scrollHeight;
        }

        // ===== CHECK FOR REPLIES =====
        function checkForReplies() {
            try {
                var messages = JSON.parse(localStorage.getItem('owner_chat_' + currentUserId)) || [];
                var hasNewReply = false;
                var replyText = '';

                for (var i = 0; i < messages.length; i++) {
                    var msg = messages[i];
                    if (msg.sender === 'student' && msg.status === 'pending') {
                        var queue = JSON.parse(localStorage.getItem('management_support_messages')) || [];
                        for (var j = 0; j < queue.length; j++) {
                            if (queue[j].id === msg.id && queue[j].replies && queue[j].replies.length > 0) {
                                var lastReply = queue[j].replies[queue[j].replies.length - 1];
                                var alreadyAdded = false;
                                for (var k = 0; k < messages.length; k++) {
                                    if (messages[k].id === 'reply_' + lastReply.id) {
                                        alreadyAdded = true;
                                        break;
                                    }
                                }
                                if (!alreadyAdded) {
                                    messages.push({
                                        id: 'reply_' + lastReply.id,
                                        text: lastReply.text,
                                        sender: 'received',
                                        timestamp: lastReply.timestamp
                                    });
                                    msg.status = 'replied';
                                    hasNewReply = true;
                                    replyText = lastReply.text;
                                }
                            }
                        }
                    }
                }

                if (hasNewReply) {
                    localStorage.setItem('owner_chat_' + currentUserId, JSON.stringify(messages));

                    if (!chatOpen) {
                        unreadCount++;
                        var badge = document.getElementById('chatBadge');
                        if (badge) {
                            badge.textContent = unreadCount;
                            badge.style.display = 'flex';
                        }
                    }

                    loadChatMessages();
                    showNotification('📢 تم الرد على رسالتك!', 'فريق الدعم رد: ' + replyText.substring(0, 50) + '...');
                }

                // Also check form messages
                checkFormReplies();
            } catch (e) {
                console.error('Check replies error:', e);
            }
        }

        function checkFormReplies() {
            var messages = JSON.parse(localStorage.getItem('owner_support_messages')) || [];
            var myMessages = messages.filter(function(m) { return m.senderId === currentUserId; });
            var unreadCount = 0;

            for (var i = 0; i < myMessages.length; i++) {
                if (myMessages[i].reply && myMessages[i].reply !== '' && !myMessages[i].replyRead) {
                    unreadCount++;
                }
            }

            var badge = document.getElementById('unreadBadge');
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }

            renderMessages();
        }

        // ===== NOTIFICATION =====
        function showNotification(title, text) {
            var banner = document.getElementById('notifBanner');
            var titleEl = document.getElementById('notifTitle');
            var textEl = document.getElementById('notifText');
            if (titleEl) titleEl.textContent = title;
            if (textEl) textEl.textContent = text;
            if (banner) banner.classList.add('show');

            setTimeout(function() {
                if (banner) banner.classList.remove('show');
            }, 5000);
        }

        function getSubjectLabel(subject) {
            var labels = {
                'technical': 'مشكلة تقنية',
                'payment': 'مشكلة في الدفع',
                'property': 'مشكلة في العقار',
                'account': 'مشكلة في الحساب',
                'suggestion': 'اقتراح',
                'other': 'أخرى'
            };
            return labels[subject] || subject;
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            try {
                return new Date(dateStr).toLocaleDateString('ar-EG', {
                    year: 'numeric', month: 'short', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
            } catch (e) {
                return dateStr;
            }
        }

        function escapeHtml(text) {
            if (!text) return '-';
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.querySelector('.sidebar-overlay');
            if (sidebar) sidebar.classList.toggle('open');
            if (overlay) overlay.classList.toggle('active');
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

        // Listen for storage changes
        window.addEventListener('storage', function(e) {
            if (e.key === 'management_support_messages' || e.key === 'mangment_connect_links') {
                loadContacts();
                renderMessages();
                checkForReplies();
            }
        });

        // Init
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }