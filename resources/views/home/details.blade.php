<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="stylesheet" href="../css/home-css/details.css">
    <title>تفاصيل العقار | BAYATY - بيتي</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>


    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "navy-dark": "#070B19",
                        "navy-light": "#0D162F",
                        "gold-bright": "#F4D068",
                        "gold-dark": "#AA7C11",
                    }
                },
            },
        }
    </script>
<base target="_blank">
<base target="_blank">
</head>
<body class="bg-white text-slate-800 min-h-screen flex flex-col">

<!-- TopNavBar -->
<header class="bg-gradient-to-r from-[#070B19] to-[#1E293B] border-b border-[#AA7C11]/30 sticky top-0 z-50 w-full h-16 flex items-center px-4 md:px-12 shadow-md">
    <div class="flex justify-between items-center w-full max-w-7xl mx-auto">
        <div class="flex items-center gap-8">
            <a href="home.html" class="text-2xl font-black tracking-wider text-[#F4D068]">BAYATY <span class="text-white text-sm font-normal">بيتي</span></a>
            <nav class="hidden md:flex gap-6">
                <a class="text-sm font-semibold text-slate-300 hover:text-[#F4D068] transition-colors" href="home.html">الرئيسية</a>
                <a class="text-sm font-semibold text-slate-300 hover:text-[#F4D068] transition-colors" href="favorites.html">المفضلة ❤️</a>
                <a class="text-sm font-semibold text-slate-300 hover:text-[#F4D068] transition-colors" href="about.html">معلومات عن</a>
                <a class="text-sm font-semibold text-slate-300 hover:text-[#F4D068] transition-colors" href="student-support.html">الدعم الفني</a>
            </nav>
        </div>

        <div class="flex items-center gap-4">
            <button onclick="history.back()" class="back-btn">
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                <span>رجوع</span>
            </button>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 md:px-12 py-8 flex-grow w-full">

    <!-- Loading State -->
    <div id="loadingState" class="space-y-6">
        <div class="skeleton h-8 w-1/3"></div>
        <div class="skeleton h-64 w-full"></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="skeleton h-32"></div>
            <div class="skeleton h-32"></div>
            <div class="skeleton h-32"></div>
        </div>
    </div>

    <!-- Property Content -->
    <div id="propertyContent" class="hidden space-y-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span id="propRegion" class="text-xs bg-gradient-to-r from-[#070B19] to-[#1E293B] text-[#F4D068] border border-[#AA7C11]/40 px-3 py-1 rounded-full font-bold">المنطقة</span>
                    <span id="propType" class="text-xs bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1 rounded-full font-semibold">النوع</span>
                </div>
                <h1 id="propTitle" class="text-2xl md:text-3xl font-extrabold text-slate-900">اسم العقار</h1>
                <p id="propLocation" class="text-slate-500 text-sm mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">location_on</span>
                    <span>الموقع</span>
                </p>
            </div>
            <button id="likeBtn" onclick="toggleFavorite()" class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 hover:border-red-300 hover:bg-red-50 transition-all">
                <span id="likeIcon" class="material-symbols-outlined text-xl">favorite</span>
                <span id="likeText" class="text-sm font-bold text-slate-600">أضف للمفضلة</span>
            </button>
        </div>

        <!-- Image Gallery -->
        <div class="space-y-3">
            <div class="gallery-main">
                <img id="mainImage" src="" alt="صورة العقار الرئيسية"/>
            </div>
            <div class="gallery-thumbs no-scrollbar" id="imageThumbs"></div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column: Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Quick Info -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="info-card text-center">
                        <span class="material-symbols-outlined text-2xl text-[#F4D068] mb-2">bed</span>
                        <div id="propRooms" class="text-xl font-bold text-white">-</div>
                        <div class="text-xs text-slate-400">غرف</div>
                    </div>
                    <div class="info-card text-center">
                        <span class="material-symbols-outlined text-2xl text-[#F4D068] mb-2">bathtub</span>
                        <div id="propBathrooms" class="text-xl font-bold text-white">-</div>
                        <div class="text-xs text-slate-400">حمامات</div>
                    </div>
                    <div class="info-card text-center">
                        <span class="material-symbols-outlined text-2xl text-[#F4D068] mb-2">stairs</span>
                        <div id="propFloor" class="text-xl font-bold text-white">-</div>
                        <div class="text-xs text-slate-400">الدور</div>
                    </div>
                    <div class="info-card text-center">
                        <span class="material-symbols-outlined text-2xl text-[#F4D068] mb-2">near_me</span>
                        <div id="propDistance" class="text-xl font-bold text-white">-</div>
                        <div class="text-xs text-slate-400">كم من الكلية</div>
                    </div>
                </div>

                <!-- Description -->
                <div class="info-card">
                    <h3 class="text-lg font-bold text-[#F4D068] mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined">description</span>
                        وصف العقار
                    </h3>
                    <p id="propDescription" class="text-slate-300 text-sm leading-relaxed">
                        لا يوجد وصف متاح لهذا العقار.
                    </p>
                </div>

                <!-- Amenities -->
                <div class="info-card">
                    <h3 class="text-lg font-bold text-[#F4D068] mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined">star</span>
                        المرافق والخدمات
                    </h3>
                    <div id="propAmenities" class="flex flex-wrap gap-2">
                        <!-- Amenities injected here -->
                    </div>
                </div>

                <!-- Map -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#AA7C11]">map</span>
                        الموقع على الخريطة
                    </h3>
                    <div id="mapSection">
                        <!-- Map or placeholder injected here -->
                    </div>
                </div>
            </div>

            <!-- Right Column: Pricing & Contact -->
            <div class="space-y-6">

                <!-- Pricing -->
                <div class="space-y-3">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#AA7C11]">payments</span>
                        الأسعار
                    </h3>
                    <div id="priceSingleBox" class="price-box">
                        <div class="price-label">غرفة فردية</div>
                        <div id="priceSingle" class="price-value">-</div>
                        <div class="text-xs font-bold mt-1">جنيه / شهرياً</div>
                    </div>
                    <div id="priceDoubleBox" class="price-box" style="background: linear-gradient(135deg, #1E293B 0%, #0D162F 100%); border: 2px solid #F4D068;">
                        <div class="price-label" style="color: #94A3B8;">غرفة مزدوجة</div>
                        <div id="priceDouble" class="price-value" style="color: #F4D068;">-</div>
                        <div class="text-xs font-bold mt-1" style="color: #94A3B8;">جنيه / شهرياً</div>
                    </div>
                    <div id="priceTripleBox" class="price-box" style="background: linear-gradient(135deg, #1E293B 0%, #0D162F 100%); border: 2px solid #F4D068;">
                        <div class="price-label" style="color: #94A3B8;">غرفة ثلاثية</div>
                        <div id="priceTriple" class="price-value" style="color: #F4D068;">-</div>
                        <div class="text-xs font-bold mt-1" style="color: #94A3B8;">جنيه / شهرياً</div>
                    </div>
                </div>

                                <!-- Payment Methods -->
                <div class="info-card">
                    <h3 class="text-lg font-bold text-[#F4D068] mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">payments</span>
                        طرق الدفع
                    </h3>
                    <div id="paymentSection" class="space-y-3">
                        <a href="https://vodafonecash.eg" target="_blank" class="contact-btn w-full" style="background: linear-gradient(135deg, #E60000 0%, #B30000 100%); color: white;">
                            <span class="material-symbols-outlined">phone_android</span>
                            <span>فودافون كاش</span>
                        </a>
                        <a href="https://wa.me/" id="whatsappPaymentBtn" target="_blank" class="contact-btn whatsapp w-full">
                            <span class="material-symbols-outlined">chat</span>
                            <span>الدفع عبر واتساب</span>
                        </a>
                        <a href="https://fawry.com" target="_blank" class="contact-btn w-full" style="background: linear-gradient(135deg, #0066CC 0%, #004499 100%); color: white;">
                            <span class="material-symbols-outlined">store</span>
                            <span>فوري Fawry</span>
                        </a>
                    </div>
                    
                </div>

<!-- Beds Left -->
                <div class="info-card text-center">
                    <span class="material-symbols-outlined text-3xl text-[#F4D068] mb-2">apartment</span>
                    <div class="text-xs text-slate-400 mb-1">نوع السكن</div>
                    <div id="genderDisplay" class="text-xl font-black text-white">-</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Not Found State -->
    <div id="notFoundState" class="not-found hidden">
        <div class="not-found-icon">🏠</div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">العقار غير موجود</h2>
        <p class="text-slate-500 mb-6">لم نتمكن من العثور على بيانات هذا العقار. قد يكون تم حذفه أو لم يُنشر بعد.</p>
        <a href="home.html" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#070B19] to-[#1E293B] text-[#F4D068] font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all">
            <span>العودة للرئيسية</span>
            <span class="material-symbols-outlined text-sm">arrow_back</span>
        </a>
    </div>
</main>

<!-- Footer -->
<footer class="bg-gradient-to-r from-[#070B19] to-[#1E293B] border-t border-[#AA7C11]/30 py-6 mt-8">
    <div class="max-w-7xl mx-auto px-4 md:px-12 text-center text-xs text-slate-300">
        <span class="text-lg font-black text-[#F4D068] tracking-wider block mb-1">BAYATY</span>
        <p>© 2026 إسكان طلاب بني سويف - تفاصيل العقار متصلة بالكامل بقاعدة البيانات.</p>
    </div>
</footer>
<script src="../js/home-js/details.js"></script>

</body>
</html>