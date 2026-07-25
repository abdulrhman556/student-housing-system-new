<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="stylesheet" href="{{ asset('css/home-css/favority.css') }}">
    <title>العقارات المفضلة | BAYATY - بيتي</title>
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
</head>
<body class="bg-[#070B19] text-white min-h-screen flex flex-col justify-between pb-16 md:pb-0">

<!-- TopNavBar -->
<header class="bg-[#0D162F] border-b border-[#AA7C11]/30 sticky top-0 z-50 w-full h-16 flex items-center px-4 md:px-12 shadow-md">
    <div class="flex justify-between items-center w-full max-w-7xl mx-auto">
        <div class="flex items-center gap-8">
            <a href="home.html" class="text-2xl font-black tracking-wider text-[#F4D068]">BAYATY <span class="text-white text-sm font-normal">بيتي</span></a>
            <nav class="hidden md:flex gap-6">
                <a class="text-sm font-semibold text-slate-400 hover:text-[#F4D068] transition-colors" href="home.html">الرئيسية</a>
                <a class="text-sm font-semibold text-[#F4D068] border-b-2 border-[#F4D068] pb-1" href="favorites.html">المفضلة ❤️</a>
                <a class="text-sm font-semibold text-slate-400 hover:text-[#F4D068] transition-colors" href="profile.html">بياناتي</a>
            </nav>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="home.html" class="btn-interact flex items-center gap-1 text-xs font-bold text-[#F4D068] bg-white/10 border border-[#AA7C11]/40 px-3 py-1.5 rounded-full hover:bg-white/20">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>تصفح المزيد من العقارات</span>
            </a>
        </div>
    </div>
</header>

<!-- Main Container (مساحة العرض البيضاء النقية والمحاطة بالـ فلاتر لتتناسق تماماً مع شاشتك الرئيسية) -->
<main class="max-w-7xl mx-auto px-4 md:px-12 py-8 flex-grow bg-white w-full rounded-b-2xl shadow-xl">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500" style="font-variation-settings: 'FILL' 1;">favorite</span>
                <span>العقارات التي نالت إعجابكِ</span>
                <span id="genderBadge" class="text-xs bg-gradient-to-r from-[#070B19] to-[#0D162F] text-[#F4D068] px-2.5 py-0.5 rounded-full font-semibold">جاري المزامنة...</span>
            </h1>
            <p id="favsCountText" class="text-slate-500 text-xs mt-1">يتم الآن فحص وتحديث قائمة مفضلاتك الحالية...</p>
        </div>
        <button onclick="clearAllFavorites()" class="btn-interact text-xs font-bold text-red-600 bg-red-50 border border-red-200 px-4 py-2 rounded-xl hover:bg-red-100">
            إزالة جميع المفضلة
        </button>
    </div>

    <!-- Empty State Box (يظهر برمجياً في حال عدم وجود أي قلوب) -->
    <div id="emptyState" class="hidden text-center py-20 space-y-4">
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto border border-slate-200 shadow-sm text-slate-300">
            <span class="material-symbols-outlined text-4xl">favorite_border</span>
        </div>
        <div class="space-y-1">
            <h3 class="text-base font-bold text-slate-800">قائمة المفضلة فارغة حالياً</h3>
            <p class="text-slate-400 text-xs max-w-sm mx-auto">عندما تضغطين على أيقونة القلب على أي عقار في الصفحة الرئيسية أو صفحة التفاصيل، ستظهر هنا تلقائياً لسهولة تتبعها.</p>
        </div>
        <a href="home.html" class="btn-interact inline-flex items-center gap-2 bg-[#0D162F] text-[#F4D068] font-bold text-xs px-5 py-2.5 rounded-xl shadow-md">
            <span>اكتشفي عقارات بني سويف الآن</span>
            <span class="material-symbols-outlined text-sm">explore</span>
        </a>
    </div>

    <!-- Properties Favorites Grid Container -->
    <div id="favoritesGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- الكروت المضاف إليها قلب تولد هنا ديناميكياً بتدرج كحلي-لبني رائع -->
    </div>
</main>

<!-- Bottom Navigation Bar (Mobile Only) -->
<nav class="md:hidden fixed bottom-0 w-full z-40 bg-gradient-to-r from-[#070B19] to-[#0D162F] border-t border-[#AA7C11]/30 flex justify-around items-center py-2 rounded-t-xl shadow-2xl">
    <a href="home.html" class="flex flex-col items-center justify-center text-slate-300 hover:text-[#F4D068] p-1">
        <span class="material-symbols-outlined">apartment</span>
        <span class="text-[10px] font-medium mt-0.5">الرئيسية</span>
    </a>
    <a href="favorites.html" class="flex flex-col items-center justify-center text-[#F4D068] p-1">
        <span class="material-symbols-outlined">favorite</span>
        <span class="text-[10px] font-bold mt-0.5">المفضلة</span>
    </a>
    <a href="./profile-student.html" class="flex flex-col items-center justify-center text-slate-300 hover:text-[#F4D068] p-1">
        <span class="material-symbols-outlined">person</span>
        <span class="text-[10px] font-medium mt-0.5">بياناتي</span>
    </a>
</nav>

<!-- Footer -->
<footer class="bg-[#070B19] py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-12 text-center text-xs text-slate-500">
        <span class="text-base font-black text-[#F4D068] tracking-wider block mb-1">BAYATY</span>
        <p>© 2026 منصة بيتي لإسكان طلاب بني سويف. شاشة المفضلة متصلة بالكامل بالـ localStorage.</p>
    </div>
</footer>

<script src="{{ asset('js/home-js/favourty.js') }}"></script>


</body>
</html>