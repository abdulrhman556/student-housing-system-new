document.getElementById('studentForm').addEventListener('submit', function(e) {
    // التحقق من الرقم القومي قبل الإرسال
    const nationalId = document.getElementById('nationalId').value;
    if (nationalId.length !== 14 || !/^\d{14}$/.test(nationalId)) {
        e.preventDefault();
        alert('الرقم القومي يجب أن يكون 14 رقم بالضبط');
        return;
    }

    // حفظ البيانات في localStorage
    localStorage.setItem('stu_firstName', document.getElementById('firstName').value);
    localStorage.setItem('stu_lastName', document.getElementById('lastName').value);
    localStorage.setItem('stu_email', document.getElementById('email').value);
    localStorage.setItem('stu_password', document.getElementById('password').value);
    localStorage.setItem('stu_phone', document.getElementById('phone').value);
    localStorage.setItem('stu_gender', document.getElementById('gender').value);
    localStorage.setItem('stu_university', document.getElementById('university').value);
    localStorage.setItem('stu_nationalId', document.getElementById('nationalId').value);

    // تنظيف أي بيانات قديمة
    localStorage.removeItem('stu_year');
    localStorage.removeItem('stu_natIdFile');
    localStorage.removeItem('stu_uniCardFile');

    // الإرسال يتم بشكل طبيعي لأن مفيش صورة تتعملها حفظ
});