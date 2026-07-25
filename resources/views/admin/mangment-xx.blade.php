<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mangment.css/xx.css">
    <title>سجل الأشخاص</title>
   
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
            <h1>سجل <span>الأشخاص</span></h1>
            <p>إضافة وحفظ بيانات الأشخاص بسهولة</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value" id="totalPersons">0</div>
                <div class="stat-label">إجمالي الأشخاص</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🕐</div>
                <div class="stat-value" id="lastAdded">—</div>
                <div class="stat-label">آخر إضافة</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-value" id="todayCount">0</div>
                <div class="stat-label">إضافات اليوم</div>
            </div>
        </div>

        <!-- Add Person Form -->
        <div class="form-section">
            <div class="section-header">
                <div class="icon-circle">➕</div>
                إضافة شخص جديد
            </div>
            <p class="section-desc">أدخل بيانات الشخص ثم اضغط حفظ لإضافته للسجل</p>

            <form id="personForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="personName">الاسم الكامل *</label>
                        <input type="text" id="personName" placeholder="أدخل الاسم الكامل" required>
                    </div>
                    <div class="form-group">
                        <label for="personPhone">رقم الهاتف *</label>
                        <input type="tel" id="personPhone" placeholder="05xxxxxxxx" required>
                    </div>
                    <div class="form-group">
                        <label for="personEmail">البريد الإلكتروني *</label>
                        <input type="email" id="personEmail" placeholder="email@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="personPassword">كلمة المرور *</label>
                        <input type="password" id="personPassword" placeholder="••••••" required>
                    </div>
                </div>
                <div class="action-buttons" style="margin-top: 15px;">
                    <button type="submit" class="btn btn-primary">💾 حفظ الشخص</button>
                    <button type="button" class="btn btn-secondary" onclick="clearForm()">🔄 مسح النموذج</button>
                </div>
            </form>
        </div>

        <!-- Persons List -->
        <div class="form-section">
            <div class="section-header">
                <div class="icon-circle">📋</div>
                الأشخاص المسجلين
            </div>
            <p class="section-desc">قائمة بجميع الأشخاص المسجلين في السجل</p>

            <div class="persons-list" id="personsList">
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h4>لا يوجد أشخاص مسجلين</h4>
                    <p>أضف أول شخص ليظهر هنا</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
<script src="../js/mangment.js/xx.js"></script>
   
</body>
</html>