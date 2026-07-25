
    // ===== AMENITIES LABELS =====
    const amenityLabels = {
        'wifi': '📶 واي فاي',
        'ac': '❄️ تكييف',
        'heater': '🔥 سخان مياه',
        'fridge': '🧊 ثلاجة',
        'washing': '👕 غسالة',
        'tv': '📺 تلفزيون',
        'furniture': '🛋️ مفروشة',
        'parking': '🚗 موقف سيارات',
        'kitchen': '🍳 مطبخ متكامل'
    };

    const typeLabels = {
        'apartment': 'شقة',
        'room': 'غرفة',
        'studio': 'استوديو',
        'villa': 'فيلا',
        'duplex': 'دوبلكس'
    };

    let currentProperty = null;
    let currentPropertyId = null;

    document.addEventListener("DOMContentLoaded", () => {
        // Get property ID from localStorage
        currentPropertyId = localStorage.getItem('selected_property_id');

        if (!currentPropertyId) {
            showNotFound();
            return;
        }

        loadPropertyDetails();
    });

    function loadPropertyDetails() {
        // Read FULL property data from 'properties' (where owner saved everything)
        let allProperties = JSON.parse(localStorage.getItem('properties')) || [];

        // Find the property with matching ID
        currentProperty = allProperties.find(p => p.id == currentPropertyId);

        // Also check if it's in mangment_flutter_properties (for display data)
        let flutterProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
        let flutterProp = flutterProps.find(p => p.id == currentPropertyId);

        if (!currentProperty && !flutterProp) {
            showNotFound();
            return;
        }

        // Merge: full details from 'properties', display data from flutter if needed
        if (currentProperty && flutterProp) {
            // Use flutter data for display fields if missing in full property
            currentProperty = {
                ...currentProperty,
                title: currentProperty.propertyName || flutterProp.title,
                price: currentProperty.price || flutterProp.price,
                distance: currentProperty.distance || flutterProp.distance,
                region: currentProperty.region || flutterProp.region || currentProperty.area,
                gender: currentProperty.gender || flutterProp.gender,
                services: currentProperty.services || currentProperty.amenities || flutterProp.services,
                img: (currentProperty.images && currentProperty.images[0]) || flutterProp.img,
                bedsLeft: currentProperty.bedsLeft || flutterProp.bedsLeft || currentProperty.rooms
            };
        } else if (!currentProperty && flutterProp) {
            // Only in flutter, create minimal property
            currentProperty = {
                ...flutterProp,
                propertyName: flutterProp.title,
                propertyType: 'apartment',
                city: 'بني سويف',
                area: flutterProp.region,
                rooms: flutterProp.bedsLeft,
                bathrooms: '-',
                floor: '-',
                description: 'لا يوجد وصف مفصل.',
                amenities: flutterProp.services || [],
                images: flutterProp.img ? [flutterProp.img] : []
            };
        }

        renderProperty();
    }

    function renderProperty() {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('propertyContent').classList.remove('hidden');

        const p = currentProperty;

        // Title & Location
        document.getElementById('propTitle').textContent = p.title || p.propertyName || 'عقار بدون اسم';
        document.getElementById('propRegion').textContent = p.region || p.area || 'بني سويف';
        document.getElementById('propType').textContent = typeLabels[p.propertyType] || p.propertyType || 'سكن';
        document.getElementById('propLocation').innerHTML = `
            <span class="material-symbols-outlined text-sm">location_on</span>
            <span>${p.city || 'بني سويف'}${p.area ? ' - ' + p.area : ''}</span>
        `;

        // Images - show ALL images uploaded by owner
        const images = p.images || (p.img ? [p.img] : []);
        if (images.length > 0) {
            document.getElementById('mainImage').src = images[0];

            const thumbsContainer = document.getElementById('imageThumbs');
            thumbsContainer.innerHTML = images.map((img, idx) => `
                <img src="${img}" class="${idx === 0 ? 'active' : ''}" onclick="changeMainImage('${img}', this)" alt="صورة ${idx + 1}"/>
            `).join('');
        } else {
            document.getElementById('mainImage').src = 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=800';
        }

        // Quick Info
        document.getElementById('propRooms').textContent = p.rooms || p.bedsLeft || '-';
        document.getElementById('propBathrooms').textContent = p.bathrooms || '-';
        document.getElementById('propFloor').textContent = p.floor || '-';
        document.getElementById('propDistance').textContent = p.distance || '-';

        // Description
        if (p.description) {
            document.getElementById('propDescription').textContent = p.description;
        }

        // Amenities
        const amenitiesContainer = document.getElementById('propAmenities');
        const services = p.amenities || p.services || [];
        if (services.length > 0) {
            amenitiesContainer.innerHTML = services.map(a => 
                `<span class="amenity-tag">${amenityLabels[a] || a}</span>`
            ).join('');
        } else {
            amenitiesContainer.innerHTML = '<span class="text-slate-400 text-sm">لا توجد مرافق مسجلة</span>';
        }

        // Map - show owner's map link if valid
        const mapSection = document.getElementById('mapSection');
        if (p.mapLink && p.mapLink.trim() !== '') {
            let embedUrl = p.mapLink;

            // Check if it's a valid Google Maps URL
            const isValidMap = embedUrl.includes('google.com/maps') || embedUrl.includes('goo.gl/maps') || embedUrl.includes('maps.app.goo.gl');

            if (isValidMap) {
                // Try to convert to embed format
                if (!embedUrl.includes('/embed')) {
                    // Extract coordinates if present
                    const coordsMatch = embedUrl.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                    if (coordsMatch) {
                        embedUrl = `https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d100!2d${coordsMatch[2]}!3d${coordsMatch[1]}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sar!2seg!4v1`;
                    } else {
                        // Use the URL as-is with embed parameter
                        embedUrl = embedUrl.replace('/maps/', '/maps/embed/');
                    }
                }

                mapSection.innerHTML = `
                    <div class="map-container">
                        <iframe 
                            src="${embedUrl}" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <a href="${p.mapLink}" target="_blank" class="inline-flex items-center gap-1 text-sm text-[#F4D068] hover:underline mt-2">
                        <span class="material-symbols-outlined text-sm">open_in_new</span>
                        <span>فتح الموقع في Google Maps</span>
                    </a>
                `;
            } else {
                mapSection.innerHTML = `
                    <div class="map-placeholder">
                        <span class="material-symbols-outlined text-5xl mb-3" style="color: #AA7C11;">map</span>
                        <div class="text-lg font-bold mb-1">رابط الخريطة غير صالح</div>
                        <div class="text-sm">الرابط المُدخل لا يشير إلى خريطة Google صحيحة</div>
                    </div>
                `;
            }
        } else {
            mapSection.innerHTML = `
                <div class="map-placeholder">
                    <span class="material-symbols-outlined text-5xl mb-3" style="color: #AA7C11;">map</span>
                    <div class="text-lg font-bold mb-1">لا يوجد موقع مسجل</div>
                    <div class="text-sm">لم يقم صاحب العقار بإضافة رابط خريطة</div>
                </div>
            `;
        }

        // Pricing - show ALL prices from owner
        const priceSingle = p.priceSingle || (p.price && p.price > 0 ? p.price : null);
        const priceDouble = p.priceDouble || null;
        const priceTriple = p.priceTriple || null;

        document.getElementById('priceSingle').textContent = priceSingle ? priceSingle + ' ج.م' : 'غير متاح';
        document.getElementById('priceDouble').textContent = priceDouble ? priceDouble + ' ج.م' : 'غير متاح';
        document.getElementById('priceTriple').textContent = priceTriple ? priceTriple + ' ج.م' : 'غير متاح';

        // Hide unavailable price boxes
        document.getElementById('priceSingleBox').style.display = priceSingle ? 'block' : 'none';
        document.getElementById('priceDoubleBox').style.display = priceDouble ? 'block' : 'none';
        document.getElementById('priceTripleBox').style.display = priceTriple ? 'block' : 'none';

        // Contact - show owner's contact info
        const contactSection = document.getElementById('contactSection');
        let contactHtml = '';

        if (p.contactWhatsapp) {
            const cleanPhone = p.contactWhatsapp.replace(/[^0-9]/g, '');
            contactHtml += `
                <a href="https://wa.me/${cleanPhone}" target="_blank" class="contact-btn whatsapp w-full">
                    <span class="material-symbols-outlined">chat</span>
                    <span>تواصل واتساب</span>
                </a>
            `;
        }

        if (p.contactPhone) {
            contactHtml += `
                <a href="tel:${p.contactPhone}" class="contact-btn phone w-full">
                    <span class="material-symbols-outlined">call</span>
                    <span>${p.contactPhone}</span>
                </a>
            `;
        }

        if (p.contactEmail) {
            contactHtml += `
                <a href="mailto:${p.contactEmail}" class="contact-btn phone w-full" style="border-color: #3b82f6; color: #3b82f6;">
                    <span class="material-symbols-outlined">email</span>
                    <span>إرسال بريد إلكتروني</span>
                </a>
            `;
        }

        if (!contactHtml) {
            contactHtml = '<div class="text-slate-400 text-sm text-center">لا توجد بيانات تواصل متاحة</div>';
        }
        contactSection.innerHTML = contactHtml;

        // Owner
        document.getElementById('ownerName').textContent = p.ownerName || 'مالك العقار';

        // Set WhatsApp payment link with owner number if available
        const waBtn = document.getElementById('whatsappPaymentBtn');
        
        if (p.contactWhatsapp) {
            const cleanPhone = p.contactWhatsapp.replace(/[^0-9]/g, '');
            waBtn.href = `https://wa.me/${cleanPhone}?text=مرحباً، أود الاستفسار عن السكن`;
        } else {
            waBtn.style.display = 'none';
            
        }

        // Gender Display - same labels as mangment
        const genderLabels = {
            'male': 'شباب',
            'female': 'بنات'
        };
        const genderDisplay = document.getElementById('genderDisplay');
        genderDisplay.textContent = genderLabels[p.gender] || 'غير محدد';

        // Like Button State
        updateLikeButton();
    }

    function changeMainImage(src, thumb) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.gallery-thumbs img').forEach(img => img.classList.remove('active'));
        thumb.classList.add('active');
    }

    function toggleFavorite() {
        let favs = JSON.parse(localStorage.getItem('bayaty_favorites')) || [];
        const id = currentProperty.id;

        if (favs.includes(id)) {
            favs = favs.filter(favId => favId !== id);
        } else {
            favs.push(id);
        }

        localStorage.setItem('bayaty_favorites', JSON.stringify(favs));
        updateLikeButton();
    }

    function updateLikeButton() {
        let favs = JSON.parse(localStorage.getItem('bayaty_favorites')) || [];
        const isLiked = favs.includes(currentProperty.id);
        const icon = document.getElementById('likeIcon');
        const text = document.getElementById('likeText');

        if (isLiked) {
            icon.classList.add('liked');
            icon.textContent = 'favorite';
            text.textContent = 'في المفضلة';
            text.classList.add('text-red-500');
        } else {
            icon.classList.remove('liked');
            icon.textContent = 'favorite_border';
            text.textContent = 'أضف للمفضلة';
            text.classList.remove('text-red-500');
        }
    }

    function showNotFound() {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('propertyContent').classList.add('hidden');
        document.getElementById('notFoundState').classList.remove('hidden');
    }

