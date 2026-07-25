<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>الأسئلة الشائعة وعن بيتي | BAYATY</title>
    <link rel="stylesheet" href="{{ asset('css/home-css/about.css') }}">

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
</head>
<body class="bg-[#070B19] text-white min-h-screen flex flex-col justify-between pb-16 md:pb-0">

<!-- TopNavBar -->
<header class="bg-[#0D162F] border-b border-[#AA7C11]/30 sticky top-0 z-50 w-full h-16 flex items-center px-4 md:px-12 shadow-md">
    <div class="flex justify-between items-center w-full max-w-7xl mx-auto">
        <div class="flex items-center gap-8">
            <a href="home.html" class="text-2xl font-black tracking-wider text-[#F4D068]">BAYATY <span class="text-white text-sm font-normal">بيتي</span></a>
            <nav class="hidden md:flex gap-6">
                <a class="text-sm font-semibold text-slate-400 hover:text-[#F4D068] transition-colors" href="home.html">الرئيسية</a>
                <a class="text-sm font-semibold text-slate-400 hover:text-[#F4D068] transition-colors" href="favorites.html">المفضلة ❤️</a>
                <a class="text-sm font-semibold text-slate-400 hover:text-[#F4D068] transition-colors" href="profile.html">بياناتي</a>
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

<!-- Main Container (المساحة البيضاء الشاملة المريحة للعين المتناسقة مع باقي الشاشات) -->
<main class="max-w-4xl mx-auto px-4 md:px-12 py-8 flex-grow bg-white w-full rounded-b-2xl shadow-2xl text-slate-800">
    
    <div class="space-y-10">
        <!-- Section 1: عن منصة بيتي (About Us) -->
        <section class="space-y-4">
            <div class="text-center space-y-2">
                <h1 class="text-2xl font-extrabold text-[#070B19]">منصة بيتي - BAYATY</h1>
                <p class="text-[#AA7C11] text-xs font-bold">المنصة الأولى الموثقة لإسكان الطلاب في محافظة بني سويف</p>
            </div>
            
            <p class="text-slate-600 text-sm leading-relaxed text-center max-w-2xl mx-auto">
                تأسست منصة **بيتي** لحل مشكلة السكن الجامعي والمغتربين لطلاب جامعة بني سويف، الجامعة الأهلية، الجامعة التكنولوجية، والمعاهد العليا الخاصة. نهدف إلى توفير حلقة وصل آمنة ومباشرة بين الطالب وصاحب السكن (Owner) تحت مراجعة وإشراف دقيق من إدارة المنصة (Management) لضمان توفير بيئة معيشية ممتازة ومستقرة للمذاكرة.
            </p>

            <!-- عدادات وإحصائيات ميكرو منسقة -->
            <div class="grid grid-cols-3 gap-4 pt-4 max-w-lg mx-auto text-center">
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                    <div class="text-[#070B19] font-black text-base">+1,500</div>
                    <div class="text-slate-400 text-[10px] mt-0.5">طالب مستفيد</div>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                    <div class="text-[#070B19] font-black text-base">+200</div>
                    <div class="text-slate-400 text-[10px] mt-0.5">سكن موثق</div>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                    <div class="text-[#070B19] font-black text-base">100%</div>
                    <div class="text-slate-400 text-[10px] mt-0.5">أمان ومعاينة</div>
                </div>
            </div>
        </section>

        <!-- Section 2: الأسئلة الشائعة (FAQ Toggles) -->
        <section class="space-y-4">
            <h2 class="text-base font-bold text-[#070B19] flex items-center gap-2 border-b border-slate-100 pb-2">
                <span class="material-symbols-outlined text-[#AA7C11]">help_center</span>
                <span>الأسئلة الشائعة وإجاباتها</span>
            </h2>

            <div class="space-y-3" id="faqAccordion">
                <!-- السؤال 1 -->
                <div class="faq-item border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50 transition-colors">
                    <button onclick="toggleFaqItem(this)" class="w-full p-4 flex items-center justify-between text-right font-bold text-xs sm:text-sm text-slate-800 hover:bg-slate-50">
                        <span>كيف يتم فرز وتصنيف السكن بناءً على بياناتي؟</span>
                        <span class="material-symbols-outlined chevron-icon text-slate-400">expand_more</span>
                    </button>
                    <div class="faq-answer px-4 pb-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        بمجرد تسجيل حسابك وتحديد الجنس (ذكر / أنثى)، تقوم منصة بيتي تلقائياً بفلترة وتصفية العقارات وعرض سكن الطلاب (الذكور) فقط أو سكن الطالبات (الإناث) فقط لمنع حدوث أي تداخل ولتسهيل الوصول للسكن المناسب لك.
                    </div>
                </div>

                <!-- السؤال 2 -->
                <div class="faq-item border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50 transition-colors">
                    <button onclick="toggleFaqItem(this)" class="w-full p-4 flex items-center justify-between text-right font-bold text-xs sm:text-sm text-slate-800 hover:bg-slate-50">
                        <span>ما هي وثوقية السكن المعروض وجدية الإدارة في مراجعته؟</span>
                        <span class="material-symbols-outlined chevron-icon text-slate-400">expand_more</span>
                    </button>
                    <div class="faq-answer px-4 pb-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        كل عقار يظهر في الصفحة الرئيسية يمر بمراحل تتبع صارمة؛ يرفعه صاحب السكن (Owner) أولاً بملفاته وصوره وموقعه الجغرافي، ثم ينتقل إلى لوحة تحكم الإدارة والمراجعة (Management)؛ وبمجرد التأكد من المواصفات ومطابقتها للواقع يتم الموافقة عليه ونشره للطلاب فوراً.
                    </div>
                </div>

                <!-- السؤال 3 -->
                <div class="faq-item border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50 transition-colors">
                    <button onclick="toggleFaqItem(this)" class="w-full p-4 flex items-center justify-between text-right font-bold text-xs sm:text-sm text-slate-800 hover:bg-slate-50">
                        <span>كيف يمكنني حجز السكن وتأكيده ودفع الرسوم؟</span>
                        <span class="material-symbols-outlined chevron-icon text-slate-400">expand_more</span>
                    </button>
                    <div class="faq-answer px-4 pb-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        عند اختيارك للسكن المطلوب ونوع الغرفة، يمكنك الضغط على "تأكيد حجز السكن" لتظهر لك نافذة الدفع التفاعلية؛ حيث يمكنك اختيار الدفع الفوري بكود Fawry، أو تحويل فودافون كاش (Vodafone Cash)، أو نقداً بالتواصل والاتصال المباشر مع إدارة المنصة والمالك للتنسيق والمعاينة الميدانية.
                    </div>
                </div>

                <!-- السؤال 4 -->
                <div class="faq-item border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50 transition-colors">
                    <button onclick="toggleFaqItem(this)" class="w-full p-4 flex items-center justify-between text-right font-bold text-xs sm:text-sm text-slate-800 hover:bg-slate-50">
                        <span>هل يمكنني تتبع سكن معين والعودة إليه لاحقاً؟</span>
                        <span class="material-symbols-outlined chevron-icon text-slate-400">expand_more</span>
                    </button>
                    <div class="faq-answer px-4 pb-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        نعم بالتأكيد! يمكنك النقر على زر أيقونة "القلب" الموجود على أي كارت سكن أو في شاشة التفاصيل، وسيتم إضافته فوراً إلى قائمة "المفضلة ❤️" الخاصة بك، لتتمكن من مقارنته وحذفه أو حجزه في أي وقت لاحق بسهولة.
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- Bottom Navigation Bar (Mobile Only) -->
<nav class="md:hidden fixed bottom-0 w-full z-40 bg-gradient-to-r from-[#070B19] to-[#0D162F] border-t border-[#AA7C11]/30 flex justify-around items-center py-2 rounded-t-xl shadow-2xl">
    <a href="home.html" class="flex flex-col items-center justify-center text-slate-300 hover:text-[#F4D068] p-1">
        <span class="material-symbols-outlined">apartment</span>
        <span class="text-[10px] font-medium mt-0.5">الرئيسية</span>
    </a>
    <a href="favorites.html" class="flex flex-col items-center justify-center text-slate-300 hover:text-[#F4D068] p-1">
        <span class="material-symbols-outlined">favorite</span>
        <span class="text-[10px] font-medium mt-0.5">المفضلة</span>
    </a>
    <a href="profile.html" class="flex flex-col items-center justify-center text-slate-300 hover:text-[#F4D068] p-1">
        <span class="material-symbols-outlined">person</span>
        <span class="text-[10px] font-medium mt-0.5">بياناتي</span>
    </a>
</nav>

<!-- Footer -->
<footer class="bg-[#070B19] py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-12 text-center text-xs text-slate-500">
        <span class="text-base font-black text-[#F4D068] tracking-wider block mb-1">BAYATY</span>
        <p>© 2026 إسكان طلاب بني سويف - شاشة الدعم الفني والمعلومات والأسئلة الشائعة.</p>
    </div>
</footer>
<script src="{{ asset('js/home-js/about.js') }}"></script>

</body>
</html>