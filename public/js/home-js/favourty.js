
    // قاعدة البيانات الاحتياطية المتزامنة مع باقي الصفحات (تستبدل بتحديثات المانجمينت تلقائياً)
    const fallbackProperties = [
        { id: 1, title: "شقة مفروشة بشارع عبد السلام عارف", price: 1500, bedsLeft: 2, distance: 1.5, region: "عبد السلام عارف", gender: "male", img: "https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=500" },
        { id: 2, title: "سكن طالبات النيل الفاخر", price: 1800, bedsLeft: 3, distance: 0.5, region: "شرق النيل", gender: "female", img: "https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?q=80&w=500" },
        { id: 3, title: "غرف شبابية هادئة بحي مقبل", price: 1200, bedsLeft: 1, distance: 3, region: "مقبل", gender: "male", img: "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=500" },
        { id: 4, title: "سكن زهرة الشرق للطالبات", price: 1300, bedsLeft: 4, distance: 1.1, region: "شرق النيل", gender: "female", img: "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=500" },
        { id: 5, title: "شقة مفروشة مكيفة بأرض الحرية", price: 2500, bedsLeft: 5, distance: 4.5, region: "أرض الحرية", gender: "male", img: "https://images.unsplash.com/photo-1536376072261-38c75010e6c9?q=80&w=500" },
        { id: 6, title: "استوديو بنات متميز خلف الاستاد", price: 1600, bedsLeft: 2, distance: 2.2, region: "خلف الاستاد", gender: "female", img: "https://images.unsplash.com/photo-1598928506311-c55ded91a20c?q=80&w=500" }
    ];

    let currentGender = "male";

    document.addEventListener("DOMContentLoaded", () => {
        // 1. مزامنة الجنس للتصنيف التلقائي لمنع تداخل سكن الشباب مع البنات
        const storedGender = localStorage.getItem('stu_gender');
        if (storedGender) currentGender = storedGender;
        
        document.getElementById("genderBadge").innerText = currentGender === "male" ? "سكن ذكور" : "سكن إناث";

        // 2. استدعاء دالة عرض العناصر المفضلة
        renderFavorites();
    });

    // 3. دالة جلب وطباعة عقارات المفضلة ديناميكياً
    function renderFavorites() {
        const grid = document.getElementById("favoritesGrid");
        const emptyBox = document.getElementById("emptyState");
        grid.innerHTML = "";

        // جلب قائمة المعرفات التي نالت إعجاب الطالب من الـ LocalStorage
        let favIds = JSON.parse(localStorage.getItem('bayaty_favorites')) || [];

        // جلب الداتا المحدثة القادمة من الـ Management إن وجدت
        let activePropertiesList = [...fallbackProperties];

        // Read published properties from 'properties' (full data from owner)
        const allProperties = JSON.parse(localStorage.getItem("properties")) || [];
        const publishedProperties = allProperties.filter(p => p.status === 'published' || p.status === 'approved');

        if (publishedProperties.length > 0) {
            activePropertiesList = publishedProperties.map(p => ({
                id: p.id,
                title: p.propertyName || 'عقار بدون اسم',
                price: parseInt(p.priceSingle) || parseInt(p.priceDouble) || parseInt(p.priceTriple) || 0,
                bedsLeft: parseInt(p.rooms) || 1,
                distance: 2.5,
                region: p.area || 'بني سويف',
                gender: 'male',
                services: p.amenities || [],
                img: (p.images && p.images.length > 0) ? p.images[0] : 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=500'
            }));
        }

        // فلترة العقارات: يجب أن يكون المعرف موجوداً في المفضلة ومتوافقاً مع نوع حساب الطالب الحالي
        let favoriteProperties = activePropertiesList.filter(p => favIds.includes(p.id) && p.gender === currentGender);

        // تحديث العداد والنصوص العلويّة
        document.getElementById("favsCountText").innerText = `لديكِ الآن ${favoriteProperties.length} عقار محفوظ في قائمة المفضلة الموثقة بـ بني سويف`;

        // التحقق من حالة الفراغ (Empty State)
        if (favoriteProperties.length === 0) {
            grid.classList.add("hidden");
            emptyBox.classList.remove("hidden");
            return;
        }

        grid.classList.remove("hidden");
        emptyBox.classList.add("hidden");

        // طباعة كروت المفضلة بالتدرج الكحلي-اللبني الفخم داخل الحاوية البيضاء
        favoriteProperties.forEach(prop => {
            const card = document.createElement("div");
            card.className = "property-card-premium bg-gradient-to-br from-[#070B19] to-[#1E293B] rounded-xl border border-[#AA7C11]/30 overflow-hidden shadow-md flex flex-col justify-between text-white animate-slide-up";
            
            card.innerHTML = `
                <div class="img-container h-44 cursor-pointer" onclick="navigateToDetails('${prop.id}')">
                    <img src="${prop.img}" class="img-premium-smooth w-full h-full object-cover" alt="${prop.title}" />
                    <div class="absolute top-3 right-3 bg-[#070B19]/90 backdrop-blur-sm px-2.5 py-1 rounded-lg flex items-center gap-1 border border-[#AA7C11]/30">
                        <span class="text-xs text-[#F4D068] font-bold">${prop.region}</span>
                    </div>
                </div>
                <div class="p-4 flex flex-col justify-between flex-grow">
                    <div class="flex justify-between items-start mb-2 gap-2">
                        <h3 class="font-bold text-sm text-white truncate hover:text-[#F4D068] cursor-pointer" onclick="navigateToDetails('${prop.id}')">${prop.title}</h3>
                        <!-- زر الإزالة الفورية من المفضلة بـ أنيميشن سلس -->
                        <button onclick="removeFromFavorites('${prop.id}', event)" class="btn-interact text-slate-400 hover:text-red-500 p-1.5 rounded-full bg-[#1A264E]/60 border border-[#AA7C11]/20 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-lg liked">favorite</span>
                        </button>
                    </div>
                    <div class="text-[#F4D068] font-black text-base mb-3">${prop.price} <span class="text-xs font-normal text-slate-300">جنيه / شهرياً</span></div>
                    <div class="flex flex-wrap gap-3 mb-4 text-xs text-slate-300 border-t border-slate-700/50 pt-2.5">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm text-[#AA7C11]">bed</span>
                            <span>متبقي ${prop.bedsLeft ?? 0} سرير</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm text-[#AA7C11]">near_me</span>
                            <span>تبعد ${prop.distance} كم</span>
                        </div>
                    </div>
                    <button onclick="navigateToDetails('${prop.id}')" class="w-full text-center bg-[#1A264E] text-[#F4D068] border border-[#AA7C11]/40 py-2 rounded-lg text-xs font-bold hover:bg-[#F4D068] hover:text-[#070B19] transition-all">عرض تفاصيل السكن</button>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    // 4. ميزة الحذف التفاعلي الفوري لعقار معين
    function toggleLike(id, element) {
        let favs = JSON.parse(localStorage.getItem('bayaty_favorites')) || [];
        favs = favs.filter(favId => favId !== id);
        localStorage.setItem('bayaty_favorites', JSON.stringify(favs));
        renderFavorites();
    }

    // دالة مساعدة لمعالجة كليك زر الحذف الفوري
    function removeFromFavorites(id, event) {
        event.stopPropagation(); // منع كليك الكرت من فتح صفحة التفاصيل أثناء الحذف
        let favs = JSON.parse(localStorage.getItem('bayaty_favorites')) || [];
        favs = favs.filter(favId => favId !== id);
        localStorage.setItem('bayaty_favorites', JSON.stringify(favs));
        renderFavorites(); // إعادة بناء الجريد تلقائياً بدون تحديث الصفحة
    }

    // 5. مسح كلي لكافة المفضلات المحفوظة دفعة واحدة
    function clearAllFavorites() {
        if(confirm("هل أنتِ متأكدة من رغبتكِ في مسح كافة العقارات المحفوظة في قائمة المفضلة؟")) {
            localStorage.removeItem('bayaty_favorites');
            renderFavorites();
        }
    }

    function navigateToDetails(propertyId) {
        localStorage.setItem("selected_property_id", propertyId);
        window.location.href = "details.html";
    }
