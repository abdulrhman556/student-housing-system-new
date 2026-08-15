document.getElementById('ownerForm').addEventListener('submit', function(e) {
    // التحقق من الرقم القومي قبل الإرسال
    const nationalId = document.getElementById('nationalId').value;
    if (nationalId.length !== 14 || !/^\d{14}$/.test(nationalId)) {
        e.preventDefault();
        alert('الرقم القومي يجب أن يكون 14 رقم بالضبط');
        return;
    }

    // حفظ البيانات الأساسية
    localStorage.setItem('owner_firstName', document.getElementById('firstName').value);
    localStorage.setItem('owner_lastName', document.getElementById('lastName').value);
    localStorage.setItem('owner_email', document.getElementById('email').value);
    localStorage.setItem('owner_password', document.getElementById('password').value);
    localStorage.setItem('owner_phone', document.getElementById('phone').value);
    localStorage.setItem('owner_whatsapp', document.getElementById('whatsapp').value);
    localStorage.setItem('owner_gender', document.getElementById('gender').value);
    localStorage.setItem('owner_nationalId', document.getElementById('nationalId').value);
    localStorage.setItem('owner_address', document.getElementById('address').value);

    // تنظيف بيانات قديمة
    localStorage.removeItem('owner_propertyType');

    // حفظ صورة البطاقة كـ Base64 (اختياري)
    const natIdInput = document.getElementById('natId');
    if (natIdInput.files && natIdInput.files[0]) {
        e.preventDefault();
        const reader = new FileReader();
        reader.onload = function(event) {
            localStorage.setItem('owner_natIdFile', event.target.result);
            document.getElementById('ownerForm').submit();
        };
        reader.readAsDataURL(natIdInput.files[0]);
    } else {
        localStorage.removeItem('owner_natIdFile');
        // الإرسال يتم بشكل طبيعي لأن مفيش صورة تتعملها حفظ
    }
});