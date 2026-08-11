<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <link rel="stylesheet" href="../css/mangment.css/support.css">
=======
    <link rel="stylesheet" href="{{ asset('css/mangment.css/support.css') }}">
>>>>>>> new-origin/abdulrhman
    <title>إدارة الدعم الفني - بيتي</title>
   
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

        <a href="mangment-home.html" class="sidebar-btn active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            لوحة التحكم
        </a>

        <div class="sidebar-section-title">العقارات</div>

        <a href="mangment-build.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            مراجعة العقارات
            <span class="badge" id="pendingBadge">0</span>
        </a>

        <a href="mangment-properties.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            جميع العقارات
        </a>

        <div class="sidebar-section-title">المستخدمين</div>

        <a href="mangment-all.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            المستخدمين والطلبات
        </a>

        <a href="mangment-xx.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            سجل الأشخاص
        </a>

        <div class="sidebar-section-title">الدعم</div>

        <a href="mangment-support.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
            الدعم الفني
            <span class="badge" id="supportBadge">0</span>
        </a>

        <div class="sidebar-bottom">
            <div class="sidebar-divider"></div>
            <a href="../splashscreen.html" class="sidebar-btn">
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
            <h1>إدارة <span>الدعم الفني</span></h1>
            <p>إدارة أرقام التواصل ورسائل الطلاب وأصحاب العقارات والرد عليها</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📩</div>
                <div class="stat-value" id="statTotal">0</div>
                <div class="stat-label">إجمالي الرسائل</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-value" id="statPending">0</div>
                <div class="stat-label">قيد الانتظار</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value" id="statReplied">0</div>
                <div class="stat-label">تم الرد عليها</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📞</div>
                <div class="stat-value" id="statNumbers">0</div>
                <div class="stat-label">أرقام التواصل</div>
            </div>
        </div>

        <!-- Section 1: Contact Numbers Management -->
        <div class="form-section">
            <div class="section-header">
                <div class="icon-circle">📞</div>
                إدارة أرقام التواصل
            </div>
            <p class="section-desc">أضف أرقام التواصل التي ستظهر للطلاب وأصحاب العقارات في صفحات الدعم. يمكن للمستخدمين الضغط على الرقم للتواصل مباشرة عبر واتساب.</p>

            <form id="contactForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="contactRole">الدور / الوظيفة</label>
                        <input type="text" id="contactRole" placeholder="مثال: مدير الدعم الفني" required>
                    </div>
                    <div class="form-group">
                        <label for="contactPhone">رقم التليفون (مع كود الدولة)</label>
                        <input type="tel" id="contactPhone" placeholder="مثال: 201000000000" required>
                        <span class="hint">اكتب الرقم بدون علامات + أو - أو مسافات</span>
                    </div>
                </div>
                <div class="action-buttons" style="margin-top: 15px;">
                    <button type="submit" class="btn btn-primary">➕ إضافة رقم</button>
                </div>
            </form>

            <div style="margin-top: 25px;">
                <h4 style="color: var(--gold-bright); font-size: 1rem; margin-bottom: 15px;">📋 الأرقام المضافة</h4>
                <div class="numbers-list" id="numbersList">
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <h4>لا توجد أرقام بعد</h4>
                        <p>أضف أول رقم تواصل ليظهر للمستخدمين</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Messages Management -->
        <div class="form-section">
            <div class="section-header">
                <div class="icon-circle">📩</div>
                رسائل المستخدمين
            </div>
            <p class="section-desc">عرض وإدارة رسائل الدعم الواردة من الطلاب وأصحاب العقارات والرد عليها</p>

            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('all', this)">الكل</button>
                <button class="tab-btn" onclick="switchTab('pending', this)">⏳ قيد الانتظار</button>
                <button class="tab-btn" onclick="switchTab('replied', this)">✅ تم الرد</button>
                <button class="tab-btn" onclick="switchTab('students', this)">🎓 الطلاب</button>
                <button class="tab-btn" onclick="switchTab('owners', this)">🏠 أصحاب العقارات</button>
            </div>

            <div id="messagesContainer">
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h4>لا توجد رسائل بعد</h4>
                    <p>ستظهر هنا رسائل المستخدمين الواردة</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Reply Modal -->
    <div class="reply-modal-overlay" id="replyModal">
        <div class="reply-modal-box">
            <h3>📨 الرد على الرسالة</h3>
            <div class="message-details" id="replyDetails"></div>
            <textarea class="reply-textarea" id="replyText" placeholder="اكتب ردك هنا..."></textarea>
            <div class="action-buttons" style="justify-content: center;">
                <button class="btn btn-primary btn-sm" onclick="submitReply()">📨 إرسال الرد</button>
                <button class="btn btn-secondary btn-sm" onclick="closeReplyModal()">❌ إلغاء</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
<<<<<<< HEAD

    <script src="../js/mangment.js/support.js"></script>
      
=======
    <script src="{{ asset('js/mangment.js/support.js') }}"></script>
>>>>>>> new-origin/abdulrhman
</body>
</html>