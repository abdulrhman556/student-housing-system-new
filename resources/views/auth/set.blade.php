<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/set.css') }}">
    <title>بيتي - Bayaty</title>

</head>
<body>

    <!-- الهيدر مع الكلمة التعريفية للمشروع -->
    <header>
        <h1>بيتي <span>Bayaty</span></h1>
        <p>منصتك الذكية والموثوقة لتسهيل رحلة البحث عن السكن الجامعي المثالي، والربط المباشر بين الطلاب وأصحاب العقارات بأمان وسهولة المطلقة.</p>
    </header>

    <!-- حاوية الكروت -->
    <main class="cards-container">

        <!-- كارد الطلاب -->
        <div class="card" id="studentCard">
            <div class="card-content">
                <h2>تسجيل للطلاب</h2>
                <p>ابحث عن سكنك القادم بمميزات تناسب دراستك وميزانيتك، وتواصل مباشرة مع الملاك دون تعقيد.</p>
            </div>
            <button class="card-btn">دخول الطلاب</button>
        </div>

        <!-- كارد صاحب السكن -->
        <div class="card" id="ownerCard">
            <div class="card-content">
                <h2>صاحب سكن</h2>
                <p>أعلن عن عقارك أو غرفتك الشاغرة، وأدر حجوزاتك بسهولة مع وصول مباشر لآلاف الطلاب بحثاً عن سكن.</p>
            </div>
            <button class="card-btn">دخول الملاك</button>
        </div>

    </main>

    <!-- التوجيه للسجل العام -->
    <footer class="footer-login">
        <p>ليس لديك حساب بعد؟ <a href="#" id="registerLink">سجل حساباً جديداً من هنا</a></p>
    </footer>


    <script>
        const studentRegistrationUrl = "{{ route('student.registration') }}";
        const ownerRegistrationUrl = "{{ route('owner.registration') }}";
        const loginUrl = "{{ route('Login.enter') }}";
    </script>
    <script src="{{ asset('js/set.js') }}"></script>


</body>
</html>
