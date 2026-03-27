<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pahina - Bookstore</title>
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <h1>Pahina</h1>
                <p>Your next chapter starts here.</p>
            </div>
            <div class="nav-buttons" id="navButtons">
                <!-- Dynamic navigation buttons will be loaded here -->
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Home Section -->
        <section id="home-section" class="section active">
            <div class="welcome-section">
                <h2>Welcome to Pahina</h2>
                <p>Discover your next favorite book from our curated collection of pre-loved and brand-new titles.</p>
                <button class="btn btn-primary" onclick="showSection('shop')" style="padding: 1rem 3rem; font-size: 1.1rem;">Start Browsing</button>
            </div>
            <h3 style="margin-bottom: 1rem; color: #1e293b;">Featured Books</h3>
            <div class="book-grid" id="featuredBooks">
                <!-- Featured books will be loaded here -->
            </div>
        </section>

        <!-- Shop Section -->
        <section id="shop-section" class="section">
            <div class="search-section">
                <input type="text" class="search-input" placeholder="Search by title, author, or ISBN..." id="searchInput">
                <button class="search-btn" onclick="searchBooks()">Search</button>
            </div>
            <div class="book-grid" id="bookGrid">
                <!-- Books will be loaded here -->
            </div>
        </section>

        <!-- Wishlist Section -->
        <section id="wishlist-section" class="section">
            <h2 style="margin-bottom: 2rem; color: #1e293b;">❤️ My Wishlist</h2>
            <div class="book-grid" id="wishlistGrid">
                <!-- Wishlist items will be loaded here -->
            </div>
        </section>

        <!-- Cart Section with Payment Methods -->
        <section id="cart-section" class="section">
            <h2 style="margin-bottom: 2rem; color: #1e293b;">🛒 Shopping Cart</h2>
            
            <!-- Select All Checkbox -->
            <div class="cart-select-all">
                <input type="checkbox" id="selectAll" class="cart-checkbox" onchange="toggleSelectAll()">
                <label for="selectAll">Select All Items</label>
                <span class="selected-count" id="selectedCount">0 selected</span>
            </div>

            <div class="table-container">
                <table id="cartTable">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Book</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                        <!-- Cart items will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Payment Method Section -->
            <div class="payment-section">
                <div class="payment-title">
                    <span>💳</span> Select Payment Method
                </div>
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" name="paymentMethod" id="paymentCOD" value="cod" checked onchange="togglePaymentDetails()">
                        <label for="paymentCOD">
                            <span class="payment-icon">💵</span>
                            <span class="payment-name">Cash on Delivery</span>
                            <span class="payment-desc">Pay when you receive your books</span>
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="paymentMethod" id="paymentBank" value="bank" onchange="togglePaymentDetails()">
                        <label for="paymentBank">
                            <span class="payment-icon">🏦</span>
                            <span class="payment-name">Bank Transfer</span>
                            <span class="payment-desc">Pay via bank transfer</span>
                        </label>
                    </div>
                </div>

                <!-- Bank Transfer Payment Form (Hidden by default) -->
                <div class="bank-transfer-form" id="bankTransferSection">
                    <div class="form-title">
                        <span>📝</span> Complete Your Bank Transfer
                    </div>
                    
                    <div class="amount-display" id="amountDisplay">
                        <div class="amount-label">Exact Amount to Pay:</div>
                        <div class="amount-value" id="bankAmount">$0.00</div>
                        <div class="amount-updated" id="amountUpdated">
                            <span>✓</span> Updates in real-time
                        </div>
                    </div>

                    <form id="bankTransferForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="senderBank">Your Bank Name *</label>
                                <input type="text" id="senderBank" placeholder="e.g., BDO, BPI, Metrobank" required>
                            </div>
                            <div class="form-group">
                                <label for="senderAccountName">Account Name *</label>
                                <input type="text" id="senderAccountName" placeholder="Your full name as in bank" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="senderAccountNumber">Account Number *</label>
                                <input type="text" id="senderAccountNumber" placeholder="Your bank account number" required>
                            </div>
                            <div class="form-group">
                                <label for="referenceNumber">Reference Number *</label>
                                <input type="text" id="referenceNumber" placeholder="Transfer reference/transaction ID" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="transferDate">Transfer Date *</label>
                                <input type="date" id="transferDate" required>
                            </div>
                            <div class="form-group">
                                <label for="transferTime">Transfer Time</label>
                                <input type="time" id="transferTime">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="transferAmount">Transfer Amount *</label>
                            <input type="number" id="transferAmount" step="0.01" placeholder="Enter exact amount transferred" readonly style="background: #f0f9ff; color: #3b82f6; font-weight: bold; border-color: #3b82f6;">
                            <small style="color: #64748b; font-size: 0.8rem;">Amount is automatically set to the total above</small>
                        </div>

                        <div class="form-group">
                            <label for="paymentProof">Upload Payment Proof (Optional)</label>
                            <input type="file" id="paymentProof" accept="image/*,.pdf" onchange="previewPaymentProof(this)">
                            <div class="file-upload-note">Upload screenshot or photo of transfer confirmation (JPG, PNG, PDF)</div>
                            <img id="proofPreview" class="image-preview" src="#" alt="Payment proof preview" style="max-width: 100%; margin-top: 0.5rem;">
                        </div>

                        <div class="form-group">
                            <label for="additionalNotes">Additional Notes</label>
                            <textarea id="additionalNotes" rows="2" placeholder="Any additional information about your transfer..."></textarea>
                        </div>
                    </form>
                </div>

                <!-- COD Details Section (with Address and Name) -->
                <div class="bank-details active" id="codDetails">
                    <div class="cod-note">
                        <strong>💵 Cash on Delivery</strong>
                        <p style="margin-top: 0.5rem; color: #475569;">Pay exactly <span id="codAmount">$0.00</span> in cash when your books are delivered. Please prepare the exact amount.</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="codRecipientName">Recipient Full Name *</label>
                        <input type="text" id="codRecipientName" placeholder="Enter recipient's full name" required>
                        <small style="color: #64748b;">This will be printed on the delivery label</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="codAddress">Complete Delivery Address *</label>
                        <textarea id="codAddress" rows="3" placeholder="Street address, barangay, city, province, zip code" required></textarea>
                        <small style="color: #64748b;">Please provide your complete address for delivery</small>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="codPhone">Contact Number *</label>
                            <input type="tel" id="codPhone" placeholder="e.g., 09123456789" required>
                        </div>
                        <div class="form-group">
                            <label for="codDeliveryInstructions">Delivery Instructions (Optional)</label>
                            <input type="text" id="codDeliveryInstructions" placeholder="e.g., Landmark, gate code, etc.">
                        </div>
                    </div>
                    
                    <p style="margin-top: 1rem; font-size: 0.9rem; color: #64748b;">
                        <span>📍 </span>Delivery within 3-5 business days
                    </p>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h4 style="color: #1e293b; margin-bottom: 1rem;">Order Summary</h4>
                    <div class="summary-row">
                        <span>Selected Items:</span>
                        <span id="selectedItemsCount">0 items</span>
                    </div>
                    <div class="summary-row">
                        <span>Subtotal (selected items):</span>
                        <span id="summarySubtotal">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping Fee:</span>
                        <span id="summaryShipping">$5.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Amount to Pay:</span>
                        <span id="summaryTotal">$0.00</span>
                    </div>
                </div>
            </div>

            <div style="text-align: right; margin-top: 2rem;">
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button class="btn btn-secondary" onclick="clearCart()">Clear Cart</button>
                    <button class="btn btn-primary" onclick="proceedToCheckout()" style="padding: 1rem 3rem;" id="checkoutBtn">Proceed to Checkout</button>
                </div>
            </div>
        </section>

        <!-- Payment Confirmation Modal -->
        <div id="paymentConfirmModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Confirm Order</h3>
                    <button class="close-btn" onclick="closeModal('paymentConfirm')">&times;</button>
                </div>
                <div id="paymentConfirmContent" style="line-height: 1.6;">
                    <!-- Will be filled dynamically -->
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button class="btn btn-secondary" onclick="closeModal('paymentConfirm')" style="flex: 1;">Cancel</button>
                    <button class="btn btn-success" onclick="finalizeCheckout()" style="flex: 1;" id="confirmPaymentBtn">Place Order</button>
                </div>
            </div>
        </div>

        <!-- My Orders Section -->
        <section id="orders-section" class="section">
            <h2 style="margin-bottom: 2rem; color: #1e293b;">📦 My Orders</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Payment Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userOrdersBody">
                        <!-- User orders will be loaded here -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Profile Section -->
        <section id="profile-section" class="section">
            <div class="profile-section">
                <div class="profile-card">
                    <div class="profile-header">
                        <h2>My Profile</h2>
                        <p>Manage your personal information and account settings</p>
                    </div>

                    <!-- Profile Picture -->
                    <div class="profile-avatar-large" id="profileAvatarLarge" onclick="document.getElementById('profilePicUpload').click()">
                        <img id="profileLargeImg" src="#" alt="Profile" style="display: none;">
                        <span id="profileLargeInitial" style="display: block;">👤</span>
                    </div>
                    <div class="profile-avatar-upload">
                        <input type="file" id="profilePicUpload" accept="image/*" style="display: none;" onchange="updateProfilePicture(this)">
                        <button class="upload-btn" onclick="document.getElementById('profilePicUpload').click()">
                            <span>📷</span> Change Profile Picture
                        </button>
                    </div>

                    <!-- Profile Tabs -->
                    <div class="profile-tabs">
                        <button class="profile-tab active" onclick="showProfileTab('info')">📋 Personal Info</button>
                        <button class="profile-tab" onclick="showProfileTab('security')">🔒 Security</button>
                        <button class="profile-tab" onclick="showProfileTab('stats')">📊 Statistics</button>
                    </div>

                    <!-- Personal Info Tab -->
                    <div id="profileInfoTab" class="profile-tab-content active">
                        <form id="profileInfoForm" onsubmit="updateProfileInfo(event)">
                            <div class="form-group">
                                <label for="profileName">Full Name</label>
                                <input type="text" id="profileName" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="profileEmail">Email Address</label>
                                <input type="email" id="profileEmail" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="profileBirthday">Birthday</label>
                                <input type="date" id="profileBirthday" value="" onchange="validateProfileAge()" required>
                                <small id="profileAgeWarning" style="color: #ef4444; display: none;">You must be at least 15 years old</small>
                            </div>
                            <div class="form-group">
                                <label for="profilePhone">Phone Number</label>
                                <input type="tel" id="profilePhone" placeholder="Enter your phone number">
                            </div>
                            <div class="form-group">
                                <label for="profileAddress">Default Delivery Address</label>
                                <textarea id="profileAddress" rows="3" placeholder="Enter your default delivery address"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Changes</button>
                                <button type="button" class="btn btn-secondary" onclick="resetProfileForm()">Reset</button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Tab -->
                    <div id="profileSecurityTab" class="profile-tab-content">
                        <form id="profileSecurityForm" onsubmit="changePassword(event)">
                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" id="currentPassword" required>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" id="newPassword" onkeyup="checkPasswordStrength()" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmNewPassword">Confirm New Password</label>
                                <input type="password" id="confirmNewPassword" required>
                            </div>

                            <!-- Password Requirements -->
                            <div class="password-requirements">
                                <h4><span>🔒</span> Password Requirements</h4>
                                <div class="requirement-item" id="reqLength">
                                    <span>🔴</span> At least 8 characters
                                </div>
                                <div class="requirement-item" id="reqUppercase">
                                    <span>🔴</span> At least one uppercase letter
                                </div>
                                <div class="requirement-item" id="reqLowercase">
                                    <span>🔴</span> At least one lowercase letter
                                </div>
                                <div class="requirement-item" id="reqNumber">
                                    <span>🔴</span> At least one number
                                </div>
                                <div class="requirement-item" id="reqSpecial">
                                    <span>🔴</span> At least one special character (!@#$%^&*)
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                </div>
                                <div style="font-size: 0.85rem; color: #64748b; text-align: right;" id="passwordStrengthText">Weak</div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" style="flex: 1;">Update Password</button>
                                <button type="button" class="btn btn-secondary" onclick="resetPasswordForm()">Clear</button>
                            </div>
                        </form>
                    </div>

                    <!-- Statistics Tab -->
                    <div id="profileStatsTab" class="profile-tab-content">
                        <div class="profile-info-grid">
                            <div class="profile-info-item">
                                <div class="profile-info-label">📅 Member Since</div>
                                <div class="profile-info-value" id="memberSince">Loading...</div>
                            </div>
                            <div class="profile-info-item">
                                <div class="profile-info-label">🆔 User ID</div>
                                <div class="profile-info-value small" id="userId">Loading...</div>
                            </div>
                        </div>

                        <div class="profile-stats">
                            <div class="profile-stat-card">
                                <div class="profile-stat-value" id="totalOrders">0</div>
                                <div class="profile-stat-label">Total Orders</div>
                            </div>
                            <div class="profile-stat-card">
                                <div class="profile-stat-value" id="totalSpent">$0</div>
                                <div class="profile-stat-label">Total Spent</div>
                            </div>
                            <div class="profile-stat-card">
                                <div class="profile-stat-value" id="wishlistCount">0</div>
                                <div class="profile-stat-label">Wishlist Items</div>
                            </div>
                        </div>

                        <div style="margin-top: 2rem;">
                            <h4 style="color: #1e293b; margin-bottom: 1rem;">Recent Activity</h4>
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Activity</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentActivity">
                                        <tr>
                                            <td colspan="3" style="text-align: center; color: #64748b;">Loading activity...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modals -->
    <!-- Registration Modal -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create Account</h3>
                <button class="close-btn" onclick="closeModal('register')">&times;</button>
            </div>
            <form onsubmit="registerUser(event)">
                <div class="form-group">
                    <label for="regName">Full Name</label>
                    <input type="text" id="regName" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="regBirthday">Birthday</label>
                    <input type="date" id="regBirthday" onchange="validateAge()" required>
                    <small id="ageWarning" style="color: #ef4444; display: none;">You must be at least 15 years old to register</small>
                </div>
                
                <div class="form-group">
                    <label for="regEmail">Email Address</label>
                    <input type="email" id="regEmail" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <input type="password" id="regPassword" placeholder="Create a password" required>
                </div>
                <div class="form-group">
                    <label for="regConfirmPassword">Confirm Password</label>
                    <input type="password" id="regConfirmPassword" placeholder="Confirm your password" required>
                </div>
                <div class="form-group">
                    <label for="regPhone">Phone Number</label>
                    <input type="tel" id="regPhone" placeholder="Enter your phone number (optional)">
                </div>
                <div class="form-group">
                    <label for="regAddress">Default Delivery Address</label>
                    <textarea id="regAddress" rows="2" placeholder="Enter your default address (optional)"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;" id="registerBtn">Create Account</button>
                <p style="text-align: center; margin-top: 1rem;">
                    Already have an account? <a href="#" onclick="openLoginModal(); return false;" style="color: #3b82f6; text-decoration: none;">Login here</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Welcome Back</h3>
                <button class="close-btn" onclick="closeModal('login')">&times;</button>
            </div>
            <form onsubmit="loginUser(event)">
                <div class="form-group">
                    <label for="loginEmail">Email Address</label>
                    <input type="email" id="loginEmail" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Login</button>
                <p style="text-align: center; margin-top: 1rem;">
                    Don't have an account? <a href="#" onclick="openRegisterModal(); return false;" style="color: #3b82f6; text-decoration: none;">Register here</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Book Preview Modal -->
    <div id="previewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="previewTitle">Book Details</h3>
                <button class="close-btn" onclick="closeModal('preview')">&times;</button>
            </div>
            <div id="previewContent" style="line-height: 1.6;">
                <!-- Book details will be loaded here -->
            </div>
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button class="btn btn-primary" id="previewAddToCartBtn" style="flex: 1;" onclick="addToCartFromPreview()">Add to Cart</button>
                <button class="btn btn-secondary" id="previewAddToWishlistBtn" style="flex: 1;" onclick="toggleWishlistFromPreview()">❤️ Wishlist</button>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Order Details</h3>
                <button class="close-btn" onclick="closeModal('order')">&times;</button>
            </div>
            <div id="orderDetails">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Payment Details Modal -->
    <div id="paymentDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Payment Details</h3>
                <button class="close-btn" onclick="closeModal('paymentDetails')">&times;</button>
            </div>
            <div id="paymentDetailsContent" style="line-height: 1.6;">
                <!-- Payment details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Cart Confirmation Modal -->
    <div id="cartConfirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add to Cart</h3>
                <button class="close-btn" onclick="closeModal('cartConfirm')">&times;</button>
            </div>
            <div id="confirmBookDetails" style="display: flex; gap: 1rem; margin: 1.5rem 0; padding: 1rem; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                <!-- Will be filled dynamically -->
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin: 1.5rem 0; padding: 1rem; background: #f8fafc; border-radius: 16px;">
                <span style="font-weight: 500;">Quantity:</span>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="quantity-btn" onclick="updateConfirmQuantity(-1)" id="confirmQtyDec">−</button>
                    <span class="quantity-value" id="confirmQuantity" style="font-weight: 600; min-width: 30px; text-align: center;">1</span>
                    <button class="quantity-btn" onclick="updateConfirmQuantity(1)" id="confirmQtyInc">+</button>
                </div>
            </div>
            <div style="font-size: 1.2rem; font-weight: 700; color: #1e293b; text-align: right; margin: 1rem 0;" id="confirmTotal">Total: $0.00</div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button class="btn btn-secondary" onclick="closeModal('cartConfirm')" style="flex: 1; padding: 1rem;">Cancel</button>
                <button class="btn btn-success" onclick="confirmAddToCart()" style="flex: 1; padding: 1rem;">✓ Confirm Add to Cart</button>
            </div>
        </div>
    </div>

    <script>
        // ==================== SHARED DATA (Connected via localStorage) ====================
        let books = [];
        let users = [];
        let orders = [];
        let currentUser = null;
        let cart = [];
        let wishlist = [];
        let currentPreviewBook = null;
        let pendingCartItem = null;
        let selectedItems = new Set();
        const SHIPPING_FEE = 5.00;

        // ==================== DATA LOAD/SAVE FUNCTIONS ====================
        function loadData() {
            // Load from localStorage - this is the CONNECTION between admin and user
            books = JSON.parse(localStorage.getItem('pahina_books')) || [];
            users = JSON.parse(localStorage.getItem('pahina_users')) || [];
            orders = JSON.parse(localStorage.getItem('pahina_orders')) || [];
            currentUser = JSON.parse(localStorage.getItem('pahina_currentUser')) || null;
            cart = JSON.parse(localStorage.getItem('pahina_cart')) || [];
            wishlist = JSON.parse(localStorage.getItem('pahina_wishlist')) || [];

            // Add sample books if empty
            if (books.length === 0) {
                books = [
                    { 
                        id: '1', 
                        isbn: '9780141439518', 
                        title: 'Pride and Prejudice', 
                        author: 'Jane Austen', 
                        price: 12.99, 
                        stock: 15, 
                        synopsis: 'A classic novel of love and social standing in 19th century England.', 
                        condition: 'good',
                        image: null
                    },
                    { 
                        id: '2', 
                        isbn: '9780061120084', 
                        title: 'To Kill a Mockingbird', 
                        author: 'Harper Lee', 
                        price: 14.99, 
                        stock: 8, 
                        synopsis: 'A gripping, heart-wrenching tale of racial injustice in the Deep South.', 
                        condition: 'like-new',
                        image: null
                    },
                    { 
                        id: '3', 
                        isbn: '9780451524935', 
                        title: '1984', 
                        author: 'George Orwell', 
                        price: 11.99, 
                        stock: 3, 
                        synopsis: 'A dystopian social science fiction novel and cautionary tale.', 
                        condition: 'acceptable',
                        image: null
                    },
                    { 
                        id: '4', 
                        isbn: '9780743273565', 
                        title: 'The Great Gatsby', 
                        author: 'F. Scott Fitzgerald', 
                        price: 13.99, 
                        stock: 10, 
                        synopsis: 'A critique of the American Dream set in the Jazz Age.', 
                        condition: 'new',
                        image: null
                    }
                ];
                saveBooks();
            }
        }

        function saveBooks() {
            localStorage.setItem('pahina_books', JSON.stringify(books));
        }

        function saveUsers() {
            localStorage.setItem('pahina_users', JSON.stringify(users));
        }

        function saveOrders() {
            localStorage.setItem('pahina_orders', JSON.stringify(orders));
        }

        function saveCart() {
            localStorage.setItem('pahina_cart', JSON.stringify(cart));
            updateCartCount();
        }

        function saveWishlist() {
            localStorage.setItem('pahina_wishlist', JSON.stringify(wishlist));
        }

        function saveCurrentUser() {
            localStorage.setItem('pahina_currentUser', JSON.stringify(currentUser));
        }

        // ==================== UI FUNCTIONS ====================
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById(`${sectionId}-section`).classList.add('active');
            
            if (sectionId === 'home') loadFeaturedBooks();
            if (sectionId === 'shop') loadShop();
            if (sectionId === 'wishlist') loadWishlist();
            if (sectionId === 'cart') {
                loadCart();
                updateSelectedSummary();
                if (currentUser) {
                    document.getElementById('codRecipientName').value = currentUser.name || '';
                }
            }
            if (sectionId === 'orders') loadUserOrders();
            if (sectionId === 'profile') loadProfile();
        }

        function updateNavigation() {
            const navButtons = document.getElementById('navButtons');
            const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            if (currentUser) {
                let profileHtml = '';
                if (currentUser.profilePic) {
                    profileHtml = `<img src="${currentUser.profilePic}" alt="${currentUser.name}">`;
                } else {
                    profileHtml = `<span>${currentUser.name.charAt(0).toUpperCase()}</span>`;
                }
                
                // Regular user navigation
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

        // Dropdown functions
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) dropdown.classList.toggle('show');
        }

        function closeDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) dropdown.classList.remove('show');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const avatar = document.querySelector('.profile-avatar');
            if (dropdown && avatar && !avatar.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Modal Functions
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

        // Age Validation
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

        // ==================== USER AUTHENTICATION ====================
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

            if (users.find(u => u.email === email)) {
                showToast('Email already registered!', 'error');
                return;
            }

            const newUser = {
                id: 'user_' + Date.now(),
                name,
                email,
                password,
                birthday,
                phone,
                address,
                profilePic: null,
                isAdmin: false,
                joinDate: new Date().toISOString()
            };

            users.push(newUser);
            saveUsers();
            
            currentUser = newUser;
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

            const user = users.find(u => u.email === email && u.password === password);

            if (user) {
                if (user.isAdmin) {
                    showToast('Please use the admin login page', 'error');
                    return;
                }
                currentUser = user;
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
            currentUser = null;
            saveCurrentUser();
            cart = [];
            wishlist = [];
            selectedItems.clear();
            saveCart();
            saveWishlist();
            updateNavigation();
            showToast('Logged out successfully', 'info');
            showSection('home');
        }

        // ==================== PROFILE FUNCTIONS ====================
        function loadProfile() {
            if (!currentUser) {
                showToast('Please login to view profile', 'error');
                showSection('home');
                return;
            }

            document.getElementById('profileName').value = currentUser.name || '';
            document.getElementById('profileEmail').value = currentUser.email || '';
            document.getElementById('profileBirthday').value = currentUser.birthday || '';
            document.getElementById('profilePhone').value = currentUser.phone || '';
            document.getElementById('profileAddress').value = currentUser.address || '';

            updateProfilePictureDisplay();

            const userOrders = orders.filter(o => o.userId === currentUser.id);
            const totalSpent = userOrders.reduce((sum, o) => sum + o.total, 0);
            
            document.getElementById('memberSince').textContent = currentUser.joinDate ? new Date(currentUser.joinDate).toLocaleDateString() : 'N/A';
            document.getElementById('userId').textContent = '#' + (currentUser.id ? currentUser.id.slice(-6) : 'N/A');
            document.getElementById('totalOrders').textContent = userOrders.length;
            document.getElementById('totalSpent').textContent = '$' + totalSpent.toFixed(2);
            document.getElementById('wishlistCount').textContent = wishlist.length;

            loadRecentActivity();
        }

        function updateProfilePictureDisplay() {
            const largeImg = document.getElementById('profileLargeImg');
            const largeInitial = document.getElementById('profileLargeInitial');

            if (currentUser && currentUser.profilePic) {
                largeImg.src = currentUser.profilePic;
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

        function updateProfilePicture(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (currentUser) {
                        currentUser.profilePic = e.target.result;
                        
                        const userIndex = users.findIndex(u => u.id === currentUser.id);
                        if (userIndex !== -1) {
                            users[userIndex].profilePic = e.target.result;
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

            if (currentUser) {
                currentUser.name = document.getElementById('profileName').value;
                currentUser.email = document.getElementById('profileEmail').value;
                currentUser.birthday = document.getElementById('profileBirthday').value;
                currentUser.phone = document.getElementById('profilePhone').value;
                currentUser.address = document.getElementById('profileAddress').value;

                const userIndex = users.findIndex(u => u.id === currentUser.id);
                if (userIndex !== -1) {
                    users[userIndex] = currentUser;
                }

                saveUsers();
                saveCurrentUser();
                updateNavigation();
                
                showToast('Profile updated successfully!', 'success');
            }
        }

        function resetProfileForm() {
            if (currentUser) {
                document.getElementById('profileName').value = currentUser.name || '';
                document.getElementById('profileEmail').value = currentUser.email || '';
                document.getElementById('profileBirthday').value = currentUser.birthday || '';
                document.getElementById('profilePhone').value = currentUser.phone || '';
                document.getElementById('profileAddress').value = currentUser.address || '';
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

            if (!currentUser || currentUser.password !== current) {
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

            currentUser.password = newPass;
            const userIndex = users.findIndex(u => u.id === currentUser.id);
            if (userIndex !== -1) {
                users[userIndex].password = newPass;
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
            
            const strengthBar = document.getElementById('passwordStrengthBar');
            strengthBar.className = 'password-strength-bar';
            strengthBar.style.width = '0%';
            document.getElementById('passwordStrengthText').textContent = 'Weak';
        }

        function loadRecentActivity() {
            const activityBody = document.getElementById('recentActivity');
            const userOrders = orders.filter(o => o.userId === currentUser?.id)
                .sort((a, b) => new Date(b.date) - new Date(a.date))
                .slice(0, 5);

            if (userOrders.length === 0) {
                activityBody.innerHTML = `
                    <tr>
                        <td colspan="3" style="text-align: center; color: #64748b; padding: 2rem;">
                            No recent activity
                        </td>
                    </tr>
                `;
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

        // ==================== BOOK FUNCTIONS ====================
        function loadFeaturedBooks() {
            const featured = books.slice(0, 4);
            document.getElementById('featuredBooks').innerHTML = featured.map(book => createBookCard(book)).join('');
        }

        function loadShop() {
            const grid = document.getElementById('bookGrid');
            grid.innerHTML = books.map(book => createBookCard(book)).join('');
        }

        function searchBooks() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const filtered = books.filter(book => 
                book.title.toLowerCase().includes(query) ||
                book.author.toLowerCase().includes(query) ||
                book.isbn.includes(query)
            );
            document.getElementById('bookGrid').innerHTML = filtered.map(book => createBookCard(book)).join('');
            
            if (filtered.length === 0) {
                showToast('No books found matching your search', 'info');
            }
        }

        function createBookCard(book) {
            const stockClass = book.stock > 5 ? 'in-stock' : book.stock > 0 ? 'low-stock' : 'out-of-stock';
            const stockText = book.stock > 5 ? 'In Stock' : book.stock > 0 ? `Only ${book.stock} left` : 'Out of Stock';
            const inWishlist = wishlist.includes(book.isbn);
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

        // ==================== CART FUNCTIONS ====================
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

            pendingCartItem = {
                book: book,
                quantity: 1
            };

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
            if (!pendingCartItem) return;
            
            const newQty = pendingCartItem.quantity + change;
            const maxStock = pendingCartItem.book.stock;
            
            if (newQty >= 1 && newQty <= maxStock) {
                pendingCartItem.quantity = newQty;
                document.getElementById('confirmQuantity').textContent = newQty;
                
                document.getElementById('confirmQtyDec').disabled = newQty <= 1;
                document.getElementById('confirmQtyInc').disabled = newQty >= maxStock;
                
                updateConfirmTotal();
            }
        }

        function updateConfirmTotal() {
            if (!pendingCartItem) return;
            const total = pendingCartItem.book.price * pendingCartItem.quantity;
            document.getElementById('confirmTotal').textContent = `Total: $${total.toFixed(2)}`;
        }

        function confirmAddToCart() {
            if (!pendingCartItem || !currentUser) {
                closeModal('cartConfirm');
                return;
            }

            const book = pendingCartItem.book;
            const quantity = pendingCartItem.quantity;

            if (book.stock < quantity) {
                showToast('Stock changed - not enough available', 'error');
                closeModal('cartConfirm');
                return;
            }

            const cartItem = cart.find(i => i.isbn === book.isbn);
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
                cart.push({ 
                    isbn: book.isbn, 
                    quantity: quantity, 
                    price: book.price, 
                    title: book.title,
                    author: book.author
                });
                showToast(`${quantity} × ${book.title} added to cart!`, 'success');
            }

            saveCart();
            closeModal('cartConfirm');
            pendingCartItem = null;
            
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
                const book = books.find(b => b.isbn === item.isbn);
                const itemTotal = item.price * item.quantity;
                const isSelected = selectedItems.has(item.isbn);
                
                return `
                    <tr>
                        <td>
                            <input type="checkbox" class="cart-checkbox" onchange="toggleItemSelection('${item.isbn}')" ${isSelected ? 'checked' : ''}>
                        </td>
                        <td>
                            <strong>${book?.title || 'Unknown'}</strong><br>
                            <small style="color: #64748b;">by ${book?.author || 'Unknown'}</small>
                        </td>
                        <td>$${item.price.toFixed(2)}</td>
                        <td>
                            <div class="quantity-control">
                                <button class="quantity-btn" onclick="updateCartQuantity('${item.isbn}', -1)" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                                <span style="min-width: 30px; text-align: center;">${item.quantity}</span>
                                <button class="quantity-btn" onclick="updateCartQuantity('${item.isbn}', 1)" ${item.quantity >= (book?.stock || 0) ? 'disabled' : ''}>+</button>
                            </div>
                        </td>
                        <td><strong>$${itemTotal.toFixed(2)}</strong></td>
                        <td>
                            <button class="btn btn-danger" onclick="removeFromCart('${item.isbn}')" style="padding: 0.3rem 0.8rem;">Remove</button>
                        </td>
                    </tr>
                `;
            }).join('');

            updateSelectAllCheckbox();
        }

        function updateCartQuantity(isbn, change) {
            const item = cart.find(i => i.isbn === isbn);
            const book = books.find(b => b.isbn === isbn);
            
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
            cart = cart.filter(i => i.isbn !== isbn);
            selectedItems.delete(isbn);
            saveCart();
            loadCart();
            updateSelectedSummary();
            updateNavigation();
            showToast('Item removed from cart', 'info');
        }

        function clearCart() {
            if (cart.length > 0 && confirm('Are you sure you want to clear your cart?')) {
                cart = [];
                selectedItems.clear();
                saveCart();
                loadCart();
                updateSelectedSummary();
                updateNavigation();
                showToast('Cart cleared', 'info');
            }
        }

        // Cart Selection Functions
        function toggleItemSelection(isbn) {
            if (selectedItems.has(isbn)) {
                selectedItems.delete(isbn);
            } else {
                selectedItems.add(isbn);
            }
            updateSelectedSummary();
            updateSelectAllCheckbox();
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll').checked;
            if (selectAll) {
                cart.forEach(item => selectedItems.add(item.isbn));
            } else {
                selectedItems.clear();
            }
            updateSelectedSummary();
            loadCart();
        }

        function updateSelectAllCheckbox() {
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.checked = cart.length > 0 && cart.every(item => selectedItems.has(item.isbn));
            }
        }

        function getSelectedCartItems() {
            return cart.filter(item => selectedItems.has(item.isbn));
        }

        function updateSelectedSummary() {
            const selectedItems_list = getSelectedCartItems();
            const selectedCount = selectedItems_list.length;
            const subtotal = selectedItems_list.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const total = subtotal + (selectedCount > 0 ? SHIPPING_FEE : 0);

            document.getElementById('selectedCount').textContent = `${selectedCount} selected`;
            document.getElementById('selectedItemsCount').textContent = `${selectedCount} items`;
            document.getElementById('summarySubtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('summaryTotal').textContent = `$${total.toFixed(2)}`;
            document.getElementById('codAmount').textContent = `$${total.toFixed(2)}`;
            
            const bankAmount = document.getElementById('bankAmount');
            if (bankAmount) {
                bankAmount.textContent = `$${total.toFixed(2)}`;
            }
            
            const transferAmount = document.getElementById('transferAmount');
            if (transferAmount) {
                transferAmount.value = total.toFixed(2);
            }
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
                if (currentUser) {
                    document.getElementById('codRecipientName').value = currentUser.name || '';
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

            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            
            if (paymentMethod === 'bank') {
                if (!validateBankTransferForm()) {
                    return;
                }
            } else {
                if (!validateCODAddress()) {
                    return;
                }
            }

            const subtotal = selectedItems_list.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const total = subtotal + SHIPPING_FEE;
            
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
                const book = books.find(b => b.isbn === item.isbn);
                if (!book || book.stock < item.quantity) {
                    showToast(`Insufficient stock for ${item.title}`, 'error');
                    closeModal('paymentConfirm');
                    return;
                }
            }

            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            const subtotal = selectedItems_list.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const total = subtotal + SHIPPING_FEE;

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
                userId: currentUser.id,
                userName: currentUser.name,
                items: selectedItems_list.map(item => ({ ...item })),
                subtotal: subtotal,
                shippingFee: SHIPPING_FEE,
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

            orders.push(order);
            saveOrders();

            cart = cart.filter(item => !selectedItems.has(item.isbn));
            selectedItems.clear();
            saveCart();

            resetBankTransferForm();
            document.getElementById('codAddress').value = '';
            document.getElementById('codPhone').value = '';
            document.getElementById('codDeliveryInstructions').value = '';
            if (currentUser) {
                document.getElementById('codRecipientName').value = currentUser.name || '';
            }

            closeModal('paymentConfirm');
            showToast('Order placed successfully! Please wait for payment confirmation.', 'success');
            updateNavigation();
            
            loadCart();
            updateSelectedSummary();
        }

        // ==================== WISHLIST FUNCTIONS ====================
        function toggleWishlist(isbn) {
            if (!currentUser) {
                showToast('Please login to use wishlist', 'error');
                openLoginModal();
                return;
            }

            const wasInWishlist = wishlist.includes(isbn);
            
            if (wasInWishlist) {
                wishlist = wishlist.filter(i => i !== isbn);
                showToast('Removed from wishlist', 'info');
            } else {
                wishlist.push(isbn);
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
            if (currentPreviewBook) {
                toggleWishlist(currentPreviewBook.isbn);
            }
        }

        function loadWishlist() {
            const grid = document.getElementById('wishlistGrid');
            const wishlistBooks = books.filter(book => wishlist.includes(book.isbn));
            
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

        // ==================== PREVIEW FUNCTIONS ====================
        function previewBook(isbn) {
            const book = books.find(b => b.isbn === isbn);
            if (book) {
                currentPreviewBook = book;
                document.getElementById('previewTitle').textContent = book.title;
                const stockStatus = book.stock > 0 ? 
                    `<span style="color: #22c55e;">✓ In Stock (${book.stock} available)</span>` : 
                    '<span style="color: #ef4444;">✗ Out of Stock</span>';
                
                const coverHtml = book.image ? 
                    `<img src="${book.image}" alt="${book.title}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px;">` :
                    `<div style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); width: 100%; height: 200px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem;">📚</div>`;
                
                document.getElementById('previewContent').innerHTML = `
                    <div style="display: flex; gap: 2rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            ${coverHtml}
                        </div>
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

                const addToCartBtn = document.getElementById('previewAddToCartBtn');
                addToCartBtn.disabled = book.stock === 0;
                addToCartBtn.textContent = book.stock === 0 ? 'Out of Stock' : 'Add to Cart';

                const wishlistBtn = document.getElementById('previewAddToWishlistBtn');
                if (wishlist.includes(book.isbn)) {
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
            if (currentPreviewBook) {
                openCartConfirm(currentPreviewBook.isbn);
                closeModal('preview');
            }
        }

        // ==================== ORDER FUNCTIONS ====================
        function loadUserOrders() {
            const tbody = document.getElementById('userOrdersBody');
            const userOrders = orders.filter(o => o.userId === currentUser?.id).sort((a, b) => new Date(b.date) - new Date(a.date));
            
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
                        <td>
                            <span class="status-badge ${paymentStatusClass}">
                                ${order.paymentStatus}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-${orderStatusClass}">
                                ${order.approvalStatus === 'pending' ? 'Pending Approval' : 
                                  order.approvalStatus === 'cancelled' ? 'Cancelled' : 
                                  order.status}
                            </span>
                        </td>
                        <td>
                            ${order.paymentDetails ? 
                                `<button class="action-btn view" onclick="viewPaymentDetails('${order.id}')">👁️ View Details</button>` : 
                                '<span style="color: #64748b;">—</span>'}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn view" onclick="viewOrderDetails('${order.id}')">👁️ View</button>
                                ${order.approvalStatus === 'pending' && order.paymentStatus !== 'Paid' ? 
                                    `<button class="action-btn cancel" onclick="cancelOrder('${order.id}')">✗ Cancel</button>` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function viewOrderDetails(orderId) {
            const order = orders.find(o => o.id === orderId);
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
                        <p><strong>Order Status:</strong> 
                            <span class="status-badge status-${statusClass}">
                                ${displayStatus}
                            </span>
                        </p>
                        <p><strong>Payment Method:</strong> ${order.paymentMethod}</p>
                        <p><strong>Payment Status:</strong> 
                            <span class="status-badge ${paymentStatusClass}">
                                ${order.paymentStatus}
                            </span>
                        </p>
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
                const order = orders.find(o => o.id === orderId);
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
            const order = orders.find(o => o.id === orderId);
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

        // ==================== TOAST NOTIFICATION ====================
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.remove();
            }
            
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
                if (toastElement) {
                    toastElement.remove();
                }
            }, 3000);
        }

        // ==================== INITIALIZE ====================
        loadData();
        updateNavigation();
        loadFeaturedBooks();

        // Add toast container
        document.body.insertAdjacentHTML('beforeend', '<div id="toast"></div>');
    </script>
</body>
</html>
