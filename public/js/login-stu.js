
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            e.preventDefault(); // إيقاف الإرسال مؤقتاً لحين حفظ الصورة

            localStorage.setItem('stu_firstName', document.getElementById('firstName').value);
            localStorage.setItem('stu_lastName', document.getElementById('lastName').value);
            localStorage.setItem('stu_email', document.getElementById('email').value);
            localStorage.setItem('stu_password', document.getElementById('password').value); // حفظ الباسورد هنا
            localStorage.setItem('stu_phone', document.getElementById('phone').value);
            localStorage.setItem('stu_gender', document.getElementById('gender').value);
            localStorage.setItem('stu_university', document.getElementById('university').value);
            localStorage.setItem('stu_year', document.getElementById('academicYear').value);

            // حفظ صورة البطاقة كـ Base64
            const natIdInput = document.getElementById('natId');
            if (natIdInput.files && natIdInput.files[0]) {
                const reader = new FileReader();
                window.location.href = './home/home.html';
                reader.onload = function(event) {
                    localStorage.setItem('stu_natIdFile', event.target.result);
                    // نلغي الكارنيه تماماً من الـ storage لتجنب بقايا داتا قديمة
                    localStorage.removeItem('stu_uniCardFile'); 
                    document.getElementById('studentForm').submit();
                    window.location.href = './home/home.html';  // إتمام الإرسال الفعلي بعد الحفظ
                };
                reader.readAsDataURL(natIdInput.files[0]);
            } else {
                localStorage.removeItem('stu_natIdFile');
                document.getElementById('studentForm').submit();
            }
        });
