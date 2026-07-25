
        // دالة الانتقال لكارد الطلاب عند الضغط في أي مكان داخل الكارد
document.getElementById('studentCard').addEventListener('click', function() {
    window.location.href = studentRegistrationUrl;
});

        // دالة الانتقال لكارد أصحاب السكن
document.getElementById('ownerCard').addEventListener('click', function() {
    window.location.href = ownerRegistrationUrl;
});

        // دالة الانتقال لصفحة التسجيل
document.getElementById('registerLink').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = loginUrl;
});

