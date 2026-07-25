<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login-stu.css">
    <title>تسجيل بيانات الطالب - بيتي</title>
   
</head>
<body>

    <div class="form-container">
        <div class="form-header">
            <h2>تسجيل بيانات <span>الطلاب</span></h2>
            <p>يرجى إدخال البيانات المطلوبة لتهيئة حسابك</p>
        </div>

        <form action="./home/home.html" method="GET" id="studentForm">
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
                    <label for="phone">رقم الهاتف</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="gender">الجنس (النوع)</label>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>اختر الجنس</option>
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="university">الجامعة / المعهد</label>
                    <input type="text" id="university" name="university" required>
                </div>

                <div class="form-group">
                    <label for="academicYear">الفرقة الدراسية</label>
                    <select id="academicYear" name="academic_year" required>
                        <option value="" disabled selected>اختر الفرقة</option>
                        <option value="1">الفرقة الأولى</option>
                        <option value="2">الفرقة الثانية</option>
                        <option value="3">الفرقة الثالثة</option>
                        <option value="4">الفرقة الرابعة</option>
                    </select>
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

   <script src="./js/login-stu.js"></script>
</body>
</html>