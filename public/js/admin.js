document.addEventListener('DOMContentLoaded', function() {
   
    window.books = [];
    window.users = [];
    window.orders = [];
    window.currentUser = null;
    window.pendingPaymentOrderId = null;
    window.SHIPPING_FEE = 5.00;
    window.notifications = [];
    window.notificationCheckInterval = null;
    window.isOnline = navigator.onLine;
    window.lastSyncTime = null;


    loadData();
    updateNavigation();
    initConnectionMonitoring();


    if (window.currentUser?.isAdmin) {
        showSection('dashboard-section');
        loadAdminDashboard();
        loadDashboardPreviews();
        updateConnectionStatus(true);


        if (window.notificationCheckInterval) {
            clearInterval(window.notificationCheckInterval);
        }
        window.notificationCheckInterval = setInterval(checkForNewOrders, 5000);
    } else {
        loadBestSellers();
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const avatar = document.querySelector('.profile-avatar');
        if (dropdown && avatar && !avatar.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }

        const panel = document.getElementById('notificationPanel');
        const notifBtn = event.target.closest('.nav-btn[onclick*="toggleNotificationPanel"]');
        if (panel && panel.classList.contains('show') && !panel.contains(event.target) && !notifBtn) {
            toggleNotificationPanel(false);
        }
    });
});

function initConnectionMonitoring() {
    window.addEventListener('online', () => {
        updateConnectionStatus(true);
        showToast('Connection restored! Syncing data...', 'success');
        syncData();
    });
    
    window.addEventListener('offline', () => {
        updateConnectionStatus(false);
        showToast('You are offline. Changes will sync when connection returns.', 'warning');
    });
    
    updateConnectionStatus(window.isOnline);
}

function updateConnectionStatus(isOnline) {
    const connectionDot = document.querySelector('.connection-dot');
    const connectionText = document.querySelector('.connection-status span');
    const connectionTime = document.getElementById('connectionTime');
    
    if (connectionDot) {
        connectionDot.style.background = isOnline ? '#22c55e' : '#ef4444';
        connectionDot.style.animation = isOnline ? 'pulse-green 2s infinite' : 'none';
    }
    
    if (connectionText) {
        connectionText.textContent = isOnline ? 'Connected to server' : 'Offline mode';
    }
    
    if (connectionTime && window.lastSyncTime) {
        const timeDiff = Math.floor((Date.now() - window.lastSyncTime) / 1000);
        connectionTime.textContent = timeDiff < 60 ? 'just now' : `${Math.floor(timeDiff / 60)}m ago`;
    }
    
    window.isOnline = isOnline;
}

function syncData() {
    if (!window.isOnline) return;
    
    loadData();
    window.lastSyncTime = Date.now();
    updateConnectionStatus(true);
    
    if (document.getElementById('dashboard-section')?.classList.contains('active')) {
        loadAdminDashboard();
        loadDashboardPreviews();
    }
    
    if (document.getElementById('orders-section')?.classList.contains('active')) {
        loadOrders();
    }
    
    if (document.getElementById('inventory-section')?.classList.contains('active')) {
        loadInventory();
    }
    
    if (document.getElementById('reports-section')?.classList.contains('active')) {
        loadSalesReports();
    }
    
    if (document.getElementById('users-section')?.classList.contains('active')) {
        loadUsers();
    }
    
    showToast('Data synced successfully', 'success');
}

function openUserInterface() {
    localStorage.setItem('pahina_admin_view_section', 'home');
    window.open('/user', '_blank');
    showToast('Opening user interface...', 'info');
}

function getIcon(iconName) {
    const icons = {
        'dashboard': '<i class="fas fa-chart-line"></i>',
        'inventory': '<i class="fas fa-book"></i>',
        'orders': '<i class="fas fa-truck"></i>',
        'reports': '<i class="fas fa-chart-bar"></i>',
        'users': '<i class="fas fa-users"></i>',
        'notifications': '<i class="fas fa-bell"></i>',
        'logout': '<i class="fas fa-sign-out-alt"></i>',
        'settings': '<i class="fas fa-cog"></i>',
        'admin': '<i class="fas fa-user-shield"></i>',
        'customer': '<i class="fas fa-user"></i>',
        'revenue': '<i class="fas fa-dollar-sign"></i>',
        'pending': '<i class="fas fa-clock"></i>',
        'payment': '<i class="fas fa-credit-card"></i>',
        'stock': '<i class="fas fa-boxes"></i>',
        'complete': '<i class="fas fa-check-circle"></i>',
        'edit': '<i class="fas fa-pencil-alt"></i>',
        'delete': '<i class="fas fa-trash-alt"></i>',
        'view': '<i class="fas fa-eye"></i>',
        'approve': '<i class="fas fa-check"></i>',
        'reject': '<i class="fas fa-times"></i>',
        'ship': '<i class="fas fa-shipping-fast"></i>',
        'deliver': '<i class="fas fa-home"></i>',
        'restock': '<i class="fas fa-box-open"></i>',
        'export': '<i class="fas fa-download"></i>',
        'sync': '<i class="fas fa-sync-alt"></i>',
        'add': '<i class="fas fa-plus-circle"></i>',
        'close': '<i class="fas fa-times"></i>',
        'search': '<i class="fas fa-search"></i>',
        'filter': '<i class="fas fa-filter"></i>',
        'calendar': '<i class="fas fa-calendar-alt"></i>',
        'money': '<i class="fas fa-money-bill-wave"></i>',
        'bank': '<i class="fas fa-university"></i>',
        'cash': '<i class="fas fa-coins"></i>',
        'cart': '<i class="fas fa-shopping-cart"></i>',
        'star': '<i class="fas fa-star"></i>',
        'trophy': '<i class="fas fa-trophy"></i>',
        'medal': '<i class="fas fa-medal"></i>',
        'crown': '<i class="fas fa-crown"></i>'
    };
    return icons[iconName] || '<i class="fas fa-circle"></i>';
}


function loadBestSellers() {
    const bestSellersGrid = document.getElementById('bestSellersGrid');
    if (!bestSellersGrid) return;

    const bookSales = {};
    const completedOrders = window.orders.filter(o => o.status === 'delivered' || (o.approvalStatus === 'approved' && o.status === 'delivered'));

    completedOrders.forEach(order => {
        order.items.forEach(item => {
            if (!bookSales[item.isbn]) {
                bookSales[item.isbn] = {
                    title: item.title,
                    author: '',
                    price: item.price,
                    quantity: 0,
                    revenue: 0,
                    isbn: item.isbn
                };
            }
            bookSales[item.isbn].quantity += item.quantity;
            bookSales[item.isbn].revenue += item.quantity * item.price;
        });
    });

    window.books.forEach(book => {
        if (bookSales[book.isbn]) {
            bookSales[book.isbn].author = book.author;
            bookSales[book.isbn].condition = book.condition;
        }
    });

    const topSellers = Object.values(bookSales)
        .sort((a, b) => b.quantity - a.quantity)
        .slice(0, 8);

    if (topSellers.length === 0) {
        const featuredBooks = window.books.slice(0, 8);
        bestSellersGrid.innerHTML = featuredBooks.map((book, index) => `
            <div class="best-seller-card" style="animation: fadeIn 0.3s ease-out ${index * 0.05}s both;">
                <div class="best-seller-rank">#${index + 1}</div>
                <div class="best-seller-icon">${index === 0 ? getIcon('crown') : getIcon('book')}</div>
                <h4>${escapeHtml(book.title)}</h4>
                <div class="best-seller-author">by ${escapeHtml(book.author)}</div>
                <div class="best-seller-stats">
                    <div class="best-seller-stat">
                        <div class="best-seller-stat-value">0</div>
                        <div class="best-seller-stat-label">sold</div>
                    </div>
                    <div class="best-seller-stat">
                        <div class="best-seller-stat-value">$${book.price.toFixed(2)}</div>
                        <div class="best-seller-stat-label">price</div>
                    </div>
                </div>
                <div class="best-seller-price">$${book.price.toFixed(2)}</div>
                <span class="best-seller-badge">${book.condition}</span>
                <p style="color: #AE7F62; font-size: 0.8rem; margin-top: 0.5rem;">✨ Featured</p>
            </div>
        `).join('');
        return;
    }

    bestSellersGrid.innerHTML = topSellers.map((book, index) => `
        <div class="best-seller-card" style="animation: fadeIn 0.3s ease-out ${index * 0.05}s both;">
            <div class="best-seller-rank">#${index + 1}</div>
            <div class="best-seller-icon">${index === 0 ? getIcon('crown') : index === 1 ? getIcon('medal') : index === 2 ? getIcon('star') : getIcon('book')}</div>
            <h4>${escapeHtml(book.title)}</h4>
            <div class="best-seller-author">by ${escapeHtml(book.author || 'Unknown')}</div>
            <div class="best-seller-stats">
                <div class="best-seller-stat">
                    <div class="best-seller-stat-value">${book.quantity}</div>
                    <div class="best-seller-stat-label">sold</div>
                </div>
                <div class="best-seller-stat">
                    <div class="best-seller-stat-value">$${book.revenue.toFixed(2)}</div>
                    <div class="best-seller-stat-label">revenue</div>
                </div>
            </div>
            <div class="best-seller-price">$${book.price.toFixed(2)}</div>
            <span class="best-seller-badge">${book.condition || 'N/A'}</span>
        </div>
    `).join('');
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}


function loadNotifications() {
    window.notifications = JSON.parse(localStorage.getItem('pahina_notifications')) || [];
    updateNotificationBadge();
}

function saveNotifications() {
    localStorage.setItem('pahina_notifications', JSON.stringify(window.notifications));
    updateNotificationBadge();
}

function addNotification(order) {
    const notification = {
        id: 'notif_' + Date.now(),
        orderId: order.id,
        customerName: order.userName,
        total: order.total,
        timestamp: new Date().toISOString(),
        read: false,
        type: 'new_order',
        message: `🛒 New order #${order.id.slice(-6)} from ${order.userName} - $${order.total.toFixed(2)}`
    };

    window.notifications.unshift(notification);
    saveNotifications();
    showToast(`✨ New order received from ${order.userName}!`, 'info');
    playNotificationSound();
}

function playNotificationSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        oscillator.frequency.value = 800;
        gainNode.gain.value = 0.1;
        oscillator.start();
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (e) {

    }
}

function updateNotificationBadge() {
    const unreadCount = window.notifications.filter(n => !n.read).length;
    const ordersNavBtn = document.querySelector('.nav-btn[onclick*="showSection(\'orders-section\')"]');
    if (ordersNavBtn) {
        if (unreadCount > 0) {
            ordersNavBtn.classList.add('notification-badge');
            const existingBadge = ordersNavBtn.querySelector('.badge');
            if (existingBadge) {
                existingBadge.textContent = unreadCount;
            } else {
                const badge = document.createElement('span');
                badge.className = 'badge';
                badge.textContent = unreadCount;
                ordersNavBtn.appendChild(badge);
            }
        } else {
            ordersNavBtn.classList.remove('notification-badge');
            const badge = ordersNavBtn.querySelector('.badge');
            if (badge) badge.remove();
        }
    }

    document.title = unreadCount > 0 ? `(${unreadCount}) 📚 Pahina Admin` : '📚 Pahina Admin';
    updateNotificationPanel();
}

function updateNotificationPanel() {
    const notificationList = document.getElementById('notificationList');
    if (!notificationList) return;

    if (window.notifications.length === 0) {
        notificationList.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: #AE7F62;">
                ${getIcon('bell')} No new notifications
            </div>
        `;
        return;
    }

    notificationList.innerHTML = window.notifications.slice(0, 10).map(notif => `
        <div class="notification-item ${notif.read ? '' : 'new'}" onclick="markNotificationRead('${notif.id}'); showSection('orders-section'); toggleNotificationPanel(false);">
            <div class="notification-title">
                <span>${notif.type === 'new_order' ? getIcon('cart') : getIcon('money')} New Order</span>
                <span class="notification-time">${timeAgo(notif.timestamp)}</span>
            </div>
            <div class="notification-message">${escapeHtml(notif.message)}</div>
        </div>
    `).join('');
}

function timeAgo(timestamp) {
    const now = new Date();
    const past = new Date(timestamp);
    const diffMs = now - past;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);
    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return diffMins + 'm ago';
    if (diffHours < 24) return diffHours + 'h ago';
    return diffDays + 'd ago';
}

function markNotificationRead(notificationId) {
    const notification = window.notifications.find(n => n.id === notificationId);
    if (notification) {
        notification.read = true;
        saveNotifications();
        updateNotificationPanel();
    }
}

function markAllNotificationsRead() {
    window.notifications.forEach(n => n.read = true);
    saveNotifications();
}

function toggleNotificationPanel(show) {
    const panel = document.getElementById('notificationPanel');
    if (show !== undefined) {
        if (show) {
            panel.classList.add('show');
            markAllNotificationsRead();
        } else {
            panel.classList.remove('show');
        }
    } else {
        panel.classList.toggle('show');
        if (panel.classList.contains('show')) {
            markAllNotificationsRead();
        }
    }
}

function checkForNewOrders() {
    loadData();
    const pendingOrders = window.orders.filter(o => o.approvalStatus === 'pending');
    pendingOrders.forEach(order => {
        const existingNotification = window.notifications.find(n => n.orderId === order.id);
        if (!existingNotification) {
            addNotification(order);
        }
    });
    if (document.getElementById('orders-section')?.classList.contains('active')) {
        loadOrders();
    }
    if (document.getElementById('dashboard-section')?.classList.contains('active')) {
        loadAdminDashboard();
        loadDashboardPreviews();
    }
}


function loadData() {
    window.books = JSON.parse(localStorage.getItem('pahina_books')) || [];
    window.users = JSON.parse(localStorage.getItem('pahina_users')) || [];
    window.orders = JSON.parse(localStorage.getItem('pahina_orders')) || [];
    window.currentUser = JSON.parse(localStorage.getItem('pahina_currentUser')) || null;
    loadNotifications();

    const adminExists = window.users.some(u => u.email === 'admin.pahina@gmail.com');
    if (!adminExists) {
        window.users.push({
            id: 'admin_' + Date.now(),
            name: 'Admin Pahina',
            email: 'admin.pahina@gmail.com',
            password: 'pahina123',
            isAdmin: true,
            profilePic: null,
            birthday: '1990-01-01',
            phone: '',
            address: '',
            joinDate: new Date().toISOString()
        });
        saveUsers();
    }

    if (window.books.length === 0) {
        window.books = [
            { id: '1', isbn: '9780141439518', title: 'Pride and Prejudice', author: 'Jane Austen', price: 12.99, stock: 15, synopsis: 'A classic novel of love and social standing in 19th century England.', condition: 'good', image: null },
            { id: '2', isbn: '9780061120084', title: 'To Kill a Mockingbird', author: 'Harper Lee', price: 14.99, stock: 8, synopsis: 'A gripping, heart-wrenching tale of racial injustice in the Deep South.', condition: 'like-new', image: null },
            { id: '3', isbn: '9780451524935', title: '1984', author: 'George Orwell', price: 11.99, stock: 3, synopsis: 'A dystopian social science fiction novel and cautionary tale.', condition: 'acceptable', image: null },
            { id: '4', isbn: '9780743273565', title: 'The Great Gatsby', author: 'F. Scott Fitzgerald', price: 13.99, stock: 10, synopsis: 'A critique of the American Dream set in the Jazz Age.', condition: 'new', image: null }
        ];
        saveBooks();
    }
}

function saveBooks() {
    localStorage.setItem('pahina_books', JSON.stringify(window.books));
}

function saveUsers() {
    localStorage.setItem('pahina_users', JSON.stringify(window.users));
}

function saveOrders() {
    localStorage.setItem('pahina_orders', JSON.stringify(window.orders));
}

function saveCurrentUser() {
    localStorage.setItem('pahina_currentUser', JSON.stringify(window.currentUser));
}


function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    updateNavigation();

    if (sectionId === 'dashboard-section') {
        loadAdminDashboard();
        loadDashboardPreviews();
    } else if (sectionId === 'inventory-section') {
        loadInventory();
        document.getElementById('totalBooksCount').textContent = window.books.length;
    } else if (sectionId === 'orders-section') {
        loadOrders();
        document.getElementById('totalOrdersCount').textContent = window.orders.length;
    } else if (sectionId === 'reports-section') {
        loadSalesReports();
    } else if (sectionId === 'users-section') {
        loadUsers();
    }
    toggleNotificationPanel(false);
}

function updateNavigation() {
    const navButtons = document.getElementById('navButtons');
    if (!navButtons) return;

    if (window.currentUser && window.currentUser.isAdmin) {
        document.getElementById('bestSellersContainer').style.display = 'none';
        let profileHtml = '';
        if (window.currentUser.profilePic) {
            profileHtml = `<img src="${window.currentUser.profilePic}" alt="${window.currentUser.name}">`;
        } else {
            profileHtml = `<span>${window.currentUser.name.charAt(0).toUpperCase()}</span>`;
        }
        const unreadCount = window.notifications.filter(n => !n.read).length;

        navButtons.innerHTML = `
            <button class="nav-btn ${document.getElementById('dashboard-section').classList.contains('active') ? 'active' : ''}" onclick="showSection('dashboard-section')">
                ${getIcon('dashboard')} Dashboard
            </button>
            <button class="nav-btn ${document.getElementById('inventory-section').classList.contains('active') ? 'active' : ''}" onclick="showSection('inventory-section')">
                ${getIcon('inventory')} Inventory
            </button>
            <button class="nav-btn notification-badge ${document.getElementById('orders-section').classList.contains('active') ? 'active' : ''}" onclick="showSection('orders-section')">
                ${getIcon('orders')} Orders
                ${unreadCount > 0 ? `<span class="badge">${unreadCount}</span>` : ''}
            </button>
            <button class="nav-btn ${document.getElementById('reports-section').classList.contains('active') ? 'active' : ''}" onclick="showSection('reports-section')">
                ${getIcon('reports')} Reports
            </button>
            <button class="nav-btn ${document.getElementById('users-section').classList.contains('active') ? 'active' : ''}" onclick="showSection('users-section')">
                ${getIcon('users')} Users
            </button>
            <button class="nav-btn" onclick="toggleNotificationPanel()">
                ${getIcon('notifications')}
                ${unreadCount > 0 ? `<span class="badge">${unreadCount}</span>` : ''}
            </button>
            <div class="profile-container">
                <div class="profile-avatar" onclick="toggleDropdown()">
                    ${profileHtml}
                </div>
                <div class="dropdown-menu" id="profileDropdown">
                    <div class="dropdown-header">
                        <h4>${escapeHtml(window.currentUser.name)}</h4>
                        <p>${escapeHtml(window.currentUser.email)}</p>
                    </div>
                    <button class="dropdown-item" onclick="openProfileSettings()">
                        ${getIcon('settings')} Settings
                    </button>
                    <button class="dropdown-item" onclick="logout()">
                        ${getIcon('logout')} Logout
                    </button>
                </div>
            </div>
        `;
    } else {
        document.getElementById('bestSellersContainer').style.display = 'flex';
        document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
        navButtons.innerHTML = `
            <button class="view-user-btn" onclick="openUserInterface()">
                ${getIcon('users')} View User Interface
            </button>
            <button class="nav-btn" onclick="openLoginModal()">
                ${getIcon('admin')} Admin Login
            </button>
        `;
        loadBestSellers();
    }
}

function toggleDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) dropdown.classList.toggle('show');
}

function openProfileSettings() {
    if (window.currentUser) {
        showToast('Profile settings coming soon!', 'info');
    }
}


function openModal(modalName) {
    document.getElementById(`${modalName}Modal`).classList.add('active');
}

function closeModal(modalName) {
    document.getElementById(`${modalName}Modal`).classList.remove('active');
}

function openLoginModal() {
    document.getElementById('loginEmail').value = '';
    document.getElementById('loginPassword').value = '';
    openModal('login');
}


function loginUser(event) {
    event.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const user = window.users.find(u => u.email === email && u.password === password);
    if (user && user.isAdmin) {
        window.currentUser = user;
        saveCurrentUser();
        closeModal('login');
        showToast(`✨ Welcome back, ${user.name}!`, 'success');
        updateNavigation();
        showSection('dashboard-section');
        loadAdminDashboard();
        loadDashboardPreviews();
        if (window.notificationCheckInterval) {
            clearInterval(window.notificationCheckInterval);
        }
        window.notificationCheckInterval = setInterval(checkForNewOrders, 5000);
        updateConnectionStatus(window.isOnline);
        syncData();
    } else {
        showToast('❌ Invalid admin credentials!', 'error');
    }
}

function logout() {
    window.currentUser = null;
    saveCurrentUser();
    if (window.notificationCheckInterval) {
        clearInterval(window.notificationCheckInterval);
        window.notificationCheckInterval = null;
    }
    updateNavigation();
    showToast('👋 Logged out successfully', 'info');
}


function loadAdminDashboard() {
    if (!window.currentUser?.isAdmin) return;
    const totalRevenue = window.orders.reduce((sum, order) => sum + order.total, 0);
    const pendingOrders = window.orders.filter(o => o.approvalStatus === 'pending').length;
    const pendingPayments = window.orders.filter(o => o.paymentStatus === 'Awaiting Payment' || o.paymentStatus === 'Pending').length;
    const totalBooks = window.books.reduce((sum, book) => sum + book.stock, 0);
    const totalUsers = window.users.length;
    const completedOrders = window.orders.filter(o => o.status === 'delivered').length;
    const completionRate = window.orders.length > 0 ? ((completedOrders / window.orders.length) * 100).toFixed(0) : 0;
    
    document.getElementById('adminStats').innerHTML = `
        <div class="stat-card stat-highlight" style="animation: fadeIn 0.3s ease-out 0.1s both;">
            <div class="stat-icon">${getIcon('revenue')}</div>
            <h4>Total Revenue</h4>
            <div class="stat-value">$${totalRevenue.toFixed(2)}</div>
            <div class="stat-label">All time sales</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.2s both;">
            <div class="stat-icon">${getIcon('pending')}</div>
            <h4>Pending Orders</h4>
            <div class="stat-value">${pendingOrders}</div>
            <div class="stat-label">Awaiting approval</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.3s both;">
            <div class="stat-icon">${getIcon('payment')}</div>
            <h4>Pending Payments</h4>
            <div class="stat-value">${pendingPayments}</div>
            <div class="stat-label">Awaiting confirmation</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.4s both;">
            <div class="stat-icon">${getIcon('stock')}</div>
            <h4>Books in Stock</h4>
            <div class="stat-value">${totalBooks}</div>
            <div class="stat-label">Total inventory</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.5s both;">
            <div class="stat-icon">${getIcon('users')}</div>
            <h4>Total Users</h4>
            <div class="stat-value">${totalUsers}</div>
            <div class="stat-label">Registered customers</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.6s both;">
            <div class="stat-icon">${getIcon('complete')}</div>
            <h4>Completion Rate</h4>
            <div class="stat-value">${completionRate}%</div>
            <div class="stat-label">Orders delivered</div>
        </div>
    `;
}

function loadDashboardPreviews() {
    const recentOrders = window.orders.sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 5);
    const recentOrdersBody = document.getElementById('recentOrdersPreview');
    if (recentOrders.length === 0) {
        recentOrdersBody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #AE7F62;">✨ No orders yet</td></tr>';
    } else {
        recentOrdersBody.innerHTML = recentOrders.map(order => `
            <tr style="transition: all 0.2s;">
                <td style="font-weight: 500;">#${order.id.slice(-6)}</td>
                <td>${escapeHtml(order.userName)}</td>
                <td>${new Date(order.date).toLocaleDateString()}</td>
                <td><strong>$${order.total.toFixed(2)}</strong></td>
                <td>${order.paymentMethod === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.paymentMethod}</td>
                <td>
                    <span class="status-badge ${order.paymentStatus === 'Paid' ? 'payment-paid' : 'payment-pending'}">
                        ${order.paymentStatus === 'Paid' ? getIcon('check') : getIcon('clock')} ${order.paymentStatus}
                    </span>
                </td>
            </tr>
        `).join('');
    }

    const lowStockBooks = window.books.filter(book => book.stock > 0 && book.stock < 5);
    const lowStockBody = document.getElementById('lowStockPreview');
    if (lowStockBooks.length === 0) {
        lowStockBody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #22c55e;">✅ All items well stocked</td></tr>';
    } else {
        lowStockBody.innerHTML = lowStockBooks.map(book => `
            <tr>
                <td>${book.isbn}</td>
                <td><strong>${escapeHtml(book.title)}</strong></td>
                <td>${escapeHtml(book.author)}</td>
                <td>
                    <span class="status-badge ${book.stock < 3 ? 'status-cancelled' : 'status-pending'}">
                        ${getIcon('stock')} ${book.stock} left
                    </span>
                </td>
                <td>
                    <button class="action-btn edit" onclick="editBook('${book.id}')">${getIcon('restock')} Restock</button>
                </td>
            </tr>
        `).join('');
    }
}


function loadInventory() {
    const inventoryBody = document.getElementById('inventoryBody');
    inventoryBody.innerHTML = window.books.map((book, index) => {
        const imageHtml = book.image ? 
            `<img src="${book.image}" alt="${book.title}" class="inventory-image">` :
            `<div class="inventory-image-placeholder">${getIcon('book')}</div>`;
        const stockClass = book.stock > 10 ? 'status-shipped' : book.stock > 0 ? 'status-pending' : 'status-cancelled';
        const stockText = book.stock > 10 ? `${book.stock} available` : book.stock > 0 ? `⚠️ ${book.stock} left` : '❌ Out of stock';
        return `
            <tr style="animation: fadeIn 0.2s ease-out ${index * 0.02}s both;">
                <td>${imageHtml}</td>
                <td><code style="font-size: 0.8rem;">${book.isbn}</code></td>
                <td><strong>${escapeHtml(book.title)}</strong></td>
                <td>${escapeHtml(book.author)}</td>
                <td style="color: #AE7F62; font-weight: 500;">$${book.price.toFixed(2)}</td>
                <td>
                    <span class="status-badge ${stockClass}">
                        ${stockText}
                    </span>
                </td>
                <td><span style="text-transform: capitalize; background: #AE7F6220; padding: 0.2rem 0.6rem; border-radius: 20px;">${book.condition}</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn edit" onclick="editBook('${book.id}')">${getIcon('edit')} Edit</button>
                        <button class="action-btn delete" onclick="deleteBook('${book.id}')">${getIcon('delete')} Delete</button>
                        <button class="action-btn view" onclick="previewBook('${book.isbn}')">${getIcon('view')} View</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    document.getElementById('totalBooksCount').textContent = window.books.length;
}

function openAddBookModal() {
    document.getElementById('bookModalTitle').textContent = `✨ Add New Book`;
    document.getElementById('bookId').value = '';
    document.getElementById('bookISBN').value = '';
    document.getElementById('bookTitle').value = '';
    document.getElementById('bookAuthor').value = '';
    document.getElementById('bookPrice').value = '';
    document.getElementById('bookStock').value = '';
    document.getElementById('bookSynopsis').value = '';
    document.getElementById('bookCondition').value = 'new';
    document.getElementById('imagePreview').classList.remove('show');
    document.getElementById('imagePreview').src = '#';
    openModal('book');
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.add('show');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function saveBook(event) {
    event.preventDefault();
    const bookId = document.getElementById('bookId').value;
    const imagePreview = document.getElementById('imagePreview');
    const bookData = {
        id: bookId || 'book_' + Date.now(),
        isbn: document.getElementById('bookISBN').value,
        title: document.getElementById('bookTitle').value,
        author: document.getElementById('bookAuthor').value,
        price: parseFloat(document.getElementById('bookPrice').value),
        stock: parseInt(document.getElementById('bookStock').value),
        synopsis: document.getElementById('bookSynopsis').value,
        condition: document.getElementById('bookCondition').value,
        image: imagePreview.classList.contains('show') ? imagePreview.src : null
    };
    if (bookId) {
        const index = window.books.findIndex(b => b.id === bookId);
        if (index !== -1) {
            if (!bookData.image) {
                bookData.image = window.books[index].image;
            }
            window.books[index] = bookData;
            showToast('📚 Book updated successfully!', 'success');
        }
    } else {
        window.books.push(bookData);
        showToast('✨ New book added successfully!', 'success');
    }
    saveBooks();
    closeModal('book');
    loadInventory();
    loadDashboardPreviews();
}

function editBook(bookId) {
    const book = window.books.find(b => b.id === bookId);
    if (book) {
        document.getElementById('bookModalTitle').textContent = `✏️ Edit Book`;
        document.getElementById('bookId').value = book.id;
        document.getElementById('bookISBN').value = book.isbn;
        document.getElementById('bookTitle').value = book.title;
        document.getElementById('bookAuthor').value = book.author;
        document.getElementById('bookPrice').value = book.price;
        document.getElementById('bookStock').value = book.stock;
        document.getElementById('bookSynopsis').value = book.synopsis;
        document.getElementById('bookCondition').value = book.condition;
        if (book.image) {
            const preview = document.getElementById('imagePreview');
            preview.src = book.image;
            preview.classList.add('show');
        } else {
            document.getElementById('imagePreview').classList.remove('show');
        }
        openModal('book');
    }
}

function deleteBook(bookId) {
    if (confirm('⚠️ Are you sure you want to delete this book? This action cannot be undone.')) {
        window.books = window.books.filter(b => b.id !== bookId);
        saveBooks();
        loadInventory();
        loadDashboardPreviews();
        showToast('🗑️ Book deleted successfully', 'success');
    }
}

function previewBook(isbn) {
    const book = window.books.find(b => b.isbn === isbn);
    if (book) {
        const modalContent = `
            <div style="text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">${getIcon('book')}</div>
                <h3 style="color: #231810;">${escapeHtml(book.title)}</h3>
                <p style="color: #AE7F62;">by ${escapeHtml(book.author)}</p>
                <hr style="margin: 1rem 0; border-color: #AE7F62;">
                <p><strong>Price:</strong> <span style="color: #613D28; font-size: 1.2rem;">$${book.price.toFixed(2)}</span></p>
                <p><strong>Stock:</strong> ${book.stock} copies</p>
                <p><strong>Condition:</strong> ${book.condition}</p>
                <hr style="margin: 1rem 0; border-color: #AE7F62;">
                <p><strong>Synopsis:</strong></p>
                <p style="color: #613D28;">${escapeHtml(book.synopsis)}</p>
            </div>
        `;
        const tempModal = document.createElement('div');
        tempModal.className = 'modal active';
        tempModal.innerHTML = `
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3>${getIcon('book')} Book Preview</h3>
                    <button class="close-btn" onclick="this.closest('.modal').remove()">${getIcon('close')}</button>
                </div>
                ${modalContent}
            </div>
        `;
        document.body.appendChild(tempModal);
        tempModal.addEventListener('click', (e) => {
            if (e.target === tempModal) tempModal.remove();
        });
    }
}


function loadOrders() {
    const ordersBody = document.getElementById('ordersBody');
    ordersBody.innerHTML = window.orders.sort((a, b) => new Date(b.date) - new Date(a.date)).map((order, index) => {
        const displayStatus = order.approvalStatus === 'pending' ? 'Pending Approval' :
                             order.approvalStatus === 'cancelled' ? 'Cancelled' :
                             order.status;
        const statusClass = order.approvalStatus === 'pending' ? 'pending' :
                           order.approvalStatus === 'cancelled' ? 'cancelled' :
                           order.status;
        const paymentStatusClass = order.paymentStatus === 'Paid' ? 'payment-paid' : 
                                  order.paymentStatus === 'Awaiting Payment' ? 'payment-awaiting' : 'payment-pending';
        return `
            <tr style="animation: fadeIn 0.2s ease-out ${index * 0.01}s both;">
                <td style="font-weight: 600;">#${order.id.slice(-6)}</td>
                <td>${escapeHtml(order.userName)}</td>
                <td>${new Date(order.date).toLocaleDateString()}</td>
                <td>${order.items.length} items</td>
                <td style="color: #613D28; font-weight: 700;">$${order.total.toFixed(2)}</td>
                <td>${order.paymentMethod === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.paymentMethod}</td>
                <td>
                    <span class="status-badge ${paymentStatusClass}">
                        ${order.paymentStatus === 'Paid' ? getIcon('check') : getIcon('clock')} ${order.paymentStatus}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-${statusClass}">
                        ${displayStatus}
                    </span>
                </td>
                <td>
                    ${order.paymentDetails ? 
                        `<button class="action-btn view" onclick="viewPaymentDetails('${order.id}')">${getIcon('view')} Details</button>` : 
                        '<span style="color: #AE7F62;">—</span>'}
                </td>
                <td>
                    <div class="action-buttons">
                        ${order.approvalStatus === 'pending' ? 
                            `<button class="action-btn approve" onclick="approveOrder('${order.id}')">${getIcon('approve')} Approve</button>
                             <button class="action-btn cancel" onclick="rejectOrder('${order.id}')">${getIcon('reject')} Reject</button>` : 
                          order.approvalStatus === 'approved' && order.status === 'pending' ?
                            `<button class="action-btn ship" onclick="updateOrderStatus('${order.id}', 'shipped')">${getIcon('ship')} Ship</button>` :
                          order.status === 'shipped' ?
                            `<button class="action-btn approve" onclick="updateOrderStatus('${order.id}', 'delivered')">${getIcon('deliver')} Deliver</button>` :
                          order.status === 'delivered' ?
                            `<span style="color: #22c55e; font-weight: 500;">${getIcon('complete')} Completed</span>` :
                          order.approvalStatus === 'cancelled' ?
                            `<span style="color: #ef4444; font-weight: 500;">${getIcon('reject')} Rejected</span>` : ''}
                        ${(order.paymentMethod === 'Bank Transfer' && order.paymentStatus === 'Awaiting Payment') || 
                           (order.paymentMethod === 'Cash on Delivery' && order.paymentStatus === 'Pending') ?
                            `<button class="action-btn payment" onclick="openPaymentApproveModal('${order.id}')">${getIcon('payment')} Confirm Payment</button>` : ''}
                        <button class="action-btn view" onclick="viewOrderDetails('${order.id}')">${getIcon('view')} View</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    document.getElementById('totalOrdersCount').textContent = window.orders.length;
}

function viewOrderDetails(orderId) {
    const order = window.orders.find(o => o.id === orderId);
    if (order) {
        const detailsDiv = document.getElementById('orderDetails');
        const paymentStatusClass = order.paymentStatus === 'Paid' ? 'payment-paid' : 
                                  order.paymentStatus === 'Awaiting Payment' ? 'payment-awaiting' : 'payment-pending';
        const displayStatus = order.approvalStatus === 'pending' ? 'Pending Approval' :
                             order.approvalStatus === 'cancelled' ? 'Cancelled' :
                             order.status;
        const statusClass = order.approvalStatus === 'pending' ? 'pending' :
                           order.approvalStatus === 'cancelled' ? 'cancelled' :
                           order.status;
        let paymentDetailsHtml = '';
        if (order.paymentDetails) {
            paymentDetailsHtml = `
                <h4 style="color: #231810; margin: 1.5rem 0 1rem;">${getIcon('bank')} Payment Details</h4>
                <div style="background: #AE7F6220; padding: 1rem; border-radius: 12px;">
                    <p><strong>Bank:</strong> ${escapeHtml(order.paymentDetails.senderBank)}</p>
                    <p><strong>Account Name:</strong> ${escapeHtml(order.paymentDetails.senderAccountName)}</p>
                    <p><strong>Account Number:</strong> ${escapeHtml(order.paymentDetails.senderAccountNumber)}</p>
                    <p><strong>Reference Number:</strong> ${escapeHtml(order.paymentDetails.referenceNumber)}</p>
                    <p><strong>Transfer Date:</strong> ${order.paymentDetails.transferDate}</p>
                    <p><strong>Transfer Time:</strong> ${order.paymentDetails.transferTime}</p>
                    <p><strong>Amount Transferred:</strong> <strong style="color: #613D28;">$${order.paymentDetails.transferAmount.toFixed(2)}</strong></p>
                    <p><strong>Additional Notes:</strong> ${escapeHtml(order.paymentDetails.additionalNotes)}</p>
                </div>
            `;
        }
        let deliveryHtml = '';
        if (order.deliveryAddress) {
            deliveryHtml = `
                <h4 style="color: #231810; margin: 1.5rem 0 1rem;">${getIcon('ship')} Delivery Details</h4>
                <div style="background: #FCCDAC; padding: 1rem; border-radius: 12px; border-left: 4px solid #613D28;">
                    <p><strong>Recipient:</strong> ${escapeHtml(order.recipientName || order.userName)}</p>
                    <p><strong>Address:</strong> ${escapeHtml(order.deliveryAddress)}</p>
                    <p><strong>Contact Number:</strong> ${escapeHtml(order.contactNumber)}</p>
                    ${order.deliveryInstructions ? `<p><strong>Instructions:</strong> ${escapeHtml(order.deliveryInstructions)}</p>` : ''}
                </div>
            `;
        }
        detailsDiv.innerHTML = `
            <div style="margin-bottom: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div><strong>Order ID:</strong> #${order.id.slice(-6)}</div>
                <div><strong>Date:</strong> ${new Date(order.date).toLocaleString()}</div>
                <div><strong>Order Status:</strong> <span class="status-badge status-${statusClass}">${displayStatus}</span></div>
                <div><strong>Payment Method:</strong> ${order.paymentMethod === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.paymentMethod}</div>
                <div><strong>Payment Status:</strong> <span class="status-badge ${paymentStatusClass}">${order.paymentStatus}</span></div>
                <div><strong>Customer:</strong> ${escapeHtml(order.userName)}</div>
            </div>
            <h4 style="color: #231810; margin-bottom: 1rem;">${getIcon('inventory')} Order Items</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #613D28; color: #FCCDAC;">
                        <th style="padding: 0.75rem; text-align: left;">Book</th>
                        <th style="padding: 0.75rem; text-align: center;">Quantity</th>
                        <th style="padding: 0.75rem; text-align: right;">Price</th>
                        <th style="padding: 0.75rem; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${order.items.map(item => `
                        <tr style="border-bottom: 1px solid #AE7F62;">
                            <td style="padding: 0.75rem;">${escapeHtml(item.title)}</td>
                            <td style="padding: 0.75rem; text-align: center;">${item.quantity}</td>
                            <td style="padding: 0.75rem; text-align: right;">$${item.price.toFixed(2)}</td>
                            <td style="padding: 0.75rem; text-align: right;">$${(item.quantity * item.price).toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
                <tfoot>
                    <tr style="background: #AE7F6220;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Subtotal:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;">$${order.subtotal.toFixed(2)}</td>
                    </tr>
                    <tr style="background: #AE7F6220;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Shipping Fee:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;">$${order.shippingFee.toFixed(2)}</td>
                    </tr>
                    <tr style="background: #AE7F6220; font-weight: bold;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Total:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;"><strong style="color: #613D28;">$${order.total.toFixed(2)}</strong></td>
                    </tr>
                </tfoot>
            </table>
            ${deliveryHtml}
            ${paymentDetailsHtml}
        `;
        openModal('order');
    }
}

function approveOrder(orderId) {
    const order = window.orders.find(o => o.id === orderId);
    if (order) {
        let hasEnoughStock = true;
        order.items.forEach(item => {
            const book = window.books.find(b => b.isbn === item.isbn);
            if (!book || book.stock < item.quantity) {
                hasEnoughStock = false;
                showToast(`⚠️ Insufficient stock for ${item.title}`, 'error');
            }
        });
        if (!hasEnoughStock) return;
        order.items.forEach(item => {
            const book = window.books.find(b => b.isbn === item.isbn);
            if (book) {
                book.stock -= item.quantity;
            }
        });
        order.approvalStatus = 'approved';
        order.status = 'pending';
        saveOrders();
        saveBooks();
        loadOrders();
        loadDashboardPreviews();
        loadInventory();
        showToast(`✅ Order #${order.id.slice(-6)} approved successfully`, 'success');
        window.notifications.forEach(n => {
            if (n.orderId === orderId) {
                n.read = true;
            }
        });
        saveNotifications();
    }
}

function rejectOrder(orderId) {
    if (confirm('⚠️ Are you sure you want to reject this order?')) {
        const order = window.orders.find(o => o.id === orderId);
        if (order) {
            order.approvalStatus = 'cancelled';
            order.status = 'cancelled';
            saveOrders();
            loadOrders();
            loadDashboardPreviews();
            showToast(`❌ Order #${order.id.slice(-6)} rejected`, 'info');
            window.notifications.forEach(n => {
                if (n.orderId === orderId) {
                    n.read = true;
                }
            });
            saveNotifications();
        }
    }
}

function updateOrderStatus(orderId, newStatus) {
    const order = window.orders.find(o => o.id === orderId);
    if (order) {
        order.status = newStatus;
        saveOrders();
        loadOrders();
        loadDashboardPreviews();
        const statusEmoji = newStatus === 'shipped' ? getIcon('ship') : newStatus === 'delivered' ? getIcon('complete') : getIcon('pending');
        showToast(`${statusEmoji} Order #${order.id.slice(-6)} updated to ${newStatus}`, 'success');
    }
}

function openPaymentApproveModal(orderId) {
    const order = window.orders.find(o => o.id === orderId);
    if (!order) return;
    window.pendingPaymentOrderId = orderId;
    let paymentDetailsHtml = '';
    if (order.paymentDetails) {
        paymentDetailsHtml = `
            <div style="background: #AE7F6220; padding: 1rem; border-radius: 12px; margin: 1rem 0;">
                <h5 style="color: #231810; margin-bottom: 0.5rem;">${getIcon('money')} Customer Payment Details:</h5>
                <p><strong>Bank:</strong> ${escapeHtml(order.paymentDetails.senderBank)}</p>
                <p><strong>Account Name:</strong> ${escapeHtml(order.paymentDetails.senderAccountName)}</p>
                <p><strong>Account Number:</strong> ${escapeHtml(order.paymentDetails.senderAccountNumber)}</p>
                <p><strong>Reference Number:</strong> ${escapeHtml(order.paymentDetails.referenceNumber)}</p>
                <p><strong>Transfer Date:</strong> ${order.paymentDetails.transferDate}</p>
                <p><strong>Transfer Time:</strong> ${order.paymentDetails.transferTime}</p>
                <p><strong>Amount Transferred:</strong> <strong style="color: #613D28;">$${order.paymentDetails.transferAmount.toFixed(2)}</strong></p>
                <p><strong>Additional Notes:</strong> ${escapeHtml(order.paymentDetails.additionalNotes)}</p>
            </div>
        `;
    }
    let deliveryHtml = '';
    if (order.deliveryAddress) {
        deliveryHtml = `
            <div style="background: #FCCDAC; padding: 1rem; border-radius: 12px; margin: 1rem 0; border-left: 4px solid #613D28;">
                <h5 style="color: #231810; margin-bottom: 0.5rem;">${getIcon('ship')} Delivery Details:</h5>
                <p><strong>Recipient:</strong> ${escapeHtml(order.recipientName || order.userName)}</p>
                <p><strong>Address:</strong> ${escapeHtml(order.deliveryAddress)}</p>
                <p><strong>Contact:</strong> ${escapeHtml(order.contactNumber)}</p>
                ${order.deliveryInstructions ? `<p><strong>Instructions:</strong> ${escapeHtml(order.deliveryInstructions)}</p>` : ''}
            </div>
        `;
    }
    const content = `
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">${order.paymentMethod === 'Cash on Delivery' ? getIcon('cash') : getIcon('bank')}</div>
            <h4 style="color: #231810;">Confirm Payment for Order #${order.id.slice(-6)}</h4>
        </div>
        <div style="background: #FCCDAC; padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem;">
            <p><strong>Customer:</strong> ${escapeHtml(order.userName)}</p>
            <p><strong>Payment Method:</strong> ${order.paymentMethod === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.paymentMethod}</p>
            <p><strong>Current Status:</strong> <span class="status-badge ${order.paymentStatus === 'Pending' ? 'payment-pending' : 'payment-awaiting'}">${order.paymentStatus}</span></p>
            <p><strong>Total Amount:</strong> <strong style="color: #613D28;">$${order.total.toFixed(2)}</strong></p>
            <p><strong>Order Date:</strong> ${new Date(order.date).toLocaleString()}</p>
        </div>
        ${deliveryHtml}
        ${paymentDetailsHtml}
        <div style="margin: 1.5rem 0; padding: 1rem; background: #FCCDAC; border-radius: 8px; border-left: 4px solid #613D28;">
            <strong>⚠️ Confirmation Action:</strong>
            <p style="margin-top: 0.5rem;">Mark this payment as confirmed. This will update the payment status to "Paid" and allow the order to proceed.</p>
        </div>
    `;
    document.getElementById('paymentApproveContent').innerHTML = content;
    openModal('paymentApprove');
}

function approvePayment() {
    if (!window.pendingPaymentOrderId) {
        closeModal('paymentApprove');
        return;
    }
    const order = window.orders.find(o => o.id === window.pendingPaymentOrderId);
    if (order) {
        order.paymentStatus = 'Paid';
        saveOrders();
        loadOrders();
        loadDashboardPreviews();
        showToast(`✅ Payment confirmed for order #${order.id.slice(-6)}`, 'success');
    }
    closeModal('paymentApprove');
    window.pendingPaymentOrderId = null;
}

function viewPaymentDetails(orderId) {
    const order = window.orders.find(o => o.id === orderId);
    if (!order || !order.paymentDetails) {
        showToast('No payment details available', 'info');
        return;
    }
    const details = order.paymentDetails;
    const content = `
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">${getIcon('bank')}</div>
            <h4 style="color: #231810;">Bank Transfer Details</h4>
        </div>
        <div style="background: #AE7F6220; padding: 1.5rem; border-radius: 12px;">
            <h5 style="color: #231810; margin-bottom: 1rem;">${getIcon('users')} Sender Information:</h5>
            <p><strong>Bank:</strong> ${escapeHtml(details.senderBank)}</p>
            <p><strong>Account Name:</strong> ${escapeHtml(details.senderAccountName)}</p>
            <p><strong>Account Number:</strong> ${escapeHtml(details.senderAccountNumber)}</p>
            <h5 style="color: #231810; margin: 1rem 0;">${getIcon('money')} Transfer Information:</h5>
            <p><strong>Reference Number:</strong> ${escapeHtml(details.referenceNumber)}</p>
            <p><strong>Transfer Date:</strong> ${details.transferDate}</p>
            <p><strong>Transfer Time:</strong> ${details.transferTime}</p>
            <p><strong>Amount Transferred:</strong> <strong style="color: #613D28;">$${details.transferAmount.toFixed(2)}</strong></p>
            <h5 style="color: #231810; margin: 1rem 0;">📝 Additional Notes:</h5>
            <p style="background: #FCCDAC; padding: 0.5rem; border-radius: 8px;">${escapeHtml(details.additionalNotes)}</p>
        </div>
    `;
    document.getElementById('paymentDetailsContent').innerHTML = content;
    openModal('paymentDetails');
}


function loadUsers() {
    const searchTerm = document.getElementById('userSearch')?.value.toLowerCase() || '';
    const userType = document.getElementById('userTypeFilter')?.value || 'all';
    let filteredUsers = [...window.users];
    if (searchTerm) {
        filteredUsers = filteredUsers.filter(u => 
            u.name.toLowerCase().includes(searchTerm) ||
            u.email.toLowerCase().includes(searchTerm)
        );
    }
    if (userType === 'customers') {
        filteredUsers = filteredUsers.filter(u => !u.isAdmin);
    } else if (userType === 'admins') {
        filteredUsers = filteredUsers.filter(u => u.isAdmin);
    }
    const usersBody = document.getElementById('usersBody');
    usersBody.innerHTML = filteredUsers.map((user, index) => {
        const userOrders = window.orders.filter(o => o.userId === user.id);
        const totalSpent = userOrders.reduce((sum, o) => sum + o.total, 0);
        const initial = user.name.charAt(0).toUpperCase();
        const avatarHtml = user.profilePic ? 
            `<img src="${user.profilePic}" alt="${user.name}" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover;">` :
            `<div class="user-avatar">${initial}</div>`;
        return `
            <tr style="animation: fadeIn 0.2s ease-out ${index * 0.02}s both;">
                <td>${avatarHtml}</td>
                <td><code style="font-size: 0.8rem;">#${user.id.slice(-6)}</code></td>
                <td><strong>${escapeHtml(user.name)}</strong></td>
                <td>${escapeHtml(user.email)}</td>
                <td>
                    <span class="status-badge ${user.isAdmin ? 'status-shipped' : 'status-pending'}">
                        ${user.isAdmin ? getIcon('admin') : getIcon('customer')} ${user.isAdmin ? 'Admin' : 'Customer'}
                    </span>
                </td>
                <td>${userOrders.length}</td>
                <td><strong style="color: #613D28;">$${totalSpent.toFixed(2)}</strong></td>
                <td>${new Date(user.joinDate).toLocaleDateString()}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" onclick="viewUserDetails('${user.id}')">${getIcon('view')} View</button>
                        <button class="action-btn edit" onclick="editUser('${user.id}')">${getIcon('edit')} Edit</button>
                        ${!user.isAdmin ? 
                            `<button class="action-btn delete" onclick="deleteUser('${user.id}')">${getIcon('delete')} Delete</button>` : 
                            user.id !== window.currentUser?.id ? 
                            `<button class="action-btn delete" onclick="deleteUser('${user.id}')">${getIcon('delete')} Delete</button>` : ''}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function filterUsers() {
    loadUsers();
}

function showAddUserModal() {
    document.getElementById('userModalTitle').textContent = `✨ Add New User`;
    document.getElementById('userId').value = '';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userType').value = 'customer';
    openModal('user');
}

function editUser(userId) {
    const user = window.users.find(u => u.id === userId);
    if (user) {
        document.getElementById('userModalTitle').textContent = `✏️ Edit User`;
        document.getElementById('userId').value = user.id;
        document.getElementById('userName').value = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').required = false;
        document.getElementById('userType').value = user.isAdmin ? 'admin' : 'customer';
        openModal('user');
    }
}

function saveUser(event) {
    event.preventDefault();
    const userId = document.getElementById('userId').value;
    const password = document.getElementById('userPassword').value;
    const userData = {
        id: userId || 'user_' + Date.now(),
        name: document.getElementById('userName').value,
        email: document.getElementById('userEmail').value,
        isAdmin: document.getElementById('userType').value === 'admin',
        joinDate: new Date().toISOString()
    };
    if (userId) {
        const index = window.users.findIndex(u => u.id === userId);
        if (index !== -1) {
            userData.password = password || window.users[index].password;
            userData.birthday = window.users[index].birthday;
            userData.profilePic = window.users[index].profilePic;
            userData.phone = window.users[index].phone;
            userData.address = window.users[index].address;
            window.users[index] = userData;
            showToast('✅ User updated successfully!', 'success');
        }
    } else {
        if (!password) {
            showToast('⚠️ Password is required for new users', 'error');
            return;
        }
        userData.password = password;
        if (window.users.find(u => u.email === userData.email)) {
            showToast('❌ Email already exists!', 'error');
            return;
        }
        window.users.push(userData);
        showToast('✨ New user added successfully!', 'success');
    }
    saveUsers();
    closeModal('user');
    loadUsers();
}

function deleteUser(userId) {
    if (userId === window.currentUser?.id) {
        showToast('❌ You cannot delete your own account!', 'error');
        return;
    }
    const user = window.users.find(u => u.id === userId);
    if (user?.isAdmin) {
        const adminCount = window.users.filter(u => u.isAdmin).length;
        if (adminCount <= 1) {
            showToast('⚠️ Cannot delete the last admin account!', 'error');
            return;
        }
    }
    if (confirm('⚠️ Are you sure you want to delete this user? This action cannot be undone.')) {
        window.users = window.users.filter(u => u.id !== userId);
        saveUsers();
        loadUsers();
        showToast('🗑️ User deleted successfully', 'success');
    }
}

function viewUserDetails(userId) {
    const user = window.users.find(u => u.id === userId);
    if (user) {
        const userOrders = window.orders.filter(o => o.userId === userId);
        const totalSpent = userOrders.reduce((sum, o) => sum + o.total, 0);
        let ageInfo = '';
        if (user.birthday) {
            const birthDate = new Date(user.birthday);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            ageInfo = `<p><strong>Age:</strong> ${age} years old</p>`;
        }
        const profilePicHtml = user.profilePic ? 
            `<img src="${user.profilePic}" alt="${user.name}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem; border: 3px solid #AE7F62;">` :
            `<div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #613D28 0%, #231810 100%); margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: #FCCDAC; font-size: 2rem; font-weight: bold;">
                ${user.name.charAt(0).toUpperCase()}
            </div>`;
        const detailsDiv = document.getElementById('userDetails');
        detailsDiv.innerHTML = `
            <div style="text-align: center; margin-bottom: 2rem;">
                ${profilePicHtml}
                <h3 style="color: #231810;">${escapeHtml(user.name)}</h3>
                <p style="color: #AE7F62;">${escapeHtml(user.email)}</p>
                <span class="status-badge ${user.isAdmin ? 'status-shipped' : 'status-pending'}">
                    ${user.isAdmin ? getIcon('admin') : getIcon('customer')} ${user.isAdmin ? 'Administrator' : 'Customer'}
                </span>
            </div>
            <div style="margin-bottom: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                <p><strong>User ID:</strong> #${user.id.slice(-6)}</p>
                ${user.birthday ? `<p><strong>Birthday:</strong> ${new Date(user.birthday).toLocaleDateString()}</p>` : ''}
                ${ageInfo}
                <p><strong>Phone:</strong> ${user.phone || 'Not provided'}</p>
                <p><strong>Address:</strong> ${user.address || 'Not provided'}</p>
                <p><strong>Member Since:</strong> ${new Date(user.joinDate).toLocaleDateString()}</p>
                <p><strong>Total Orders:</strong> ${userOrders.length}</p>
                <p><strong>Total Spent:</strong> <strong style="color: #613D28;">$${totalSpent.toFixed(2)}</strong></p>
            </div>
            ${userOrders.length > 0 ? `
                <h4 style="color: #231810; margin-bottom: 1rem;">${getIcon('orders')} Recent Orders</h4>
                <table style="width: 100%;">
                    <thead>
                        <tr style="background: #613D28; color: #FCCDAC;">
                            <th style="padding: 0.5rem;">Order ID</th>
                            <th style="padding: 0.5rem;">Date</th>
                            <th style="padding: 0.5rem;">Total</th>
                            <th style="padding: 0.5rem;">Payment</th>
                            <th style="padding: 0.5rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${userOrders.slice(0, 5).map(order => `
                            <tr style="border-bottom: 1px solid #AE7F62;">
                                <td style="padding: 0.5rem;">#${order.id.slice(-6)}</td>
                                <td style="padding: 0.5rem;">${new Date(order.date).toLocaleDateString()}</td>
                                <td style="padding: 0.5rem; color: #613D28;">$${order.total.toFixed(2)}</td>
                                <td style="padding: 0.5rem;">${order.paymentMethod === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.paymentMethod}</td>
                                <td style="padding: 0.5rem;">
                                    <span class="status-badge status-${order.approvalStatus === 'pending' ? 'pending' : order.status}">
                                        ${order.approvalStatus === 'pending' ? 'Pending' : order.status}
                                    </span>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            ` : '<p style="text-align: center; color: #AE7F62;">✨ No orders yet</p>'}
        `;
        openModal('viewUser');
    }
}


function loadSalesReports() {
    const period = document.getElementById('reportPeriod').value;
    let filteredOrders = window.orders.filter(o => o.approvalStatus === 'approved' && o.status === 'delivered');
    if (period !== 'all') {
        const filterDate = new Date();
        if (period === 'today') {
            filterDate.setHours(0, 0, 0, 0);
            filteredOrders = filteredOrders.filter(o => new Date(o.date) >= filterDate);
        } else if (period === 'week') {
            filterDate.setDate(filterDate.getDate() - 7);
            filteredOrders = filteredOrders.filter(o => new Date(o.date) >= filterDate);
        } else if (period === 'month') {
            filterDate.setMonth(filterDate.getMonth() - 1);
            filteredOrders = filteredOrders.filter(o => new Date(o.date) >= filterDate);
        } else if (period === 'year') {
            filterDate.setFullYear(filterDate.getFullYear() - 1);
            filteredOrders = filteredOrders.filter(o => new Date(o.date) >= filterDate);
        }
    }
    const paidOrders = filteredOrders.filter(o => o.paymentStatus === 'Paid');
    const codRevenue = paidOrders.filter(o => o.paymentMethod === 'Cash on Delivery').reduce((sum, o) => sum + o.total, 0);
    const bankRevenue = paidOrders.filter(o => o.paymentMethod === 'Bank Transfer').reduce((sum, o) => sum + o.total, 0);
    const totalRevenue = paidOrders.reduce((sum, o) => sum + o.total, 0);
    const paidOrdersCount = paidOrders.length;
    const pendingPaymentsCount = window.orders.filter(o => o.paymentStatus !== 'Paid' && o.approvalStatus !== 'cancelled').length;
    const avgOrderValue = paidOrdersCount > 0 ? totalRevenue / paidOrdersCount : 0;
    document.getElementById('revenueStats').innerHTML = `
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.1s both;">
            <div class="stat-icon">${getIcon('revenue')}</div>
            <h4>Total Revenue</h4>
            <div class="stat-value">$${totalRevenue.toFixed(2)}</div>
            <div class="stat-label">${period} (paid orders)</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.2s both;">
            <div class="stat-icon">${getIcon('cash')}</div>
            <h4>COD Revenue</h4>
            <div class="stat-value">$${codRevenue.toFixed(2)}</div>
            <div class="stat-label">Cash on Delivery</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.3s both;">
            <div class="stat-icon">${getIcon('bank')}</div>
            <h4>Bank Transfer</h4>
            <div class="stat-value">$${bankRevenue.toFixed(2)}</div>
            <div class="stat-label">Paid via bank</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.4s both;">
            <div class="stat-icon">${getIcon('pending')}</div>
            <h4>Pending Payments</h4>
            <div class="stat-value">${pendingPaymentsCount}</div>
            <div class="stat-label">Awaiting confirmation</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.5s both;">
            <div class="stat-icon">${getIcon('reports')}</div>
            <h4>Average Order</h4>
            <div class="stat-value">$${avgOrderValue.toFixed(2)}</div>
            <div class="stat-label">Per paid order</div>
        </div>
    `;
    const bookSales = {};
    paidOrders.forEach(order => {
        order.items.forEach(item => {
            if (!bookSales[item.isbn]) {
                bookSales[item.isbn] = { title: item.title, quantity: 0, revenue: 0 };
            }
            bookSales[item.isbn].quantity += item.quantity;
            bookSales[item.isbn].revenue += item.quantity * item.price;
        });
    });
    const topBooks = Object.values(bookSales).sort((a, b) => b.quantity - a.quantity).slice(0, 5);
    const topBooksHtml = topBooks.length > 0 ? topBooks.map((book, index) => `
        <div class="top-book-card" style="animation: fadeIn 0.2s ease-out ${index * 0.1}s both;">
            <div class="top-book-title">${escapeHtml(book.title)}</div>
            <div class="top-book-sales">${getIcon('inventory')} ${book.quantity} sold</div>
            <div class="top-book-revenue">${getIcon('revenue')} $${book.revenue.toFixed(2)}</div>
        </div>
    `).join('') : '<p style="text-align: center; color: #AE7F62;">✨ No sales data available</p>';
    document.getElementById('topBooks').innerHTML = topBooksHtml;
}


function exportData() {
    const data = {
        books: window.books,
        users: window.users,
        orders: window.orders,
        notifications: window.notifications,
        exportDate: new Date().toISOString(),
        exportVersion: '1.0'
    };
    const dataStr = JSON.stringify(data, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    const exportFileDefaultName = `pahina_export_${new Date().toISOString().slice(0,10)}.json`;
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
    showToast('📥 Data exported successfully!', 'success');
}


function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.remove();
    }
    const typeIcon = {
        success: getIcon('complete'),
        error: getIcon('reject'),
        info: getIcon('bell'),
        warning: getIcon('pending')
    };
    const newToast = document.createElement('div');
    newToast.className = `toast toast-${type}`;
    newToast.id = 'toast';
    newToast.innerHTML = `
        <span>${typeIcon[type] || getIcon('bell')} ${message}</span>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; font-size: 1.2rem; color: #AE7F62;">${getIcon('close')}</button>
    `;
    document.body.appendChild(newToast);
    setTimeout(() => {
        const toastElement = document.getElementById('toast');
        if (toastElement) {
            toastElement.remove();
        }
    }, 3500);
}

function quickSync() {
    syncData();
}

function quickExport() {
    exportData();
}

function quickAddBook() {
    openAddBookModal();
}