<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login-owner.css') }}">

    <title>تسجيل بيانات صاحب السكن - بيتي</title>

</head>
<body>

    <div class="form-container">
        <div class="form-header">
            <h2>تسجيل بيانات <span>صاحب السكن</span></h2>
            <p>يرجى إدخال البيانات المطلوبة لتهيئة حسابك</p>
        </div>

        <form action="./owner/add-property.html" method="post" id="ownerForm">
            <div class="form-grid">

                <div class="form-group">
                    <label for="firstName">الاسم الأول</label>
                    <input type="text" id="firstName" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="lastName">اسم العائلة</label>
                    <input type="text" id="lastName" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" minlength="6" required>
                </div>

                <div class="form-group">
                    <label for="phone">رقم الهاتف الشخصي</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="whatsapp">رقم الواتساب</label>
                    <input type="tel" id="whatsapp" name="whatsapp" required>
                </div>

                <div class="form-group">
                    <label for="gender">الجنس</label>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>اختر الجنس</option>
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="propertyType">نوع العقار</label>
                    <select id="propertyType" name="property_type" required>
                        <option value="" disabled selected>اختر نوع العقار</option>
                        <option value="apartment">شقة</option>
                        <option value="room">غرفة</option>
                        <option value="studio">استوديو</option>
                        <option value="villa">فيلا</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="address">عنوان السكن بالتفصيل</label>
                    <textarea id="address" name="address" required placeholder="المحافظة - المنطقة - الشارع - رقم العقار"></textarea>
                </div>

                <div class="form-group full-width">
                    <label>رفع بطاقة الرقم القومي</label>
                    <div class="file-upload">
                        <span id="txtId">🆔 اضغط لرفع الملف</span>
                        <input type="file" id="natId" name="nat_id" accept="image/*" required onchange="document.getElementById('txtId').innerText = '✓ تم اختيار الملف'">
                    </div>
                </div>

            </div>

            <button type="submit" class="submit-btn">إتمام التسجيل والدخول</button>
        </form>
    </div>
<script src="{{ asset('js/auth/register/owner-login.js') }}"></script>
</body>
</html>
