 <script>
        document.getElementById('ownerForm').addEventListener('submit', function(e) {
            e.preventDefault(); // إيقاف الإرسال مؤقتاً لحين حفظ الصورة

            localStorage.setItem('owner_firstName', document.getElementById('firstName').value);
            localStorage.setItem('owner_lastName', document.getElementById('lastName').value);
            localStorage.setItem('owner_email', document.getElementById('email').value);
            localStorage.setItem('owner_password', document.getElementById('password').value); // حفظ الباسورد هنا
            localStorage.setItem('owner_phone', document.getElementById('phone').value);
            localStorage.setItem('owner_whatsapp', document.getElementById('whatsapp').value);
            localStorage.setItem('owner_gender', document.getElementById('gender').value);
            localStorage.setItem('owner_propertyType', document.getElementById('propertyType').value);
            localStorage.setItem('owner_address', document.getElementById('address').value);

            // حفظ صورة البطاقة كـ Base64
            const natIdInput = document.getElementById('natId');
            if (natIdInput.files && natIdInput.files[0]) {
                const reader = new FileReader();
                window.location.href = './owner/add-property.html';
                reader.onload = function(event) {
                    localStorage.setItem('owner_natIdFile', event.target.result);
                    document.getElementById('ownerForm').submit(); // إتمام الإرسال الفعلي بعد الحفظ
                };
                reader.readAsDataURL(natIdInput.files[0]);
            } else {
                localStorage.removeItem('owner_natIdFile');
                document.getElementById('ownerForm').submit();
            }
        });
    </script>