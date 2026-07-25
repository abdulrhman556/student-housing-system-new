<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/regest.css">
    <title>تسجيل الدخول - بيتي</title>

<base target="_blank">
</head>
<body>

    <div class="form-container">
        <div class="form-header">
            <div class="logo">بيتي <span>BAYATY</span></div>
            <h2>تسجيل الدخول</h2>
            <p>أدخل بيانات حسابك للمتابعة</p>
        </div>

        <div class="error-msg" id="errorMsg"></div>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" placeholder="example@email.com" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" placeholder="••••••" required autocomplete="current-password">
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">تسجيل الدخول</button>
        </form>

        <div class="links">
            <a href="login-student.html">تسجيل جديد كطالب</a>
            <a href="login-owner.html">تسجيل جديد كصاحب سكن</a>
            <span class="muted">ليس لديك حساب؟ سجل من الأعلى</span>
        </div>
    </div>

    <script src="./js/regest.js"></script>
</body>
</html>
