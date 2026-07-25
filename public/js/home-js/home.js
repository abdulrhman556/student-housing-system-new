
    const mockupProperties = [
        { id: 1, title: "شقة مفروشة بشارع عبد السلام عارف", price: 1500, bedsLeft: 2, distance: 1.5, region: "عبد السلام عارف", gender: "male", services: ["wifi", "ac"], img: "https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=500" },
        { id: 2, title: "سكن طالبات النيل الفاخر", price: 1800, bedsLeft: 3, distance: 0.5, region: "شرق النيل", gender: "female", services: ["wifi", "kitchen"], img: "https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?q=80&w=500" },
        { id: 3, title: "غرف شبابية هادئة بحي مقبل", price: 1200, bedsLeft: 1, distance: 3, region: "مقبل", gender: "male", services: ["wifi"], img: "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=500" },
        { id: 4, title: "سكن زهرة الشرق للطالبات", price: 1300, bedsLeft: 4, distance: 1.1, region: "شرق النيل", gender: "female", services: ["wifi", "kitchen"], img: "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=500" },
        { id: 5, title: "شقة مفروشة مكيفة بأرض الحرية", price: 2500, bedsLeft: 5, distance: 4.5, region: "أرض الحرية", gender: "male", services: ["wifi", "ac", "kitchen"], img: "https://images.unsplash.com/photo-1536376072261-38c75010e6c9?q=80&w=500" },
        { id: 6, title: "استوديو بنات متميز خلف الاستاد", price: 1600, bedsLeft: 2, distance: 2.2, region: "خلف الاستاد", gender: "female", services: ["ac", "kitchen"], img: "https://images.unsplash.com/photo-1598928506311-c55ded91a20c?q=80&w=500" }
    ];

    let currentGender = "male";
    let genderFilter = 'all'; // 'all', 'male', or 'female' 
    let selectedRegion = "all";
    let sortBy = "price"; 
    let currentPage = 1;
    let currentUserId = null;
    let chatOpen = false;
    let unreadCount = 0;

    document.addEventListener("DOMContentLoaded", () => {
        const storedGender = localStorage.getItem('stu_gender');
        if (storedGender) currentGender = storedGender;

        document.getElementById("genderBadge").innerText = "كل السكنات المتاحة";

        handleResponsiveFilters();
        window.addEventListener('resize', handleResponsiveFilters);

        renderProperties();

        // Chat init
        const userData = JSON.parse(localStorage.getItem('userData')) || {};
        currentUserId = userData.id || 'student_' + Date.now();
        loadChatContacts();
        loadChatMessages();
        setInterval(checkForReplies, 3000);
    });

    function handleResponsiveFilters() {
        const desktopWrapper = document.getElementById("desktopFilterWrapper");
        const mobilePlaceholder = document.getElementById("mobileFilterPlaceholder");

        if (window.innerWidth < 1024) {
            if (desktopWrapper && desktopWrapper.children.length > 1) {
                while (desktopWrapper.children.length > 1) {
                    mobilePlaceholder.appendChild(desktopWrapper.children[1]);
                }
            }
        } else {
            if (mobilePlaceholder && mobilePlaceholder.children.length > 0) {
                while (mobilePlaceholder.children.length > 0) {
                    desktopWrapper.appendChild(mobilePlaceholder.children[0]);
                }
            }
            toggleMobileFilter(false);
        }
    }

    function showNoNotificationsAlert() {
        alert("لا توجد إشعارات جديدة حالياً في بني سويف.");
    }

    function updateDistanceText(val) {
        document.getElementById('distValue').innerText = val + ' كم';
        applyFilters();
    }

    function toggleMobileFilter(show) {
        const modal = document.getElementById("mobileFilterModal");
        if (show) modal.classList.remove("hidden");
        else modal.classList.add("hidden");
    }

    function renderProperties() {
        const grid = document.getElementById("propertyGrid");
        grid.innerHTML = "";

        // Read published properties from 'properties' (full data from owner)
        let activePropertiesList = [...mockupProperties];
        const allProperties = JSON.parse(localStorage.getItem("properties")) || [];
        const publishedProperties = allProperties.filter(p => p.status === 'published' || p.status === 'approved');

        // Convert published properties to home display format
        if (publishedProperties.length > 0) {
            activePropertiesList = publishedProperties.map(p => ({
                id: p.id,
                title: p.propertyName || 'عقار بدون اسم',
                price: parseInt(p.priceSingle) || parseInt(p.priceDouble) || parseInt(p.priceTriple) || 0,
                bedsLeft: parseInt(p.rooms) || 1,
                distance: 2.5, // Default, can be updated
                region: p.area || 'بني سويف',
                gender: 'male', // Default, can be updated
                services: p.amenities || [],
                img: (p.images && p.images.length > 0) ? p.images[0] : 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=500',
                // Keep full data for details page
                _fullData: p
            }));
        }

        let favs = JSON.parse(localStorage.getItem('bayaty_favorites')) || [];
        let filtered = activePropertiesList;
        // Apply gender filter if selected
        if (genderFilter !== 'all') {
            filtered = filtered.filter(p => p.gender === genderFilter);
        }

        if (selectedRegion !== "all") {
            filtered = filtered.filter(p => p.region === selectedRegion);
        }

        const searchVal = document.getElementById("searchInput").value.trim().toLowerCase();
        const minP = parseFloat(document.getElementById("minPrice")?.value) || 0;
        const maxP = parseFloat(document.getElementById("maxPrice")?.value) || Infinity;
        const maxDist = parseFloat(document.getElementById("distanceRange")?.value) || 50;

        if(searchVal) {
            filtered = filtered.filter(p => p.title.toLowerCase().includes(searchVal) || p.region.toLowerCase().includes(searchVal));
        }

        filtered = filtered.filter(p => p.price >= minP && p.price <= maxP && p.distance <= maxDist);

        if (sortBy === "price") {
            filtered.sort((a, b) => a.price - b.price);
        } else if (sortBy === "distance") {
            filtered.sort((a, b) => a.distance - b.distance);
        }

        document.getElementById("resultsCount").innerText = `تم العثور على ${filtered.length} سكن متاح في بني سويف حالياً`;

        if (filtered.length === 0) {
            grid.innerHTML = `<div class="col-span-full text-center py-12 text-slate-400 text-xs">لا توجد نتائج مطابقة لخيارات الفلترة الحالية.</div>`;
            return;
        }

        filtered.forEach(prop => {
            const isLiked = favs.includes(prop.id) ? 'liked' : '';
            const card = document.createElement("div");
            card.className = "property-card-premium bg-gradient-to-br from-[#070B19] to-[#1E293B] rounded-xl border border-[#AA7C11]/30 overflow-hidden shadow-md flex flex-col justify-between text-white";

            card.innerHTML = `
                <div class="img-container h-48 cursor-pointer" onclick="navigateToDetails('${prop.id}')">
                    <img src="${prop.img}" class="img-premium-smooth w-full h-full object-cover" alt="${prop.title}" />
                    <div class="absolute top-3 right-3 bg-[#070B19]/90 backdrop-blur-sm px-2.5 py-1 rounded-lg flex items-center gap-1 border border-[#AA7C11]/30">
                        <span class="text-xs text-[#F4D068] font-bold">${prop.region}</span>
                    </div>
                </div>
                <div class="p-4 flex flex-col justify-between flex-grow">
                    <div class="flex justify-between items-start mb-2 gap-2">
                        <h3 class="font-bold text-sm text-white truncate hover:text-[#F4D068] cursor-pointer" onclick="navigateToDetails('${prop.id}')">${prop.title}</h3>
                        <button onclick="toggleLike('${prop.id}', this); event.stopPropagation();" class="text-slate-400 hover:text-red-500 p-1.5 rounded-full bg-[#1A264E]/60 border border-[#AA7C11]/20 transition-colors flex items-center justify-center">
                            <span class="material-symbols-outlined text-lg ${isLiked}">favorite</span>
                        </button>
                    </div>
                    <div class="text-[#F4D068] font-black text-base mb-3">${prop.price} <span class="text-xs font-normal text-slate-300">جنيه / شهرياً</span></div>
                    <div class="flex flex-wrap gap-3 mb-4 text-xs text-slate-300 border-t border-slate-700/50 pt-2.5">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm text-[#AA7C11]">bed</span>
                            <span>متبقي ${prop.bedsLeft} سرير</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm text-[#AA7C11]">near_me</span>
                            <span>تبعد ${prop.distance} كم من الكلية</span>
                        </div>
                    </div>
                    <button onclick="navigateToDetails('${prop.id}')" class="w-full text-center bg-[#1A264E] text-[#F4D068] border border-[#AA7C11]/40 py-2 rounded-lg text-xs font-bold hover:bg-[#F4D068] hover:text-[#070B19] transition-all">عرض تفاصيل السكن</button>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function filterByArea(areaName, element) {
        selectedRegion = areaName;
        document.querySelectorAll(".filter-tag").forEach(btn => {
            btn.className = "bg-slate-50 text-slate-700 hover:bg-slate-100 px-4 py-1.5 rounded-full text-xs border border-slate-200/80 transition-all filter-tag";
        });
        if(areaName === 'all') {
            element.className = "bg-gradient-to-r from-[#070B19] to-[#1E293B] text-[#F4D068] font-bold px-4 py-1.5 rounded-full text-xs shadow-sm filter-tag";
        } else {
            element.className = "bg-gradient-to-r from-[#070B19] to-[#1E293B] text-[#F4D068] font-bold px-4 py-1.5 rounded-full text-xs shadow-sm filter-tag";
        }
        renderProperties();
    }

    function toggleLike(id, element) {
        let favs = JSON.parse(localStorage.getItem('bayaty_favorites')) || [];
        const icon = element.querySelector('.material-symbols-outlined');

        if (favs.includes(id)) {
            favs = favs.filter(favId => favId !== id);
            icon.classList.remove('liked');
        } else {
            favs.push(id);
            icon.classList.add('liked');
        }
        localStorage.setItem('bayaty_favorites', JSON.stringify(favs));
    }

    function applyFilters() {
        renderProperties();
    }

    function sortProperties(type) {
        sortBy = type;
        const btnPrice = document.getElementById("sortLowPrice");
        const btnDist = document.getElementById("sortNearest");
        if(type === 'price') {
            btnPrice.className = "px-3 py-1.5 rounded-lg font-bold bg-gradient-to-r from-[#070B19] to-[#1E293B] text-[#F4D068] shadow-md";
            btnDist.className = "px-3 py-1.5 rounded-lg font-medium text-slate-600 hover:text-slate-900 transition-colors";
        } else {
            btnDist.className = "px-3 py-1.5 rounded-lg font-bold bg-gradient-to-r from-[#070B19] to-[#1E293B] text-[#F4D068] shadow-md";
            btnPrice.className = "px-3 py-1.5 rounded-lg font-medium text-slate-600 hover:text-slate-900 transition-colors";
        }
        renderProperties();
    }

    function changePage(pageIndex) {
        if (pageIndex === 'prev') {
            if (currentPage > 1) currentPage--;
        } else if (pageIndex === 'next') {
            if (currentPage < 3) currentPage++;
        } else {
            currentPage = pageIndex;
        }

        const buttons = document.querySelectorAll("#paginationNumbers .page-btn");
        buttons.forEach((btn, idx) => {
            if ((idx + 1) === currentPage) {
                btn.className = "page-btn w-9 h-9 rounded-full bg-gradient-to-r from-[#070B19] to-[#1E293B] text-[#F4D068] font-bold text-sm shadow-md";
            } else {
                btn.className = "page-btn w-9 h-9 rounded-full bg-slate-50 text-slate-500 border border-slate-200 font-bold text-sm shadow-sm";
            }
        });
        renderProperties();
    }

    function resetFilters() {
        if(document.getElementById("minPrice")) document.getElementById("minPrice").value = "";
        if(document.getElementById("maxPrice")) document.getElementById("maxPrice").value = "";
        if(document.getElementById("distanceRange")) {
            document.getElementById("distanceRange").value = 50;
            document.getElementById("distValue").innerText = "50 كم";
        }
        document.getElementById("searchInput").value = "";
        renderProperties();
    }

    function filterByGender(gender) {
        genderFilter = gender;
        const btnAll = document.getElementById("filterAll");
        const btnMale = document.getElementById("filterMale");
        const btnFemale = document.getElementById("filterFemale");

        const inactiveClass = "px-3 py-1.5 rounded-lg font-medium text-slate-600 hover:text-slate-900 transition-colors";
        const activeClass = "px-3 py-1.5 rounded-lg font-bold bg-gradient-to-r from-[#070B19] to-[#1E293B] text-[#F4D068] shadow-md";

        btnAll.className = inactiveClass;
        btnMale.className = inactiveClass;
        btnFemale.className = inactiveClass;

        if (gender === 'all') btnAll.className = activeClass;
        else if (gender === 'male') btnMale.className = activeClass;
        else if (gender === 'female') btnFemale.className = activeClass;

        renderProperties();
    }

    function navigateToDetails(propertyId) {
        localStorage.setItem("selected_property_id", propertyId);
        window.location.href = "details.html";
    }

    // ===== CHAT WIDGET =====
    function toggleChat() {
        chatOpen = !chatOpen;
        const box = document.getElementById('chatBox');
        const badge = document.getElementById('chatBadge');

        if (chatOpen) {
            box.classList.add('active');
            unreadCount = 0;
            if (badge) badge.style.display = 'none';
            loadChatMessages();
        } else {
            box.classList.remove('active');
        }
    }

    // Load contacts from management
    function loadChatContacts() {
        const contactsList = document.getElementById('contactsList');
        const stored = localStorage.getItem('mangment_connect_links');

        if (stored) {
            const contacts = JSON.parse(stored);
            if (contacts.length > 0) {
                let html = '';
                contacts.forEach(c => {
                    const cleanPhone = (c.phone || '').replace(/[^0-9]/g, '');
                    html += `<a href="https://wa.me/${cleanPhone}" target="_blank" class="contact-chip">💬 ${escapeHtml(c.role || 'الدعم')}</a>`;
                });
                contactsList.innerHTML = html;
            } else {
                contactsList.innerHTML = '<span style="color: #94a3b8; font-size: 0.8rem;">لا توجد أرقام متاحة</span>';
            }
        } else {
            contactsList.innerHTML = '<span style="color: #94a3b8; font-size: 0.8rem;">سيتم إضافة أرقام التواصل قريباً</span>';
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function sendChatMessage() {
        const input = document.getElementById('chatInput');
        const text = input.value.trim();
        if (!text) return;

        const msgId = 'chat_student_' + Date.now();
        const timestamp = new Date().toISOString();

        let studentMessages = JSON.parse(localStorage.getItem('student_chat_' + currentUserId)) || [];
        studentMessages.push({
            id: msgId,
            text: text,
            sender: 'student',
            timestamp: timestamp,
            status: 'pending'
        });
        localStorage.setItem('student_chat_' + currentUserId, JSON.stringify(studentMessages));

        let supportQueue = JSON.parse(localStorage.getItem('management_support_messages')) || [];
        supportQueue.push({
            id: msgId,
            senderId: currentUserId,
            senderName: 'طالب',
            senderType: 'student',
            senderTypeLabel: 'طالب',
            type: 'chat',
            typeLabel: 'دردشة مباشرة',
            message: text,
            status: 'pending',
            createdAt: timestamp,
            replies: []
        });
        localStorage.setItem('management_support_messages', JSON.stringify(supportQueue));

        input.value = '';
        loadChatMessages();
    }

    function loadChatMessages() {
        const container = document.getElementById('chatMessages');
        let messages = JSON.parse(localStorage.getItem('student_chat_' + currentUserId)) || [];

        if (messages.length === 0) {
            container.innerHTML = `
                <div class="empty-chat">
                    <div style="font-size: 2rem; margin-bottom: 10px;">👋</div>
                    <div>أهلاً بيك! أرسل رسالتك لفريق الدعم</div>
                </div>
            `;
            return;
        }

        container.innerHTML = '';
        messages.forEach(msg => {
            const time = new Date(msg.timestamp).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
            const statusHtml = msg.sender === 'student' ? 
                `<span class="msg-status"><span class="status-dot ${msg.status}"></span> ${msg.status === 'pending' ? 'قيد الانتظار' : 'تم الرد'}</span>` : '';

            container.innerHTML += `
                <div class="message-bubble ${msg.sender}">
                    ${msg.text.replace(/\n/g, '<br>')}
                    <span class="msg-time">${time}</span>
                    ${statusHtml}
                </div>
            `;
        });

        container.scrollTop = container.scrollHeight;
    }

    function checkForReplies() {
        let messages = JSON.parse(localStorage.getItem('student_chat_' + currentUserId)) || [];
        let hasNewReply = false;
        let replyText = '';

        messages.forEach(msg => {
            if (msg.sender === 'student' && msg.status === 'pending') {
                let supportQueue = JSON.parse(localStorage.getItem('management_support_messages')) || [];
                const supportMsg = supportQueue.find(s => s.id === msg.id);
                if (supportMsg && supportMsg.replies && supportMsg.replies.length > 0) {
                    const lastReply = supportMsg.replies[supportMsg.replies.length - 1];

                    const alreadyAdded = messages.some(m => m.id === 'reply_' + lastReply.id);
                    if (!alreadyAdded) {
                        messages.push({
                            id: 'reply_' + lastReply.id,
                            text: lastReply.text,
                            sender: 'received',
                            timestamp: lastReply.timestamp
                        });
                        msg.status = 'replied';
                        hasNewReply = true;
                        replyText = lastReply.text;
                    }
                }
            }
        });

        if (hasNewReply) {
            localStorage.setItem('student_chat_' + currentUserId, JSON.stringify(messages));

            if (!chatOpen) {
                unreadCount++;
                const badge = document.getElementById('chatBadge');
                if (badge) {
                    badge.textContent = unreadCount;
                    badge.style.display = 'flex';
                }
            }

            loadChatMessages();
            showNotification('👑 تم الرد على رسالتك!', 'فريق الدعم رد: ' + replyText.substring(0, 50) + '...');
        }
    }

    function showNotification(title, text) {
        const banner = document.getElementById('notifBanner');
        const titleEl = document.getElementById('notifTitle');
        const textEl = document.getElementById('notifText');
        if (titleEl) titleEl.textContent = title;
        if (textEl) textEl.textContent = text;
        if (banner) banner.classList.add('show');

        setTimeout(() => {
            if (banner) banner.classList.remove('show');
        }, 5000);
    }
