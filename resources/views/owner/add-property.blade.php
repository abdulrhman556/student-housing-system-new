<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/owner-css/add.css">
    <title>إضافة عقار جديد - بيتي</title>
    
<base target="_blank">
<base target="_blank">
</head>
<body>

    <!-- Mobile Toggle -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">بيتي <span>BAYATY</span></div>

        <a href="owner-profile.html" class="user-profile" id="userProfile">
            <div class="user-avatar" id="userAvatar">م</div>
            <div class="user-info">
                <span class="user-name" id="userName">مالك العقار</span>
                <span class="user-role">الملف الشخصي</span>
            </div>
        </a>

        <button class="sidebar-btn active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            إضافة عقار جديد
        </button>

        <a href="owner-buld.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            عقاراتي
        </a>

        <a href="owner-mony.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            المدفوعات
        </a>

        <a href="owner-support.html" class="sidebar-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
            الدعم
        </a>

        <div class="sidebar-bottom">
            <div class="sidebar-divider"></div>
            <a href="home.html" class="sidebar-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                تسجيل الخروج
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1>إضافة عقار <span>جديد</span></h1>
            <p>أدخل التفاصيل الكاملة لعقارك لعرضه على المنصة</p>
        </div>

        <form id="propertyForm">

            <!-- Section 1: Property Name -->
            <div class="form-section">
                <div class="section-header">
                    <div class="icon-circle">🏷️</div>
                    اسم العقار
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="propertyName">اسم العقار</label>
                        <input type="text" id="propertyName" name="property_name" placeholder="مثال: شقة النصر المفروشة" required>
                        <span class="hint">هذا الاسم سيظهر في قائمة عقاراتك</span>
                    </div>
                </div>
            </div>

            <!-- Section 2: Photos & Video -->
            <div class="form-section">
                <div class="section-header">
                    <div class="icon-circle">📷</div>
                    الصور والفيديو
                </div>
                <p class="section-desc">يمكنك رفع حتى 5 صور. يُفضل إضافة صورة واضحة للعقار.</p>

                <div class="upload-grid">
                    <div class="upload-main">
                        <div class="upload-box" id="box0" onclick="document.getElementById('mainPhoto').click()">
                            <span class="plus-icon">+</span>
                            <span class="upload-label">أضف صورة رئيسية</span>
                            <input type="file" id="mainPhoto" accept="image/*" onchange="previewImage(this, 0)">
                        </div>
                    </div>
                    <div class="upload-box" id="box1" onclick="document.getElementById('photo2').click()">
                        <span class="plus-icon">+</span>
                        <input type="file" id="photo2" accept="image/*" onchange="previewImage(this, 1)">
                    </div>
                    <div class="upload-box" id="box2" onclick="document.getElementById('photo3').click()">
                        <span class="plus-icon">+</span>
                        <input type="file" id="photo3" accept="image/*" onchange="previewImage(this, 2)">
                    </div>
                    <div class="upload-box" id="box3" onclick="document.getElementById('photo4').click()">
                        <span class="plus-icon">+</span>
                        <input type="file" id="photo4" accept="image/*" onchange="previewImage(this, 3)">
                    </div>
                    <div class="upload-box" id="box4" onclick="document.getElementById('photo5').click()">
                        <span class="plus-icon">+</span>
                        <input type="file" id="photo5" accept="image/*" onchange="previewImage(this, 4)">
                    </div>
                </div>
            </div>

            <!-- Section 3: Location -->
            <div class="form-section">
                <div class="section-header">
                    <div class="icon-circle">📍</div>
                    الموقع الجغرافي
                </div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="mapLink">رابط الموقع على خرائط Google</label>
                        <input type="url" id="mapLink" name="map_link" placeholder="https://maps.google.com/...">
                    </div>
                    <div class="form-group">
                        <label for="city">المدينة</label>
                        <input type="text" id="city" name="city" placeholder="مثال: القاهرة" required>
                    </div>
                    <div class="form-group">
                        <label for="area">المنطقة / الحي</label>
                        <input type="text" id="area" name="area" placeholder="مثال: مدينة نصر" required>
                    </div>
                </div>
            </div>

            <!-- Section 4: Property Type & Details -->
            <div class="form-section">
                <div class="section-header">
                    <div class="icon-circle">🏠</div>
                    نوع العقار والتفاصيل
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="propertyType">نوع العقار</label>
                        <select id="propertyType" name="property_type" required>
                            <option value="" disabled selected>اختر نوع العقار</option>
                            <option value="apartment">شقة</option>
                            <option value="room">غرفة</option>
                            <option value="studio">استوديو</option>
                            <option value="villa">فيلا</option>
                            <option value="duplex">دوبلكس</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rooms">عدد الغرف</label>
                        <input type="number" id="rooms" name="rooms" min="1" placeholder="مثال: 3" required>
                    </div>
                    <div class="form-group">
                        <label for="beds">عدد السرائر المتاحة</label>
                        <input type="number" id="beds" name="beds" min="1" placeholder="مثال: 6" required>
                        <span class="hint">إجمالي عدد السرائر المتاحة في العقار</span>
                    </div>
                    <div class="form-group">
                        <label for="bathrooms">عدد الحمامات</label>
                        <input type="number" id="bathrooms" name="bathrooms" min="1" placeholder="مثال: 2" required>
                    </div>
                    <div class="form-group">
                        <label for="floor">الدور</label>
                        <input type="number" id="floor" name="floor" placeholder="مثال: 4">
                    </div>
                </div>
            </div>

            <!-- Section 5: Gender Accommodation -->
            <div class="form-section">
                <div class="section-header">
                    <div class="icon-circle">👥</div>
                    نوع السكن
                </div>
                <p class="section-desc">حدد ما إذا كان العقار مخصص للطلاب أم الطالبات أم للجنسين</p>

                <div class="gender-grid">
                    <label class="gender-option">
                        <input type="radio" name="gender" value="male" checked>
                        <div class="gender-card male">
                            <span class="gender-icon">👨‍🎓</span>
                            <div class="gender-label">طلاب</div>
                            <div class="gender-desc">سكن مخصص للطلاب فقط</div>
                        </div>
                    </label>
                    <label class="gender-option">
                        <input type="radio" name="gender" value="female">
                        <div class="gender-card female">
                            <span class="gender-icon">👩‍🎓</span>
                            <div class="gender-label">طالبات</div>
                            <div class="gender-desc">سكن مخصص للطالبات فقط</div>
                        </div>
                    </label>
                    <label class="gender-option">
                        <input type="radio" name="gender" value="mixed">
                        <div class="gender-card mixed">
                            <span class="gender-icon">👫</span>
                            <div class="gender-label">مشترك</div>
                            <div class="gender-desc">سكن متاح للجنسين</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section 6: Pricing (Student Housing) -->
            <div class="form-section">
                <div class="section-header">
                    <div class="icon-circle">💰</div>
                    تسعير سكن الطلاب
                </div>
                <p class="section-desc">حدد سعر الغرفة حسب عدد الطلاب في كل غرفة (سكن طلاب)</p>

                <div class="pricing-row">
                    <div class="form-group">
                        <label for="priceSingle">غرفة فردية (طالب واحد)</label>
                        <div class="price-input-wrapper">
                            <input type="number" id="priceSingle" name="price_single" min="0" placeholder="2000">
                            <span class="currency">ج.م</span>
                        </div>
                        <span class="hint">سعر الغرفة للطالب الواحد</span>
                    </div>
                    <div class="form-group">
                        <label for="priceDouble">غرفة مزدوجة (طالبين)</label>
                        <div class="price-input-wrapper">
                            <input type="number" id="priceDouble" name="price_double" min="0" placeholder="1500">
                            <span class="currency">ج.م</span>
                        </div>
                        <span class="hint">سعر الغرفة لكل طالب</span>
                    </div>
                    <div class="form-group">
                        <label for="priceTriple">غرفة ثلاثية (3 طلاب)</label>
                        <div class="price-input-wrapper">
                            <input type="number" id="priceTriple" name="price_triple" min="0" placeholder="1200">
                            <span class="currency">ج.م</span>
                        </div>
                        <span class="hint">سعر الغرفة لكل طالب</span>
                    </div>
                </div>
            </div>

            <!-- Section 7: Amenities -->
            <div class="form-section">
                <div class="section-header">
                    <div class="icon-circle">✨</div>
                    المرافق والخدمات
                </div>

                <div class="amenities-grid">
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities" value="wifi">
                        <span>واي فاي (WiFi)</span>
                    </label>
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities" value="ac">
                        <span>تكييف (AC)</span>
                    </label>
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities" value="heater">
                        <span>سخان مياه</span>
                    </label>
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities" value="fridge">
                        <span>ثلاجة</span>
                    </label>
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities" value="washing">
                        <span>غسالة</span>
                    </label>
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities" value="tv">
                        <span>تلفزيون</span>
                    </label>
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities" value="furniture">
                        <span>مفروشة</span>
                    </label>
                    <label class="amenity-item">
                        <input type="checkbox" name="amenities" value="parking">
                        <span>موقف سيارات</span>
                    </label>
                </div>
            </div>

            <!-- Section 8: Contact & Policies -->
            <div class="form-section">
                <div class="section-header">
                    <div class="icon-circle">📋</div>
                    التفاصيل والقواعد
                </div>

                <div class="contact-grid">
                    <div class="contact-item">
                        <span class="contact-icon">📞</span>
                        <input type="tel" placeholder="رقم التليفون (اختياري)" name="contact_phone" id="contactPhone">
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">💬</span>
                        <input type="tel" placeholder="رقم واتساب (اختياري)" name="contact_whatsapp" id="contactWhatsapp">
                    </div>
                    <div class="contact-item full-width">
                        <span class="contact-icon">📧</span>
                        <input type="email" placeholder="بريد إلكتروني (اختياري)" name="contact_email" id="contactEmail">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label for="description">وصف مفصل للعقار</label>
                    <textarea class="desc-textarea" id="description" name="description" placeholder="اكتب وصفاً مفصلاً للعقار يتضمن المميزات والقواعد..."></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary" id="publishBtn">نشر العقار</button>
                <button type="button" class="btn btn-secondary" id="draftBtn">حفظ كمسودة</button>
            </div>

        </form>
    </main>

    <!-- Modal for Image Confirmation -->
    <div class="modal-overlay" id="imageModal">
        <div class="modal-box">
            <h3>⚠️ هناك صور مرفوعة</h3>
            <p>لديك صور مرفوعة بالفعل. هل تريد تجاهلها أم حفظ العقار كمسودة مع الصور؟</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="ignoreImages()">تجاهل الصور</button>
                <button class="btn btn-primary" onclick="saveDraftWithImages()">حفظ كمسودة</button>
                <button class="btn btn-secondary" onclick="closeModal('imageModal')" style="border-color: var(--text-muted); color: var(--text-muted);">إلغاء</button>
            </div>
        </div>
    </div>

    <!-- Modal for Success -->
    <div class="modal-overlay" id="successModal">
        <div class="modal-box">
            <h3 id="successTitle">✅ تم بنجاح</h3>
            <p id="successMessage">تم حفظ العقار بنجاح</p>
            <div class="modal-actions">
                <button class="btn btn-primary" onclick="goToProperties()">الذهاب لعقاراتي</button>
            </div>
        </div>
    </div>
<script src="../js/owner-js/add.js"></script>
   
</body>
</html>