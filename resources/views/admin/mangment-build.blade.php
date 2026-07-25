<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mangment.css/buld.css">
    <title>مراجعة العقارات - بيتي</title>
   
<base target="_blank">
<base target="_blank">
</head>
<body>

    <!-- Mobile Toggle -->
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
            <h1>مراجعة <span>العقارات</span></h1>
            <p>إدارة ومراجعة العقارات المرفوعة من أصحاب السكن</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="pendingCount">0</div>
                <div class="stat-label">قيد المراجعة</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="approvedCount">0</div>
                <div class="stat-label">تمت الموافقة</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="rejectedCount">0</div>
                <div class="stat-label">مرفوض</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalCount">0</div>
                <div class="stat-label">إجمالي العقارات</div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <button class="filter-btn active" onclick="filterByStatus('all', this)">الكل</button>
            <button class="filter-btn" onclick="filterByStatus('pending', this)">قيد المراجعة</button>
            <button class="filter-btn" onclick="filterByStatus('approved', this)">تمت الموافقة</button>
            <button class="filter-btn" onclick="filterByStatus('rejected', this)">مرفوض</button>
            <button class="filter-btn" onclick="filterByStatus('draft', this)">مسودة</button>
        </div>

        <!-- Properties Table -->
        <div class="table-container">
            <div class="table-header">
                <h3>📋 قائمة العقارات</h3>
                <span id="tableCount" style="color: var(--text-muted); font-size: 0.85rem;">0 عقار</span>
            </div>
            <div id="propertiesTable">
                <!-- Will be populated by JS -->
            </div>
        </div>
    </main>

    <!-- Property Detail Modal -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-box">
            <h3 id="modalTitle">تفاصيل العقار</h3>
            <div class="detail-images" id="modalImages"></div>
            <div class="detail-grid" id="modalDetails"></div>
            <div class="detail-pricing" id="modalPricing"></div>
            <div class="amenities-list" id="modalAmenities"></div>
            <div class="description-box" id="modalDescription"></div>
            <div class="modal-actions" id="modalActions"></div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon">✅</div>
        <div class="toast-text">
            <h5 id="toastTitle">تم بنجاح</h5>
            <p id="toastText">تمت العملية بنجاح</p>
        </div>
    </div>
<script src="../js/mangment.js/buld.js"></script>
    
</body>
</html>