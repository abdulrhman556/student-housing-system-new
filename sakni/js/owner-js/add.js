/* ============================================
   BAYATY - Add Property JS
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {

    // ===== SIDEBAR =====
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        if (sidebar) sidebar.classList.toggle('open');
        if (overlay) overlay.classList.toggle('active');
    };

    // ===== IMAGE PREVIEW =====
    window.previewImage = function(input, index) {
        const box = document.getElementById('box' + index);
        if (!box || !input.files || !input.files[0]) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            box.innerHTML = '<img src="' + e.target.result + '" alt="صورة عقار">';
            box.classList.add('has-image');
            box.dataset.imageBase64 = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    };

    // ===== PROPERTY TYPE & DYNAMIC PRICING =====
    const propertyTypeSelect = document.getElementById('propertyType');
    const pricingContainer = document.getElementById('pricingContainer');
    const pricingTitle = document.getElementById('pricingTitle');
    const pricingDesc = document.getElementById('pricingDesc');

    const propertyTypeNames = {
        'apartment': 'الشقة',
        'room': 'الغرفة',
        'studio': 'الاستوديو',
        'villa': 'الفيلا',
        'duplex': 'الدوبلكس'
    };

    function updatePricingSection() {
        if (!propertyTypeSelect || !pricingContainer) return;
        const type = propertyTypeSelect.value;

        if (!type) {
            if (pricingTitle) pricingTitle.textContent = 'التسعير';
            if (pricingDesc) pricingDesc.textContent = 'اختر نوع العقار لعرض خيارات التسعير المناسبة';
            pricingContainer.innerHTML = '<p style="color: var(--text-muted); padding: 10px 0;">اختر نوع العقار أولاً</p>';
            return;
        }

        const typeName = propertyTypeNames[type] || 'العقار';

        if (type === 'room') {
            if (pricingTitle) pricingTitle.textContent = 'تسعير سكن الطلاب';
            if (pricingDesc) pricingDesc.textContent = 'حدد عدد السراير ثم أدخل سعر كل سرير';
            pricingContainer.innerHTML = `
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="bedCountInput">عدد السراير للتسعير</label>
                    <input type="number" id="bedCountInput" min="1" max="20" placeholder="مثال: 3">
                    <span class="hint">أدخل عدد السراير لإنشاء حقول التسعير (كل سرير بسعره)</span>
                </div>
                <div id="bedPricesWrapper" style="margin-top: 15px;"></div>
            `;

            const bedCountInput = document.getElementById('bedCountInput');
            if (bedCountInput) {
                bedCountInput.addEventListener('input', function() {
                    const count = parseInt(this.value) || 0;
                    const wrapper = document.getElementById('bedPricesWrapper');
                    if (!wrapper) return;

                    if (count <= 0 || count > 20) {
                        wrapper.innerHTML = '';
                        return;
                    }

                    let html = '<div class="pricing-row">';
                    for (let i = 1; i <= count; i++) {
                        html += `
                            <div class="form-group">
                                <label for="bedPrice_${i}">سعر السرير ${i}</label>
                                <div class="price-input-wrapper">
                                    <input type="number" id="bedPrice_${i}" class="bed-price-input" data-bed="${i}" min="0" placeholder="1500">
                                    <span class="currency">ج.م</span>
                                </div>
                                <span class="hint">سعر السرير رقم ${i} شهرياً</span>
                            </div>
                        `;
                    }
                    html += '</div>';
                    wrapper.innerHTML = html;
                });
            }
        } else {
            if (pricingTitle) pricingTitle.textContent = 'تسعير ' + typeName;
            if (pricingDesc) pricingDesc.textContent = 'أدخل سعر الإيجار الشهري لـ ' + typeName;
            pricingContainer.innerHTML = `
                <div class="form-group">
                    <label for="propertyPrice">السعر الشهري</label>
                    <div class="price-input-wrapper">
                        <input type="number" id="propertyPrice" min="0" placeholder="2000">
                        <span class="currency">ج.م</span>
                    </div>
                    <span class="hint">سعر الإيجار الشهري بالجنيه المصري</span>
                </div>
            `;
        }
    }

    if (propertyTypeSelect) {
        propertyTypeSelect.addEventListener('change', updatePricingSection);
    }

    // ===== MODALS =====
    window.closeModal = function(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('active');
    };

    window.openModal = function(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('active');
    };

    // ===== FORM SUBMISSION =====
    const form = document.getElementById('propertyForm');
    const publishBtn = document.getElementById('publishBtn');
    const draftBtn = document.getElementById('draftBtn');

    // Handle PUBLISH button
    if (publishBtn) {
        publishBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            submitProperty('pending');
        });
    }

    // Handle DRAFT button
    if (draftBtn) {
        draftBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const hasImages = [0,1,2,3,4].some(i => {
                const box = document.getElementById('box' + i);
                return box && box.classList.contains('has-image');
            });

            if (hasImages) {
                openModal('imageModal');
            } else {
                submitProperty('draft');
            }
        });
    }

    window.ignoreImages = function() {
        closeModal('imageModal');
        [0,1,2,3,4].forEach(i => {
            const box = document.getElementById('box' + i);
            if (box && box.classList.contains('has-image')) {
                box.classList.remove('has-image');
                delete box.dataset.imageBase64;
                const label = i === 0 ? '<span class="upload-label">أضف صورة رئيسية</span>' : '';
                const inputId = i === 0 ? 'mainPhoto' : 'photo' + (i + 1);
                box.innerHTML = `
                    <span class="plus-icon">+</span>
                    ${label}
                    <input type="file" id="${inputId}" accept="image/*" onchange="previewImage(this, ${i})">
                `;
            }
        });
        submitProperty('draft');
    };

    window.saveDraftWithImages = function() {
        closeModal('imageModal');
        submitProperty('draft');
    };

    // ===== MAIN SUBMIT FUNCTION =====
    function submitProperty(status) {
        const formData = new FormData(form);
        const propertyId = 'prop_' + Date.now();

        // Collect images
        const images = [];
        [0,1,2,3,4].forEach(i => {
            const box = document.getElementById('box' + i);
            if (box && box.dataset.imageBase64) {
                images.push({
                    index: i,
                    base64: box.dataset.imageBase64,
                    isMain: i === 0
                });
            }
        });

        // Collect pricing based on property type
        const propType = formData.get('property_type');
        let pricing = {};

        if (propType === 'room') {
            const bedCountInput = document.getElementById('bedCountInput');
            const bedCount = bedCountInput ? parseInt(bedCountInput.value) || 0 : 0;
            const bedPrices = [];

            for (let i = 1; i <= bedCount; i++) {
                const input = document.getElementById('bedPrice_' + i);
                bedPrices.push({
                    bedNumber: i,
                    price: input ? input.value : ''
                });
            }

            pricing = {
                mode: 'per_bed',
                bedCount: bedCount,
                bedPrices: bedPrices
            };
        } else {
            const priceInput = document.getElementById('propertyPrice');
            pricing = {
                mode: 'single',
                price: priceInput ? priceInput.value : ''
            };
        }

        // Collect amenities
        const amenities = [];
        const amenityCheckboxes = form.querySelectorAll('input[name="amenities"]:checked');
        amenityCheckboxes.forEach(cb => amenities.push(cb.value));

        // Build property object
        const property = {
            id: propertyId,
            status: status,
            createdAt: new Date().toISOString(),
            updatedAt: new Date().toISOString(),

            name: formData.get('property_name') || '',
            images: images,

            city: formData.get('city') || '',
            area: formData.get('area') || '',

            type: propType || '',
            typeLabel: propertyTypeNames[propType] || '',
            rooms: formData.get('rooms') || '',
            beds: formData.get('beds') || '',
            bathrooms: formData.get('bathrooms') || '',
            floor: formData.get('floor') || '',

            gender: formData.get('gender') || 'male',
            genderLabel: getGenderLabel(formData.get('gender')),

            pricing: pricing,

            amenities: amenities,
            amenitiesLabels: amenities.map(getAmenityLabel),

            description: formData.get('description') || ''
        };

        console.log('Property data:', property);

        // Save to both storages
        saveToStorage('ownerProperties', property);
        saveToStorage('managementProperties', property);

        // Show success
        showSuccessModal(status);

        // Reset form
        setTimeout(() => {
            form.reset();
            [0,1,2,3,4].forEach(i => {
                const box = document.getElementById('box' + i);
                if (box) {
                    box.classList.remove('has-image');
                    delete box.dataset.imageBase64;
                    const label = i === 0 ? '<span class="upload-label">أضف صورة رئيسية</span>' : '';
                    const inputId = i === 0 ? 'mainPhoto' : 'photo' + (i + 1);
                    box.innerHTML = `
                        <span class="plus-icon">+</span>
                        ${label}
                        <input type="file" id="${inputId}" accept="image/*" onchange="previewImage(this, ${i})">
                    `;
                }
            });
            updatePricingSection();
        }, 1500);
    }

    function saveToStorage(key, property) {
        let items = [];
        try {
            const existing = localStorage.getItem(key);
            if (existing) {
                items = JSON.parse(existing);
            }
        } catch (e) {
            console.error('Error reading localStorage:', e);
        }
        items.push(property);
        localStorage.setItem(key, JSON.stringify(items));
        console.log('Saved to ' + key + ':', property.id);
    }

    function getGenderLabel(gender) {
        const labels = { 'male': 'طلاب', 'female': 'طالبات', 'mixed': 'مشترك' };
        return labels[gender] || gender;
    }

    function getAmenityLabel(value) {
        const labels = {
            'wifi': 'واي فاي', 'ac': 'تكييف', 'heater': 'سخان مياه',
            'fridge': 'ثلاجة', 'washing': 'غسالة', 'tv': 'تلفزيون',
            'furniture': 'مفروشة', 'parking': 'موقف سيارات'
        };
        return labels[value] || value;
    }

    function showSuccessModal(status) {
        const title = document.getElementById('successTitle');
        const msg = document.getElementById('successMessage');

        if (status === 'pending') {
            if (title) title.textContent = '✅ تم إرسال العقار';
            if (msg) msg.textContent = 'تم إرسال عقارك للمراجعة، سيتم نشره بعد موافقة الإدارة';
        } else {
            if (title) title.textContent = '✅ تم حفظ المسودة';
            if (msg) msg.textContent = 'تم حفظ العقار كمسودة بنجاح، يمكنك متابعته لاحقاً';
        }

        openModal('successModal');
    }

    window.goToProperties = function() {
        window.location.href = 'owner-buld.html';
    };

    // Init
    updatePricingSection();

}); // end DOMContentLoaded