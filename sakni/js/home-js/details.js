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
let currentBedType = null;
let currentBedPrice = null;

document.addEventListener("DOMContentLoaded", () => {
    currentPropertyId = localStorage.getItem('selected_property_id');
    if (!currentPropertyId) {
        showNotFound();
        return;
    }
    loadPropertyDetails();
});

function loadPropertyDetails() {
    let allProperties = JSON.parse(localStorage.getItem('properties')) || [];
    let flutterProps = JSON.parse(localStorage.getItem('mangment_flutter_properties')) || [];
    
    currentProperty = allProperties.find(p => p.id == currentPropertyId);
    let flutterProp = flutterProps.find(p => p.id == currentPropertyId);

    if (!currentProperty && !flutterProp) {
        showNotFound();
        return;
    }

    if (currentProperty && flutterProp) {
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
    checkBookingStatus();
}

function renderProperty() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('propertyContent').classList.remove('hidden');

    const p = currentProperty;

    document.getElementById('propTitle').textContent = p.title || p.propertyName || 'عقار بدون اسم';
    document.getElementById('propRegion').textContent = p.region || p.area || 'بني سويف';
    document.getElementById('propType').textContent = typeLabels[p.propertyType] || p.propertyType || 'سكن';
    document.getElementById('propLocation').innerHTML = `
        <span class="material-symbols-outlined text-sm">location_on</span>
        <span>${p.city || 'بني سويف'}${p.area ? ' - ' + p.area : ''}</span>
    `;

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

    document.getElementById('propRooms').textContent = p.rooms || p.bedsLeft || '-';
    document.getElementById('propBathrooms').textContent = p.bathrooms || '-';
    document.getElementById('propFloor').textContent = p.floor || '-';
    document.getElementById('propDistance').textContent = p.distance || '-';

    if (p.description) {
        document.getElementById('propDescription').textContent = p.description;
    }

    const amenitiesContainer = document.getElementById('propAmenities');
    const services = p.amenities || p.services || [];
    if (services.length > 0) {
        amenitiesContainer.innerHTML = services.map(a => 
            `<span class="amenity-tag">${amenityLabels[a] || a}</span>`
        ).join('');
    } else {
        amenitiesContainer.innerHTML = '<span class="text-slate-400 text-sm">لا توجد مرافق مسجلة</span>';
    }

    const priceSingle = p.priceSingle || (p.price && p.price > 0 ? p.price : null);
    const priceDouble = p.priceDouble || null;
    const priceTriple = p.priceTriple || null;

    document.getElementById('priceSingle').textContent = priceSingle ? priceSingle + ' ج.م' : 'غير متاح';
    document.getElementById('priceDouble').textContent = priceDouble ? priceDouble + ' ج.م' : 'غير متاح';
    document.getElementById('priceTriple').textContent = priceTriple ? priceTriple + ' ج.م' : 'غير متاح';

    const singleBox = document.getElementById('priceSingleBox');
    const doubleBox = document.getElementById('priceDoubleBox');
    const tripleBox = document.getElementById('priceTripleBox');

    singleBox.style.display = priceSingle ? 'block' : 'none';
    doubleBox.style.display = priceDouble ? 'block' : 'none';
    tripleBox.style.display = priceTriple ? 'block' : 'none';

    const genderLabels = { 'male': 'شباب', 'female': 'بنات' };
    document.getElementById('genderDisplay').textContent = genderLabels[p.gender] || 'غير محدد';

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

// ===== BOOKING SYSTEM =====

function openBookingModal(bedType, priceText) {
    currentBedType = bedType;
    currentBedPrice = priceText;

    const modal = document.getElementById('bookingModal');
    const bookingImg = document.getElementById('modalBookingImg');
    const bookingDetails = document.getElementById('modalBookingDetails');
    const barcodeSection = document.getElementById('barcodeSection');
    const phoneSection = document.getElementById('phoneSection');
    const barcodeImg = document.getElementById('modalBarcode');
    const phoneDiv = document.getElementById('modalPhone');
    const waBtn = document.getElementById('modalWaBtn');

    const images = currentProperty.images || (currentProperty.img ? [currentProperty.img] : []);
    bookingImg.src = images[0] || 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=500';

    bookingDetails.innerHTML = `
        <strong style="color:#F4D068;">${currentProperty.title || currentProperty.propertyName}</strong><br>
        🛏️ سرير ${bedType}<br>
        💰 ${priceText}
    `;

    const config = JSON.parse(localStorage.getItem('booking_config')) || {};
    
    if (config.barcodeImage) {
        barcodeImg.src = config.barcodeImage;
        barcodeSection.style.display = 'block';
    } else {
        barcodeSection.style.display = 'none';
    }

    if (config.contactPhone) {
        phoneDiv.textContent = config.contactPhone;
        phoneSection.style.display = 'block';
        const cleanPhone = config.contactPhone.replace(/[^0-9]/g, '');
        waBtn.href = `https://wa.me/${cleanPhone}?text=مرحباً، أود حجز ${currentProperty.title} - سرير ${bedType} بسعر ${priceText}`;
    } else {
        phoneSection.style.display = 'none';
        waBtn.href = '#';
        waBtn.onclick = (e) => { e.preventDefault(); alert('لا يوجد رقم تواصل مسجل'); };
    }

    modal.classList.add('active');
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.remove('active');
}

function confirmBooking() {
    if (!currentProperty || !currentBedType) return;

    const bookingId = 'book_' + Date.now();
    
    const bookingData = {
        id: bookingId,
        propertyId: currentProperty.id,
        propertyName: currentProperty.title || currentProperty.propertyName,
        propertyImage: (currentProperty.images && currentProperty.images[0]) || currentProperty.img || '',
        propertyType: currentProperty.propertyType || 'شقة',
        bedType: currentBedType,
        price: currentBedPrice,
        region: currentProperty.region || currentProperty.area || 'بني سويف',
        city: currentProperty.city || 'بني سويف',
        gender: currentProperty.gender || 'غير محدد',
        
        rooms: currentProperty.rooms || '-',
        bathrooms: currentProperty.bathrooms || '-',
        floor: currentProperty.floor || '-',
        distance: currentProperty.distance || '-',
        description: currentProperty.description || 'لا يوجد وصف',
        amenities: currentProperty.amenities || currentProperty.services || [],
        
        ownerName: currentProperty.ownerName || 'غير معروف',
        contactWhatsapp: currentProperty.contactWhatsapp || '',
        contactPhone: currentProperty.contactPhone || '',
        contactEmail: currentProperty.contactEmail || '',
        
        status: 'pending',
        createdAt: new Date().toISOString()
    };

    let bookings = JSON.parse(localStorage.getItem('support_bookings')) || [];
    bookings.push(bookingData);
    localStorage.setItem('support_bookings', JSON.stringify(bookings));

    let statuses = JSON.parse(localStorage.getItem('booking_statuses')) || {};
    statuses[currentProperty.id + '_' + currentBedType] = 'pending';
    localStorage.setItem('booking_statuses', JSON.stringify(statuses));

    alert('✅ تم إرسال طلب الحجز بنجاح! سيتم مراجعته من الإدارة.');
    closeBookingModal();
    checkBookingStatus();
}

function checkBookingStatus() {
    const statuses = JSON.parse(localStorage.getItem('booking_statuses')) || {};
    
    ['فردية', 'مزدوجة', 'ثلاثية'].forEach(type => {
        const key = currentProperty.id + '_' + type;
        const status = statuses[key];
        const btnId = type === 'فردية' ? 'bookSingle' : type === 'مزدوجة' ? 'bookDouble' : 'bookTriple';
        const btn = document.getElementById(btnId);
        if (!btn) return;

        if (status === 'approved') {
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span><span>تم الحجز بنجاح</span>';
        } else if (status === 'pending') {
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">hourglass_top</span><span>قيد المراجعة...</span>';
            btn.style.background = 'linear-gradient(135deg, #D97706 0%, #B45309 100%)';
        }
    });
}

function showNotFound() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('propertyContent').classList.add('hidden');
    document.getElementById('notFoundState').classList.remove('hidden');
}