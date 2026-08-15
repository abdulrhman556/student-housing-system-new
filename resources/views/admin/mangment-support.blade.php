<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/mangment.css/support.css') }}">
    <title>طلبات الحجز - بيتي</title>
    <style>
        .booking-card { background: linear-gradient(135deg, #070B19 0%, #1E293B 100%); border: 1px solid rgba(170,124,17,0.3); border-radius: 20px; padding: 24px; margin-bottom: 20px; }
        .booking-header { display: flex; gap: 16px; margin-bottom: 20px; border-bottom: 1px solid rgba(170,124,17,0.2); padding-bottom: 16px; }
        .booking-img { width: 140px; height: 100px; object-fit: cover; border-radius: 12px; border: 2px solid rgba(170,124,17,0.4); flex-shrink: 0; }
        .booking-title h3 { color: #F4D068; font-size: 1.3rem; font-weight: 800; margin-bottom: 6px; }
        .booking-title p { color: #94A3B8; font-size: 0.9rem; margin-bottom: 4px; }
        .status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; margin-top: 6px; }
        .status-pending { background: rgba(217,119,6,0.2); color: #F4D068; border: 1px solid rgba(244,208,104,0.3); }
        .status-approved { background: rgba(22,163,74,0.2); color: #4ADE80; border: 1px solid rgba(74,222,128,0.3); }
        .status-rejected { background: rgba(220,38,38,0.2); color: #F87171; border: 1px solid rgba(248,113,113,0.3); }

        .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .detail-item { background: rgba(13,22,47,0.6); border: 1px solid rgba(170,124,17,0.2); border-radius: 10px; padding: 12px; }
        .detail-item label { color: #94A3B8; font-size: 0.75rem; display: block; margin-bottom: 4px; }
        .detail-item span { color: #F4D068; font-weight: 700; font-size: 0.95rem; }

        .amenities-box { margin: 12px 0; }
        .amenities-box h4 { color: #F4D068; font-size: 0.9rem; margin-bottom: 8px; }
        .amenity-tag { display: inline-block; background: rgba(244,208,104,0.15); color: #F4D068; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(244,208,104,0.3); margin: 2px; }

        .description-box { background: rgba(13,22,47,0.6); border: 1px solid rgba(170,124,17,0.2); border-radius: 10px; padding: 14px; margin: 12px 0; }
        .description-box h4 { color: #F4D068; font-size: 0.9rem; margin-bottom: 6px; }
        .description-box p { color: #CBD5E1; font-size: 0.85rem; line-height: 1.6; }

        .contact-box { background: rgba(13,22,47,0.6); border: 1px solid rgba(170,124,17,0.2); border-radius: 10px; padding: 14px; margin: 12px 0; }
        .contact-box h4 { color: #F4D068; font-size: 0.9rem; margin-bottom: 8px; }
        .contact-box p { color: #CBD5E1; font-size: 0.85rem; margin-bottom: 4px; }

        .booking-actions { display: flex; gap: 10px; margin-top: 16px; }
        .btn-approve { background: linear-gradient(135deg, #16A34A 0%, #15803D 100%); color: white; border: none; padding: 10px 24px; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 0.95rem; }
        .btn-reject { background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; border: none; padding: 10px 24px; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 0.95rem; }
    </style>
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

        <a href="support-mangment.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path d="M3 9h18"/>
            </svg>
            طلبات الحجز
            <span class="badge" id="bookingBadge">0</span>
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
            <h1>طلبات <span>الحجز</span></h1>
            <p>إدارة طلبات الحجز القادمة من الطلاب مع كل التفاصيل</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📥</div>
                <div class="stat-value" id="totalBookings">0</div>
                <div class="stat-label">إجمالي الطلبات</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-value" id="pendingBookings">0</div>
                <div class="stat-label">قيد الانتظار</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value" id="approvedBookings">0</div>
                <div class="stat-label">تمت الموافقة</div>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="form-section">
            <div class="section-header">
                <div class="icon-circle">📋</div>
                قائمة طلبات الحجز
            </div>
            <p class="section-desc">جميع طلبات الحجز من صفحة تفاصيل العقار</p>

            <div id="bookingsList">
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h4>لا توجد طلبات حجز</h4>
                    <p>الطلبات ستظهر هنا عندما يضغط الطلاب على "احجز الآن"</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    <script src="../js/mangment.js/support.js"></script>
</body>
</html>
