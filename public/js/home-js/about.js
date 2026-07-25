
    // دالة التحكم الحركية في الأكورديون (Accordion Switch)
    function toggleFaqItem(button) {
        const item = button.parentElement;
        
        // التحقق مما إذا كان العنصر الحالي مفتوحاً بالفعل لإغلاقه
        const isActive = item.classList.contains("active");
        
        // إغلاق أي عنصر آخر مفتوح للحفاظ على مظهر الصفحة منسق (الاختيار الفندقي الذكي)
        document.querySelectorAll(".faq-item").forEach(el => {
            el.classList.remove("active");
        });
        
        // إذا لم يكن العنصر مفتوحاً، نقوم بفتحه الآن بـ أنيميشن
        if (!isActive) {
            item.classList.add("active");
        }
    }
