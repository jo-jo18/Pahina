const API_BASE_URL = '/api/admin';
let authToken = localStorage.getItem('admin_token');
let notificationCheckInterval = null;
const SHIPPING_FEE = 5.00;
let currentUser = null;
let pendingPaymentOrderId = null;
let notifications = [];
let bestSellersLoaded = false;
let bestSellersLoading = false;
let bestSellersLoadAttempted = false;
let bestSellersLoadTimeout = null;
let currentActiveSection = 'dashboard-section';
let isOnline = navigator.onLine;
let lastSyncTime = null;

// ========== STYLE & DESIGN FUNCTIONS ==========

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
        'crown': '<i class="fas fa-crown"></i>',
        'book': '<i class="fas fa-book"></i>',
        'check': '<i class="fas fa-check-circle"></i>',
        'clock': '<i class="fas fa-clock"></i>',
        'box': '<i class="fas fa-box"></i>',
        'lock': '<i class="fas fa-lock"></i>'
    };
    return icons[iconName] || '<i class="fas fa-circle"></i>';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

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
    
    updateConnectionStatus(isOnline);
}

function updateConnectionStatus(online) {
    const connectionDot = document.querySelector('.connection-dot');
    const connectionText = document.querySelector('.connection-status span');
    const connectionTime = document.getElementById('connectionTime');
    
    if (connectionDot) {
        connectionDot.style.background = online ? '#22c55e' : '#ef4444';
        connectionDot.style.animation = online ? 'pulse-green 2s infinite' : 'none';
    }
    
    if (connectionText) {
        connectionText.textContent = online ? 'Connected to server' : 'Offline mode';
    }
    
    if (connectionTime && lastSyncTime) {
        const timeDiff = Math.floor((Date.now() - lastSyncTime) / 1000);
        connectionTime.textContent = timeDiff < 60 ? 'just now' : `${Math.floor(timeDiff / 60)}m ago`;
    }
    
    isOnline = online;
}

function syncData() {
    if (!isOnline) return;
    
    if (currentUser && currentUser.is_admin) {
        loadAdminDashboard();
        loadDashboardPreviews();
        if (currentActiveSection === 'orders-section') loadOrders();
        if (currentActiveSection === 'inventory-section') loadInventory();
        if (currentActiveSection === 'reports-section') loadSalesReports();
        if (currentActiveSection === 'users-section') loadUsers();
    }
    lastSyncTime = Date.now();
    updateConnectionStatus(true);
    showToast('Data synced successfully', 'success');
}

function openProfileSettings() {
    showToast('Profile settings coming soon!', 'info');
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

// ========== ORIGINAL FUNCTIONS (ENHANCED WITH STYLES) ==========

async function checkAdminAuth() {
    const token = localStorage.getItem('admin_token');
    
    if (!token) {
        updateNavigation();
        return;
    }
    
    try {
        const response = await fetch('/api/user', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.user && data.user.is_admin) {
                currentUser = data.user;
                authToken = token;
                sessionStorage.setItem('admin_logged_in', 'true');
                sessionStorage.setItem('admin_user', JSON.stringify(data.user));
                bestSellersLoaded = false;
                bestSellersLoading = false;
                bestSellersLoadAttempted = false;
                updateNavigation();
                showSection(currentActiveSection);
                loadAdminDashboard();
                loadDashboardPreviews();
                startNotificationCheck();
                startRealTimeUpdates();
                initConnectionMonitoring();
                console.log('Admin authenticated successfully');
            } else {
                localStorage.removeItem('admin_token');
                sessionStorage.removeItem('admin_logged_in');
                sessionStorage.removeItem('admin_user');
                authToken = null;
                updateNavigation();
            }
        } else {
            localStorage.removeItem('admin_token');
            sessionStorage.removeItem('admin_logged_in');
            sessionStorage.removeItem('admin_user');
            authToken = null;
            updateNavigation();
        }
    } catch (error) {
        console.error('Auth check error:', error);
        localStorage.removeItem('admin_token');
        sessionStorage.removeItem('admin_logged_in');
        sessionStorage.removeItem('admin_user');
        authToken = null;
        updateNavigation();
    }
}

function startRealTimeUpdates() {
    if (notificationCheckInterval) {
        clearInterval(notificationCheckInterval);
    }
    notificationCheckInterval = setInterval(() => {
        if (currentUser && currentUser.is_admin) {
            loadDashboardPreviews();
            updateLowStockAlert();
            updateRecentOrders();
            loadNotifications();
            if (currentActiveSection === 'orders-section') {
                loadOrders();
            }
            if (currentActiveSection === 'inventory-section') {
                loadInventory();
            }
        } else {
            loadPublicDashboardData();
        }
    }, 30000);
}

async function apiRequest(endpoint, method = 'GET', data = null, isFormData = false, usePublicEndpoint = false) {
    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    };

    if (authToken && !usePublicEndpoint) {
        headers['Authorization'] = `Bearer ${authToken}`;
    }

    if (!isFormData) {
        headers['Content-Type'] = 'application/json';
    }

    const options = {
        method,
        headers,
        credentials: 'same-origin'
    };

    if (data) {
        if (isFormData) {
            options.body = data;
        } else {
            options.body = JSON.stringify(data);
        }
    }

    try {
        const baseUrl = usePublicEndpoint ? '/api' : API_BASE_URL;
        const response = await fetch(`${baseUrl}${endpoint}`, options);
        const result = await response.json();
        
        if (!response.ok) {
            if (response.status === 401 && !usePublicEndpoint) {
                localStorage.removeItem('admin_token');
                sessionStorage.removeItem('admin_logged_in');
                sessionStorage.removeItem('admin_user');
                authToken = null;
                currentUser = null;
                updateNavigation();
                showToast('Session expired. Please login again.', 'error');
            }
            throw new Error(result.message || result.error || 'API request failed');
        }
        
        return result;
    } catch (error) {
        console.error('API Error:', error);
        if (error.message !== 'API request failed' && !usePublicEndpoint) {
            showToast(error.message, 'error');
        }
        throw error;
    }
}

function openUserInterface() {
    window.open('/user', '_blank');
    showToast('Opening user interface...', 'info');
}

async function loadPublicDashboardData() {
    try {
        const [recentOrders, lowStock] = await Promise.all([
            apiRequest('/public/recent-orders', 'GET', null, false, true),
            apiRequest('/public/low-stock', 'GET', null, false, true)
        ]);
        
        displayRecentOrdersPublic(recentOrders);
        displayLowStockPublic(lowStock);
    } catch (error) {
        console.error('Error loading public dashboard data:', error);
        displayRecentOrdersPublic([]);
        displayLowStockPublic([]);
    }
}

async function loadBestSellers() {
    if (bestSellersLoading || bestSellersLoaded || bestSellersLoadAttempted) {
        return;
    }
    
    if (bestSellersLoadTimeout) {
        clearTimeout(bestSellersLoadTimeout);
    }
    
    bestSellersLoading = true;
    bestSellersLoadAttempted = true;
    
    try {
        const data = await apiRequest('/dashboard/best-sellers', 'GET', null, false, true);
        displayBestSellers(data);
        bestSellersLoaded = true;
        console.log('Best sellers loaded successfully');
    } catch (error) {
        console.error('Error loading best sellers:', error);
        const bestSellersGrid = document.getElementById('bestSellersGrid');
        if (bestSellersGrid) {
            bestSellersGrid.innerHTML = '<p style="text-align: center; color: #64748b;">Unable to load best sellers. Please try again later.</p>';
        }
        bestSellersLoadTimeout = setTimeout(() => {
            bestSellersLoading = false;
            bestSellersLoaded = false;
            bestSellersLoadAttempted = false;
            loadBestSellers();
        }, 60000);
    } finally {
        bestSellersLoading = false;
    }
}

function displayBestSellers(books) {
    const bestSellersGrid = document.getElementById('bestSellersGrid');
    if (!bestSellersGrid) return;

    if (!books || books.length === 0) {
        bestSellersGrid.innerHTML = '<p style="text-align: center; color: #64748b;">No sales data yet</p>';
        return;
    }

    bestSellersGrid.innerHTML = books.map((book, index) => `
        <div class="best-seller-card" style="animation: fadeIn 0.3s ease-out ${index * 0.05}s both;">
            <div class="best-seller-rank">#${index + 1}</div>
            <div class="best-seller-icon">${index === 0 ? getIcon('crown') : index === 1 ? getIcon('medal') : index === 2 ? getIcon('star') : getIcon('book')}</div>
            <h4>${escapeHtml(book.title)}</h4>
            <div class="best-seller-author">by ${escapeHtml(book.author || 'Unknown')}</div>
            <div class="best-seller-stats">
                <div class="best-seller-stat">
                    <div class="best-seller-stat-value">${book.total_sold || 0}</div>
                    <div class="best-seller-stat-label">sold</div>
                </div>
                <div class="best-seller-stat">
                    <div class="best-seller-stat-value">$${(book.total_revenue || 0).toFixed(2)}</div>
                    <div class="best-seller-stat-label">revenue</div>
                </div>
            </div>
            <div class="best-seller-price">$${parseFloat(book.price).toFixed(2)}</div>
            <span class="best-seller-badge">${book.condition || 'N/A'}</span>
        </div>
    `).join('');
}

function displayRecentOrdersPublic(orders) {
    const recentOrdersBody = document.getElementById('recentOrdersPreview');
    if (!recentOrdersBody) return;
    
    if (!orders || orders.length === 0) {
        recentOrdersBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                    ✨ No orders yet
                </td>
            </tr>
        `;
    } else {
        recentOrdersBody.innerHTML = orders.map(order => `
            <tr style="transition: all 0.2s;">
                <td style="font-weight: 500;"><strong>#${order.order_number}</strong></td>
                <td>${escapeHtml(order.user?.name || 'Unknown')}</td>
                <td>${new Date(order.created_at).toLocaleDateString()}</td>
                <td><strong>$${parseFloat(order.total).toFixed(2)}</strong></td>
                <td>${order.payment_method === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.payment_method}</td>
                <td>
                    <span class="status-badge ${order.payment_status === 'Paid' ? 'payment-paid' : 'payment-pending'}">
                        ${order.payment_status === 'Paid' ? getIcon('check') : getIcon('clock')} ${order.payment_status}
                    </span>
                </td>
            </tr>
        `).join('');
    }
}

function displayLowStockPublic(books) {
    const lowStockBody = document.getElementById('lowStockPreview');
    if (!lowStockBody) return;
    
    if (!books || books.length === 0) {
        lowStockBody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 2rem; color: #22c55e;">
                    ✅ All items well stocked
                </td>
            </tr>
        `;
    } else {
        lowStockBody.innerHTML = books.map(book => {
            let stockClass = '';
            
            if (book.stock === 0) {
                stockClass = 'status-cancelled';
            } else if (book.stock <= 2) {
                stockClass = 'status-cancelled';
            } else if (book.stock <= 5) {
                stockClass = 'status-pending';
            }
            
            const stockText = book.stock === 0 ? '❌ Out of stock' : book.stock <= 2 ? `⚠️ ${book.stock} left` : `${book.stock} left`;
            
            return `
                <tr>
                    <td><strong>${book.isbn}</strong></td>
                    <td>${escapeHtml(book.title)}</td>
                    <td>${escapeHtml(book.author)}</td>
                    <td>
                        <span class="status-badge ${stockClass}" style="${book.stock <= 2 ? 'animation: pulse 1s infinite;' : ''}">
                            ${getIcon('stock')} ${stockText}
                        </span>
                        ${book.stock <= 2 ? '<span style="margin-left: 8px; color: #ef4444;">⚠️</span>' : ''}
                    </td>
                    <td>
                        ${currentUser && currentUser.is_admin ? 
                            `<button class="action-btn edit" onclick="editBook(${book.id})" style="background: #f59e0b;">
                                ${getIcon('restock')} Restock
                            </button>` : 
                            `<button class="action-btn view" onclick="openLoginModal()" style="background: #3b82f6;">
                                ${getIcon('lock')} Login to Restock
                            </button>`
                        }
                    </td>
                </tr>
            `;
        }).join('');
    }
}

async function loadNotifications() {
    if (!currentUser?.is_admin) return;
    
    try {
        const data = await apiRequest('/notifications');
        notifications = data.notifications;
        updateNotificationBadge(data.unread_count);
        displayNotifications(notifications);
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

function displayNotifications(notificationsList) {
    const notificationList = document.getElementById('notificationList');
    if (!notificationList) return;

    if (!notificationsList || notificationsList.length === 0) {
        notificationList.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: #64748b;">
                ${getIcon('bell')} No new notifications
            </div>
        `;
        return;
    }

    notificationList.innerHTML = notificationsList.slice(0, 10).map(notif => `
        <div class="notification-item ${notif.is_read ? '' : 'new'}" onclick="markNotificationRead(${notif.id}); showSection('orders-section'); toggleNotificationPanel(false);">
            <div class="notification-title">
                <span>${notif.type === 'new_order' ? getIcon('cart') : getIcon('money')} ${escapeHtml(notif.title)}</span>
                <span class="notification-time">${timeAgo(notif.created_at)}</span>
            </div>
            <div class="notification-message">${escapeHtml(notif.message)}</div>
        </div>
    `).join('');
}

function timeAgo(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return diffMins + 'm ago';
    if (diffHours < 24) return diffHours + 'h ago';
    return diffDays + 'd ago';
}

async function markNotificationRead(notificationId) {
    try {
        await apiRequest(`/notifications/${notificationId}/read`, 'POST');
        loadNotifications();
    } catch (error) {
        console.error('Error marking notification read:', error);
    }
}

async function markAllNotificationsRead() {
    try {
        await apiRequest('/notifications/read-all', 'POST');
        loadNotifications();
    } catch (error) {
        console.error('Error marking all notifications read:', error);
    }
}

function updateNotificationBadge(unreadCount) {
    const ordersNavBtn = document.querySelector('.nav-btn[onclick="showSection(\'orders-section\')"]');
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

    if (unreadCount > 0) {
        document.title = `(${unreadCount}) Pahina Admin`;
    } else {
        document.title = 'Pahina Admin';
    }
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

function startNotificationCheck() {
    if (notificationCheckInterval) {
        clearInterval(notificationCheckInterval);
    }
    notificationCheckInterval = setInterval(loadNotifications, 10000);
}

function showSection(sectionId) {
    currentActiveSection = sectionId;

    document.querySelectorAll('.section').forEach(s => {
        s.classList.remove('active');
        s.style.display = 'none';
    });

    const section = document.getElementById(sectionId);
    if (section) {
        section.style.display = 'block';
        section.classList.add('active');
    }
    
    updateNavigationActiveState(sectionId);

    if (currentUser && currentUser.is_admin) {
        if (sectionId === 'dashboard-section') {
            loadAdminDashboard();
            loadDashboardPreviews();
        } else if (sectionId === 'inventory-section') {
            loadInventory();
        } else if (sectionId === 'orders-section') {
            loadOrders();
        } else if (sectionId === 'reports-section') {
            loadSalesReports();
        } else if (sectionId === 'users-section') {
            loadUsers();
        }
    }

    toggleNotificationPanel(false);
}

function updateNavigationActiveState(sectionId) {
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const activeBtn = document.querySelector(`.nav-btn[onclick="showSection('${sectionId}')"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }
}

function updateNavigation() {
    const navButtons = document.getElementById('navButtons');
    if (!navButtons) return;
    
    if (currentUser && currentUser.is_admin) {
        const bestSellersContainer = document.getElementById('bestSellersContainer');
        if (bestSellersContainer) {
            bestSellersContainer.style.display = 'none';
        }

        document.querySelectorAll('.section').forEach(s => {
            s.style.display = 'none';
        });
        
        const activeSection = document.getElementById(currentActiveSection);
        if (activeSection) {
            activeSection.style.display = 'block';
            activeSection.classList.add('active');
        } else {
            const dashboardSection = document.getElementById('dashboard-section');
            if (dashboardSection) {
                dashboardSection.style.display = 'block';
                dashboardSection.classList.add('active');
                currentActiveSection = 'dashboard-section';
            }
        }
        
        let profileHtml = '';
        if (currentUser.profile_pic) {
            profileHtml = `<img src="/storage/${currentUser.profile_pic}" alt="${currentUser.name}">`;
        } else {
            profileHtml = `<span>${currentUser.name.charAt(0).toUpperCase()}</span>`;
        }
        
        const unreadCount = notifications ? notifications.filter(n => !n.read).length : 0;
        
        navButtons.innerHTML = `
            <button class="nav-btn ${currentActiveSection === 'dashboard-section' ? 'active' : ''}" onclick="showSection('dashboard-section')">
                ${getIcon('dashboard')} Dashboard
            </button>
            <button class="nav-btn ${currentActiveSection === 'inventory-section' ? 'active' : ''}" onclick="showSection('inventory-section')">
                ${getIcon('inventory')} Inventory
            </button>
            <button class="nav-btn notification-badge ${currentActiveSection === 'orders-section' ? 'active' : ''}" onclick="showSection('orders-section')">
                ${getIcon('orders')} Orders
                ${unreadCount > 0 ? `<span class="badge">${unreadCount}</span>` : ''}
            </button>
            <button class="nav-btn ${currentActiveSection === 'reports-section' ? 'active' : ''}" onclick="showSection('reports-section')">
                ${getIcon('reports')} Reports
            </button>
            <button class="nav-btn ${currentActiveSection === 'users-section' ? 'active' : ''}" onclick="showSection('users-section')">
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
                        <h4>${escapeHtml(currentUser.name)}</h4>
                        <p>${escapeHtml(currentUser.email)}</p>
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
        
        if (!notifications || notifications.length === 0) {
            loadNotifications();
        }
    } else {
        const bestSellersContainer = document.getElementById('bestSellersContainer');
        if (bestSellersContainer) {
            bestSellersContainer.style.display = 'flex';
        }
        
        document.querySelectorAll('.section').forEach(s => {
            s.style.display = 'none';
        });
        
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('active');
        });
        
        navButtons.innerHTML = `
            <button class="view-user-btn" onclick="openUserInterface()">
                ${getIcon('users')} View User Interface
            </button>
            <button class="nav-btn" onclick="openLoginModal()">
                ${getIcon('admin')} Admin Login
            </button>
        `;

        if (!bestSellersLoaded && !bestSellersLoading && !bestSellersLoadAttempted) {
            loadBestSellers();
        }
    }
}

function toggleDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) dropdown.classList.toggle('show');
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

function openModal(modalName) {
    const modal = document.getElementById(`${modalName}Modal`);
    if (modal) modal.classList.add('active');
}

function closeModal(modalName) {
    const modal = document.getElementById(`${modalName}Modal`);
    if (modal) modal.classList.remove('active');
}

function openLoginModal() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.classList.remove('active');
    });
    document.getElementById('loginEmail').value = '';
    document.getElementById('loginPassword').value = '';
    openModal('login');
}

async function loginUser(event) {
    event.preventDefault();
    
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    const loginBtn = event.target.querySelector('button[type="submit"]');
    const originalText = loginBtn.textContent;
    loginBtn.textContent = 'Logging in...';
    loginBtn.disabled = true;

    try {
        const response = await fetch('/api/admin/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok) {
            localStorage.setItem('admin_token', data.token);
            authToken = data.token;
            currentUser = data.user;
            sessionStorage.setItem('admin_logged_in', 'true');
            sessionStorage.setItem('admin_user', JSON.stringify(data.user));
            bestSellersLoaded = false;
            bestSellersLoading = false;
            bestSellersLoadAttempted = false;
            currentActiveSection = 'dashboard-section';
            closeModal('login');
            showToast(`✨ Welcome back, ${data.user.name}!`, 'success');
            updateNavigation();
            showSection('dashboard-section');
            loadAdminDashboard();
            loadDashboardPreviews();
            startNotificationCheck();
            startRealTimeUpdates();
            initConnectionMonitoring();
            syncData();
        } else {
            showToast(data.message || 'Invalid credentials!', 'error');
            document.getElementById('loginPassword').value = '';
        }
    } catch (error) {
        console.error('Login error:', error);
        showToast('Login failed. Please try again.', 'error');
    } finally {
        loginBtn.textContent = originalText;
        loginBtn.disabled = false;
    }
}

async function logout() {
    try {
        await fetch('/api/admin/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        localStorage.removeItem('admin_token');
        sessionStorage.removeItem('admin_logged_in');
        sessionStorage.removeItem('admin_user');
        authToken = null;
        currentUser = null;
        bestSellersLoaded = false;
        bestSellersLoading = false;
        bestSellersLoadAttempted = false;
        currentActiveSection = 'dashboard-section';
        
        if (bestSellersLoadTimeout) {
            clearTimeout(bestSellersLoadTimeout);
            bestSellersLoadTimeout = null;
        }
        
        if (notificationCheckInterval) {
            clearInterval(notificationCheckInterval);
            notificationCheckInterval = null;
        }
        
        updateNavigation();
        showToast('👋 Logged out successfully', 'info');
        loadBestSellers();
    }
}

async function loadAdminDashboard() {
    if (!currentUser?.is_admin) return;

    try {
        const data = await apiRequest('/dashboard');
        displayAdminStats(data.stats);
    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

function displayAdminStats(stats) {
    const adminStats = document.getElementById('adminStats');
    if (!adminStats) return;
    
    adminStats.innerHTML = `
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.1s both;">
            <div class="stat-icon">${getIcon('revenue')}</div>
            <h4>Total Revenue</h4>
            <div class="stat-value">$${parseFloat(stats.total_revenue).toFixed(2)}</div>
            <div class="stat-label">All time</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.2s both;">
            <div class="stat-icon">${getIcon('pending')}</div>
            <h4>Pending Orders</h4>
            <div class="stat-value">${stats.pending_orders}</div>
            <div class="stat-label">Awaiting approval</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.3s both;">
            <div class="stat-icon">${getIcon('payment')}</div>
            <h4>Pending Payments</h4>
            <div class="stat-value">${stats.pending_payments}</div>
            <div class="stat-label">Awaiting confirmation</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.4s both;">
            <div class="stat-icon">${getIcon('stock')}</div>
            <h4>Books in Stock</h4>
            <div class="stat-value">${stats.total_books}</div>
            <div class="stat-label">Total inventory</div>
        </div>
        <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.5s both;">
            <div class="stat-icon">${getIcon('users')}</div>
            <h4>Total Users</h4>
            <div class="stat-value">${stats.total_users}</div>
            <div class="stat-label">Registered customers</div>
        </div>
    `;
}

async function loadDashboardPreviews() {
    if (!currentUser?.is_admin) return;
    
    try {
        const data = await apiRequest('/dashboard');
        displayRecentOrders(data.recent_orders);
        displayLowStock(data.low_stock);

        const pendingCount = data.recent_orders ? data.recent_orders.filter(o => o.approval_status === 'pending').length : 0;
        const lowStockCount = data.low_stock ? data.low_stock.length : 0;

        const ordersNavBtn = document.querySelector('.nav-btn[onclick="showSection(\'orders-section\')"]');
        if (ordersNavBtn) {
            const existingBadge = ordersNavBtn.querySelector('.badge');
            if (pendingCount > 0) {
                if (existingBadge) {
                    existingBadge.textContent = pendingCount;
                } else {
                    const badge = document.createElement('span');
                    badge.className = 'badge';
                    badge.textContent = pendingCount;
                    ordersNavBtn.appendChild(badge);
                }
            } else if (existingBadge) {
                existingBadge.remove();
            }
        }

        const inventoryNavBtn = document.querySelector('.nav-btn[onclick="showSection(\'inventory-section\')"]');
        if (inventoryNavBtn) {
            const existingBadge = inventoryNavBtn.querySelector('.low-stock-badge');
            if (lowStockCount > 0) {
                if (existingBadge) {
                    existingBadge.textContent = lowStockCount;
                } else {
                    const badge = document.createElement('span');
                    badge.className = 'badge low-stock-badge';
                    badge.style.background = '#ef4444';
                    badge.textContent = lowStockCount;
                    inventoryNavBtn.appendChild(badge);
                }
            } else if (existingBadge) {
                existingBadge.remove();
            }
        }

        if (lowStockCount > 0 && data.low_stock) {
            const criticalBooks = data.low_stock.filter(b => b.stock <= 2);
            if (criticalBooks.length > 0 && !sessionStorage.getItem('low_stock_notified')) {
                showToast(`⚠️ ${criticalBooks.length} book(s) have critically low stock! Please restock.`, 'warning');
                sessionStorage.setItem('low_stock_notified', 'true');
                setTimeout(() => {
                    sessionStorage.removeItem('low_stock_notified');
                }, 60000);
            }
        }

        if (pendingCount > 0) {
            const lastOrderCount = localStorage.getItem('last_order_count') || 0;
            if (pendingCount > lastOrderCount) {
                showToast(`📦 ${pendingCount - lastOrderCount} new order(s) received!`, 'info');
                playNotificationSound();
            }
            localStorage.setItem('last_order_count', pendingCount);
        }
    } catch (error) {
        console.error('Error loading dashboard previews:', error);
        displayRecentOrders([]);
        displayLowStock([]);
    }
}

function displayRecentOrders(orders) {
    const recentOrdersBody = document.getElementById('recentOrdersPreview');
    if (!recentOrdersBody) return;
    
    if (!orders || orders.length === 0) {
        recentOrdersBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                    ✨ No orders yet
                <\/td>
            <\/tr>
        `;
    } else {
        recentOrdersBody.innerHTML = orders.map(order => `
            <tr style="transition: all 0.2s;">
                <td style="font-weight: 500;"><strong>#${order.order_number}</strong><\/td>
                <td>${escapeHtml(order.user?.name || 'Unknown')}<\/td>
                <td>${new Date(order.created_at).toLocaleDateString()}<\/td>
                <td><strong>$${parseFloat(order.total).toFixed(2)}</strong><\/td>
                <td>${order.payment_method === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.payment_method}<\/td>
                <td>
                    <span class="status-badge ${order.payment_status === 'Paid' ? 'payment-paid' : 'payment-pending'}">
                        ${order.payment_status === 'Paid' ? getIcon('check') : getIcon('clock')} ${order.payment_status}
                    </span>
                <\/td>
            <\/tr>
        `).join('');
    }
}

function displayLowStock(books) {
    const lowStockBody = document.getElementById('lowStockPreview');
    if (!lowStockBody) return;
    
    if (!books || books.length === 0) {
        lowStockBody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 2rem; color: #22c55e;">
                    ✅ All items well stocked
                <\/td>
            <\/tr>
        `;
    } else {
        lowStockBody.innerHTML = books.map(book => {
            let stockClass = '';
            
            if (book.stock === 0) {
                stockClass = 'status-cancelled';
            } else if (book.stock <= 2) {
                stockClass = 'status-cancelled';
            } else if (book.stock <= 5) {
                stockClass = 'status-pending';
            }
            
            const stockText = book.stock === 0 ? '❌ Out of stock' : book.stock <= 2 ? `⚠️ ${book.stock} left` : `${book.stock} left`;
            
            return `
                <tr>
                    <td><strong>${book.isbn}</strong><\/td>
                    <td><strong>${escapeHtml(book.title)}</strong><\/td>
                    <td>${escapeHtml(book.author)}<\/td>
                    <td>
                        <span class="status-badge ${stockClass}" style="${book.stock <= 2 ? 'animation: pulse 1s infinite;' : ''}">
                            ${getIcon('stock')} ${stockText}
                        </span>
                        ${book.stock <= 2 ? '<span style="margin-left: 8px; color: #ef4444;">⚠️</span>' : ''}
                    <\/td>
                    <td>
                        <button class="action-btn edit" onclick="editBook(${book.id})" style="background: #f59e0b;">
                            ${getIcon('restock')} Restock
                        </button>
                    <\/td>
                <\/tr>
            `;
        }).join('');
    }
}

async function updateLowStockAlert() {
    if (!currentUser?.is_admin) return;
    
    try {
        const data = await apiRequest('/dashboard');
        const lowStockBooks = data.low_stock;
        const lowStockCount = lowStockBooks ? lowStockBooks.length : 0;
        
        const inventoryNavBtn = document.querySelector('.nav-btn[onclick="showSection(\'inventory-section\')"]');
        if (inventoryNavBtn) {
            const existingBadge = inventoryNavBtn.querySelector('.low-stock-badge');
            if (lowStockCount > 0) {
                if (existingBadge) {
                    existingBadge.textContent = lowStockCount;
                } else {
                    const badge = document.createElement('span');
                    badge.className = 'badge low-stock-badge';
                    badge.style.background = '#ef4444';
                    badge.textContent = lowStockCount;
                    inventoryNavBtn.appendChild(badge);
                }
            } else if (existingBadge) {
                existingBadge.remove();
            }
        }
        
        displayLowStock(lowStockBooks);
        
        if (lowStockCount > 0) {
            const criticalBooks = lowStockBooks.filter(b => b.stock <= 2);
            if (criticalBooks.length > 0 && !sessionStorage.getItem('low_stock_notified')) {
                showToast(`⚠️ ${criticalBooks.length} book(s) have critically low stock!`, 'warning');
                sessionStorage.setItem('low_stock_notified', 'true');
                setTimeout(() => {
                    sessionStorage.removeItem('low_stock_notified');
                }, 60000);
            }
        }
    } catch (error) {
        console.error('Error updating low stock alert:', error);
    }
}

async function updateRecentOrders() {
    if (!currentUser?.is_admin) return;
    
    try {
        const data = await apiRequest('/dashboard');
        const recentOrders = data.recent_orders;
        const pendingCount = recentOrders ? recentOrders.filter(o => o.approval_status === 'pending').length : 0;
        
        const ordersNavBtn = document.querySelector('.nav-btn[onclick="showSection(\'orders-section\')"]');
        if (ordersNavBtn) {
            const existingBadge = ordersNavBtn.querySelector('.badge');
            if (pendingCount > 0) {
                if (existingBadge) {
                    existingBadge.textContent = pendingCount;
                } else {
                    const badge = document.createElement('span');
                    badge.className = 'badge';
                    badge.textContent = pendingCount;
                    ordersNavBtn.appendChild(badge);
                }
            } else if (existingBadge) {
                existingBadge.remove();
            }
        }
        
        displayRecentOrders(recentOrders);
        
        if (pendingCount > 0) {
            const lastOrderCount = localStorage.getItem('last_order_count') || 0;
            if (pendingCount > lastOrderCount) {
                showToast(`📦 You have ${pendingCount} new order(s) pending approval!`, 'info');
                playNotificationSound();
            }
            localStorage.setItem('last_order_count', pendingCount);
        }
    } catch (error) {
        console.error('Error updating recent orders:', error);
    }
}

function playNotificationSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        gainNode.gain.value = 0.2;
        
        oscillator.start();
        oscillator.stop(audioContext.currentTime + 0.3);
    } catch (e) {
        // Silent fail if audio context not supported
    }
}

async function loadInventory() {
    if (!currentUser?.is_admin) return;
    
    try {
        const books = await apiRequest('/books');
        displayInventory(books);
    } catch (error) {
        console.error('Error loading inventory:', error);
    }
}

function displayInventory(books) {
    const inventoryBody = document.getElementById('inventoryBody');
    if (!inventoryBody) return;
    
    if (!books || books.length === 0) {
        inventoryBody.innerHTML = '<tr><td colspan="8" style="text-align: center;">No books available<\/td><\/tr>';
        const totalBooksCount = document.getElementById('totalBooksCount');
        if (totalBooksCount) totalBooksCount.textContent = '0';
        return;
    }

    inventoryBody.innerHTML = books.map((book, index) => {
        const imageHtml = book.image ? 
            `<img src="/storage/${book.image}" alt="${book.title}" class="inventory-image">` :
            `<div class="inventory-image-placeholder">${getIcon('book')}</div>`;
        
        const stockClass = book.stock > 10 ? 'status-shipped' : book.stock > 0 ? 'status-pending' : 'status-cancelled';
        const stockText = book.stock > 10 ? `${book.stock} available` : book.stock > 0 ? `⚠️ ${book.stock} left` : '❌ Out of stock';
        
        return `
            <tr style="animation: fadeIn 0.2s ease-out ${index * 0.02}s both;">
                <td>${imageHtml}<\/td>
                <td><code style="font-size: 0.8rem;">${book.isbn}</code><\/td>
                <td><strong>${escapeHtml(book.title)}</strong><\/td>
                <td>${escapeHtml(book.author)}<\/td>
                <td style="color: #AE7F62; font-weight: 500;">$${parseFloat(book.price).toFixed(2)}<\/td>
                <td>
                    <span class="status-badge ${stockClass}">
                        ${stockText}
                    </span>
                <\/td>
                <td><span style="text-transform: capitalize; background: #AE7F6220; padding: 0.2rem 0.6rem; border-radius: 20px;">${book.condition}</span><\/td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn edit" onclick="editBook(${book.id})">${getIcon('edit')} Edit</button>
                        <button class="action-btn delete" onclick="deleteBook(${book.id})">${getIcon('delete')} Delete</button>
                        <button class="action-btn view" onclick="previewBook(${book.id})">${getIcon('view')} View</button>
                    </div>
                <\/td>
            <\/tr>
        `;
    }).join('');
    
    const totalBooksCount = document.getElementById('totalBooksCount');
    if (totalBooksCount) totalBooksCount.textContent = books.length;
}

function openAddBookModal() {
    document.getElementById('bookModalTitle').textContent = '✨ Add New Book';
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
    document.getElementById('bookImage').value = '';
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
    } else {
        preview.classList.remove('show');
        preview.src = '#';
    }
}

async function saveBook(event) {
    event.preventDefault();

    const bookId = document.getElementById('bookId').value;
    const formData = new FormData();
    
    formData.append('isbn', document.getElementById('bookISBN').value);
    formData.append('title', document.getElementById('bookTitle').value);
    formData.append('author', document.getElementById('bookAuthor').value);
    formData.append('price', document.getElementById('bookPrice').value);
    formData.append('stock', document.getElementById('bookStock').value);
    formData.append('synopsis', document.getElementById('bookSynopsis').value);
    formData.append('condition', document.getElementById('bookCondition').value);
    
    const imageFile = document.getElementById('bookImage').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    if (bookId) {
        formData.append('_method', 'PUT');
    }

    try {
        const url = bookId ? `/books/${bookId}` : '/books';
        
        const response = await fetch(`${API_BASE_URL}${url}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();
        
        if (response.ok) {
            showToast(bookId ? '📚 Book updated successfully!' : '✨ New book added successfully!', 'success');
            closeModal('book');
            await loadInventory();
            await loadDashboardPreviews();
        } else {
            showToast(data.message || data.error || 'Error saving book', 'error');
        }
    } catch (error) {
        console.error('Error saving book:', error);
        showToast('Error saving book', 'error');
    }
}

async function editBook(bookId) {
    try {
        const book = await apiRequest(`/books/${bookId}`);
        
        document.getElementById('bookModalTitle').textContent = '✏️ Edit Book';
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
            preview.src = `/storage/${book.image}`;
            preview.classList.add('show');
        } else {
            document.getElementById('imagePreview').classList.remove('show');
            document.getElementById('imagePreview').src = '#';
        }
        
        document.getElementById('bookImage').value = '';
        
        openModal('book');
    } catch (error) {
        console.error('Error loading book:', error);
        showToast('Error loading book', 'error');
    }
}

async function deleteBook(bookId) {
    if (confirm('⚠️ Are you sure you want to delete this book? This action cannot be undone.')) {
        try {
            await apiRequest(`/books/${bookId}`, 'DELETE');
            await loadInventory();
            await loadDashboardPreviews();
            showToast('🗑️ Book deleted successfully', 'success');
        } catch (error) {
            console.error('Error deleting book:', error);
        }
    }
}

async function previewBook(bookId) {
    try {
        const book = await apiRequest(`/books/${bookId}`);
        
        const modalContent = `
            <div style="text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">${getIcon('book')}</div>
                <h3 style="color: #231810;">${escapeHtml(book.title)}</h3>
                <p style="color: #AE7F62;">by ${escapeHtml(book.author)}</p>
                <hr style="margin: 1rem 0; border-color: #AE7F62;">
                <p><strong>Price:</strong> <span style="color: #613D28; font-size: 1.2rem;">$${parseFloat(book.price).toFixed(2)}</span></p>
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
    } catch (error) {
        console.error('Error loading book:', error);
    }
}

async function loadOrders() {
    if (!currentUser?.is_admin) return;
    
    try {
        const orders = await apiRequest('/orders');
        displayOrders(orders);
    } catch (error) {
        console.error('Error loading orders:', error);
    }
}

function displayOrders(orders) {
    const ordersBody = document.getElementById('ordersBody');
    if (!ordersBody) return;
    
    if (!orders || orders.length === 0) {
        ordersBody.innerHTML = '<tr><td colspan="10" style="text-align: center;">No orders found<\/td><\/tr>';
        const totalOrdersCount = document.getElementById('totalOrdersCount');
        if (totalOrdersCount) totalOrdersCount.textContent = '0';
        return;
    }

    ordersBody.innerHTML = orders.map((order, index) => {
        const displayStatus = order.approval_status === 'pending' ? 'Pending Approval' :
                             order.approval_status === 'cancelled' ? 'Cancelled' :
                             order.order_status;
        const statusClass = order.approval_status === 'pending' ? 'pending' :
                           order.approval_status === 'cancelled' ? 'cancelled' :
                           order.order_status;
        const paymentStatusClass = order.payment_status === 'Paid' ? 'payment-paid' : 
                                  order.payment_status === 'Awaiting Payment' ? 'payment-awaiting' : 'payment-pending';
        
        const itemsPreview = order.items.map(item => `
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                ${item.book_image ? 
                    `<img src="/storage/${item.book_image}" style="width: 30px; height: 30px; border-radius: 4px; object-fit: cover;">` : 
                    `<div style="width: 30px; height: 30px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">${getIcon('book')}</div>`
                }
                <span>${escapeHtml(item.title)} x${item.quantity}</span>
            </div>
        `).join('');
        
        return `
            <tr style="animation: fadeIn 0.2s ease-out ${index * 0.01}s both;">
                <td style="font-weight: 600;">#${order.order_number}<\/td>
                <td>${escapeHtml(order.user?.name || 'Unknown')}<\/td>
                <td>${new Date(order.created_at).toLocaleDateString()}<\/td>
                <td><div style="max-height: 100px; overflow-y: auto;">${itemsPreview}</div><\/td>
                <td style="color: #613D28; font-weight: 700;">$${parseFloat(order.total).toFixed(2)}<\/td>
                <td>${order.payment_method === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.payment_method}<\/td>
                <td>
                    <span class="status-badge ${paymentStatusClass}">
                        ${order.payment_status === 'Paid' ? getIcon('check') : getIcon('clock')} ${order.payment_status}
                    </span>
                <\/td>
                <td>
                    <span class="status-badge status-${statusClass}">
                        ${displayStatus}
                    </span>
                <\/td>
                <td>${order.payment_details ? `<button class="action-btn view" onclick="viewPaymentDetails(${order.id})">${getIcon('view')} Details</button>` : '<span style="color: #64748b;">—</span>'}<\/td>
                <td>
                    <div class="action-buttons" style="display: flex; gap: 5px; flex-wrap: wrap;">
                        ${order.approval_status === 'pending' ? 
                            `<button class="action-btn approve" onclick="approveOrder(${order.id})">${getIcon('approve')} Approve</button>
                             <button class="action-btn cancel" onclick="rejectOrder(${order.id})">${getIcon('reject')} Reject</button>` : 
                          order.approval_status === 'approved' && order.order_status === 'pending' ?
                            `<button class="action-btn ship" onclick="updateOrderStatus(${order.id}, 'shipped')">${getIcon('ship')} Ship</button>` :
                          order.order_status === 'shipped' ?
                            `<button class="action-btn approve" onclick="updateOrderStatus(${order.id}, 'delivered')">${getIcon('deliver')} Deliver</button>` :
                          order.order_status === 'delivered' ?
                            `<span style="color: #22c55e; font-weight: 500;">${getIcon('complete')} Completed</span>` :
                          order.approval_status === 'cancelled' ?
                            `<span style="color: #ef4444; font-weight: 500;">${getIcon('reject')} Rejected</span>` : ''}
                        ${(order.payment_method === 'Bank Transfer' && order.payment_status === 'Awaiting Payment') || 
                           (order.payment_method === 'Cash on Delivery' && order.payment_status === 'Pending') ?
                            `<button class="action-btn payment" onclick="openPaymentApproveModal(${order.id})">${getIcon('payment')} Confirm Payment</button>` : ''}
                        <button class="action-btn view" onclick="viewOrderDetails(${order.id})">${getIcon('view')} View</button>
                        <button class="action-btn delete" onclick="deleteOrder(${order.id})">${getIcon('delete')} Delete</button>
                    </div>
                <\/td>
            <\/tr>
        `;
    }).join('');
    
    const totalOrdersCount = document.getElementById('totalOrdersCount');
    if (totalOrdersCount) totalOrdersCount.textContent = orders.length;
}

async function viewOrderDetails(orderId) {
    try {
        const order = await apiRequest(`/orders/${orderId}`);
        displayOrderDetails(order);
        openModal('order');
    } catch (error) {
        console.error('Error loading order details:', error);
    }
}

function displayOrderDetails(order) {
    const detailsDiv = document.getElementById('orderDetails');
    if (!detailsDiv) return;
    
    const paymentStatusClass = order.payment_status === 'Paid' ? 'payment-paid' : 
                              order.payment_status === 'Awaiting Payment' ? 'payment-awaiting' : 'payment-pending';
    const displayStatus = order.approval_status === 'pending' ? 'Pending Approval' :
                         order.approval_status === 'cancelled' ? 'Cancelled' :
                         order.order_status;
    const statusClass = order.approval_status === 'pending' ? 'pending' :
                       order.approval_status === 'cancelled' ? 'cancelled' :
                       order.order_status;
    
    let paymentDetailsHtml = '';
    if (order.payment_details) {
        paymentDetailsHtml = `
            <h4 style="margin: 1.5rem 0 1rem; color: #231810;">${getIcon('bank')} Payment Details</h4>
            <div style="background: #AE7F6220; padding: 1rem; border-radius: 12px;">
                <p><strong>Bank:</strong> ${escapeHtml(order.payment_details.sender_bank)}</p>
                <p><strong>Account Name:</strong> ${escapeHtml(order.payment_details.sender_account_name)}</p>
                <p><strong>Account Number:</strong> ${escapeHtml(order.payment_details.sender_account_number)}</p>
                <p><strong>Reference Number:</strong> ${escapeHtml(order.payment_details.reference_number)}</p>
                <p><strong>Transfer Date:</strong> ${order.payment_details.transfer_date}</p>
                <p><strong>Transfer Time:</strong> ${order.payment_details.transfer_time || 'N/A'}</p>
                <p><strong>Amount Transferred:</strong> $${parseFloat(order.payment_details.transfer_amount).toFixed(2)}</p>
                <p><strong>Additional Notes:</strong> ${escapeHtml(order.payment_details.additional_notes) || 'None'}</p>
            </div>
        `;
    }

    let deliveryHtml = '';
    if (order.delivery_address) {
        deliveryHtml = `
            <h4 style="margin: 1.5rem 0 1rem; color: #231810;">${getIcon('ship')} Delivery Details</h4>
            <div style="background: #FCCDAC; padding: 1rem; border-radius: 12px; border-left: 4px solid #613D28;">
                <p><strong>Recipient:</strong> ${escapeHtml(order.recipient_name || order.user?.name)}</p>
                <p><strong>Address:</strong> ${escapeHtml(order.delivery_address)}</p>
                <p><strong>Contact Number:</strong> ${escapeHtml(order.contact_number)}</p>
                ${order.delivery_instructions ? `<p><strong>Instructions:</strong> ${escapeHtml(order.delivery_instructions)}</p>` : ''}
            </div>
        `;
    }

    detailsDiv.innerHTML = `
        <div style="margin-bottom: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div><strong>Order ID:</strong> #${order.order_number}</div>
            <div><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</div>
            <div><strong>Order Status:</strong> <span class="status-badge status-${statusClass}">${displayStatus}</span></div>
            <div><strong>Payment Method:</strong> ${order.payment_method === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.payment_method}</div>
            <div><strong>Payment Status:</strong> <span class="status-badge ${paymentStatusClass}">${order.payment_status}</span></div>
            <div><strong>Customer:</strong> ${escapeHtml(order.user?.name)} (${escapeHtml(order.user?.email)})</div>
        </div>
        <h4 style="color: #231810; margin-bottom: 1rem;">${getIcon('inventory')} Order Items</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #613D28; color: #FCCDAC;">
                    <th style="padding: 0.75rem; text-align: left;">Image</th>
                    <th style="padding: 0.75rem; text-align: left;">Book</th>
                    <th style="padding: 0.75rem; text-align: center;">Quantity</th>
                    <th style="padding: 0.75rem; text-align: right;">Price</th>
                    <th style="padding: 0.75rem; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                ${order.items?.map(item => `
                    <tr style="border-bottom: 1px solid #AE7F62;">
                        <td style="padding: 0.75rem;">
                            ${item.book_image ? 
                                `<img src="/storage/${item.book_image}" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">` : 
                                `<div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">${getIcon('book')}</div>`
                            }
                        <\/td>
                        <td style="padding: 0.75rem;">
                            <strong>${escapeHtml(item.title)}</strong><br>
                            <small style="color: #64748b;">by ${escapeHtml(item.author)}</small><br>
                            <small style="color: #22c55e;">Condition: ${item.condition || 'N/A'}</small>
                        <\/td>
                        <td style="padding: 0.75rem; text-align: center;">${item.quantity}<\/td>
                        <td style="padding: 0.75rem; text-align: right;">$${parseFloat(item.price).toFixed(2)}<\/td>
                        <td style="padding: 0.75rem; text-align: right;"><strong>$${(item.quantity * item.price).toFixed(2)}</strong><\/td>
                    <\/tr>
                `).join('')}
            </tbody>
            <tfoot>
                <tr style="background: #AE7F6220;">
                    <td colspan="4" style="padding: 0.75rem; text-align: right;"><strong>Subtotal:</strong><\/td>
                    <td style="padding: 0.75rem; text-align: right;">$${parseFloat(order.subtotal).toFixed(2)}<\/td>
                <\/tr>
                <tr style="background: #AE7F6220;">
                    <td colspan="4" style="padding: 0.75rem; text-align: right;"><strong>Shipping Fee:</strong><\/td>
                    <td style="padding: 0.75rem; text-align: right;">$${parseFloat(order.shipping_fee).toFixed(2)}<\/td>
                <\/tr>
                <tr style="background: #AE7F6220; font-weight: bold;">
                    <td colspan="4" style="padding: 0.75rem; text-align: right;"><strong>Total:</strong><\/td>
                    <td style="padding: 0.75rem; text-align: right;"><strong style="color: #613D28;">$${parseFloat(order.total).toFixed(2)}</strong><\/td>
                <\/tr>
            </tfoot>
        <\/table>
        ${deliveryHtml}
        ${paymentDetailsHtml}
        <div style="margin-top: 1.5rem; text-align: right;">
            <button class="btn btn-secondary" onclick="closeModal('order')">Close</button>
        </div>
    `;
}

async function approveOrder(orderId) {
    try {
        await apiRequest(`/orders/${orderId}/approve`, 'POST');
        loadOrders();
        loadDashboardPreviews();
        loadInventory();
        showToast('✅ Order approved successfully', 'success');
        loadNotifications();
    } catch (error) {
        console.error('Error approving order:', error);
    }
}

async function rejectOrder(orderId) {
    if (confirm('⚠️ Are you sure you want to reject this order?')) {
        try {
            await apiRequest(`/orders/${orderId}/reject`, 'POST');
            loadOrders();
            loadDashboardPreviews();
            showToast('❌ Order rejected', 'info');
            loadNotifications();
        } catch (error) {
            console.error('Error rejecting order:', error);
        }
    }
}

async function updateOrderStatus(orderId, newStatus) {
    try {
        await apiRequest(`/orders/${orderId}/status`, 'POST', { status: newStatus });
        loadOrders();
        loadDashboardPreviews();
        const statusEmoji = newStatus === 'shipped' ? getIcon('ship') : newStatus === 'delivered' ? getIcon('complete') : getIcon('pending');
        showToast(`${statusEmoji} Order status updated to ${newStatus}`, 'success');
        loadNotifications();
    } catch (error) {
        console.error('Error updating order status:', error);
    }
}

async function deleteOrder(orderId) {
    if (confirm('⚠️ Are you sure you want to delete this order? This action cannot be undone.')) {
        try {
            await apiRequest(`/orders/${orderId}`, 'DELETE');
            loadOrders();
            loadDashboardPreviews();
            showToast('🗑️ Order deleted successfully', 'success');
            loadNotifications();
        } catch (error) {
            console.error('Error deleting order:', error);
        }
    }
}

async function openPaymentApproveModal(orderId) {
    try {
        const order = await apiRequest(`/orders/${orderId}`);
        pendingPaymentOrderId = orderId;
        
        let paymentDetailsHtml = '';
        if (order.payment_details) {
            paymentDetailsHtml = `
                <div style="background: #AE7F6220; padding: 1rem; border-radius: 12px; margin: 1rem 0;">
                    <h5 style="color: #231810; margin-bottom: 0.5rem;">${getIcon('money')} Customer Payment Details:</h5>
                    <p><strong>Bank:</strong> ${escapeHtml(order.payment_details.sender_bank)}</p>
                    <p><strong>Account Name:</strong> ${escapeHtml(order.payment_details.sender_account_name)}</p>
                    <p><strong>Account Number:</strong> ${escapeHtml(order.payment_details.sender_account_number)}</p>
                    <p><strong>Reference Number:</strong> ${escapeHtml(order.payment_details.reference_number)}</p>
                    <p><strong>Transfer Date:</strong> ${order.payment_details.transfer_date}</p>
                    <p><strong>Amount Transferred:</strong> <strong style="color: #613D28;">$${parseFloat(order.payment_details.transfer_amount).toFixed(2)}</strong></p>
                </div>
            `;
        }

        let deliveryHtml = '';
        if (order.delivery_address) {
            deliveryHtml = `
                <div style="background: #FCCDAC; padding: 1rem; border-radius: 12px; margin: 1rem 0; border-left: 4px solid #613D28;">
                    <h5 style="color: #231810; margin-bottom: 0.5rem;">${getIcon('ship')} Delivery Details:</h5>
                    <p><strong>Recipient:</strong> ${escapeHtml(order.recipient_name || order.user?.name)}</p>
                    <p><strong>Address:</strong> ${escapeHtml(order.delivery_address)}</p>
                    <p><strong>Contact:</strong> ${escapeHtml(order.contact_number)}</p>
                    ${order.delivery_instructions ? `<p><strong>Instructions:</strong> ${escapeHtml(order.delivery_instructions)}</p>` : ''}
                </div>
            `;
        }

        const content = `
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">${order.payment_method === 'Cash on Delivery' ? getIcon('cash') : getIcon('bank')}</div>
                <h4 style="color: #231810;">Confirm Payment for Order #${order.order_number}</h4>
            </div>
            <div style="background: #FCCDAC; padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem;">
                <p><strong>Customer:</strong> ${escapeHtml(order.user?.name)}</p>
                <p><strong>Payment Method:</strong> ${order.payment_method === 'Bank Transfer' ? getIcon('bank') : getIcon('cash')} ${order.payment_method}</p>
                <p><strong>Current Status:</strong> <span class="status-badge ${order.payment_status === 'Pending' ? 'payment-pending' : 'payment-awaiting'}">${order.payment_status}</span></p>
                <p><strong>Total Amount:</strong> <strong style="color: #613D28;">$${parseFloat(order.total).toFixed(2)}</strong></p>
                <p><strong>Order Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
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
    } catch (error) {
        console.error('Error loading order for payment approval:', error);
    }
}

async function approvePayment() {
    if (!pendingPaymentOrderId) {
        closeModal('paymentApprove');
        return;
    }

    try {
        await apiRequest(`/orders/${pendingPaymentOrderId}/confirm-payment`, 'POST');
        loadOrders();
        loadDashboardPreviews();
        showToast('✅ Payment confirmed successfully', 'success');
        closeModal('paymentApprove');
        pendingPaymentOrderId = null;
        loadNotifications();
    } catch (error) {
        console.error('Error confirming payment:', error);
    }
}

async function viewPaymentDetails(orderId) {
    try {
        const order = await apiRequest(`/orders/${orderId}`);
        if (!order.payment_details) {
            showToast('No payment details available', 'info');
            return;
        }

        const details = order.payment_details;
        const content = `
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">${getIcon('bank')}</div>
                <h4 style="color: #231810;">Bank Transfer Details</h4>
            </div>
            <div style="background: #AE7F6220; padding: 1.5rem; border-radius: 12px;">
                <h5 style="color: #231810; margin-bottom: 1rem;">${getIcon('users')} Sender Information:</h5>
                <p><strong>Bank:</strong> ${escapeHtml(details.sender_bank)}</p>
                <p><strong>Account Name:</strong> ${escapeHtml(details.sender_account_name)}</p>
                <p><strong>Account Number:</strong> ${escapeHtml(details.sender_account_number)}</p>
                <h5 style="color: #231810; margin: 1rem 0;">${getIcon('money')} Transfer Information:</h5>
                <p><strong>Reference Number:</strong> ${escapeHtml(details.reference_number)}</p>
                <p><strong>Transfer Date:</strong> ${details.transfer_date}</p>
                <p><strong>Amount Transferred:</strong> <strong style="color: #613D28;">$${parseFloat(details.transfer_amount).toFixed(2)}</strong></p>
                <h5 style="color: #231810; margin: 1rem 0;">📝 Additional Notes:</h5>
                <p style="background: #FCCDAC; padding: 0.5rem; border-radius: 8px;">${escapeHtml(details.additional_notes) || 'None'}</p>
            </div>
        `;
        
        document.getElementById('paymentDetailsContent').innerHTML = content;
        openModal('paymentDetails');
    } catch (error) {
        console.error('Error loading payment details:', error);
    }
}

async function loadUsers() {
    if (!currentUser?.is_admin) return;
    
    const searchTerm = document.getElementById('userSearch')?.value || '';
    const userType = document.getElementById('userTypeFilter')?.value || 'all';
    
    try {
        let url = '/users';
        const params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (userType !== 'all') params.append('type', userType);
        if (params.toString()) url += '?' + params.toString();
        
        const users = await apiRequest(url);
        displayUsers(users);
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

function displayUsers(users) {
    const usersBody = document.getElementById('usersBody');
    if (!usersBody) return;
    
    if (!users || users.length === 0) {
        usersBody.innerHTML = '<tr><td colspan="9" style="text-align: center;">No users found<\/td><\/tr>';
        return;
    }

    usersBody.innerHTML = users.map((user, index) => {
        const initial = user.name.charAt(0).toUpperCase();
        const avatarHtml = user.profile_pic ? 
            `<img src="/storage/${user.profile_pic}" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover;">` :
            `<div class="user-avatar">${initial}</div>`;
        
        return `
            <tr style="animation: fadeIn 0.2s ease-out ${index * 0.02}s both;">
                <td>${avatarHtml}<\/td>
                <td><code style="font-size: 0.8rem;">#${user.id}</code><\/td>
                <td><strong>${escapeHtml(user.name)}</strong><\/td>
                <td>${escapeHtml(user.email)}<\/td>
                <td>
                    <span class="status-badge ${user.is_admin ? 'status-shipped' : 'status-pending'}">
                        ${user.is_admin ? getIcon('admin') : getIcon('customer')} ${user.is_admin ? 'Admin' : 'Customer'}
                    </span>
                <\/td>
                <td>${user.orders_count || 0}<\/td>
                <td><strong style="color: #613D28;">$${parseFloat(user.total_spent || 0).toFixed(2)}</strong><\/td>
                <td>${new Date(user.created_at).toLocaleDateString()}<\/td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" onclick="viewUserDetails(${user.id})">${getIcon('view')} View</button>
                        <button class="action-btn edit" onclick="editUser(${user.id})">${getIcon('edit')} Edit</button>
                        ${!user.is_admin ? `<button class="action-btn delete" onclick="deleteUser(${user.id})">${getIcon('delete')} Delete</button>` : (user.id !== currentUser?.id ? `<button class="action-btn delete" onclick="deleteUser(${user.id})">${getIcon('delete')} Delete</button>` : '')}
                    </div>
                <\/td>
            <\/tr>
        `;
    }).join('');
}

function filterUsers() {
    loadUsers();
}

function showAddUserModal() {
    document.getElementById('userModalTitle').textContent = '✨ Add New User';
    document.getElementById('userId').value = '';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userType').value = 'customer';
    openModal('user');
}

async function editUser(userId) {
    try {
        const user = await apiRequest(`/users/${userId}`);
        document.getElementById('userModalTitle').textContent = '✏️ Edit User';
        document.getElementById('userId').value = user.id;
        document.getElementById('userName').value = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').required = false;
        document.getElementById('userType').value = user.is_admin ? 'admin' : 'customer';
        openModal('user');
    } catch (error) {
        console.error('Error loading user:', error);
    }
}

async function saveUser(event) {
    event.preventDefault();

    const userId = document.getElementById('userId').value;
    const password = document.getElementById('userPassword').value;
    
    const userData = {
        name: document.getElementById('userName').value,
        email: document.getElementById('userEmail').value,
        is_admin: document.getElementById('userType').value === 'admin'
    };

    if (password) {
        userData.password = password;
    }

    try {
        const url = userId ? `/users/${userId}` : '/users';
        const method = userId ? 'PUT' : 'POST';
        
        const data = await apiRequest(url, method, userData);
        
        showToast(userId ? '✅ User updated successfully!' : '✨ New user added successfully!', 'success');
        closeModal('user');
        loadUsers();
    } catch (error) {
        console.error('Error saving user:', error);
    }
}

async function deleteUser(userId) {
    if (userId === currentUser?.id) {
        showToast('❌ You cannot delete your own account!', 'error');
        return;
    }

    if (confirm('⚠️ Are you sure you want to delete this user? This action cannot be undone.')) {
        try {
            await apiRequest(`/users/${userId}`, 'DELETE');
            loadUsers();
            showToast('🗑️ User deleted successfully', 'success');
        } catch (error) {
            console.error('Error deleting user:', error);
        }
    }
}

async function viewUserDetails(userId) {
    try {
        const user = await apiRequest(`/users/${userId}`);
        
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
        
        const profilePicHtml = user.profile_pic ? 
            `<img src="/storage/${user.profile_pic}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem; border: 3px solid #AE7F62;">` :
            `<div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #613D28 0%, #231810 100%); margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: #FCCDAC; font-size: 2rem; font-weight: bold;">
                ${user.name.charAt(0).toUpperCase()}
            </div>`;
        
        const detailsDiv = document.getElementById('userDetails');
        if (detailsDiv) {
            detailsDiv.innerHTML = `
                <div style="text-align: center; margin-bottom: 2rem;">
                    ${profilePicHtml}
                    <h3 style="color: #231810;">${escapeHtml(user.name)}</h3>
                    <p style="color: #AE7F62;">${escapeHtml(user.email)}</p>
                    <span class="status-badge ${user.is_admin ? 'status-shipped' : 'status-pending'}">
                        ${user.is_admin ? getIcon('admin') : getIcon('customer')} ${user.is_admin ? 'Administrator' : 'Customer'}
                    </span>
                </div>
                <div style="margin-bottom: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                    <p><strong>User ID:</strong> #${user.id}</p>
                    ${user.birthday ? `<p><strong>Birthday:</strong> ${new Date(user.birthday).toLocaleDateString()}</p>` : ''}
                    ${ageInfo}
                    <p><strong>Phone:</strong> ${user.phone || 'Not provided'}</p>
                    <p><strong>Address:</strong> ${user.address || 'Not provided'}</p>
                    <p><strong>Member Since:</strong> ${new Date(user.created_at).toLocaleDateString()}</p>
                    <p><strong>Total Orders:</strong> ${user.orders_count || 0}</p>
                    <p><strong>Total Spent:</strong> <strong style="color: #613D28;">$${parseFloat(user.total_spent || 0).toFixed(2)}</strong></p>
                </div>
            `;
        }
        openModal('viewUser');
    } catch (error) {
        console.error('Error loading user details:', error);
    }
}

async function loadSalesReports() {
    if (!currentUser?.is_admin) return;
    
    const period = document.getElementById('reportPeriod')?.value || 'today';
    
    try {
        const data = await apiRequest(`/reports/sales?period=${period}`);
        displaySalesReports(data);
    } catch (error) {
        console.error('Error loading sales reports:', error);
        const revenueStats = document.getElementById('revenueStats');
        if (revenueStats) {
            revenueStats.innerHTML = `
                <div class="stat-card">
                    <div class="stat-icon">${getIcon('reject')}</div>
                    <h4>Error</h4>
                    <div class="stat-value">Unable to load reports</div>
                    <div class="stat-label">Please try again later</div>
                </div>
            `;
        }
    }
}

function displaySalesReports(data) {
    const revenueStats = document.getElementById('revenueStats');
    const topBooks = document.getElementById('topBooks');
    
    if (revenueStats) {
        revenueStats.innerHTML = `
            <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.1s both;">
                <div class="stat-icon">${getIcon('revenue')}</div>
                <h4>Total Revenue</h4>
                <div class="stat-value">$${parseFloat(data.stats.total_revenue).toFixed(2)}</div>
                <div class="stat-label">${data.period}</div>
            </div>
            <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.2s both;">
                <div class="stat-icon">${getIcon('cash')}</div>
                <h4>COD Revenue</h4>
                <div class="stat-value">$${parseFloat(data.stats.cod_revenue).toFixed(2)}</div>
                <div class="stat-label">Cash on Delivery</div>
            </div>
            <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.3s both;">
                <div class="stat-icon">${getIcon('bank')}</div>
                <h4>Bank Transfer</h4>
                <div class="stat-value">$${parseFloat(data.stats.bank_revenue).toFixed(2)}</div>
                <div class="stat-label">Paid via bank</div>
            </div>
            <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.4s both;">
                <div class="stat-icon">${getIcon('pending')}</div>
                <h4>Pending Payments</h4>
                <div class="stat-value">${data.stats.pending_payments || 0}</div>
                <div class="stat-label">Awaiting confirmation</div>
            </div>
            <div class="stat-card" style="animation: fadeIn 0.3s ease-out 0.5s both;">
                <div class="stat-icon">${getIcon('reports')}</div>
                <h4>Average Order</h4>
                <div class="stat-value">$${parseFloat(data.stats.avg_order_value || 0).toFixed(2)}</div>
                <div class="stat-label">Per paid order</div>
            </div>
        `;
    }

    if (topBooks) {
        const topBooksHtml = data.top_books?.length > 0 ? 
            data.top_books.map((book, index) => `
                <div class="top-book-card" style="animation: fadeIn 0.2s ease-out ${index * 0.1}s both;">
                    <div class="top-book-title">${escapeHtml(book.title)}</div>
                    <div class="top-book-sales">${getIcon('inventory')} ${book.total_sold} sold</div>
                    <div class="top-book-revenue">${getIcon('revenue')} $${parseFloat(book.total_revenue).toFixed(2)}</div>
                </div>
            `).join('') : '<p style="text-align: center; color: #AE7F62;">✨ No sales data available</p>';
        
        topBooks.innerHTML = topBooksHtml;
    }
}

async function exportData() {
    try {
        const response = await fetch('/api/admin/export', {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `pahina_export_${new Date().toISOString().slice(0,10)}.json`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            showToast('📥 Data exported successfully!', 'success');
        } else {
            showToast('Export failed', 'error');
        }
    } catch (error) {
        console.error('Error exporting data:', error);
        showToast('Error exporting data', 'error');
    }
}

async function exportOrders() {
    try {
        const response = await fetch('/api/admin/orders/export', {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `orders_export_${new Date().toISOString().slice(0,10)}.json`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            showToast('📥 Orders exported successfully!', 'success');
        } else {
            showToast('Export failed', 'error');
        }
    } catch (error) {
        console.error('Error exporting orders:', error);
        showToast('Error exporting orders', 'error');
    }
}

async function exportReport() {
    const period = document.getElementById('reportPeriod')?.value || 'today';
    
    try {
        const response = await fetch(`/api/admin/reports/export?period=${period}`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `sales_report_${period}_${new Date().toISOString().slice(0,10)}.json`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            showToast('📥 Report exported successfully!', 'success');
        } else {
            showToast('Export failed', 'error');
        }
    } catch (error) {
        console.error('Error exporting report:', error);
        showToast('Error exporting report', 'error');
    }
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    if (toast) toast.remove();
    
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
        if (toastElement) toastElement.remove();
    }, 3500);
}

// Initialization
bestSellersLoaded = false;
bestSellersLoading = false;
bestSellersLoadAttempted = false;
currentActiveSection = 'dashboard-section';

if (authToken) {
    fetch('/api/user', {
        headers: { 
            'Authorization': `Bearer ${authToken}`,
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.user && data.user.is_admin) {
            currentUser = data.user;
            updateNavigation();
            showSection('dashboard-section');
            loadAdminDashboard();
            loadDashboardPreviews();
            startNotificationCheck();
            startRealTimeUpdates();
            initConnectionMonitoring();
            console.log('Admin session restored successfully');
        } else {
            localStorage.removeItem('admin_token');
            sessionStorage.removeItem('admin_logged_in');
            sessionStorage.removeItem('admin_user');
            authToken = null;
            updateNavigation();
        }
    })
    .catch(() => {
        localStorage.removeItem('admin_token');
        sessionStorage.removeItem('admin_logged_in');
        sessionStorage.removeItem('admin_user');
        authToken = null;
        updateNavigation();
    });
} else {
    updateNavigation();
}

// Expose functions to global scope
window.showSection = showSection;
window.openLoginModal = openLoginModal;
window.loginUser = loginUser;
window.logout = logout;
window.toggleDropdown = toggleDropdown;
window.toggleNotificationPanel = toggleNotificationPanel;
window.loadInventory = loadInventory;
window.openAddBookModal = openAddBookModal;
window.saveBook = saveBook;
window.editBook = editBook;
window.deleteBook = deleteBook;
window.previewBook = previewBook;
window.previewImage = previewImage;
window.loadOrders = loadOrders;
window.viewOrderDetails = viewOrderDetails;
window.approveOrder = approveOrder;
window.rejectOrder = rejectOrder;
window.updateOrderStatus = updateOrderStatus;
window.deleteOrder = deleteOrder;
window.openPaymentApproveModal = openPaymentApproveModal;
window.approvePayment = approvePayment;
window.viewPaymentDetails = viewPaymentDetails;
window.loadUsers = loadUsers;
window.filterUsers = filterUsers;
window.showAddUserModal = showAddUserModal;
window.editUser = editUser;
window.saveUser = saveUser;
window.deleteUser = deleteUser;
window.viewUserDetails = viewUserDetails;
window.loadSalesReports = loadSalesReports;
window.exportData = exportData;
window.exportOrders = exportOrders;
window.exportReport = exportReport;
window.closeModal = closeModal;
window.openUserInterface = openUserInterface;
window.openProfileSettings = openProfileSettings;
window.quickSync = quickSync;
window.quickExport = quickExport;
window.quickAddBook = quickAddBook;