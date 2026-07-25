<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="stylesheet" href="../css/home-css/profil-stu.css">
    <title>بياناتي الشخصية | BAYATY - بيتي</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght=300;400;500;600;700;800&display=swap" rel="stylesheet"/>
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

<header class="bg-[#0D162F] border-b border-[#AA7C11]/30 sticky top-0 z-50 w-full h-16 flex items-center px-4 md:px-12 shadow-md">
    <div class="flex justify-between items-center w-full max-w-7xl mx-auto">
        <div class="flex items-center gap-8">
            <a href="home.html" class="text-2xl font-black tracking-wider text-[#F4D068]">BAYATY <span class="text-white text-sm font-normal">بيتي</span></a>
            <nav class="hidden md:flex gap-6">
                <a class="text-sm font-semibold text-slate-400 hover:text-[#F4D068] transition-colors" href="home.html">الرئيسية</a>
                <a class="text-sm font-semibold text-slate-400 hover:text-[#F4D068] transition-colors" href="favorites.html">المفضلة ❤️</a>
                <a class="text-sm font-semibold text-[#F4D068] border-b-2 border-[#F4D068] pb-1" href="profile.html">بياناتي</a>
            </nav>
        </div>

        <div class="flex items-center gap-4">
            <a href="home.html" class="btn-interact flex items-center gap-1 text-xs font-bold text-[#F4D068] bg-white/10 border border-[#AA7C11]/40 px-3 py-1.5 rounded-full hover:bg-white/20">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>العودة للرئيسية</span>
            </a>
        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-4 md:px-12 py-8 flex-grow bg-white w-full rounded-b-2xl shadow-2xl text-slate-800">

    <div class="animate-fade space-y-8">
        <div class="bg-gradient-to-r from-[#070B19] to-[#1E293B] p-6 rounded-2xl border border-[#AA7C11]/30 flex flex-col sm:flex-row items-center gap-5 text-white shadow-lg">
            <div class="relative w-20 h-20 rounded-full overflow-hidden border-2 border-[#F4D068] shadow-md flex-shrink-0 bg-gradient-to-br from-[#F4D068] to-[#AA7C11] flex items-center justify-center text-3xl font-bold text-[#070B19]" id="avatarDiv">
                <span id="avatarLetter">ط</span>
            </div>
            <div class="text-center sm:text-right space-y-1 flex-grow">
                <h2 id="welcomeName" class="text-lg font-extrabold text-white">طالب منصة بيتي</h2>
                <p id="welcomeEmail" class="text-slate-400 text-xs tracking-wide">user@bayaty.com</p>
                <span class="inline-block text-[10px] bg-blue-500/20 text-blue-300 border border-blue-500/40 px-2.5 py-0.5 rounded-full">👨‍🎓 حساب طالب</span>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-[#0D162F] mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#AA7C11]">person</span>
                البيانات الشخصية <span class="text-xs text-slate-400 font-normal">(للتعديل تواصل مع الإدارة)</span>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="infoGrid">
                </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-[#0D162F] mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#AA7C11]">description</span>
                المستندات المرفقة
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="docsGrid">
                </div>
        </div>
    </div>
</main>

<nav class="md:hidden fixed bottom-0 w-full z-40 bg-gradient-to-r from-[#070B19] to-[#0D162F] border-t border-[#AA7C11]/30 flex justify-around items-center py-2 rounded-t-xl shadow-2xl">
    <a href="home.html" class="flex flex-col items-center justify-center text-slate-300 hover:text-[#F4D068] p-1">
        <span class="material-symbols-outlined">apartment</span>
        <span class="text-[10px] font-medium mt-0.5">الرئيسية</span>
    </a>
    <a href="favorites.html" class="flex flex-col items-center justify-center text-slate-300 hover:text-[#F4D068] p-1">
        <span class="material-symbols-outlined">favorite</span>
        <span class="text-[10px] font-medium mt-0.5">المفضلة</span>
    </a>
    <a href="profile.html" class="flex flex-col items-center justify-center text-[#F4D068] p-1">
        <span class="material-symbols-outlined">person</span>
        <span class="text-[10px] font-bold mt-0.5">بياناتي</span>
    </a>
</nav>

<footer class="bg-[#070B19] py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-12 text-center text-xs text-slate-500">
        <span class="text-base font-black text-[#F4D068] tracking-wider block mb-1">BAYATY</span>
        <p>© 2026 إسكان طلاب بني سويف — ملف الطالب الشخصي</p>
    </div>
</footer>

<div id="imgModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.9); z-index:9999; align-items:center; justify-content:center; padding:20px;" onclick="closeImgModal()">
    <div style="position:relative; max-width:90vw; max-height:90vh;" onclick="event.stopPropagation()">
        <button onclick="closeImgModal()" style="position:absolute; top:-40px; right:0; width:36px; height:36px; border-radius:50%; background:#ef4444; color:white; border:none; font-size:1.2rem; cursor:pointer; z-index:10;">✕</button>
        <img id="imgModalSrc" src="" style="max-width:90vw; max-height:85vh; border-radius:12px; border:2px solid #F4D068;">
        <p id="imgModalTitle" style="text-align:center; color:white; margin-top:10px; font-weight:600;"></p>
    </div>
</div>
<script src="../js/home-js/profile-stu.js"></script>


</body>
</html>