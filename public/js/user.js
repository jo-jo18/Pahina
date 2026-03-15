
document.addEventListener('DOMContentLoaded', function() {
   
    window.books = [];
    window.users = [];
    window.orders = [];
    window.currentUser = null;
    window.cart = [];
    window.wishlist = [];
    window.currentPreviewBook = null;
    window.pendingCartItem = null;
    window.selectedItems = new Set();
    window.SHIPPING_FEE = 5.00;

    loadData();
    updateNavigation();
    loadFeaturedBooks();

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const avatar = document.querySelector('.profile-avatar');
        if (dropdown && avatar && !avatar.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
});


function loadData() {
    window.books = JSON.parse(localStorage.getItem('pahina_books')) || [];
    window.users = JSON.parse(localStorage.getItem('pahina_users')) || [];
    window.orders = JSON.parse(localStorage.getItem('pahina_orders')) || [];
    window.currentUser = JSON.parse(localStorage.getItem('pahina_currentUser')) || null;
    window.cart = JSON.parse(localStorage.getItem('pahina_cart')) || [];
    window.wishlist = JSON.parse(localStorage.getItem('pahina_wishlist')) || [];

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

function saveCart() {
    localStorage.setItem('pahina_cart', JSON.stringify(window.cart));
    updateCartCount();
}

function saveWishlist() {
    localStorage.setItem('pahina_wishlist', JSON.stringify(window.wishlist));
}

function saveCurrentUser() {
    localStorage.setItem('pahina_currentUser', JSON.stringify(window.currentUser));
}


function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById(`${sectionId}-section`).classList.add('active');
    
    if (sectionId === 'home') loadFeaturedBooks();
    if (sectionId === 'shop') loadShop();
    if (sectionId === 'wishlist') loadWishlist();
    if (sectionId === 'cart') {
        loadCart();
        updateSelectedSummary();
        if (window.currentUser) {
            document.getElementById('codRecipientName').value = window.currentUser.name || '';
        }
    }
    if (sectionId === 'orders') loadUserOrders();
    if (sectionId === 'profile') loadProfile();
}

function updateNavigation() {
    const navButtons = document.getElementById('navButtons');
    const cartCount = window.cart.reduce((sum, item) => sum + item.quantity, 0);
    
    if (window.currentUser) {
        let profileHtml = '';
        if (window.currentUser.profilePic) {
            profileHtml = `<img src="${window.currentUser.profilePic}" alt="${window.currentUser.name}">`;
        } else {
            profileHtml = `<span>${window.currentUser.name.charAt(0).toUpperCase()}</span>`;
        }
        
        navButtons.innerHTML = `
            <button class="nav-btn" onclick="showSection('home')">🏠 Home</button>
            <button class="nav-btn" onclick="showSection('shop')">📚 Shop</button>
            <button class="nav-btn" onclick="showSection('wishlist')">❤️ Wishlist</button>
            <button class="nav-btn" onclick="showSection('cart')">🛒 Cart (${cartCount})</button>
            <button class="nav-btn" onclick="showSection('orders')">📦 Orders</button>
            <div class="profile-container">
                <div class="profile-avatar" onclick="toggleDropdown()">
                    ${profileHtml}
                </div>
                <div class="dropdown-menu" id="profileDropdown">
                    <div class="dropdown-header">
                        <h4>${window.currentUser.name}</h4>
                        <p>${window.currentUser.email}</p>
                    </div>
                    <button class="dropdown-item" onclick="showSection('profile'); closeDropdown()">
                        <i>👤</i> My Profile
                    </button>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item" onclick="logout()">
                        <i>🚪</i> Logout
                    </button>
                </div>
            </div>
        `;
    } else {
        navButtons.innerHTML = `
            <button class="nav-btn" onclick="showSection('home')">🏠 Home</button>
            <button class="nav-btn" onclick="showSection('shop')">📚 Shop</button>
            <button class="nav-btn" onclick="openLoginModal()">🔑 Login</button>
            <button class="nav-btn" onclick="openRegisterModal()">📝 Register</button>
        `;
    }
}

function toggleDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) dropdown.classList.toggle('show');
}

function closeDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) dropdown.classList.remove('show');
}

function openModal(modalName) {
    document.getElementById(`${modalName}Modal`).classList.add('active');
}

function closeModal(modalName) {
    document.getElementById(`${modalName}Modal`).classList.remove('active');
}

function openRegisterModal() {
    closeModal('login');
    document.getElementById('regName').value = '';
    document.getElementById('regBirthday').value = '';
    document.getElementById('regEmail').value = '';
    document.getElementById('regPassword').value = '';
    document.getElementById('regConfirmPassword').value = '';
    document.getElementById('regPhone').value = '';
    document.getElementById('regAddress').value = '';
    document.getElementById('ageWarning').style.display = 'none';
    document.getElementById('registerBtn').disabled = false;
    openModal('register');
}

function openLoginModal() {
    closeModal('register');
    document.getElementById('loginEmail').value = '';
    document.getElementById('loginPassword').value = '';
    openModal('login');
}

function validateAge() {
    const birthday = document.getElementById('regBirthday').value;
    const ageWarning = document.getElementById('ageWarning');
    const registerBtn = document.getElementById('registerBtn');
    if (!birthday) {
        ageWarning.style.display = 'none';
        registerBtn.disabled = false;
        return true;
    }
    const birthDate = new Date(birthday);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    if (age < 15) {
        ageWarning.style.display = 'block';
        registerBtn.disabled = true;
        return false;
    } else {
        ageWarning.style.display = 'none';
        registerBtn.disabled = false;
        return true;
    }
}


function registerUser(event) {
    event.preventDefault();
    const name = document.getElementById('regName').value;
    const birthday = document.getElementById('regBirthday').value;
    const email = document.getElementById('regEmail').value;
    const password = document.getElementById('regPassword').value;
    const confirmPassword = document.getElementById('regConfirmPassword').value;
    const phone = document.getElementById('regPhone').value;
    const address = document.getElementById('regAddress').value;

    if (!validateAge()) {
        showToast('You must be at least 15 years old to register', 'error');
        return;
    }
    if (password !== confirmPassword) {
        showToast('Passwords do not match!', 'error');
        return;
    }
    if (window.users.find(u => u.email === email)) {
        showToast('Email already registered!', 'error');
        return;
    }

    const newUser = {
        id: 'user_' + Date.now(),
        name, email, password, birthday, phone, address,
        profilePic: null,
        isAdmin: false,
        joinDate: new Date().toISOString()
    };
    window.users.push(newUser);
    saveUsers();
    window.currentUser = newUser;
    saveCurrentUser();
    closeModal('register');
    showToast(`Welcome to Pahina, ${name}!`, 'success');
    updateNavigation();
    showSection('home');
}

function loginUser(event) {
    event.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const user = window.users.find(u => u.email === email && u.password === password);
    if (user) {
        if (user.isAdmin) {
            showToast('Please use the admin login page', 'error');
            return;
        }
        window.currentUser = user;
        saveCurrentUser();
        closeModal('login');
        showToast(`Welcome back, ${user.name}!`, 'success');
        updateNavigation();
        showSection('home');
    } else {
        showToast('Invalid email or password!', 'error');
    }
}

function logout() {
    window.currentUser = null;
    saveCurrentUser();
    window.cart = [];
    window.wishlist = [];
    window.selectedItems.clear();
    saveCart();
    saveWishlist();
    updateNavigation();
    showToast('Logged out successfully', 'info');
    showSection('home');
}


function loadProfile() {
    if (!window.currentUser) {
        showToast('Please login to view profile', 'error');
        showSection('home');
        return;
    }
    document.getElementById('profileName').value = window.currentUser.name || '';
    document.getElementById('profileEmail').value = window.currentUser.email || '';
    document.getElementById('profileBirthday').value = window.currentUser.birthday || '';
    document.getElementById('profilePhone').value = window.currentUser.phone || '';
    document.getElementById('profileAddress').value = window.currentUser.address || '';
    updateProfilePictureDisplay();

    const userOrders = window.orders.filter(o => o.userId === window.currentUser.id);
    const totalSpent = userOrders.reduce((sum, o) => sum + o.total, 0);
    document.getElementById('memberSince').textContent = window.currentUser.joinDate ? new Date(window.currentUser.joinDate).toLocaleDateString() : 'N/A';
    document.getElementById('userId').textContent = '#' + (window.currentUser.id ? window.currentUser.id.slice(-6) : 'N/A');
    document.getElementById('totalOrders').textContent = userOrders.length;
    document.getElementById('totalSpent').textContent = '$' + totalSpent.toFixed(2);
    document.getElementById('wishlistCount').textContent = window.wishlist.length;
    loadRecentActivity();
}

function updateProfilePictureDisplay() {
    const largeImg = document.getElementById('profileLargeImg');
    const largeInitial = document.getElementById('profileLargeInitial');
    if (window.currentUser && window.currentUser.profilePic) {
        largeImg.src = window.currentUser.profilePic;
        largeImg.style.display = 'block';
        largeInitial.style.display = 'none';
        updateNavigation();
    } else {
        largeImg.style.display = 'none';
        largeInitial.style.display = 'block';
        if (window.currentUser) {
            largeInitial.textContent = window.currentUser.name.charAt(0).toUpperCase();
        }
    }
}

function updateProfilePicture(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (window.currentUser) {
                window.currentUser.profilePic = e.target.result;
                const userIndex = window.users.findIndex(u => u.id === window.currentUser.id);
                if (userIndex !== -1) {
                    window.users[userIndex].profilePic = e.target.result;
                }
                saveUsers();
                saveCurrentUser();
                updateProfilePictureDisplay();
                updateNavigation();
                showToast('Profile picture updated!', 'success');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function validateProfileAge() {
    const birthday = document.getElementById('profileBirthday').value;
    const warning = document.getElementById('profileAgeWarning');
    if (!birthday) {
        warning.style.display = 'none';
        return true;
    }
    const birthDate = new Date(birthday);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    if (age < 15) {
        warning.style.display = 'block';
        return false;
    } else {
        warning.style.display = 'none';
        return true;
    }
}

function updateProfileInfo(event) {
    event.preventDefault();
    if (!validateProfileAge()) {
        showToast('You must be at least 15 years old', 'error');
        return;
    }
    if (window.currentUser) {
        window.currentUser.name = document.getElementById('profileName').value;
        window.currentUser.email = document.getElementById('profileEmail').value;
        window.currentUser.birthday = document.getElementById('profileBirthday').value;
        window.currentUser.phone = document.getElementById('profilePhone').value;
        window.currentUser.address = document.getElementById('profileAddress').value;
        const userIndex = window.users.findIndex(u => u.id === window.currentUser.id);
        if (userIndex !== -1) {
            window.users[userIndex] = window.currentUser;
        }
        saveUsers();
        saveCurrentUser();
        updateNavigation();
        showToast('Profile updated successfully!', 'success');
    }
}

function resetProfileForm() {
    if (window.currentUser) {
        document.getElementById('profileName').value = window.currentUser.name || '';
        document.getElementById('profileEmail').value = window.currentUser.email || '';
        document.getElementById('profileBirthday').value = window.currentUser.birthday || '';
        document.getElementById('profilePhone').value = window.currentUser.phone || '';
        document.getElementById('profileAddress').value = window.currentUser.address || '';
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('newPassword').value;
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*]/.test(password)
    };
    document.getElementById('reqLength').innerHTML = (requirements.length ? '🟢' : '🔴') + ' At least 8 characters';
    document.getElementById('reqUppercase').innerHTML = (requirements.uppercase ? '🟢' : '🔴') + ' At least one uppercase letter';
    document.getElementById('reqLowercase').innerHTML = (requirements.lowercase ? '🟢' : '🔴') + ' At least one lowercase letter';
    document.getElementById('reqNumber').innerHTML = (requirements.number ? '🟢' : '🔴') + ' At least one number';
    document.getElementById('reqSpecial').innerHTML = (requirements.special ? '🟢' : '🔴') + ' At least one special character (!@#$%^&*)';

    const metCount = Object.values(requirements).filter(Boolean).length;
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');

    if (metCount <= 2) {
        strengthBar.className = 'password-strength-bar strength-weak';
        strengthText.textContent = 'Weak';
        strengthText.style.color = '#ef4444';
    } else if (metCount <= 4) {
        strengthBar.className = 'password-strength-bar strength-medium';
        strengthText.textContent = 'Medium';
        strengthText.style.color = '#f59e0b';
    } else {
        strengthBar.className = 'password-strength-bar strength-strong';
        strengthText.textContent = 'Strong';
        strengthText.style.color = '#22c55e';
    }
}

function changePassword(event) {
    event.preventDefault();
    const current = document.getElementById('currentPassword').value;
    const newPass = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmNewPassword').value;

    if (!window.currentUser || window.currentUser.password !== current) {
        showToast('Current password is incorrect', 'error');
        return;
    }
    if (newPass !== confirm) {
        showToast('New passwords do not match', 'error');
        return;
    }
    const requirements = {
        length: newPass.length >= 8,
        uppercase: /[A-Z]/.test(newPass),
        lowercase: /[a-z]/.test(newPass),
        number: /[0-9]/.test(newPass),
        special: /[!@#$%^&*]/.test(newPass)
    };
    const metCount = Object.values(requirements).filter(Boolean).length;
    if (metCount < 3) {
        showToast('Password is too weak. Please follow the requirements.', 'error');
        return;
    }
    window.currentUser.password = newPass;
    const userIndex = window.users.findIndex(u => u.id === window.currentUser.id);
    if (userIndex !== -1) {
        window.users[userIndex].password = newPass;
    }
    saveUsers();
    saveCurrentUser();
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmNewPassword').value = '';
    showToast('Password changed successfully!', 'success');
}

function resetPasswordForm() {
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmNewPassword').value = '';
    document.getElementById('reqLength').innerHTML = '🔴 At least 8 characters';
    document.getElementById('reqUppercase').innerHTML = '🔴 At least one uppercase letter';
    document.getElementById('reqLowercase').innerHTML = '🔴 At least one lowercase letter';
    document.getElementById('reqNumber').innerHTML = '🔴 At least one number';
    document.getElementById('reqSpecial').innerHTML = '🔴 At least one special character (!@#$%^&*)';
    document.getElementById('passwordStrengthBar').className = 'password-strength-bar';
    document.getElementById('passwordStrengthBar').style.width = '0%';
    document.getElementById('passwordStrengthText').textContent = 'Weak';
}

function loadRecentActivity() {
    const activityBody = document.getElementById('recentActivity');
    const userOrders = window.orders.filter(o => o.userId === window.currentUser?.id)
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .slice(0, 5);
    if (userOrders.length === 0) {
        activityBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: #64748b; padding: 2rem;">No recent activity</td></tr>';
        return;
    }
    activityBody.innerHTML = userOrders.map(order => `
        <tr>
            <td>${new Date(order.date).toLocaleDateString()}</td>
            <td>Order Placed</td>
            <td>#${order.id.slice(-6)} - $${order.total.toFixed(2)} (${order.items.length} items)</td>
        </tr>
    `).join('');
}

function showProfileTab(tab) {
    document.querySelectorAll('.profile-tab').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    document.getElementById('profileInfoTab').classList.remove('active');
    document.getElementById('profileSecurityTab').classList.remove('active');
    document.getElementById('profileStatsTab').classList.remove('active');
    if (tab === 'info') {
        document.getElementById('profileInfoTab').classList.add('active');
    } else if (tab === 'security') {
        document.getElementById('profileSecurityTab').classList.add('active');
    } else if (tab === 'stats') {
        document.getElementById('profileStatsTab').classList.add('active');
    }
}


function loadFeaturedBooks() {
    const featured = window.books.slice(0, 4);
    document.getElementById('featuredBooks').innerHTML = featured.map(book => createBookCard(book)).join('');
}

function loadShop() {
    const grid = document.getElementById('bookGrid');
    grid.innerHTML = window.books.map(book => createBookCard(book)).join('');
}

function searchBooks() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const filtered = window.books.filter(book => 
        book.title.toLowerCase().includes(query) ||
        book.author.toLowerCase().includes(query) ||
        book.isbn.includes(query)
    );
    document.getElementById('bookGrid').innerHTML = filtered.map(book => createBookCard(book)).join('');
    if (filtered.length === 0) showToast('No books found matching your search', 'info');
}

function createBookCard(book) {
    const stockClass = book.stock > 5 ? 'in-stock' : book.stock > 0 ? 'low-stock' : 'out-of-stock';
    const stockText = book.stock > 5 ? 'In Stock' : book.stock > 0 ? `Only ${book.stock} left` : 'Out of Stock';
    const inWishlist = window.wishlist.includes(book.isbn);
    const coverStyle = book.image ? 
        `style="background-image: url('${book.image}');"` : 
        'class="book-cover default-cover"';
    return `
        <div class="book-card">
            <div class="book-cover" ${coverStyle}>
                ${!book.image ? '📚' : ''}
                <span class="book-condition">${book.condition}</span>
            </div>
            <div class="book-info">
                <h3 class="book-title">${book.title}</h3>
                <p class="book-author">by ${book.author}</p>
                <p class="book-price">$${book.price.toFixed(2)}</p>
                <span class="stock-badge ${stockClass}">${stockText}</span>
            </div>
            <div class="book-actions">
                <button class="btn btn-primary" onclick="openCartConfirm('${book.isbn}')" ${book.stock === 0 ? 'disabled' : ''}>
                    ${book.stock === 0 ? 'Out of Stock' : 'Add to Cart'}
                </button>
                <button class="btn btn-secondary" onclick="toggleWishlist('${book.isbn}')">
                    ${inWishlist ? '❤️ Wishlist' : '🤍 Wishlist'}
                </button>
            </div>
        </div>
    `;
}


function openCartConfirm(isbn) {
    if (!window.currentUser) {
        showToast('Please login to add items to cart', 'error');
        openLoginModal();
        return;
    }
    const book = window.books.find(b => b.isbn === isbn);
    if (!book || book.stock === 0) {
        showToast('Book is out of stock!', 'error');
        return;
    }
    window.pendingCartItem = { book, quantity: 1 };
    const coverHtml = book.image ? 
        `<img src="${book.image}" alt="${book.title}" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">` :
        `<div style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">📚</div>`;
    document.getElementById('confirmBookDetails').innerHTML = `
        ${coverHtml}
        <div style="flex: 1;">
            <div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0.25rem;">${book.title}</div>
            <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.5rem;">by ${book.author}</div>
            <div style="color: #3b82f6; font-weight: 700; font-size: 1.2rem;">$${book.price.toFixed(2)} each</div>
            <div style="margin-top: 0.5rem; font-size: 0.9rem; color: #475569;">Condition: <span style="text-transform: capitalize;">${book.condition}</span></div>
            <div style="font-size: 0.9rem; color: #475569;">Available: ${book.stock} in stock</div>
            <div style="margin-top: 0.5rem; padding: 0.5rem; background: white; border-radius: 8px; font-size: 0.9rem; color: #1e293b; border: 1px solid #e2e8f0;">
                <strong>Synopsis:</strong> ${book.synopsis.substring(0, 100)}${book.synopsis.length > 100 ? '...' : ''}
            </div>
        </div>
    `;
    document.getElementById('confirmQuantity').textContent = '1';
    updateConfirmTotal();
    document.getElementById('confirmQtyDec').disabled = true;
    document.getElementById('confirmQtyInc').disabled = book.stock <= 1;
    openModal('cartConfirm');
}

function updateConfirmQuantity(change) {
    if (!window.pendingCartItem) return;
    const newQty = window.pendingCartItem.quantity + change;
    const maxStock = window.pendingCartItem.book.stock;
    if (newQty >= 1 && newQty <= maxStock) {
        window.pendingCartItem.quantity = newQty;
        document.getElementById('confirmQuantity').textContent = newQty;
        document.getElementById('confirmQtyDec').disabled = newQty <= 1;
        document.getElementById('confirmQtyInc').disabled = newQty >= maxStock;
        updateConfirmTotal();
    }
}

function updateConfirmTotal() {
    if (!window.pendingCartItem) return;
    const total = window.pendingCartItem.book.price * window.pendingCartItem.quantity;
    document.getElementById('confirmTotal').textContent = `Total: $${total.toFixed(2)}`;
}

function confirmAddToCart() {
    if (!window.pendingCartItem || !window.currentUser) {
        closeModal('cartConfirm');
        return;
    }
    const book = window.pendingCartItem.book;
    const quantity = window.pendingCartItem.quantity;
    if (book.stock < quantity) {
        showToast('Stock changed - not enough available', 'error');
        closeModal('cartConfirm');
        return;
    }
    const cartItem = window.cart.find(i => i.isbn === book.isbn);
    if (cartItem) {
        const newQty = cartItem.quantity + quantity;
        if (newQty > book.stock) {
            showToast(`Cannot add ${quantity} more, only ${book.stock - cartItem.quantity} left`, 'error');
            closeModal('cartConfirm');
            return;
        }
        cartItem.quantity = newQty;
        showToast(`Updated quantity: ${book.title} (${newQty} total)`, 'success');
    } else {
        window.cart.push({ isbn: book.isbn, quantity: quantity, price: book.price, title: book.title, author: book.author });
        showToast(`${quantity} × ${book.title} added to cart!`, 'success');
    }
    saveCart();
    closeModal('cartConfirm');
    window.pendingCartItem = null;
    if (document.getElementById('cart-section').classList.contains('active')) {
        loadCart();
        updateSelectedSummary();
    }
}

function updateCartCount() {
    updateNavigation();
}

function loadCart() {
    const tbody = document.getElementById('cartBody');
    if (window.cart.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 3rem;">
                    <p style="font-size: 1.2rem; color: #64748b;">Your cart is empty</p>
                    <button class="btn btn-primary" onclick="showSection('shop')" style="margin-top: 1rem;">Start Shopping</button>
                </td>
            </tr>
        `;
        updateSelectedSummary();
        return;
    }
    tbody.innerHTML = window.cart.map(item => {
        const book = window.books.find(b => b.isbn === item.isbn);
        const itemTotal = item.price * item.quantity;
        const isSelected = window.selectedItems.has(item.isbn);
        return `
            <tr>
                <td><input type="checkbox" class="cart-checkbox" onchange="toggleItemSelection('${item.isbn}')" ${isSelected ? 'checked' : ''}></td>
                <td><strong>${book?.title || 'Unknown'}</strong><br><small style="color: #64748b;">by ${book?.author || 'Unknown'}</small></td>
                <td>$${item.price.toFixed(2)}</td>
                <td>
                    <div class="quantity-control">
                        <button class="quantity-btn" onclick="updateCartQuantity('${item.isbn}', -1)" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                        <span style="min-width: 30px; text-align: center;">${item.quantity}</span>
                        <button class="quantity-btn" onclick="updateCartQuantity('${item.isbn}', 1)" ${item.quantity >= (book?.stock || 0) ? 'disabled' : ''}>+</button>
                    </div>
                </td>
                <td><strong>$${itemTotal.toFixed(2)}</strong></td>
                <td><button class="btn btn-danger" onclick="removeFromCart('${item.isbn}')" style="padding: 0.3rem 0.8rem;">Remove</button></td>
            </tr>
        `;
    }).join('');
    updateSelectAllCheckbox();
}

function updateCartQuantity(isbn, change) {
    const item = window.cart.find(i => i.isbn === isbn);
    const book = window.books.find(b => b.isbn === isbn);
    if (item) {
        const newQuantity = item.quantity + change;
        if (newQuantity <= 0) {
            removeFromCart(isbn);
        } else if (newQuantity <= book.stock) {
            item.quantity = newQuantity;
            saveCart();
            loadCart();
            updateSelectedSummary();
        } else {
            showToast('Not enough stock available!', 'error');
        }
    }
}

function removeFromCart(isbn) {
    window.cart = window.cart.filter(i => i.isbn !== isbn);
    window.selectedItems.delete(isbn);
    saveCart();
    loadCart();
    updateSelectedSummary();
    updateNavigation();
    showToast('Item removed from cart', 'info');
}

function clearCart() {
    if (window.cart.length > 0 && confirm('Are you sure you want to clear your cart?')) {
        window.cart = [];
        window.selectedItems.clear();
        saveCart();
        loadCart();
        updateSelectedSummary();
        updateNavigation();
        showToast('Cart cleared', 'info');
    }
}

function toggleItemSelection(isbn) {
    if (window.selectedItems.has(isbn)) {
        window.selectedItems.delete(isbn);
    } else {
        window.selectedItems.add(isbn);
    }
    updateSelectedSummary();
    updateSelectAllCheckbox();
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll').checked;
    if (selectAll) {
        window.cart.forEach(item => window.selectedItems.add(item.isbn));
    } else {
        window.selectedItems.clear();
    }
    updateSelectedSummary();
    loadCart();
}

function updateSelectAllCheckbox() {
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked = window.cart.length > 0 && window.cart.every(item => window.selectedItems.has(item.isbn));
    }
}

function getSelectedCartItems() {
    return window.cart.filter(item => window.selectedItems.has(item.isbn));
}

function updateSelectedSummary() {
    const selectedItems_list = getSelectedCartItems();
    const selectedCount = selectedItems_list.length;
    const subtotal = selectedItems_list.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const total = subtotal + (selectedCount > 0 ? window.SHIPPING_FEE : 0);
    document.getElementById('selectedCount').textContent = `${selectedCount} selected`;
    document.getElementById('selectedItemsCount').textContent = `${selectedCount} items`;
    document.getElementById('summarySubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('summaryTotal').textContent = `$${total.toFixed(2)}`;
    document.getElementById('codAmount').textContent = `$${total.toFixed(2)}`;
    const bankAmount = document.getElementById('bankAmount');
    if (bankAmount) bankAmount.textContent = `$${total.toFixed(2)}`;
    const transferAmount = document.getElementById('transferAmount');
    if (transferAmount) transferAmount.value = total.toFixed(2);
}

// ==================== PAYMENT FUNCTIONS ====================
function togglePaymentDetails() {
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    const bankTransferSection = document.getElementById('bankTransferSection');
    const codDetails = document.getElementById('codDetails');
    if (paymentMethod === 'bank') {
        bankTransferSection.classList.add('active');
        codDetails.classList.remove('active');
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('transferDate').value = today;
    } else {
        bankTransferSection.classList.remove('active');
        codDetails.classList.add('active');
        if (window.currentUser) {
            document.getElementById('codRecipientName').value = window.currentUser.name || '';
        }
    }
}

function previewPaymentProof(input) {
    const preview = document.getElementById('proofPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.add('show');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetBankTransferForm() {
    const form = document.getElementById('bankTransferForm');
    if (form) {
        form.reset();
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('transferDate').value = today;
        const proofPreview = document.getElementById('proofPreview');
        if (proofPreview) {
            proofPreview.classList.remove('show');
            proofPreview.src = '#';
        }
    }
}

function validateBankTransferForm() {
    const senderBank = document.getElementById('senderBank').value;
    const senderAccountName = document.getElementById('senderAccountName').value;
    const senderAccountNumber = document.getElementById('senderAccountNumber').value;
    const referenceNumber = document.getElementById('referenceNumber').value;
    const transferDate = document.getElementById('transferDate').value;
    const transferAmount = parseFloat(document.getElementById('transferAmount').value);
    const total = parseFloat(document.getElementById('summaryTotal').textContent.replace('$', ''));
    if (!senderBank || !senderAccountName || !senderAccountNumber || !referenceNumber || !transferDate) {
        showToast('Please fill in all required bank transfer fields', 'error');
        return false;
    }
    if (Math.abs(transferAmount - total) > 0.01) {
        showToast(`Transfer amount must match the total amount $${total.toFixed(2)}`, 'error');
        return false;
    }
    return true;
}

function getBankTransferDetails() {
    return {
        senderBank: document.getElementById('senderBank').value,
        senderAccountName: document.getElementById('senderAccountName').value,
        senderAccountNumber: document.getElementById('senderAccountNumber').value,
        referenceNumber: document.getElementById('referenceNumber').value,
        transferDate: document.getElementById('transferDate').value,
        transferTime: document.getElementById('transferTime').value || 'Not specified',
        transferAmount: parseFloat(document.getElementById('transferAmount').value),
        additionalNotes: document.getElementById('additionalNotes').value || 'No additional notes'
    };
}

function validateCODAddress() {
    const recipientName = document.getElementById('codRecipientName').value;
    const address = document.getElementById('codAddress').value;
    const phone = document.getElementById('codPhone').value;
    if (!recipientName) {
        showToast('Please provide recipient name', 'error');
        return false;
    }
    if (!address) {
        showToast('Please provide your complete delivery address', 'error');
        return false;
    }
    if (!phone) {
        showToast('Please provide your contact number', 'error');
        return false;
    }
    const phoneDigits = phone.replace(/\D/g, '');
    if (phoneDigits.length < 10) {
        showToast('Please enter a valid contact number (at least 10 digits)', 'error');
        return false;
    }
    return true;
}

function proceedToCheckout() {
    if (!window.currentUser) {
        showToast('Please login to checkout', 'error');
        openLoginModal();
        return;
    }
    const selectedItems_list = getSelectedCartItems();
    if (selectedItems_list.length === 0) {
        showToast('Please select at least one item to checkout', 'error');
        return;
    }
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    if (paymentMethod === 'bank') {
        if (!validateBankTransferForm()) return;
    } else {
        if (!validateCODAddress()) return;
    }
    const subtotal = selectedItems_list.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const total = subtotal + window.SHIPPING_FEE;
    let confirmContent = '';
    if (paymentMethod === 'cod') {
        const recipientName = document.getElementById('codRecipientName').value;
        const address = document.getElementById('codAddress').value;
        const phone = document.getElementById('codPhone').value;
        const instructions = document.getElementById('codDeliveryInstructions').value || 'None';
        confirmContent = `
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💵</div>
                <h4 style="color: #1e293b; margin-bottom: 0.5rem;">Cash on Delivery</h4>
                <p style="color: #64748b;">You'll pay $${total.toFixed(2)} in cash upon delivery</p>
            </div>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                <p><strong>Items:</strong> ${selectedItems_list.length}</p>
                <p><strong>Subtotal:</strong> $${subtotal.toFixed(2)}</p>
                <p><strong>Shipping Fee:</strong> $${window.SHIPPING_FEE.toFixed(2)}</p>
                <p><strong>Total:</strong> $${total.toFixed(2)}</p>
            </div>
            <div style="background: #f0f9ff; padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                <h5 style="color: #1e293b; margin-bottom: 0.5rem;">Delivery Details:</h5>
                <p><strong>Recipient:</strong> ${recipientName}</p>
                <p><strong>Address:</strong> ${address}</p>
                <p><strong>Contact:</strong> ${phone}</p>
                <p><strong>Instructions:</strong> ${instructions}</p>
            </div>
            <p style="color: #64748b; font-size: 0.9rem;">Please prepare the exact amount for the delivery driver. Payment will be confirmed upon delivery.</p>
        `;
    } else {
        const bankDetails = getBankTransferDetails();
        confirmContent = `
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏦</div>
                <h4 style="color: #1e293b; margin-bottom: 0.5rem;">Bank Transfer</h4>
                <p style="color: #64748b;">You've submitted your transfer details</p>
            </div>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                <p><strong>Items:</strong> ${selectedItems_list.length}</p>
                <p><strong>Total Amount:</strong> $${total.toFixed(2)}</p>
                <p><strong>Your Bank:</strong> ${bankDetails.senderBank}</p>
                <p><strong>Account Name:</strong> ${bankDetails.senderAccountName}</p>
                <p><strong>Reference Number:</strong> ${bankDetails.referenceNumber}</p>
                <p><strong>Transfer Date:</strong> ${bankDetails.transferDate}</p>
            </div>
            <p style="color: #64748b; font-size: 0.9rem;">Your order will be processed once the payment is confirmed by our admin.</p>
        `;
    }
    document.getElementById('paymentConfirmContent').innerHTML = confirmContent;
    openModal('paymentConfirm');
}

function finalizeCheckout() {
    const selectedItems_list = getSelectedCartItems();
    for (const item of selectedItems_list) {
        const book = window.books.find(b => b.isbn === item.isbn);
        if (!book || book.stock < item.quantity) {
            showToast(`Insufficient stock for ${item.title}`, 'error');
            closeModal('paymentConfirm');
            return;
        }
    }
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    const subtotal = selectedItems_list.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const total = subtotal + window.SHIPPING_FEE;
    let paymentStatus, paymentDetails = null;
    let deliveryAddress = null, contactNumber = null, deliveryInstructions = null, recipientName = null;
    if (paymentMethod === 'cod') {
        paymentStatus = 'Pending';
        recipientName = document.getElementById('codRecipientName').value;
        deliveryAddress = document.getElementById('codAddress').value;
        contactNumber = document.getElementById('codPhone').value;
        deliveryInstructions = document.getElementById('codDeliveryInstructions').value;
    } else {
        paymentStatus = 'Awaiting Payment';
        paymentDetails = getBankTransferDetails();
    }
    const order = {
        id: 'ORD' + Date.now(),
        userId: window.currentUser.id,
        userName: window.currentUser.name,
        items: selectedItems_list.map(item => ({ ...item })),
        subtotal: subtotal,
        shippingFee: window.SHIPPING_FEE,
        total: total,
        paymentMethod: paymentMethod === 'cod' ? 'Cash on Delivery' : 'Bank Transfer',
        paymentStatus: paymentStatus,
        paymentDetails: paymentDetails,
        status: 'pending',
        approvalStatus: 'pending',
        date: new Date().toISOString(),
        recipientName: recipientName,
        deliveryAddress: deliveryAddress,
        contactNumber: contactNumber,
        deliveryInstructions: deliveryInstructions
    };
    window.orders.push(order);
    saveOrders();
    window.cart = window.cart.filter(item => !window.selectedItems.has(item.isbn));
    window.selectedItems.clear();
    saveCart();
    resetBankTransferForm();
    document.getElementById('codAddress').value = '';
    document.getElementById('codPhone').value = '';
    document.getElementById('codDeliveryInstructions').value = '';
    if (window.currentUser) {
        document.getElementById('codRecipientName').value = window.currentUser.name || '';
    }
    closeModal('paymentConfirm');
    showToast('Order placed successfully! Please wait for payment confirmation.', 'success');
    updateNavigation();
    loadCart();
    updateSelectedSummary();
}


function toggleWishlist(isbn) {
    if (!window.currentUser) {
        showToast('Please login to use wishlist', 'error');
        openLoginModal();
        return;
    }
    const wasInWishlist = window.wishlist.includes(isbn);
    if (wasInWishlist) {
        window.wishlist = window.wishlist.filter(i => i !== isbn);
        showToast('Removed from wishlist', 'info');
    } else {
        window.wishlist.push(isbn);
        showToast('Added to wishlist!', 'success');
    }
    saveWishlist();
    if (document.getElementById('shop-section').classList.contains('active')) {
        loadShop();
    } else if (document.getElementById('wishlist-section').classList.contains('active')) {
        loadWishlist();
    }
}

function toggleWishlistFromPreview() {
    if (window.currentPreviewBook) {
        toggleWishlist(window.currentPreviewBook.isbn);
    }
}

function loadWishlist() {
    const grid = document.getElementById('wishlistGrid');
    const wishlistBooks = window.books.filter(book => window.wishlist.includes(book.isbn));
    if (wishlistBooks.length === 0) {
        grid.innerHTML = `
            <div class="wishlist-empty">
                <i>❤️</i>
                <h3>Your wishlist is empty</h3>
                <p>Browse our collection and add books you love to your wishlist!</p>
                <button class="btn btn-primary" onclick="showSection('shop')">Browse Books</button>
            </div>
        `;
    } else {
        grid.innerHTML = wishlistBooks.map(book => createBookCard(book)).join('');
    }
}


function previewBook(isbn) {
    const book = window.books.find(b => b.isbn === isbn);
    if (book) {
        window.currentPreviewBook = book;
        document.getElementById('previewTitle').textContent = book.title;
        const stockStatus = book.stock > 0 ? 
            `<span style="color: #22c55e;">✓ In Stock (${book.stock} available)</span>` : 
            '<span style="color: #ef4444;">✗ Out of Stock</span>';
        const coverHtml = book.image ? 
            `<img src="${book.image}" alt="${book.title}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px;">` :
            `<div style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); width: 100%; height: 200px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem;">📚</div>`;
        document.getElementById('previewContent').innerHTML = `
            <div style="display: flex; gap: 2rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">${coverHtml}</div>
                <div style="flex: 2; min-width: 250px;">
                    <p><strong>Author:</strong> ${book.author}</p>
                    <p><strong>ISBN:</strong> ${book.isbn}</p>
                    <p><strong>Price:</strong> <span style="color: #3b82f6; font-size: 1.5rem; font-weight: bold;">$${book.price.toFixed(2)}</span></p>
                    <p><strong>Condition:</strong> <span style="text-transform: capitalize;">${book.condition}</span></p>
                    <p><strong>Stock Status:</strong> ${stockStatus}</p>
                </div>
            </div>
            <div style="margin-top: 1.5rem;">
                <h4 style="color: #1e293b; margin-bottom: 0.5rem;">Synopsis</h4>
                <p style="line-height: 1.6; color: #475569;">${book.synopsis}</p>
            </div>
        `;
        document.getElementById('previewAddToCartBtn').disabled = book.stock === 0;
        document.getElementById('previewAddToCartBtn').textContent = book.stock === 0 ? 'Out of Stock' : 'Add to Cart';
        const wishlistBtn = document.getElementById('previewAddToWishlistBtn');
        if (window.wishlist.includes(book.isbn)) {
            wishlistBtn.innerHTML = '❤️ In Wishlist';
            wishlistBtn.style.background = '#ef4444';
            wishlistBtn.style.color = 'white';
        } else {
            wishlistBtn.innerHTML = '🤍 Add to Wishlist';
            wishlistBtn.style.background = '#f1f5f9';
            wishlistBtn.style.color = '#475569';
        }
        openModal('preview');
    }
}

function addToCartFromPreview() {
    if (window.currentPreviewBook) {
        openCartConfirm(window.currentPreviewBook.isbn);
        closeModal('preview');
    }
}


function loadUserOrders() {
    const tbody = document.getElementById('userOrdersBody');
    const userOrders = window.orders.filter(o => o.userId === window.currentUser?.id).sort((a, b) => new Date(b.date) - new Date(a.date));
    if (userOrders.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align: center; padding: 3rem;">
                    <p style="font-size: 1.2rem; color: #64748b;">You haven't placed any orders yet</p>
                    <button class="btn btn-primary" onclick="showSection('shop')" style="margin-top: 1rem;">Start Shopping</button>
                </td>
            </tr>
        `;
        return;
    }
    tbody.innerHTML = userOrders.map(order => {
        const paymentStatusClass = order.paymentStatus === 'Paid' ? 'payment-paid' : 
                                  order.paymentStatus === 'Awaiting Payment' ? 'payment-awaiting' : 'payment-pending';
        const orderStatusClass = order.approvalStatus === 'pending' ? 'pending' :
                                order.approvalStatus === 'cancelled' ? 'cancelled' :
                                order.status;
        return `
            <tr>
                <td>#${order.id.slice(-6)}</td>
                <td>${new Date(order.date).toLocaleDateString()}</td>
                <td>${order.items.length} items</td>
                <td><strong>$${order.total.toFixed(2)}</strong></td>
                <td>${order.paymentMethod}</td>
                <td><span class="status-badge ${paymentStatusClass}">${order.paymentStatus}</span></td>
                <td><span class="status-badge status-${orderStatusClass}">${order.approvalStatus === 'pending' ? 'Pending Approval' : order.approvalStatus === 'cancelled' ? 'Cancelled' : order.status}</span></td>
                <td>${order.paymentDetails ? `<button class="action-btn view" onclick="viewPaymentDetails('${order.id}')">👁️ View Details</button>` : '<span style="color: #64748b;">—</span>'}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" onclick="viewOrderDetails('${order.id}')">👁️ View</button>
                        ${order.approvalStatus === 'pending' && order.paymentStatus !== 'Paid' ? `<button class="action-btn cancel" onclick="cancelOrder('${order.id}')">✗ Cancel</button>` : ''}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
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
                <h4 style="color: #1e293b; margin: 1.5rem 0 1rem;">Payment Details</h4>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 12px;">
                    <p><strong>Bank:</strong> ${order.paymentDetails.senderBank}</p>
                    <p><strong>Account Name:</strong> ${order.paymentDetails.senderAccountName}</p>
                    <p><strong>Account Number:</strong> ${order.paymentDetails.senderAccountNumber}</p>
                    <p><strong>Reference Number:</strong> ${order.paymentDetails.referenceNumber}</p>
                    <p><strong>Transfer Date:</strong> ${order.paymentDetails.transferDate}</p>
                    <p><strong>Transfer Time:</strong> ${order.paymentDetails.transferTime}</p>
                    <p><strong>Amount Transferred:</strong> $${order.paymentDetails.transferAmount.toFixed(2)}</p>
                    <p><strong>Additional Notes:</strong> ${order.paymentDetails.additionalNotes}</p>
                </div>
            `;
        }
        let deliveryHtml = '';
        if (order.deliveryAddress) {
            deliveryHtml = `
                <h4 style="color: #1e293b; margin: 1.5rem 0 1rem;">Delivery Details</h4>
                <div style="background: #f0f9ff; padding: 1rem; border-radius: 12px;">
                    <p><strong>Recipient:</strong> ${order.recipientName || order.userName}</p>
                    <p><strong>Address:</strong> ${order.deliveryAddress}</p>
                    <p><strong>Contact Number:</strong> ${order.contactNumber}</p>
                    ${order.deliveryInstructions ? `<p><strong>Instructions:</strong> ${order.deliveryInstructions}</p>` : ''}
                </div>
            `;
        }
        detailsDiv.innerHTML = `
            <div style="margin-bottom: 1.5rem;">
                <p><strong>Order ID:</strong> #${order.id.slice(-6)}</p>
                <p><strong>Date:</strong> ${new Date(order.date).toLocaleString()}</p>
                <p><strong>Order Status:</strong> <span class="status-badge status-${statusClass}">${displayStatus}</span></p>
                <p><strong>Payment Method:</strong> ${order.paymentMethod}</p>
                <p><strong>Payment Status:</strong> <span class="status-badge ${paymentStatusClass}">${order.paymentStatus}</span></p>
                <p><strong>Customer:</strong> ${order.userName}</p>
            </div>
            <h4 style="color: #1e293b; margin-bottom: 1rem;">Order Items</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 0.75rem; text-align: left;">Book</th>
                        <th style="padding: 0.75rem; text-align: center;">Quantity</th>
                        <th style="padding: 0.75rem; text-align: right;">Price</th>
                        <th style="padding: 0.75rem; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${order.items.map(item => `
                        <tr>
                            <td style="padding: 0.75rem;">${item.title}</td>
                            <td style="padding: 0.75rem; text-align: center;">${item.quantity}</td>
                            <td style="padding: 0.75rem; text-align: right;">$${item.price.toFixed(2)}</td>
                            <td style="padding: 0.75rem; text-align: right;">$${(item.quantity * item.price).toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Subtotal:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;">$${order.subtotal.toFixed(2)}</td>
                    </tr>
                    <tr style="background: #f8fafc;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Shipping Fee:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;">$${order.shippingFee.toFixed(2)}</td>
                    </tr>
                    <tr style="background: #f8fafc;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Total:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;"><strong>$${order.total.toFixed(2)}</strong></td>
                    </tr>
                </tfoot>
            </table>
            ${deliveryHtml}
            ${paymentDetailsHtml}
        `;
        openModal('order');
    }
}

function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        const order = window.orders.find(o => o.id === orderId);
        if (order && order.approvalStatus === 'pending') {
            order.approvalStatus = 'cancelled';
            order.status = 'cancelled';
            saveOrders();
            loadUserOrders();
            showToast('Order cancelled successfully', 'success');
        }
    }
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
            <div style="font-size: 3rem; margin-bottom: 1rem;">🏦</div>
            <h4 style="color: #1e293b;">Bank Transfer Details</h4>
        </div>
        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px;">
            <h5 style="color: #1e293b; margin-bottom: 1rem;">Sender Information:</h5>
            <p><strong>Bank:</strong> ${details.senderBank}</p>
            <p><strong>Account Name:</strong> ${details.senderAccountName}</p>
            <p><strong>Account Number:</strong> ${details.senderAccountNumber}</p>
            <h5 style="color: #1e293b; margin: 1rem 0;">Transfer Information:</h5>
            <p><strong>Reference Number:</strong> ${details.referenceNumber}</p>
            <p><strong>Transfer Date:</strong> ${details.transferDate}</p>
            <p><strong>Transfer Time:</strong> ${details.transferTime}</p>
            <p><strong>Amount Transferred:</strong> <span style="color: #22c55e; font-weight: bold;">$${details.transferAmount.toFixed(2)}</span></p>
            <h5 style="color: #1e293b; margin: 1rem 0;">Additional Notes:</h5>
            <p style="background: white; padding: 0.5rem; border-radius: 8px;">${details.additionalNotes}</p>
        </div>
    `;
    document.getElementById('paymentDetailsContent').innerHTML = content;
    openModal('paymentDetails');
}


function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    if (toast) toast.remove();
    const newToast = document.createElement('div');
    newToast.className = `toast toast-${type}`;
    newToast.id = 'toast';
    newToast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; font-size: 1.2rem; color: #64748b;">&times;</button>
    `;
    document.body.appendChild(newToast);
    setTimeout(() => {
        const toastElement = document.getElementById('toast');
        if (toastElement) toastElement.remove();
    }, 3000);
}