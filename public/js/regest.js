
        function showError(msg) {
            const el = document.getElementById('errorMsg');
            el.textContent = msg;
            el.classList.add('show');
            setTimeout(function() { el.classList.remove('show'); }, 4000);
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'جاري التحقق...';

            try {
                const emailInput = document.getElementById('email').value.trim().toLowerCase();
                const passwordInput = document.getElementById('password').value;

                if (!emailInput || !passwordInput) {
                    showError('❌ يرجى ملء جميع الحقول');
                    btn.disabled = false;
                    btn.textContent = 'تسجيل الدخول';
                    return;
                }

                // Check banned
                var bannedUsers = [];
                try { bannedUsers = JSON.parse(localStorage.getItem('bayaty_banned_users')) || []; } catch(e) {}
                if (Array.isArray(bannedUsers) && bannedUsers.includes(emailInput)) {
                    showError('🚫 هذا الحساب محظور! تواصل مع الإدارة.');
                    btn.disabled = false;
                    btn.textContent = 'تسجيل الدخول';
                    return;
                }

                // Search in students array
                var students = [];
                try { students = JSON.parse(localStorage.getItem('bayaty_students')) || []; } catch(e) {}
                var matchedStudent = null;

                if (Array.isArray(students)) {
                    for (var i = 0; i < students.length; i++) {
                        if (students[i] && students[i].email && students[i].email.toLowerCase() === emailInput) {
                            matchedStudent = students[i];
                            break;
                        }
                    }
                }

                // Fallback to individual keys
                if (!matchedStudent) {
                    var legacyStuEmail = localStorage.getItem('stu_email');
                    if (legacyStuEmail && legacyStuEmail.toLowerCase() === emailInput) {
                        matchedStudent = {
                            firstName: localStorage.getItem('stu_firstName') || '',
                            lastName: localStorage.getItem('stu_lastName') || '',
                            email: legacyStuEmail,
                            password: localStorage.getItem('stu_password') || '',
                            phone: localStorage.getItem('stu_phone') || '',
                            gender: localStorage.getItem('stu_gender') || '',
                            university: localStorage.getItem('stu_university') || '',
                            academicYear: localStorage.getItem('stu_year') || '',
                            natIdFile: localStorage.getItem('stu_natIdFile') || ''
                        };
                    }
                }

                if (matchedStudent) {
                    if (matchedStudent.password !== passwordInput) {
                        showError('❌ كلمة المرور غير صحيحة!');
                        btn.disabled = false;
                        btn.textContent = 'تسجيل الدخول';
                        return;
                    }

                    // Save current user data
                    localStorage.setItem('bayaty_current_user', JSON.stringify(matchedStudent));
                    localStorage.setItem('stu_firstName', matchedStudent.firstName || '');
                    localStorage.setItem('stu_lastName', matchedStudent.lastName || '');
                    localStorage.setItem('stu_email', matchedStudent.email || '');
                    localStorage.setItem('stu_password', matchedStudent.password || '');
                    localStorage.setItem('stu_phone', matchedStudent.phone || '');
                    localStorage.setItem('stu_gender', matchedStudent.gender || '');
                    localStorage.setItem('stu_university', matchedStudent.university || '');
                    localStorage.setItem('stu_year', matchedStudent.academicYear || '');
                    localStorage.setItem('stu_natIdFile', matchedStudent.natIdFile || '');

                    window.location.href = './home/profile-student.html';
                    return;
                }

                // Search in owners array
                var owners = [];
                try { owners = JSON.parse(localStorage.getItem('bayaty_owners')) || []; } catch(e) {}
                var matchedOwner = null;

                if (Array.isArray(owners)) {
                    for (var j = 0; j < owners.length; j++) {
                        if (owners[j] && owners[j].email && owners[j].email.toLowerCase() === emailInput) {
                            matchedOwner = owners[j];
                            break;
                        }
                    }
                }

                // Fallback to individual keys
                if (!matchedOwner) {
                    var legacyOwnEmail = localStorage.getItem('owner_email');
                    if (legacyOwnEmail && legacyOwnEmail.toLowerCase() === emailInput) {
                        matchedOwner = {
                            firstName: localStorage.getItem('owner_firstName') || '',
                            lastName: localStorage.getItem('owner_lastName') || '',
                            email: legacyOwnEmail,
                            password: localStorage.getItem('owner_password') || '',
                            phone: localStorage.getItem('owner_phone') || '',
                            whatsapp: localStorage.getItem('owner_whatsapp') || '',
                            gender: localStorage.getItem('owner_gender') || '',
                            propertyType: localStorage.getItem('owner_propertyType') || '',
                            address: localStorage.getItem('owner_address') || '',
                            natIdFile: localStorage.getItem('owner_natIdFile') || ''
                        };
                    }
                }

                if (matchedOwner) {
                    if (matchedOwner.password !== passwordInput) {
                        showError('❌ كلمة المرور غير صحيحة!');
                        btn.disabled = false;
                        btn.textContent = 'تسجيل الدخول';
                        return;
                    }

                    // Save current user data
                    localStorage.setItem('bayaty_current_user', JSON.stringify(matchedOwner));
                    localStorage.setItem('owner_firstName', matchedOwner.firstName || '');
                    localStorage.setItem('owner_lastName', matchedOwner.lastName || '');
                    localStorage.setItem('owner_email', matchedOwner.email || '');
                    localStorage.setItem('owner_password', matchedOwner.password || '');
                    localStorage.setItem('owner_phone', matchedOwner.phone || '');
                    localStorage.setItem('owner_whatsapp', matchedOwner.whatsapp || '');
                    localStorage.setItem('owner_gender', matchedOwner.gender || '');
                    localStorage.setItem('owner_propertyType', matchedOwner.propertyType || '');
                    localStorage.setItem('owner_address', matchedOwner.address || '');
                    localStorage.setItem('owner_natIdFile', matchedOwner.natIdFile || '');

                    localStorage.setItem('userData', JSON.stringify({
                        id: 'owner_' + Date.now(),
                        name: (matchedOwner.firstName + ' ' + matchedOwner.lastName).trim() || 'مالك العقار',
                        firstName: matchedOwner.firstName || '',
                        lastName: matchedOwner.lastName || '',
                        email: matchedOwner.email || '',
                        phone: matchedOwner.phone || '',
                        whatsapp: matchedOwner.whatsapp || '',
                        gender: matchedOwner.gender || '',
                        address: matchedOwner.address || '',
                        role: 'owner'
                    }));

                    window.location.href = './owner/owner-profile.html';
                    return;
                }

                // === الفحص الإضافي لصفحة الإدارة (mangment-xx.html) ===
                var managementPersons = [];
                try { managementPersons = JSON.parse(localStorage.getItem('my_persons_list')) || []; } catch(e) {}
                var matchedPerson = null;

                if (Array.isArray(managementPersons)) {
                    for (var k = 0; k < managementPersons.length; k++) {
                        if (managementPersons[k] && managementPersons[k].email && managementPersons[k].email.toLowerCase() === emailInput) {
                            matchedPerson = managementPersons[k];
                            break;
                        }
                    }
                }

                if (matchedPerson) {
                    if (matchedPerson.password !== passwordInput) {
                        showError('❌ كلمة المرور غير صحيحة!');
                        btn.disabled = false;
                        btn.textContent = 'تسجيل الدخول';
                        return;
                    }

                    // حفظ الجلسة الحالية للشخص المسجل من صفحة الإدارة
                    localStorage.setItem('bayaty_current_user', JSON.stringify({
                        id: 'managed_' + Date.now(),
                        name: matchedPerson.name || 'مستخدم مسجل',
                        email: matchedPerson.email,
                        phone: matchedPerson.phone || '',
                        userType: 'management_user'
                    }));

                    // التوجيه مباشرة لصفحة الإدارة
                    window.location.href = './mangment/mangment-xx.html';
                    return;
                }

                showError('❌ البريد الإلكتروني غير مسجل في النظام!');
                btn.disabled = false;
                btn.textContent = 'تسجيل الدخول';

            } catch (err) {
                console.error("Login Error: ", err);
                showError('❌ خطأ في النظام! يرجى تحديث الصفحة.');
                btn.disabled = false;
                btn.textContent = 'تسجيل الدخول';
            }
        });
