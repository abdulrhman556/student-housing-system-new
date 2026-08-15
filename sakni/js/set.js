
        // دالة الانتقال لكارد الطلاب عند الضغط في أي مكان داخل الكارد
        document.getElementById('studentCard').addEventListener('click', function() {
            window.location.href = 'login-student.html';
        });

        // دالة الانتقال لكارد أصحاب السكن
        document.getElementById('ownerCard').addEventListener('click', function() {
            window.location.href = 'login-owner.html';
        });

        // دالة الانتقال لصفحة التسجيل
        document.getElementById('registerLink').addEventListener('click', function(e) {
            e.preventDefault(); 
            window.location.href = 'regest.html';
        });

