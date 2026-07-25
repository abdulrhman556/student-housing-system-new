<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/owner-css/owner-buld.css">
    <title>عقاراتي - بيتي</title>
   
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

        <a href="owner-buld.html" class="sidebar-btn active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            عقاراتي
        </a>

        <a href="owner-mony.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            المدفوعات
        </a>

        <a href="owner-support.html" class="sidebar-btn">
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

    <main class="main-content">
        <div class="page-header">
            <div>
                <h1>عقارات<span>ي</span></h1>
                <p>إدارة عقاراتك ومسوداتك</p>
            </div>
            <a href="add-property.html" class="add-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                إضافة عقار جديد
            </a>
        </div>

        <div class="notification-banner" id="notificationBanner">
            <span>✅</span>
            <span id="notificationText">تم نشر عقارك بنجاح!</span>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('all')">الكل</button>
            <button class="tab-btn" onclick="switchTab('published')">منشور</button>
            <button class="tab-btn" onclick="switchTab('pending')">قيد المراجعة</button>
            <button class="tab-btn" onclick="switchTab('draft')">مسودة</button>
        </div>

        <div class="properties-grid" id="propertiesGrid">
        </div>

        <div class="detail-view" id="detailView">
            <div class="detail-header">
                <h2 id="detailTitle">تفاصيل العقار</h2>
                <button class="action-btn secondary" onclick="closeDetail()">← رجوع</button>
            </div>
            <div class="detail-images" id="detailImages"></div>
            <div class="detail-info" id="detailInfo"></div>
            <div class="detail-pricing" id="detailPricing"></div>
            <div class="property-actions" id="detailActions"></div>
        </div>
    </main>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <h3>⚠️ تأكيد الحذف</h3>
            <p>هل أنت متأكد من حذف هذا العقار؟ لا يمكن التراجع عن هذا الإجراء.</p>
            <div class="modal-actions">
                <button class="action-btn secondary" onclick="closeModal('deleteModal')">إلغاء</button>
                <button class="action-btn danger" onclick="confirmDelete()">حذف</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="publishModal">
        <div class="modal-box">
            <h3>📤 رفع للمراجعة</h3>
            <p>سيتم إرسال العقار للإدارة للمراجعة. هل تريد المتابعة؟</p>
            <div class="modal-actions">
                <button class="action-btn secondary" onclick="closeModal('publishModal')">إلغاء</button>
                <button class="action-btn primary" onclick="confirmPublish()">إرسال للمراجعة</button>
            </div>
        </div>
    </div>
<script src="../js/owner-js/owner-buld.js"></script>

</body>
</html>