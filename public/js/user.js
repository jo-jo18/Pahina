let currentUser = null;
let currentPreviewBook = null;
let pendingCartItem = null;
let selectedItems = new Set();
let books = [];
let cart = [];
let wishlist = [];
const SHIPPING_FEE = 5.00;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Pahina User Interface Initialized');
    checkAuth();
    loadBooks();
    loadFeaturedBooks();
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const avatar = document.querySelector('.profile-avatar');
        if (dropdown && avatar && !avatar.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
});

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

function getAuthToken() {
    return localStorage.getItem('user_token');
}

async function apiRequest(url, method = 'GET', data = null, isFormData = false) {
    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
    };

    const token = getAuthToken();
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    if (!isFormData) {
        headers['Content-Type'] = 'application/json';
    }

    const options = {
        method: method,
        headers: headers,
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
        console.log(`API Request: ${method} ${url}`);
        const response = await fetch(url, options);
        const result = await response.json();
        
        if (!response.ok) {
            console.error('API Error Response:', result);
            throw new Error(result.message || result.error || 'API request failed');
        }
        
        return result;
    } catch (error) {
        console.error('Fetch Error:', error);
        throw error;
    }
}

async function checkAuth() {
    const token = getAuthToken();
    if (!token) {
        updateNavigation();
        return;
    }
    
    try {
        const response = await fetch('/api/user', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            currentUser = data.user || data;
            await loadUserCart();
            await loadUserWishlist();
            updateNavigation();
            showSection('home');
        } else {
            localStorage.removeItem('user_token');
            updateNavigation();
        }
    } catch (error) {
        console.error('Auth check failed:', error);
        localStorage.removeItem('user_token');
        updateNavigation();
    }
}

async function registerUser(event) {
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

    try {
        const response = await fetch('/api/user/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ 
                name, 
                email, 
                password, 
                password_confirmation: confirmPassword,
                birthday, 
                phone, 
                address 
            })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            localStorage.setItem('user_token', data.token);
            currentUser = data.user;
            closeModal('register');
            showToast(`Welcome to Pahina, ${name}!`, 'success');
            await loadUserCart();
            await loadUserWishlist();
            updateNavigation();
            showSection('home');
        } else {
            showToast(data.message || 'Registration failed!', 'error');
        }
    } catch (error) {
        console.error('Registration error:', error);
        showToast('Registration failed. Please try again.', 'error');
    }
}

async function loginUser(event) {
    event.preventDefault();
    
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    try {
        const response = await fetch('/api/user/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            localStorage.setItem('user_token', data.token);
            currentUser = data.user;
            await loadUserCart();
            await loadUserWishlist();
            closeModal('login');
            showToast(`Welcome back, ${data.user.name}!`, 'success');
            updateNavigation();
            showSection('home');
        } else {
            showToast(data.message || 'Invalid credentials!', 'error');
        }
    } catch (error) {
        console.error('Login error:', error);
        showToast('Login failed. Please try again.', 'error');
    }
}

async function logout() {
    const token = getAuthToken();
    
    if (token) {
        try {
            await fetch('/api/user/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            });
        } catch (error) {
            console.error('Logout error:', error);
        }
    }
    
    localStorage.removeItem('user_token');
    currentUser = null;
    cart = [];
    wishlist = [];
    selectedItems.clear();
    updateNavigation();
    showToast('Logged out successfully', 'info');
    showSection('home');
}

async function loadBooks() {
    try {
        console.log('Loading books from API...');
        const response = await fetch('/api/user/books', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });
        
        if (response.ok) {
            books = await response.json();
            console.log(`Loaded ${books.length} books`);
        } else {
            console.error('Failed to load books:', response.status);
            books = [];
        }
    } catch (error) {
        console.error('Error loading books:', error);
        books = [];
        showToast('Failed to load books', 'error');
    }
}

async function loadFeaturedBooks() {
    try {
        console.log('Loading featured books...');
        const response = await fetch('/api/user/books/featured', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });
        
        const featuredGrid = document.getElementById('featuredBooks');
        if (!featuredGrid) return;
        
        if (response.ok) {
            const featuredBooks = await response.json();
            console.log(`Loaded ${featuredBooks.length} featured books`);
            
            if (featuredBooks.length > 0) {
                featuredGrid.innerHTML = featuredBooks.map(book => createBookCard(book)).join('');
            } else if (books.length > 0) {
                featuredGrid.innerHTML = books.slice(0, 4).map(book => createBookCard(book)).join('');
            } else {
                featuredGrid.innerHTML = '<p style="text-align: center; color: #64748b; grid-column: 1/-1;">No books available</p>';
            }
        } else {
            if (books.length > 0) {
                featuredGrid.innerHTML = books.slice(0, 4).map(book => createBookCard(book)).join('');
            } else {
                featuredGrid.innerHTML = '<p style="text-align: center; color: #64748b; grid-column: 1/-1;">No books available</p>';
            }
        }
    } catch (error) {
        console.error('Error loading featured books:', error);
        const featuredGrid = document.getElementById('featuredBooks');
        if (featuredGrid) {
            featuredGrid.innerHTML = '<p style="text-align: center; color: #64748b; grid-column: 1/-1;">Error loading books</p>';
        }
    }
}

async function loadUserCart() {
    const token = getAuthToken();
    if (!token) {
        cart = [];
        return;
    }
    
    try {
        const response = await fetch('/api/user/cart', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            cart = data.map(item => ({
                id: item.id,
                book_id: item.book_id,
                isbn: item.book?.isbn || item.isbn,
                title: item.book?.title || item.title,
                author: item.book?.author || item.author,
                price: parseFloat(item.book?.price || item.price),
                quantity: item.quantity,
                selected: item.selected,
                stock: item.book?.stock || 0
            }));
            updateCartCount();
        } else {
            cart = [];
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        cart = [];
    }
}

async function loadUserWishlist() {
    const token = getAuthToken();
    if (!token) {
        wishlist = [];
        return;
    }
    
    try {
        const response = await fetch('/api/user/wishlist', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            wishlist = data.map(item => ({
                id: item.id,
                book_id: item.book_id || item.id,
                isbn: item.isbn,
                title: item.title,
                author: item.author,
                price: parseFloat(item.price),
                image: item.image
            }));
        } else {
            wishlist = [];
        }
    } catch (error) {
        console.error('Error loading wishlist:', error);
        wishlist = [];
    }
}

function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    const section = document.getElementById(`${sectionId}-section`);
    if (section) {
        section.classList.add('active');
    }
    
    if (sectionId === 'home') {
        loadFeaturedBooks();
    }
    if (sectionId === 'shop') {
        loadShop();
    }
    if (sectionId === 'wishlist') {
        loadWishlist();
    }
    if (sectionId === 'cart') {
        loadCart();
        updateSelectedSummary();
        if (currentUser) {
            const codRecipient = document.getElementById('codRecipientName');
            if (codRecipient) codRecipient.value = currentUser.name || '';
        }
    }
    if (sectionId === 'orders') {
        loadUserOrders();
    }
    if (sectionId === 'profile') {
        loadProfile();
    }
}

function updateNavigation() {
    const navButtons = document.getElementById('navButtons');
    if (!navButtons) return;

    const cartCount = cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
    
    if (currentUser) {
        let profileHtml = '';
        if (currentUser.profile_pic) {
            profileHtml = `<img src="/storage/${currentUser.profile_pic}" alt="${currentUser.name}">`;
        } else {
            profileHtml = `<span>${currentUser.name.charAt(0).toUpperCase()}</span>`;
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
                        <h4>${currentUser.name}</h4>
                        <p>${currentUser.email}</p>
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
    const modal = document.getElementById(`${modalName}Modal`);
    if (modal) modal.classList.add('active');
}

function closeModal(modalName) {
    const modal = document.getElementById(`${modalName}Modal`);
    if (modal) modal.classList.remove('active');
}

function openRegisterModal() {
    closeModal('login');
    
    const regName = document.getElementById('regName');
    if (regName) regName.value = '';
    
    const regBirthday = document.getElementById('regBirthday');
    if (regBirthday) regBirthday.value = '';
    
    const regEmail = document.getElementById('regEmail');
    if (regEmail) regEmail.value = '';
    
    const regPassword = document.getElementById('regPassword');
    if (regPassword) regPassword.value = '';
    
    const regConfirmPassword = document.getElementById('regConfirmPassword');
    if (regConfirmPassword) regConfirmPassword.value = '';
    
    const regPhone = document.getElementById('regPhone');
    if (regPhone) regPhone.value = '';
    
    const regAddress = document.getElementById('regAddress');
    if (regAddress) regAddress.value = '';
    
    const ageWarning = document.getElementById('ageWarning');
    if (ageWarning) ageWarning.style.display = 'none';
    
    const registerBtn = document.getElementById('registerBtn');
    if (registerBtn) registerBtn.disabled = false;
    
    openModal('register');
}

function openLoginModal() {
    closeModal('register');
    
    const loginEmail = document.getElementById('loginEmail');
    if (loginEmail) loginEmail.value = '';
    
    const loginPassword = document.getElementById('loginPassword');
    if (loginPassword) loginPassword.value = '';
    
    openModal('login');
}

function validateAge() {
    const birthday = document.getElementById('regBirthday').value;
    const ageWarning = document.getElementById('ageWarning');
    const registerBtn = document.getElementById('registerBtn');
    
    if (!birthday) {
        if (ageWarning) ageWarning.style.display = 'none';
        if (registerBtn) registerBtn.disabled = false;
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
        if (ageWarning) ageWarning.style.display = 'block';
        if (registerBtn) registerBtn.disabled = true;
        return false;
    } else {
        if (ageWarning) ageWarning.style.display = 'none';
        if (registerBtn) registerBtn.disabled = false;
        return true;
    }
}

function loadShop() {
    const grid = document.getElementById('bookGrid');
    if (!grid) return;
    
    if (books.length === 0) {
        grid.innerHTML = '<p style="text-align: center; color: #64748b; padding: 2rem;">No books available</p>';
        return;
    }
    
    grid.innerHTML = books.map(book => createBookCard(book)).join('');
}

function searchBooks() {
    const query = document.getElementById('searchInput')?.value.toLowerCase() || '';
    
    if (!query) {
        loadShop();
        return;
    }
    
    const filtered = books.filter(book => 
        book.title.toLowerCase().includes(query) ||
        book.author.toLowerCase().includes(query) ||
        book.isbn.includes(query)
    );
    
    const grid = document.getElementById('bookGrid');
    if (grid) {
        if (filtered.length === 0) {
            grid.innerHTML = '<p style="text-align: center; color: #64748b; padding: 2rem;">No books found matching your search</p>';
            showToast('No books found matching your search', 'info');
        } else {
            grid.innerHTML = filtered.map(book => createBookCard(book)).join('');
        }
    }
}

function createBookCard(book) {
    const stockClass = book.stock > 5 ? 'in-stock' : book.stock > 0 ? 'low-stock' : 'out-of-stock';
    const stockText = book.stock > 5 ? 'In Stock' : book.stock > 0 ? `Only ${book.stock} left` : 'Out of Stock';
    const inWishlist = wishlist.some(w => w.book_id === book.id || w.isbn === book.isbn);
    
    const imageUrl = book.image ? `/storage/${book.image}` : null;
    const coverStyle = imageUrl ? 
        `style="background-image: url('${imageUrl}'); background-size: cover; background-position: center;"` : 
        'class="book-cover default-cover"';
    
    return `
        <div class="book-card" onclick="previewBook('${book.isbn}')">
            <div class="book-cover" ${coverStyle}>
                ${!imageUrl ? '📚' : ''}
                <span class="book-condition">${book.condition}</span>
            </div>
            <div class="book-info">
                <h3 class="book-title">${book.title}</h3>
                <p class="book-author">by ${book.author}</p>
                <p class="book-price">$${parseFloat(book.price).toFixed(2)}</p>
                <span class="stock-badge ${stockClass}">${stockText}</span>
            </div>
            <div class="book-actions" onclick="event.stopPropagation()">
                <button class="btn btn-primary" onclick="openCartConfirm('${book.isbn}')" ${book.stock === 0 ? 'disabled' : ''}>
                    ${book.stock === 0 ? 'Out of Stock' : 'Add to Cart'}
                </button>
                <button class="btn btn-secondary" onclick="toggleWishlist('${book.isbn}')">
                    ${inWishlist ? '❤️' : '🤍'}
                </button>
            </div>
        </div>
    `;
}

function previewBook(isbn) {
    const book = books.find(b => b.isbn === isbn);
    if (!book) return;
    
    currentPreviewBook = book;
    
    const previewTitle = document.getElementById('previewTitle');
    if (previewTitle) previewTitle.textContent = book.title;
    
    const stockStatus = book.stock > 0 ? 
        `<span style="color: #22c55e;">✓ In Stock (${book.stock} available)</span>` : 
        '<span style="color: #ef4444;">✗ Out of Stock</span>';
    
    const imageUrl = book.image ? `/storage/${book.image}` : null;
    const coverHtml = imageUrl ? 
        `<img src="${imageUrl}" alt="${book.title}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px;">` :
        `<div style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); width: 100%; height: 200px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem;">📚</div>`;
    
    const previewContent = document.getElementById('previewContent');
    if (previewContent) {
        previewContent.innerHTML = `
            <div style="display: flex; gap: 2rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">${coverHtml}</div>
                <div style="flex: 2; min-width: 250px;">
                    <p><strong>Author:</strong> ${book.author}</p>
                    <p><strong>ISBN:</strong> ${book.isbn}</p>
                    <p><strong>Price:</strong> <span style="color: #3b82f6; font-size: 1.5rem; font-weight: bold;">$${parseFloat(book.price).toFixed(2)}</span></p>
                    <p><strong>Condition:</strong> <span style="text-transform: capitalize;">${book.condition}</span></p>
                    <p><strong>Stock Status:</strong> ${stockStatus}</p>
                </div>
            </div>
            <div style="margin-top: 1.5rem;">
                <h4 style="color: #1e293b; margin-bottom: 0.5rem;">Synopsis</h4>
                <p style="line-height: 1.6; color: #475569;">${book.synopsis}</p>
            </div>
        `;
    }
    
    const addToCartBtn = document.getElementById('previewAddToCartBtn');
    if (addToCartBtn) {
        addToCartBtn.disabled = book.stock === 0;
        addToCartBtn.textContent = book.stock === 0 ? 'Out of Stock' : 'Add to Cart';
    }
    
    const wishlistBtn = document.getElementById('previewAddToWishlistBtn');
    if (wishlistBtn) {
        const inWishlist = wishlist.some(w => w.book_id === book.id || w.isbn === book.isbn);
        if (inWishlist) {
            wishlistBtn.innerHTML = '❤️ In Wishlist';
            wishlistBtn.style.background = '#ef4444';
            wishlistBtn.style.color = 'white';
        } else {
            wishlistBtn.innerHTML = '🤍 Add to Wishlist';
            wishlistBtn.style.background = '#f1f5f9';
            wishlistBtn.style.color = '#475569';
        }
    }
    
    openModal('preview');
}

function addToCartFromPreview() {
    if (currentPreviewBook) {
        openCartConfirm(currentPreviewBook.isbn);
        closeModal('preview');
    }
}

function openCartConfirm(isbn) {
    if (!currentUser) {
        showToast('Please login to add items to cart', 'error');
        openLoginModal();
        return;
    }
    
    const book = books.find(b => b.isbn === isbn);
    if (!book || book.stock === 0) {
        showToast('Book is out of stock!', 'error');
        return;
    }
    
    pendingCartItem = { book, quantity: 1 };
    
    const imageUrl = book.image ? `/storage/${book.image}` : null;
    const coverHtml = imageUrl ? 
        `<img src="${imageUrl}" alt="${book.title}" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">` :
        `<div style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">📚</div>`;
    
    const confirmDetails = document.getElementById('confirmBookDetails');
    if (confirmDetails) {
        confirmDetails.innerHTML = `
            ${coverHtml}
            <div style="flex: 1;">
                <div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0.25rem;">${book.title}</div>
                <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.5rem;">by ${book.author}</div>
                <div style="color: #3b82f6; font-weight: 700; font-size: 1.2rem;">$${parseFloat(book.price).toFixed(2)} each</div>
                <div style="margin-top: 0.5rem; font-size: 0.9rem; color: #475569;">Condition: <span style="text-transform: capitalize;">${book.condition}</span></div>
                <div style="font-size: 0.9rem; color: #475569;">Available: ${book.stock} in stock</div>
                <div style="margin-top: 0.5rem; padding: 0.5rem; background: white; border-radius: 8px; font-size: 0.9rem; color: #1e293b; border: 1px solid #e2e8f0;">
                    <strong>Synopsis:</strong> ${book.synopsis.substring(0, 100)}${book.synopsis.length > 100 ? '...' : ''}
                </div>
            </div>
        `;
    }
    
    const confirmQty = document.getElementById('confirmQuantity');
    if (confirmQty) confirmQty.textContent = '1';
    
    updateConfirmTotal();
    
    const decBtn = document.getElementById('confirmQtyDec');
    const incBtn = document.getElementById('confirmQtyInc');
    if (decBtn) decBtn.disabled = true;
    if (incBtn) incBtn.disabled = book.stock <= 1;
    
    openModal('cartConfirm');
}

function updateConfirmQuantity(change) {
    if (!pendingCartItem) return;
    
    const newQty = pendingCartItem.quantity + change;
    const maxStock = pendingCartItem.book.stock;
    
    if (newQty >= 1 && newQty <= maxStock) {
        pendingCartItem.quantity = newQty;
        const confirmQty = document.getElementById('confirmQuantity');
        if (confirmQty) confirmQty.textContent = newQty;
        
        const decBtn = document.getElementById('confirmQtyDec');
        const incBtn = document.getElementById('confirmQtyInc');
        if (decBtn) decBtn.disabled = newQty <= 1;
        if (incBtn) incBtn.disabled = newQty >= maxStock;
        
        updateConfirmTotal();
    }
}

function updateConfirmTotal() {
    if (!pendingCartItem) return;
    const total = pendingCartItem.book.price * pendingCartItem.quantity;
    const confirmTotal = document.getElementById('confirmTotal');
    if (confirmTotal) confirmTotal.textContent = `Total: $${total.toFixed(2)}`;
}

async function confirmAddToCart() {
    if (!pendingCartItem || !currentUser) {
        closeModal('cartConfirm');
        return;
    }
    
    const book = pendingCartItem.book;
    const quantity = pendingCartItem.quantity;
    
    try {
        const response = await fetch('/api/user/cart/add', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ 
                book_id: book.id,
                quantity: quantity 
            })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showToast(data.message, 'success');
            await loadUserCart();
            closeModal('cartConfirm');
            pendingCartItem = null;
            
            if (document.getElementById('cart-section')?.classList.contains('active')) {
                loadCart();
                updateSelectedSummary();
            }
        } else {
            showToast(data.message || 'Error adding to cart', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showToast('Error adding to cart', 'error');
    }
}

function updateCartCount() {
    updateNavigation();
}

async function loadCart() {
    await loadUserCart();
    
    const tbody = document.getElementById('cartBody');
    if (!tbody) return;
    
    if (cart.length === 0) {
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
    
    tbody.innerHTML = cart.map(item => {
        const itemTotal = parseFloat(item.price) * item.quantity;
        const itemId = item.book_id.toString();
        const isSelected = selectedItems.has(itemId);
        
        return `
            <tr>
                <td><input type="checkbox" class="cart-checkbox" onchange="toggleItemSelection('${itemId}')" ${isSelected ? 'checked' : ''}></td>
                <td><strong>${item.title || 'Unknown'}</strong><br><small style="color: #64748b;">by ${item.author || 'Unknown'}</small></td>
                <td>$${item.price.toFixed(2)}</td>
                <td>
                    <div class="quantity-control">
                        <button class="quantity-btn" onclick="updateCartQuantity('${itemId}', ${item.quantity}, -1, ${item.id})" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                        <span style="min-width: 30px; text-align: center;">${item.quantity}</span>
                        <button class="quantity-btn" onclick="updateCartQuantity('${itemId}', ${item.quantity}, 1, ${item.id})" ${item.quantity >= item.stock ? 'disabled' : ''}>+</button>
                    </div>
                </td>
                <td><strong>$${itemTotal.toFixed(2)}</strong></td>
                <td><button class="btn btn-danger" onclick="removeFromCart('${itemId}', ${item.id})" style="padding: 0.3rem 0.8rem;">Remove</button></td>
            </tr>
        `;
    }).join('');
    
    updateSelectAllCheckbox();
}

async function updateCartQuantity(itemId, currentQty, change, cartId) {
    const item = cart.find(i => i.book_id.toString() === itemId);
    if (!item) return;
    
    const newQuantity = currentQty + change;
    
    if (newQuantity <= 0) {
        await removeFromCart(itemId, cartId);
        return;
    }
    
    if (newQuantity > item.stock) {
        showToast('Not enough stock available!', 'error');
        return;
    }
    
    try {
        const response = await fetch(`/api/user/cart/${cartId}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ quantity: newQuantity })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            item.quantity = newQuantity;
            await loadCart();
            updateSelectedSummary();
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Error updating cart', 'error');
        }
    } catch (error) {
        console.error('Error updating cart:', error);
        showToast('Error updating cart', 'error');
    }
}

async function removeFromCart(itemId, cartId) {
    try {
        const response = await fetch(`/api/user/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            cart = cart.filter(i => i.id !== cartId);
            selectedItems.delete(itemId);
            await loadCart();
            updateSelectedSummary();
            updateNavigation();
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Error removing item', 'error');
        }
    } catch (error) {
        console.error('Error removing item:', error);
        showToast('Error removing item', 'error');
    }
}

async function clearCart() {
    if (cart.length === 0) return;
    
    if (!confirm('Are you sure you want to clear your cart?')) return;
    
    try {
        const response = await fetch('/api/user/cart', {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            cart = [];
            selectedItems.clear();
            await loadCart();
            updateSelectedSummary();
            updateNavigation();
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Error clearing cart', 'error');
        }
    } catch (error) {
        console.error('Error clearing cart:', error);
        showToast('Error clearing cart', 'error');
    }
}

function toggleItemSelection(itemId) {
    const id = itemId.toString();
    if (selectedItems.has(id)) {
        selectedItems.delete(id);
    } else {
        selectedItems.add(id);
    }
    updateSelectedSummary();
    updateSelectAllCheckbox();
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    if (!selectAll) return;
    
    if (selectAll.checked) {
        cart.forEach(item => {
            const itemId = item.book_id.toString();
            selectedItems.add(itemId);
        });
    } else {
        selectedItems.clear();
    }
    
    updateSelectedSummary();
    loadCart();
}

function updateSelectAllCheckbox() {
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        const allSelected = cart.length > 0 && cart.every(item => {
            const itemId = item.book_id.toString();
            return selectedItems.has(itemId);
        });
        selectAll.checked = allSelected;
    }
}

function getSelectedCartItems() {
    return cart.filter(item => {
        const itemId = item.book_id.toString();
        return selectedItems.has(itemId);
    });
}

function updateSelectedSummary() {
    const selectedItems_list = getSelectedCartItems();
    const selectedCount = selectedItems_list.length;
    const subtotal = selectedItems_list.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const total = subtotal + (selectedCount > 0 ? SHIPPING_FEE : 0);
    
    const selectedCountEl = document.getElementById('selectedCount');
    if (selectedCountEl) selectedCountEl.textContent = `${selectedCount} selected`;
    
    const selectedItemsCount = document.getElementById('selectedItemsCount');
    if (selectedItemsCount) selectedItemsCount.textContent = `${selectedCount} items`;
    
    const summarySubtotal = document.getElementById('summarySubtotal');
    if (summarySubtotal) summarySubtotal.textContent = `$${subtotal.toFixed(2)}`;
    
    const summaryTotal = document.getElementById('summaryTotal');
    if (summaryTotal) summaryTotal.textContent = `$${total.toFixed(2)}`;
    
    const codAmount = document.getElementById('codAmount');
    if (codAmount) codAmount.textContent = `$${total.toFixed(2)}`;
    
    const bankAmount = document.getElementById('bankAmount');
    if (bankAmount) bankAmount.textContent = `$${total.toFixed(2)}`;
    
    const transferAmount = document.getElementById('transferAmount');
    if (transferAmount) transferAmount.value = total.toFixed(2);
}

function togglePaymentDetails() {
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value;
    if (!paymentMethod) return;
    
    const bankTransferSection = document.getElementById('bankTransferSection');
    const codDetails = document.getElementById('codDetails');
    
    if (paymentMethod === 'bank') {
        if (bankTransferSection) bankTransferSection.classList.add('active');
        if (codDetails) codDetails.classList.remove('active');
        const today = new Date().toISOString().split('T')[0];
        const transferDate = document.getElementById('transferDate');
        if (transferDate) transferDate.value = today;
    } else {
        if (bankTransferSection) bankTransferSection.classList.remove('active');
        if (codDetails) codDetails.classList.add('active');
        if (currentUser) {
            const codRecipient = document.getElementById('codRecipientName');
            if (codRecipient) codRecipient.value = currentUser.name || '';
        }
    }
}

function previewPaymentProof(input) {
    const preview = document.getElementById('proofPreview');
    if (preview && input.files && input.files[0]) {
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
    if (form) form.reset();
    
    const today = new Date().toISOString().split('T')[0];
    const transferDate = document.getElementById('transferDate');
    if (transferDate) transferDate.value = today;
    
    const proofPreview = document.getElementById('proofPreview');
    if (proofPreview) {
        proofPreview.classList.remove('show');
        proofPreview.src = '#';
    }
}

function validateBankTransferForm() {
    const senderBank = document.getElementById('senderBank')?.value;
    const senderAccountName = document.getElementById('senderAccountName')?.value;
    const senderAccountNumber = document.getElementById('senderAccountNumber')?.value;
    const referenceNumber = document.getElementById('referenceNumber')?.value;
    const transferDate = document.getElementById('transferDate')?.value;
    const transferAmount = parseFloat(document.getElementById('transferAmount')?.value || '0');
    const total = parseFloat(document.getElementById('summaryTotal')?.textContent.replace('$', '') || '0');
    
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
        sender_bank: document.getElementById('senderBank')?.value || '',
        sender_account_name: document.getElementById('senderAccountName')?.value || '',
        sender_account_number: document.getElementById('senderAccountNumber')?.value || '',
        reference_number: document.getElementById('referenceNumber')?.value || '',
        transfer_date: document.getElementById('transferDate')?.value || '',
        transfer_time: document.getElementById('transferTime')?.value || null,
        transfer_amount: parseFloat(document.getElementById('transferAmount')?.value || '0'),
        additional_notes: document.getElementById('additionalNotes')?.value || null
    };
}

function validateCODAddress() {
    const recipientName = document.getElementById('codRecipientName')?.value;
    const address = document.getElementById('codAddress')?.value;
    const phone = document.getElementById('codPhone')?.value;
    
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
    if (!currentUser) {
        showToast('Please login to checkout', 'error');
        openLoginModal();
        return;
    }
    
    const selectedItems_list = getSelectedCartItems();
    if (selectedItems_list.length === 0) {
        showToast('Please select at least one item to checkout', 'error');
        return;
    }
    
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value;
    if (!paymentMethod) {
        showToast('Please select a payment method', 'error');
        return;
    }
    
    if (paymentMethod === 'bank') {
        if (!validateBankTransferForm()) return;
    } else {
        if (!validateCODAddress()) return;
    }
    
    const subtotal = selectedItems_list.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const total = subtotal + SHIPPING_FEE;
    
    let confirmContent = '';
    
    if (paymentMethod === 'cod') {
        const recipientName = document.getElementById('codRecipientName')?.value || '';
        const address = document.getElementById('codAddress')?.value || '';
        const phone = document.getElementById('codPhone')?.value || '';
        const instructions = document.getElementById('codDeliveryInstructions')?.value || 'None';
        
        confirmContent = `
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💵</div>
                <h4 style="color: #1e293b; margin-bottom: 0.5rem;">Cash on Delivery</h4>
                <p style="color: #64748b;">You'll pay $${total.toFixed(2)} in cash upon delivery</p>
            </div>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                <p><strong>Items:</strong> ${selectedItems_list.length}</p>
                <p><strong>Subtotal:</strong> $${subtotal.toFixed(2)}</p>
                <p><strong>Shipping Fee:</strong> $${SHIPPING_FEE.toFixed(2)}</p>
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
                <p><strong>Your Bank:</strong> ${bankDetails.sender_bank}</p>
                <p><strong>Account Name:</strong> ${bankDetails.sender_account_name}</p>
                <p><strong>Reference Number:</strong> ${bankDetails.reference_number}</p>
                <p><strong>Transfer Date:</strong> ${bankDetails.transfer_date}</p>
            </div>
            <p style="color: #64748b; font-size: 0.9rem;">Your order will be processed once the payment is confirmed by our admin.</p>
        `;
    }
    
    const confirmContentDiv = document.getElementById('paymentConfirmContent');
    if (confirmContentDiv) confirmContentDiv.innerHTML = confirmContent;
    
    openModal('paymentConfirm');
}

async function finalizeCheckout() {
    const selectedItems_list = getSelectedCartItems();
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked')?.value;
    
    if (!paymentMethod) {
        closeModal('paymentConfirm');
        return;
    }
    
    const orderData = {
        payment_method: paymentMethod,
        items: selectedItems_list.map(item => ({
            isbn: item.isbn,
            quantity: item.quantity,
            price: item.price
        }))
    };

    if (paymentMethod === 'cod') {
        orderData.recipient_name = document.getElementById('codRecipientName')?.value || '';
        orderData.delivery_address = document.getElementById('codAddress')?.value || '';
        orderData.contact_number = document.getElementById('codPhone')?.value || '';
        orderData.delivery_instructions = document.getElementById('codDeliveryInstructions')?.value || null;
    } else {
        orderData.recipient_name = currentUser?.name || '';
        orderData.delivery_address = 'Bank Transfer - No physical delivery required';
        orderData.contact_number = currentUser?.phone || 'N/A';
        orderData.delivery_instructions = 'Digital order - No shipping needed';
        orderData.payment_details = getBankTransferDetails();
    }
    
    try {
        const response = await fetch('/api/user/orders', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify(orderData)
        });
        
        const data = await response.json();
        
        if (response.ok) {
            selectedItems.clear();
            resetBankTransferForm();
            
            const codAddress = document.getElementById('codAddress');
            if (codAddress) codAddress.value = '';
            
            const codPhone = document.getElementById('codPhone');
            if (codPhone) codPhone.value = '';
            
            const codInstructions = document.getElementById('codDeliveryInstructions');
            if (codInstructions) codInstructions.value = '';
            
            if (currentUser) {
                const codRecipient = document.getElementById('codRecipientName');
                if (codRecipient) codRecipient.value = currentUser.name || '';
            }
            
            closeModal('paymentConfirm');
            showToast(data.message, 'success');
            
            await loadUserCart();
            updateNavigation();
            showSection('orders');
        } else {
            showToast(data.message || 'Error placing order', 'error');
        }
    } catch (error) {
        console.error('Error placing order:', error);
        showToast('Error placing order', 'error');
    }
}

async function toggleWishlist(isbn) {
    if (!currentUser) {
        showToast('Please login to use wishlist', 'error');
        openLoginModal();
        return;
    }
    
    const book = books.find(b => b.isbn === isbn);
    if (!book) return;
    
    const inWishlist = wishlist.some(w => w.book_id === book.id || w.isbn === isbn);
    
    try {
        const response = await fetch('/api/user/wishlist/add', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ book_id: book.id })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            if (inWishlist) {
                wishlist = wishlist.filter(w => w.book_id !== book.id && w.isbn !== isbn);
                showToast('Removed from wishlist', 'success');
            } else {
                wishlist.push({
                    book_id: book.id,
                    isbn: book.isbn,
                    title: book.title,
                    author: book.author,
                    price: book.price,
                    image: book.image
                });
                showToast('Added to wishlist!', 'success');
            }
            if (document.getElementById('shop-section')?.classList.contains('active')) {
                loadShop();
            } else if (document.getElementById('wishlist-section')?.classList.contains('active')) {
                loadWishlist();
            } else if (document.getElementById('home-section')?.classList.contains('active')) {
                loadFeaturedBooks();
            }
        } else {
            showToast(data.message || 'Error updating wishlist', 'error');
        }
    } catch (error) {
        console.error('Error updating wishlist:', error);
        showToast('Error updating wishlist', 'error');
    }
}

function toggleWishlistFromPreview() {
    if (currentPreviewBook) {
        toggleWishlist(currentPreviewBook.isbn);
    }
}

async function loadWishlist() {
    await loadUserWishlist();
    
    const grid = document.getElementById('wishlistGrid');
    if (!grid) return;
    
    if (wishlist.length === 0) {
        grid.innerHTML = `
            <div class="wishlist-empty">
                <i>❤️</i>
                <h3>Your wishlist is empty</h3>
                <p>Browse our collection and add books you love to your wishlist!</p>
                <button class="btn btn-primary" onclick="showSection('shop')">Browse Books</button>
            </div>
        `;
    } else {
        grid.innerHTML = wishlist.map(book => {
            const fullBook = {
                ...book,
                stock: books.find(b => b.id === book.book_id || b.isbn === book.isbn)?.stock || 0,
                condition: books.find(b => b.id === book.book_id || b.isbn === book.isbn)?.condition || 'good'
            };
            return createBookCard(fullBook);
        }).join('');
    }
}

async function loadUserOrders() {
    if (!currentUser) return;
    
    try {
        const response = await fetch('/api/user/orders', {
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Accept': 'application/json'
            }
        });
        
        const orders = await response.json();
        
        const tbody = document.getElementById('userOrdersBody');
        if (!tbody) return;
        
        if (orders.length === 0) {
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
        
        tbody.innerHTML = orders.map(order => {
            const paymentStatusClass = order.payment_status === 'Paid' ? 'payment-paid' : 
                                      order.payment_status === 'Awaiting Payment' ? 'payment-awaiting' : 'payment-pending';
            
            const orderStatusClass = order.approval_status === 'pending' ? 'pending' :
                                    order.approval_status === 'cancelled' ? 'cancelled' :
                                    order.status;
            
            return `
                <tr>
                    <td>#${order.id}</td>
                    <td>${new Date(order.created_at).toLocaleDateString()}</td>
                    <td>${order.items?.length || 0} items</td>
                    <td><strong>$${parseFloat(order.total).toFixed(2)}</strong></td>
                    <td>${order.payment_method}</td>
                    <td><span class="status-badge ${paymentStatusClass}">${order.payment_status}</span></td>
                    <td><span class="status-badge status-${orderStatusClass}">${order.approval_status === 'pending' ? 'Pending Approval' : order.approval_status === 'cancelled' ? 'Cancelled' : order.status}</span></td>
                    <td>${order.payment_details ? `<button class="action-btn view" onclick="viewPaymentDetails(${order.id})">👁️ View Details</button>` : '<span style="color: #64748b;">—</span>'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" onclick="viewOrderDetails(${order.id})">👁️ View</button>
                            ${order.approval_status === 'pending' && order.payment_status !== 'Paid' ? `<button class="action-btn cancel" onclick="cancelOrder(${order.id})">✗ Cancel</button>` : ''}
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    } catch (error) {
        console.error('Error loading orders:', error);
    }
}

async function viewOrderDetails(orderId) {
    try {
        const response = await fetch(`/api/user/orders/${orderId}`, {
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Accept': 'application/json'
            }
        });
        
        const order = await response.json();
        
        const detailsDiv = document.getElementById('orderDetails');
        if (!detailsDiv) return;
        
        const paymentStatusClass = order.payment_status === 'Paid' ? 'payment-paid' : 
                                  order.payment_status === 'Awaiting Payment' ? 'payment-awaiting' : 'payment-pending';
        
        const displayStatus = order.approval_status === 'pending' ? 'Pending Approval' :
                             order.approval_status === 'cancelled' ? 'Cancelled' :
                             order.status;
        
        const statusClass = order.approval_status === 'pending' ? 'pending' :
                           order.approval_status === 'cancelled' ? 'cancelled' :
                           order.status;
        
        let paymentDetailsHtml = '';
        if (order.payment_details) {
            paymentDetailsHtml = `
                <h4 style="color: #1e293b; margin: 1.5rem 0 1rem;">Payment Details</h4>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 12px;">
                    <p><strong>Bank:</strong> ${order.payment_details.sender_bank}</p>
                    <p><strong>Account Name:</strong> ${order.payment_details.sender_account_name}</p>
                    <p><strong>Account Number:</strong> ${order.payment_details.sender_account_number}</p>
                    <p><strong>Reference Number:</strong> ${order.payment_details.reference_number}</p>
                    <p><strong>Transfer Date:</strong> ${order.payment_details.transfer_date}</p>
                    <p><strong>Transfer Time:</strong> ${order.payment_details.transfer_time || 'N/A'}</p>
                    <p><strong>Amount Transferred:</strong> $${parseFloat(order.payment_details.transfer_amount).toFixed(2)}</p>
                    <p><strong>Additional Notes:</strong> ${order.payment_details.additional_notes || 'None'}</p>
                </div>
            `;
        }
        
        let deliveryHtml = '';
        if (order.delivery_address) {
            deliveryHtml = `
                <h4 style="color: #1e293b; margin: 1.5rem 0 1rem;">Delivery Details</h4>
                <div style="background: #f0f9ff; padding: 1rem; border-radius: 12px;">
                    <p><strong>Recipient:</strong> ${order.recipient_name || order.user?.name}</p>
                    <p><strong>Address:</strong> ${order.delivery_address}</p>
                    <p><strong>Contact Number:</strong> ${order.contact_number}</p>
                    ${order.delivery_instructions ? `<p><strong>Instructions:</strong> ${order.delivery_instructions}</p>` : ''}
                </div>
            `;
        }
        
        detailsDiv.innerHTML = `
            <div style="margin-bottom: 1.5rem;">
                <p><strong>Order ID:</strong> #${order.id}</p>
                <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                <p><strong>Order Status:</strong> <span class="status-badge status-${statusClass}">${displayStatus}</span></p>
                <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                <p><strong>Payment Status:</strong> <span class="status-badge ${paymentStatusClass}">${order.payment_status}</span></p>
                <p><strong>Customer:</strong> ${order.user?.name}</p>
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
                    ${order.items?.map(item => `
                        <tr>
                            <td style="padding: 0.75rem;">${item.title}</td>
                            <td style="padding: 0.75rem; text-align: center;">${item.quantity}</td>
                            <td style="padding: 0.75rem; text-align: right;">$${parseFloat(item.price).toFixed(2)}</td>
                            <td style="padding: 0.75rem; text-align: right;">$${(item.quantity * item.price).toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Subtotal:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;">$${parseFloat(order.subtotal).toFixed(2)}</td>
                    </tr>
                    <tr style="background: #f8fafc;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Shipping Fee:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;">$${parseFloat(order.shipping_fee).toFixed(2)}</td>
                    </tr>
                    <tr style="background: #f8fafc;">
                        <td colspan="3" style="padding: 0.75rem; text-align: right;"><strong>Total:</strong></td>
                        <td style="padding: 0.75rem; text-align: right;"><strong>$${parseFloat(order.total).toFixed(2)}</strong></td>
                    </tr>
                </tfoot>
            </table>
            ${deliveryHtml}
            ${paymentDetailsHtml}
        `;
        
        openModal('order');
    } catch (error) {
        console.error('Error loading order details:', error);
        showToast('Error loading order details', 'error');
    }
}

async function cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order?')) return;
    
    try {
        const response = await fetch(`/api/user/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            showToast(data.message, 'success');
            loadUserOrders();
        } else {
            showToast(data.message || 'Error cancelling order', 'error');
        }
    } catch (error) {
        console.error('Error cancelling order:', error);
        showToast('Error cancelling order', 'error');
    }
}

async function viewPaymentDetails(orderId) {
    try {
        const response = await fetch(`/api/user/orders/${orderId}`, {
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Accept': 'application/json'
            }
        });
        
        const order = await response.json();
        
        if (!order.payment_details) {
            showToast('No payment details available', 'info');
            return;
        }
        
        const details = order.payment_details;
        const content = `
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏦</div>
                <h4 style="color: #1e293b;">Bank Transfer Details</h4>
            </div>
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px;">
                <h5 style="color: #1e293b; margin-bottom: 1rem;">Sender Information:</h5>
                <p><strong>Bank:</strong> ${details.sender_bank}</p>
                <p><strong>Account Name:</strong> ${details.sender_account_name}</p>
                <p><strong>Account Number:</strong> ${details.sender_account_number}</p>
                <h5 style="color: #1e293b; margin: 1rem 0;">Transfer Information:</h5>
                <p><strong>Reference Number:</strong> ${details.reference_number}</p>
                <p><strong>Transfer Date:</strong> ${details.transfer_date}</p>
                <p><strong>Transfer Time:</strong> ${details.transfer_time || 'N/A'}</p>
                <p><strong>Amount Transferred:</strong> <span style="color: #22c55e; font-weight: bold;">$${parseFloat(details.transfer_amount).toFixed(2)}</span></p>
                <h5 style="color: #1e293b; margin: 1rem 0;">Additional Notes:</h5>
                <p style="background: white; padding: 0.5rem; border-radius: 8px;">${details.additional_notes || 'None'}</p>
            </div>
        `;
        
        const contentDiv = document.getElementById('paymentDetailsContent');
        if (contentDiv) contentDiv.innerHTML = content;
        
        openModal('paymentDetails');
    } catch (error) {
        console.error('Error loading payment details:', error);
        showToast('Error loading payment details', 'error');
    }
}

async function loadProfile() {
    if (!currentUser) {
        showToast('Please login to view profile', 'error');
        showSection('home');
        return;
    }
    
    try {
        const response = await fetch('/api/user/profile', {
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Accept': 'application/json'
            }
        });
        
        const profile = await response.json();
        
        const profileName = document.getElementById('profileName');
        if (profileName) profileName.value = profile.name || '';
        
        const profileEmail = document.getElementById('profileEmail');
        if (profileEmail) profileEmail.value = profile.email || '';
        
        const profileBirthday = document.getElementById('profileBirthday');
        if (profileBirthday) profileBirthday.value = profile.birthday || '';
        
        const profilePhone = document.getElementById('profilePhone');
        if (profilePhone) profilePhone.value = profile.phone || '';
        
        const profileAddress = document.getElementById('profileAddress');
        if (profileAddress) profileAddress.value = profile.address || '';
        
        updateProfilePictureDisplay();
        
        const ordersResponse = await fetch('/api/user/orders', {
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Accept': 'application/json'
            }
        });
        
        const orders = await ordersResponse.json();
        const totalSpent = orders.reduce((sum, o) => sum + parseFloat(o.total), 0);
        
        const memberSince = document.getElementById('memberSince');
        if (memberSince) memberSince.textContent = profile.created_at ? new Date(profile.created_at).toLocaleDateString() : 'N/A';
        
        const userId = document.getElementById('userId');
        if (userId) userId.textContent = '#' + (profile.id || 'N/A');
        
        const totalOrders = document.getElementById('totalOrders');
        if (totalOrders) totalOrders.textContent = orders.length;
        
        const totalSpentEl = document.getElementById('totalSpent');
        if (totalSpentEl) totalSpentEl.textContent = '$' + totalSpent.toFixed(2);
        
        const wishlistCount = document.getElementById('wishlistCount');
        if (wishlistCount) wishlistCount.textContent = wishlist.length;
        
        loadRecentActivity(orders);
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}

function updateProfilePictureDisplay() {
    const largeImg = document.getElementById('profileLargeImg');
    const largeInitial = document.getElementById('profileLargeInitial');
    
    if (!largeImg || !largeInitial) return;
    
    if (currentUser && currentUser.profile_pic) {
        largeImg.src = `/storage/${currentUser.profile_pic}`;
        largeImg.style.display = 'block';
        largeInitial.style.display = 'none';
        updateNavigation();
    } else {
        largeImg.style.display = 'none';
        largeInitial.style.display = 'block';
        if (currentUser) {
            largeInitial.textContent = currentUser.name.charAt(0).toUpperCase();
        }
    }
}

async function updateProfilePicture(input) {
    if (!input.files || !input.files[0]) return;
    
    const formData = new FormData();
    formData.append('avatar', input.files[0]);
    
    try {
        const response = await fetch('/api/user/profile/avatar', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok) {
            currentUser.profile_pic = data.path;
            updateProfilePictureDisplay();
            updateNavigation();
            showToast('Profile picture updated!', 'success');
        } else {
            showToast(data.message || 'Error updating profile picture', 'error');
        }
    } catch (error) {
        console.error('Error updating profile picture:', error);
        showToast('Error updating profile picture', 'error');
    }
}

function validateProfileAge() {
    const birthday = document.getElementById('profileBirthday')?.value;
    const warning = document.getElementById('profileAgeWarning');
    
    if (!birthday) {
        if (warning) warning.style.display = 'none';
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
        if (warning) warning.style.display = 'block';
        return false;
    } else {
        if (warning) warning.style.display = 'none';
        return true;
    }
}

async function updateProfileInfo(event) {
    event.preventDefault();
    
    if (!validateProfileAge()) {
        showToast('You must be at least 15 years old', 'error');
        return;
    }
    
    const profileData = {
        name: document.getElementById('profileName')?.value || '',
        email: document.getElementById('profileEmail')?.value || '',
        birthday: document.getElementById('profileBirthday')?.value || null,
        phone: document.getElementById('profilePhone')?.value || null,
        address: document.getElementById('profileAddress')?.value || null
    };
    
    try {
        const response = await fetch('/api/user/profile', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify(profileData)
        });
        
        const data = await response.json();
        
        if (response.ok) {
            currentUser = { ...currentUser, ...profileData };
            updateNavigation();
            showToast('Profile updated successfully!', 'success');
        } else {
            showToast(data.message || 'Error updating profile', 'error');
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        showToast('Error updating profile', 'error');
    }
}

function resetProfileForm() {
    if (currentUser) {
        const profileName = document.getElementById('profileName');
        if (profileName) profileName.value = currentUser.name || '';
        
        const profileEmail = document.getElementById('profileEmail');
        if (profileEmail) profileEmail.value = currentUser.email || '';
        
        const profileBirthday = document.getElementById('profileBirthday');
        if (profileBirthday) profileBirthday.value = currentUser.birthday || '';
        
        const profilePhone = document.getElementById('profilePhone');
        if (profilePhone) profilePhone.value = currentUser.phone || '';
        
        const profileAddress = document.getElementById('profileAddress');
        if (profileAddress) profileAddress.value = currentUser.address || '';
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('newPassword')?.value || '';
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*]/.test(password)
    };
    
    const reqLength = document.getElementById('reqLength');
    if (reqLength) reqLength.innerHTML = (requirements.length ? '🟢' : '🔴') + ' At least 8 characters';
    
    const reqUppercase = document.getElementById('reqUppercase');
    if (reqUppercase) reqUppercase.innerHTML = (requirements.uppercase ? '🟢' : '🔴') + ' At least one uppercase letter';
    
    const reqLowercase = document.getElementById('reqLowercase');
    if (reqLowercase) reqLowercase.innerHTML = (requirements.lowercase ? '🟢' : '🔴') + ' At least one lowercase letter';
    
    const reqNumber = document.getElementById('reqNumber');
    if (reqNumber) reqNumber.innerHTML = (requirements.number ? '🟢' : '🔴') + ' At least one number';
    
    const reqSpecial = document.getElementById('reqSpecial');
    if (reqSpecial) reqSpecial.innerHTML = (requirements.special ? '🟢' : '🔴') + ' At least one special character (!@#$%^&*)';

    const metCount = Object.values(requirements).filter(Boolean).length;
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');

    if (strengthBar) {
        if (metCount <= 2) {
            strengthBar.className = 'password-strength-bar strength-weak';
        } else if (metCount <= 4) {
            strengthBar.className = 'password-strength-bar strength-medium';
        } else {
            strengthBar.className = 'password-strength-bar strength-strong';
        }
    }

    if (strengthText) {
        if (metCount <= 2) {
            strengthText.textContent = 'Weak';
            strengthText.style.color = '#ef4444';
        } else if (metCount <= 4) {
            strengthText.textContent = 'Medium';
            strengthText.style.color = '#f59e0b';
        } else {
            strengthText.textContent = 'Strong';
            strengthText.style.color = '#22c55e';
        }
    }
}

async function changePassword(event) {
    event.preventDefault();
    
    const current = document.getElementById('currentPassword')?.value;
    const newPass = document.getElementById('newPassword')?.value;
    const confirm = document.getElementById('confirmNewPassword')?.value;

    if (!current || !newPass || !confirm) {
        showToast('Please fill in all password fields', 'error');
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
    
    try {
        const response = await fetch('/api/user/profile/change-password', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${getAuthToken()}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({ 
                current_password: current, 
                new_password: newPass,
                new_password_confirmation: confirm
            })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            const currentPass = document.getElementById('currentPassword');
            if (currentPass) currentPass.value = '';
            
            const newPassInput = document.getElementById('newPassword');
            if (newPassInput) newPassInput.value = '';
            
            const confirmPass = document.getElementById('confirmNewPassword');
            if (confirmPass) confirmPass.value = '';
            
            showToast('Password changed successfully!', 'success');
        } else {
            showToast(data.message || 'Error changing password', 'error');
        }
    } catch (error) {
        console.error('Error changing password:', error);
        showToast('Error changing password', 'error');
    }
}

function resetPasswordForm() {
    const currentPass = document.getElementById('currentPassword');
    if (currentPass) currentPass.value = '';
    
    const newPass = document.getElementById('newPassword');
    if (newPass) newPass.value = '';
    
    const confirmPass = document.getElementById('confirmNewPassword');
    if (confirmPass) confirmPass.value = '';
    
    const reqLength = document.getElementById('reqLength');
    if (reqLength) reqLength.innerHTML = '🔴 At least 8 characters';
    
    const reqUppercase = document.getElementById('reqUppercase');
    if (reqUppercase) reqUppercase.innerHTML = '🔴 At least one uppercase letter';
    
    const reqLowercase = document.getElementById('reqLowercase');
    if (reqLowercase) reqLowercase.innerHTML = '🔴 At least one lowercase letter';
    
    const reqNumber = document.getElementById('reqNumber');
    if (reqNumber) reqNumber.innerHTML = '🔴 At least one number';
    
    const reqSpecial = document.getElementById('reqSpecial');
    if (reqSpecial) reqSpecial.innerHTML = '🔴 At least one special character (!@#$%^&*)';
    
    const strengthBar = document.getElementById('passwordStrengthBar');
    if (strengthBar) strengthBar.className = 'password-strength-bar';
    
    const strengthText = document.getElementById('passwordStrengthText');
    if (strengthText) strengthText.textContent = 'Weak';
}

function loadRecentActivity(orders) {
    const activityBody = document.getElementById('recentActivity');
    if (!activityBody) return;
    
    if (orders.length === 0) {
        activityBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: #64748b; padding: 2rem;">No recent activity</td></tr>';
        return;
    }
    
    activityBody.innerHTML = orders.slice(0, 5).map(order => `
        <tr>
            <td>${new Date(order.created_at).toLocaleDateString()}</td>
            <td>Order Placed</td>
            <td>#${order.id} - $${parseFloat(order.total).toFixed(2)} (${order.items?.length || 0} items)</td>
         </tr>
    `).join('');
}

function showProfileTab(tab) {
    document.querySelectorAll('.profile-tab').forEach(btn => btn.classList.remove('active'));
    if (event.target) event.target.classList.add('active');
    
    const infoTab = document.getElementById('profileInfoTab');
    const securityTab = document.getElementById('profileSecurityTab');
    const statsTab = document.getElementById('profileStatsTab');
    
    if (infoTab) infoTab.classList.remove('active');
    if (securityTab) securityTab.classList.remove('active');
    if (statsTab) statsTab.classList.remove('active');
    
    if (tab === 'info' && infoTab) {
        infoTab.classList.add('active');
    } else if (tab === 'security' && securityTab) {
        securityTab.classList.add('active');
    } else if (tab === 'stats' && statsTab) {
        statsTab.classList.add('active');
    }
}

function showToast(message, type = 'info') {
    const existingToast = document.getElementById('toast');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.id = 'toast';
    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; font-size: 1.2rem; color: #64748b;">&times;</button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        const toastElement = document.getElementById('toast');
        if (toastElement) toastElement.remove();
    }, 3000);
}

window.showSection = showSection;
window.openLoginModal = openLoginModal;
window.openRegisterModal = openRegisterModal;
window.loginUser = loginUser;
window.registerUser = registerUser;
window.logout = logout;
window.toggleDropdown = toggleDropdown;
window.closeDropdown = closeDropdown;
window.searchBooks = searchBooks;
window.previewBook = previewBook;
window.openCartConfirm = openCartConfirm;
window.updateConfirmQuantity = updateConfirmQuantity;
window.confirmAddToCart = confirmAddToCart;
window.toggleWishlist = toggleWishlist;
window.toggleWishlistFromPreview = toggleWishlistFromPreview;
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.clearCart = clearCart;
window.toggleItemSelection = toggleItemSelection;
window.toggleSelectAll = toggleSelectAll;
window.togglePaymentDetails = togglePaymentDetails;
window.previewPaymentProof = previewPaymentProof;
window.proceedToCheckout = proceedToCheckout;
window.finalizeCheckout = finalizeCheckout;
window.viewOrderDetails = viewOrderDetails;
window.cancelOrder = cancelOrder;
window.viewPaymentDetails = viewPaymentDetails;
window.updateProfileInfo = updateProfileInfo;
window.changePassword = changePassword;
window.resetProfileForm = resetProfileForm;
window.resetPasswordForm = resetPasswordForm;
window.validateAge = validateAge;
window.validateProfileAge = validateProfileAge;
window.checkPasswordStrength = checkPasswordStrength;
window.updateProfilePicture = updateProfilePicture;
window.showProfileTab = showProfileTab;
window.closeModal = closeModal;
window.addToCartFromPreview = addToCartFromPreview;