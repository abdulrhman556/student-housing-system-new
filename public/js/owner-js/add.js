
        // ===== GLOBAL VARIABLES =====
        let uploadedImages = ['', '', '', '', ''];
        let editingPropertyId = null;
        let currentUserId = null;
        let currentUserName = 'مالك العقار';

        // ===== USER DATA FROM LOGIN =====
        function loadUserData() {
            let userData = JSON.parse(localStorage.getItem('userData')) || {};

            // Create stable ID if not exists and SAVE it
            if (!userData.id) {
                userData.id = 'owner_' + Date.now();
                localStorage.setItem('userData', JSON.stringify(userData));
            }

            currentUserId = userData.id;
            if (userData.name) {
                currentUserName = userData.name;
                document.getElementById('userName').textContent = userData.name;
                document.getElementById('userAvatar').textContent = userData.name.charAt(0);
            }
        }
        loadUserData();

        // ===== CHECK EDIT MODE =====
        function checkEditMode() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');

            if (editId) {
                const properties = JSON.parse(localStorage.getItem('properties')) || [];
                const prop = properties.find(p => p.id === editId);

                if (prop && prop.ownerId === currentUserId) {
                    editingPropertyId = editId;
                    fillFormWithProperty(prop);
                    document.querySelector('.page-header h1').innerHTML = 'تعديل عقار <span>' + prop.propertyName + '</span>';
                }
            }
        }

        function fillFormWithProperty(prop) {
            document.getElementById('propertyName').value = prop.propertyName || '';
            document.getElementById('mapLink').value = prop.mapLink || '';
            document.getElementById('city').value = prop.city || '';
            document.getElementById('area').value = prop.area || '';
            document.getElementById('propertyType').value = prop.propertyType || '';
            document.getElementById('rooms').value = prop.rooms || '';
            document.getElementById('beds').value = prop.beds || '';
            document.getElementById('bathrooms').value = prop.bathrooms || '';
            document.getElementById('floor').value = prop.floor || '';
            document.getElementById('priceSingle').value = prop.priceSingle || '';
            document.getElementById('priceDouble').value = prop.priceDouble || '';
            document.getElementById('priceTriple').value = prop.priceTriple || '';
            document.getElementById('contactPhone').value = prop.contactPhone || '';
            document.getElementById('contactWhatsapp').value = prop.contactWhatsapp || '';
            document.getElementById('contactEmail').value = prop.contactEmail || '';
            document.getElementById('description').value = prop.description || '';

            // Set gender radio
            if (prop.gender) {
                const genderRadio = document.querySelector('input[name="gender"][value="' + prop.gender + '"]');
                if (genderRadio) genderRadio.checked = true;
            }

            // Check amenities
            if (prop.amenities) {
                document.querySelectorAll('input[name="amenities"]').forEach(cb => {
                    cb.checked = prop.amenities.includes(cb.value);
                });
            }

            // Load images
            if (prop.images && prop.images.length > 0) {
                prop.images.forEach((imgSrc, index) => {
                    if (imgSrc && index < 5) {
                        uploadedImages[index] = imgSrc;
                        const box = document.getElementById('box' + index);
                        box.innerHTML = '<img src="' + imgSrc + '" alt="Preview">';
                        box.classList.add('has-image');
                        // Re-add the input
                        const inputIds = ['mainPhoto', 'photo2', 'photo3', 'photo4', 'photo5'];
                        const input = document.createElement('input');
                        input.type = 'file';
                        input.id = inputIds[index];
                        input.accept = 'image/*';
                        input.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;';
                        input.onchange = function() { previewImage(this, index); };
                        box.appendChild(input);
                    }
                });
            }
        }

        checkEditMode();

        // ===== SIDEBAR TOGGLE =====
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        // ===== IMAGE PREVIEW =====
        function previewImage(input, index) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedImages[index] = e.target.result;
                    const box = document.getElementById('box' + index);
                    box.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                    box.classList.add('has-image');
                    // Re-add the input
                    const inputIds = ['mainPhoto', 'photo2', 'photo3', 'photo4', 'photo5'];
                    const newInput = document.createElement('input');
                    newInput.type = 'file';
                    newInput.id = inputIds[index];
                    newInput.accept = 'image/*';
                    newInput.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;';
                    newInput.onchange = function() { previewImage(this, index); };
                    box.appendChild(newInput);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearPreviews() {
            uploadedImages = ['', '', '', '', ''];
            for (let i = 0; i < 5; i++) {
                const box = document.getElementById('box' + i);
                box.classList.remove('has-image');
                const inputIds = ['mainPhoto', 'photo2', 'photo3', 'photo4', 'photo5'];
                const labels = ['أضف صورة رئيسية', '', '', '', ''];
                box.innerHTML = '<span class="plus-icon">+</span>' + 
                    (i === 0 ? '<span class="upload-label">' + labels[i] + '</span>' : '') +
                    '<input type="file" id="' + inputIds[i] + '" accept="image/*" style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;" onchange="previewImage(this, ' + i + ')">';
            }
        }

        function hasImages() {
            return uploadedImages.some(img => img !== '');
        }

        // ===== MODAL FUNCTIONS =====
        function showModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function showSuccess(title, message) {
            document.getElementById('successTitle').textContent = title;
            document.getElementById('successMessage').textContent = message;
            showModal('successModal');
        }

        function goToProperties() {
            window.location.href = 'owner-buld.html';
        }

        // ===== DRAFT BUTTON =====
        document.getElementById('draftBtn').addEventListener('click', function() {
            if (hasImages()) {
                showModal('imageModal');
            } else {
                saveProperty('draft');
            }
        });

        function ignoreImages() {
            closeModal('imageModal');
            uploadedImages = ['', '', '', '', ''];
            clearPreviews();
            saveProperty('draft');
        }

        function saveDraftWithImages() {
            closeModal('imageModal');
            saveProperty('draft');
        }

        // ===== FORM SUBMIT =====
        document.getElementById('propertyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveProperty('pending');
        });

        // ===== SAVE PROPERTY =====
        function saveProperty(status) {
            console.log('[BAYATY] saveProperty called with status:', status);
            const form = document.getElementById('propertyForm');

            // Get amenities
            const amenities = [];
            document.querySelectorAll('input[name="amenities"]:checked').forEach(cb => {
                amenities.push(cb.value);
            });

            // Get gender
            const genderRadio = document.querySelector('input[name="gender"]:checked');
            const gender = genderRadio ? genderRadio.value : 'male';

            // Filter out empty images
            const images = uploadedImages.filter(img => img !== '');

            // Create property object
            const property = {
                id: editingPropertyId || 'prop_' + Date.now(),
                ownerId: currentUserId,
                ownerName: currentUserName,
                propertyName: document.getElementById('propertyName').value || 'عقار بدون اسم',
                propertyType: document.getElementById('propertyType').value,
                city: document.getElementById('city').value,
                area: document.getElementById('area').value,
                mapLink: document.getElementById('mapLink').value,
                rooms: document.getElementById('rooms').value,
                beds: document.getElementById('beds').value,
                bathrooms: document.getElementById('bathrooms').value,
                floor: document.getElementById('floor').value,
                gender: gender,
                priceSingle: document.getElementById('priceSingle').value,
                priceDouble: document.getElementById('priceDouble').value,
                priceTriple: document.getElementById('priceTriple').value,
                amenities: amenities,
                contactPhone: document.getElementById('contactPhone').value,
                contactWhatsapp: document.getElementById('contactWhatsapp').value,
                contactEmail: document.getElementById('contactEmail').value,
                description: document.getElementById('description').value,
                images: images,
                status: status,
                createdAt: editingPropertyId ? undefined : new Date().toISOString(),
                updatedAt: new Date().toISOString()
            };

            // Save to localStorage
            let properties = JSON.parse(localStorage.getItem('properties')) || [];

            if (editingPropertyId) {
                // Update existing
                const index = properties.findIndex(p => p.id === editingPropertyId);
                if (index !== -1) {
                    property.createdAt = properties[index].createdAt;
                    properties[index] = property;
                } else {
                    properties.push(property);
                }
            } else {
                properties.push(property);
            }

            console.log('[BAYATY] Saving to localStorage, properties count:', properties.length);
            localStorage.setItem('properties', JSON.stringify(properties));

            // Handle management queue
            let managementQueue = JSON.parse(localStorage.getItem('managementQueue')) || [];

            // Remove old entry if exists
            managementQueue = managementQueue.filter(q => q.propertyId !== property.id);

            if (status === 'pending') {
                managementQueue.push({
                    propertyId: property.id,
                    ownerId: currentUserId,
                    ownerName: currentUserName,
                    propertyName: property.propertyName,
                    submittedAt: new Date().toISOString(),
                    status: 'pending'
                });
                localStorage.setItem('managementQueue', JSON.stringify(managementQueue));

                showSuccess(
                    '⏳ تحت المراجعة',
                    'تم إرسال العقار "' + property.propertyName + '" للمراجعة. سيتم نشره بعد الموافقة من الإدارة.'
                );
            } else {
                showSuccess(
                    '✅ تم الحفظ',
                    'تم حفظ العقار "' + property.propertyName + '" كمسودة. يمكنك تعديله لاحقاً ورفعه للمراجعة.'
                );
            }

            // Clear edit mode
            editingPropertyId = null;
        }
