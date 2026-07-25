<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/splash.css') }}">
<title>بيتي | Bayaty</title>

</head>
<body>

<!-- ═══ NAVBAR ═══ -->
<nav id="nav">
  <div class="nav-logo">
    <span class="nav-logo-en">BAYATY</span>
    <span class="nav-logo-ar">بيتي</span>
  </div>
  <div class="nav-links" id="navLinks">
    <a href="#why">لماذا بيتي؟</a>
    <a href="#how">كيف يعمل؟</a>
    <a href="#services">الخدمات</a>
    <a href="#testimonials">آراء الطلاب</a>
<button class="nav-cta" onclick="location.href='{{ route('set') }}'">
    ابدأ الآن
</button>  </div>
  <div class="ham" id="ham" onclick="document.getElementById('nav').classList.toggle('open')">
    <span></span><span></span><span></span>
  </div>
</nav>

<!-- ═══ HERO ═══ -->
<section class="hero">
  <img class="hero-bg-img" src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=1800&q=80&auto=format&fit=crop" alt="سكن طلابي مريح">

  <div class="hero-left">
    <div class="hero-eyebrow">منصة السكن الطلابي رقم ١ في مصر</div>
    <h1 class="hero-title">
      اعثر على <span class="gold-grad">سكنك الجامعي</span>
      <span class="line2">بسهولة وأمان</span>
    </h1>
    <p class="hero-desc" style="max-width:520px;">آلاف الوحدات السكنية المعتمدة قريبة من جامعتك — تصفح على الخريطة، قارن الأسعار، واحجز في دقائق</p>
    <div class="hero-btns">
      <button class="btn-gold" onclick="location.href='{{ route('set') }}'">ابدأ الآن مجاناً</button>
      <button class="btn-outline-white" onclick="document.getElementById('how').scrollIntoView({behavior:'smooth'})">كيف يعمل؟</button>
    </div>
    <div class="hero-stats">
      <div class="hstat"><span class="hstat-num gold-grad" data-target="500">0+</span><span class="hstat-label">وحدة سكنية</span></div>
      <div class="hstat-div"></div>
      <div class="hstat"><span class="hstat-num gold-grad" data-target="3000">0+</span><span class="hstat-label">طالب مسجل</span></div>
      <div class="hstat-div"></div>
      <div class="hstat"><span class="hstat-num gold-grad">95%</span><span class="hstat-label">نسبة الرضا</span></div>
    </div>
    <div class="hero-badges">
      <div class="hero-badge-pill">
        <div class="hbp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
        <span>سكن قريب منك على الخريطة</span>
      </div>
      <div class="hero-badge-pill">
        <div class="verified-dot"></div>
        <span>وحدات معتمدة ومراجعة</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHY ═══ -->
<section class="why-sec" id="why">
  <h2 class="sec-title">لماذا <span class="gold-grad">Bayaty | بيتي</span>؟</h2>
  <p class="sec-sub">نفرق عن غيرنا بأكتر من طريقة</p>
  <div class="why-grid">
    <div class="why-card">
      <div class="why-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
      <h3>آمن وموثوق</h3>
      <p>كل الوحدات مراجعة ومعتمدة — مفيش وحدة بتظهر من غير تحقق كامل من الفريق</p>
    </div>
    <div class="why-card">
      <div class="why-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div>
      <h3>بحث ذكي وسريع</h3>
      <p>فلتر بالسعر والمسافة والمرافق — لاقي اللي يناسبك في ثواني معدودة</p>
    </div>
    <div class="why-card">
      <div class="why-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
      <h3>خريطة تفاعلية</h3>
      <p>شوف السكن على الخريطة وعرف المسافة الحقيقية من جامعتك أو كليتك</p>
    </div>
    <div class="why-card">
      <div class="why-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M8 18h.01M12 18h.01"/></svg></div>
      <h3>حجز فوري</h3>
      <p>أكد حجزك مباشرة من التطبيق بدون مكالمات أو زيارات — كل حاجة أونلاين</p>
    </div>
  </div>
</section>

<!-- ═══ HOW ═══ -->
<section class="how-sec" id="how">
  <h2 class="sec-title">كيف يعمل <span class="gold-grad">بيتي؟</span></h2>
  <p class="sec-sub">٣ خطوات بسيطة وسكنك جاهز</p>
  <div class="how-wrap">
    <div class="how-step">
      <div class="step-circle gold-grad">١</div>
      <h3>سجّل وابحث</h3>
      <p>أنشئ حسابك، حدد جامعتك وميزانيتك — التطبيق هيجيبلك أفضل الخيارات المتاحة</p>
    </div>
    <div class="how-step">
      <div class="step-circle gold-grad">٢</div>
      <h3>قارن واختار</h3>
      <p>شوف الصور والمرافق والمراجعات — وتواصل مع صاحب السكن مباشرة من التطبيق</p>
    </div>
    <div class="how-step">
      <div class="step-circle gold-grad">٣</div>
      <h3>احجز وسكن</h3>
      <p>أكد الحجز وادفع بأمان — وانقل لسكنك الجديد بأريحية تامة</p>
    </div>
  </div>
</section>

<!-- ═══ SERVICES ═══ -->
<section class="services-sec" id="services">
  <h2 class="sec-title light">خدماتنا</h2>
  <p class="sec-sub light">كل اللي تحتاجه في مكان واحد</p>
  <div class="services-grid">
    <div class="svc-card">
      <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg></div>
      <h4>سكن فردي</h4><p>غرف مفردة هادية ومريحة بأسعار تنافسية</p>
    </div>
    <div class="svc-card">
      <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
      <h4>سكن مشترك</h4><p>شارك مع زملائك وقلل التكاليف بشكل كبير</p>
    </div>
    <div class="svc-card">
      <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
      <h4>واي فاي مجاني</h4><p>إنترنت سريع وثابت في جميع الوحدات المدرجة</p>
    </div>
    <div class="svc-card">
      <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
      <h4>خريطة تفاعلية</h4><p>عرض الوحدات على الخريطة مع تحديد المسافات</p>
    </div>
    <div class="svc-card">
      <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
      <h4>دفع آمن</h4><p>طرق دفع متعددة ومحمية بأعلى معايير الأمان</p>
    </div>
    <div class="svc-card">
      <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 012 1.18 2 2 0 014 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg></div>
      <h4>دعم ٢٤/٧</h4><p>فريق دعم جاهز يساعدك على مدار الساعة</p>
    </div>
    <div class="svc-card">
      <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
      <h4>وحدات معتمدة</h4><p>كل الوحدات مراجعة ومضمونة من فريق بيتي</p>
    </div>
    <div class="svc-card">
      <div class="svc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg></div>
      <h4>تقييمات حقيقية</h4><p>آراء موثقة من طلاب حقيقيين اختبروا السكن</p>
    </div>
  </div>
</section>

<!-- ═══ STATS ═══ -->
<section class="stats-sec">
  <div class="stats-grid">
    <div class="stat-box">
      <div class="stat-num"><span class="gold-grad" data-count="500" data-suffix="+">500+</span></div>
      <div class="stat-line"></div>
      <div class="stat-label">وحدة سكنية</div>
    </div>
    <div class="stat-box">
      <div class="stat-num"><span class="gold-grad" data-count="3000" data-suffix="+">3000+</span></div>
      <div class="stat-line"></div>
      <div class="stat-label">طالب مسجل</div>
    </div>
    <div class="stat-box">
      <div class="stat-num"><span class="gold-grad">95%</span></div>
      <div class="stat-line"></div>
      <div class="stat-label">نسبة الرضا</div>
    </div>
    <div class="stat-box">
      <div class="stat-num" style="font-size:clamp(1.6rem,4vw,2.5rem);"><span class="gold-grad">24/7</span></div>
      <div class="stat-line"></div>
      <div class="stat-label">دعم متواصل</div>
    </div>
  </div>
</section>

<!-- ═══ PHOTO STRIP ═══ -->
<section class="strip-sec">
  <h2 class="strip-title">وحدات حقيقية — <span class="gold-grad">جودة حقيقية</span></h2>
  <div class="strip-scroll">
    <div class="strip-img">
      <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=600&q=80&auto=format&fit=crop" alt="غرفة معيشة">
      <div class="strip-label">غرفة معيشة مريحة</div>
    </div>
    <div class="strip-img">
      <img src="https://images.unsplash.com/photo-1486304873000-235643847519?w=600&q=80&auto=format&fit=crop" alt="مكتب للدراسة">
      <div class="strip-label">مكتب دراسة مجهز</div>
    </div>
    <div class="strip-img">
      <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=600&q=80&auto=format&fit=crop" alt="غرفة نوم">
      <div class="strip-label">غرفة نوم مريحة</div>
    </div>
    <div class="strip-img">
      <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=600&q=80&auto=format&fit=crop" alt="شقة مفروشة">
      <div class="strip-label">شقة مفروشة كاملة</div>
    </div>
    <div class="strip-img">
      <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=600&q=80&auto=format&fit=crop" alt="مطبخ">
      <div class="strip-label">مطبخ نظيف ومجهز</div>
    </div>
  </div>
</section>

<!-- ═══ TESTIMONIALS ═══ -->
<section class="testi-sec" id="testimonials">
  <h2 class="sec-title">آراء <span class="gold-grad">الطلاب</span></h2>
  <p class="sec-sub">تجارب حقيقية من طلاب حقيقيين</p>
  <div class="testi-grid">
    <div class="tcard">
      <div class="tcard-quote">"</div>
      <div class="stars">★★★★★</div>
      <p class="tcard-text">لقيت سكن قريب من الكلية في نص ساعة بس! التطبيق سهل جداً والخريطة ساعدتني أختار أحسن موقع بسعر مناسب</p>
      <div class="tcard-user">
        <div class="tcard-av">أ</div>
        <div class="tcard-info"><p>أحمد محمد</p><span>طالب هندسة — القاهرة</span></div>
      </div>
    </div>
    <div class="tcard">
      <div class="tcard-quote">"</div>
      <div class="stars">★★★★★</div>
      <p class="tcard-text">كنت خايفة من الغش بس الوحدات كلها معتمدة والتقييمات حقيقية. شعرت بأمان من أول دقيقة استخدمت التطبيق</p>
      <div class="tcard-user">
        <div class="tcard-av">س</div>
        <div class="tcard-info"><p>سارة علي</p><span>طالبة طب — الإسكندرية</span></div>
      </div>
    </div>
    <div class="tcard">
      <div class="tcard-quote">"</div>
      <div class="stars">★★★★☆</div>
      <p class="tcard-text">الحجز كان سهل جداً والدفع آمن. استغرق كل الأمر دقيقتين وأكدت السكن. تجربة ممتازة وهنصحح كل الزملاء</p>
      <div class="tcard-user">
        <div class="tcard-av">م</div>
        <div class="tcard-info"><p>محمود حسن</p><span>طالب تجارة — المنصورة</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ CTA ═══ -->
<section class="cta-sec">
  <div class="cta-inner">
    <h2 class="cta-title">جاهز <span class="gold-grad">تبدأ؟</span></h2>
    <p class="cta-sub">انضم لآلاف الطلاب اللي لاقوا سكنهم المثالي مع Bayaty | بيتي</p>
    <div class="cta-btns">
      <button class="btn-gold" onclick="location.href='{{ route('set') }}'">ابدأ الآن مجاناً</button>
      <button class="btn-blue" onclick="location.href='{{ route('set') }}'">تسجيل الدخول</button>
    </div>
  </div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer>
  <div class="foot-grid">
    <div class="foot-brand">
      <div>
        <div class="foot-logo-en">BAYATY</div>
        <div class="foot-logo-ar">بيتي</div>
      </div>
      <p class="foot-desc">منصة السكن الطلابي الأولى — نربط الطلاب بأفضل الوحدات السكنية بسهولة وأمان تام</p>
    </div>
    <div class="foot-col">
      <h5>روابط سريعة</h5>
      <a href="#why">لماذا بيتي؟</a>
      <a href="#how">كيف يعمل؟</a>
      <a href="#services">الخدمات</a>
      <a href="#testimonials">آراء الطلاب</a>
    </div>
    <div class="foot-col">
      <h5>للطلاب</h5>
      <a href="{{ route('set') }}">إنشاء حساب</a>
      <a href="{{ route('set') }}">تصفح الوحدات</a>
      <a href="#">كيفية الحجز</a>
      <a href="#">الأسئلة الشائعة</a>
    </div>
    <div class="foot-col">
      <h5>للملاك</h5>
      <a href="{{ route('set') }}">أضف وحدتك</a>
      <a href="#">إدارة الحجوزات</a>
      <a href="#">سياسة الخصوصية</a>
      <a href="#">تواصل معنا</a>
    </div>
  </div>
  <div class="foot-bottom">
    <span>© 2026 Bayaty | بيتي — جميع الحقوق محفوظة</span>
    <div class="soc">
      <div class="soc-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></div>
      <div class="soc-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></div>
      <div class="soc-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg></div>
    </div>
  </div>
</footer>


<script src="{{ asset('js/spla.js') }}"></script>

</body>
</html>
