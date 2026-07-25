<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/owner-css/support-owner.css">
    <title>الدعم الفني - بيتي</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
   
<base target="_blank">
</head>
<body>

    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">بيتي <span>BAYATY</span></div>

        <a href="owner-profile.html" class="user-profile" id="userProfile">
            <div class="user-avatar" id="userAvatar">م</div>
            <div class="user-info">
                <span class="user-name" id="userName">مالك العقار</span>
                <span class="user-role">الملف الشخصي</span>
            </div>
        </a>

        <a href="add-property.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            إضافة عقار جديد
        </a>

        <a href="owner-buld.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            عقاراتي
        </a>

        <a href="owner-many.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            المدفوعات
        </a>

        <a href="owner-support.html" class="sidebar-btn active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
            الدعم
        </a>

        <div class="sidebar-bottom">
            <div class="sidebar-divider"></div>
            <a href="home.html" class="sidebar-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                تسجيل الخروج
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1>الدعم <span>الفني</span></h1>
            <p>تواصل معنا للمساعدة وحل المشاكل</p>
        </div>

        <!-- Contact Cards -->
        <div class="support-grid">
            <div class="support-card">
                <div class="support-card-header">
                    <div class="support-icon">💬</div>
                    <h3>تواصل مع الإدارة</h3>
                </div>
                <p>يمكنك التواصل مع فريق الإدارة مباشرة عبر الواتساب أو الهاتف للحصول على المساعدة.</p>
                <div id="contactList">
                    <p style="color: var(--text-muted); font-size: 0.85rem; text-align: center; padding: 20px;">جاري تحميل أرقام التواصل...</p>
                </div>
            </div>

            <div class="support-card">
                <div class="support-card-header">
                    <div class="support-icon">💡</div>
                    <h3>كيفية استخدام المنصة</h3>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>كيف أضيف عقاري؟</span>
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="faq-answer">
                        اذهب لقائمة "إضافة عقار جديد" واملأ جميع البيانات المطلوبة ثم اضغط "نشر العقار". سيتم مراجعة العقار من الإدارة قبل النشر.
                    </div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>كيف أستلم أرباحي؟</span>
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="faq-answer">
                        اذهب لصفحة "المدفوعات" واضغط على "طلب سحب" واختر طريقة السحب المناسبة. يتم معالجة الطلب خلال 24-72 ساعة.
                    </div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>ما هي عمولة المنصة؟</span>
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="faq-answer">
                        المنصة تأخذ نسبة 2% من كل عملية دفع كعمولة، و98% تذهب لصاحب العقار مباشرة.
                    </div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>كيف أعدل على عقاري؟</span>
                        <span class="faq-icon">▼</span>
                    </div>
                    <div class="faq-answer">
                        اذهب لصفحة "عقاراتي" واضغط على العقار المراد تعديله ثم اختر "تعديل".
                    </div>
                </div>
            </div>
        </div>

        <!-- Common Issues -->
        <div class="support-card" style="margin-bottom: 25px;">
            <div class="support-card-header">
                <div class="support-icon">🔧</div>
                <h3>المشاكل الشائعة وحلولها</h3>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span>الصور لا تظهر بعد الرفع</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    تأكد من أن حجم الصورة لا يتجاوز 5 ميجابايت وأنها بصيغة JPG أو PNG. جرب إعادة تحميل الصفحة.
                </div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span>لم أستلم إشعار بحجز جديد</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    تأكد من تفعيل الإشعارات في إعدادات المتصفح. يمكنك أيضاً مراجعة صفحة "المدفوعات" بشكل دوري.
                </div>
            </div>
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    <span>طلب السحب معلق لفترة طويلة</span>
                    <span class="faq-icon">▼</span>
                </div>
                <div class="faq-answer">
                    مدة معالجة السحب 24-72 ساعة. إذا تجاوزت المدة، يرجى التواصل مع الدعم الفني مباشرة.
                </div>
            </div>
        </div>

        <!-- Send Message Section -->
        <div class="message-section">
            <h3>
                <div class="icon-circle">✉️</div>
                إرسال رسالة للدعم الفني
            </h3>
            <form id="supportForm" onsubmit="sendMessage(event)">
                <div class="form-group">
                    <label for="msgSubject">موضوع الرسالة *</label>
                    <select id="msgSubject" required>
                        <option value="" disabled selected>اختر نوع المشكلة</option>
                        <option value="technical">مشكلة تقنية</option>
                        <option value="payment">مشكلة في الدفع</option>
                        <option value="property">مشكلة في العقار</option>
                        <option value="account">مشكلة في الحساب</option>
                        <option value="suggestion">اقتراح</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="msgBody">تفاصيل الرسالة *</label>
                    <textarea id="msgBody" placeholder="اشرح مشكلتك بالتفصيل..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">📨 إرسال الرسالة</button>
            </form>
        </div>

        <!-- My Messages -->
        <div class="message-section">
            <h3>
                <div class="icon-circle">💬</div>
                رسائلي
                <span id="unreadBadge" style="display:none; background: var(--danger); color: white; font-size: 0.7rem; padding: 2px 8px; border-radius: 10px; margin-right: 8px;">0</span>
            </h3>
            <div class="messages-list" id="messagesList">
                <p style="color: var(--text-muted); text-align: center; padding: 30px;">لا توجد رسائل مرسلة بعد</p>
            </div>
        </div>
    </main>

    <!-- Floating Chat Widget -->
    <div class="chat-widget">
        <div class="chat-box" id="chatBox">
            <div class="chat-header">
                <h4>💬 الدعم المباشر</h4>
                <button class="chat-close" onclick="toggleChat()">×</button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="empty-chat">
                    <div style="font-size: 2rem; margin-bottom: 10px;">👋</div>
                    <div>أهلاً بيك! أرسل رسالتك وسنرد عليك قريباً</div>
                </div>
            </div>
            <div class="chat-input-area">
                <input type="text" class="chat-input" id="chatInput" placeholder="اكتب رسالتك هنا..." onkeypress="if(event.key==='Enter') sendChatMessage()">
                <button class="chat-send" onclick="sendChatMessage()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </button>
            </div>
        </div>
        <button class="chat-toggle" id="chatToggle" onclick="toggleChat()">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            <span class="chat-badge" id="chatBadge" style="display: none;">1</span>
        </button>
    </div>

    <!-- Notification Banner -->
    <div class="notif-banner" id="notifBanner">
        <div class="notif-icon">✅</div>
        <div class="notif-text">
            <h5 id="notifTitle">تم الرد على رسالتك!</h5>
            <p id="notifText">فريق الدعم رد على استفسارك. افتح الشات لمشاهدة الرد.</p>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
<script src="../js/owner-js/owner-support.js"></script>
   
</body>
</html>