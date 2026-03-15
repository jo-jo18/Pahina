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

<div id="previewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="previewTitle">Book Details</h3>
            <button class="close-btn" onclick="closeModal('preview')">&times;</button>
        </div>
        <div id="previewContent" style="line-height: 1.6;">
        </div>
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button class="btn btn-primary" id="previewAddToCartBtn" style="flex: 1;" onclick="addToCartFromPreview()">Add to Cart</button>
            <button class="btn btn-secondary" id="previewAddToWishlistBtn" style="flex: 1;" onclick="toggleWishlistFromPreview()">❤️ Wishlist</button>
        </div>
    </div>
</div>

<div id="orderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Order Details</h3>
            <button class="close-btn" onclick="closeModal('order')">&times;</button>
        </div>
        <div id="orderDetails">
        </div>
    </div>
</div>

<div id="paymentDetailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Payment Details</h3>
            <button class="close-btn" onclick="closeModal('paymentDetails')">&times;</button>
        </div>
        <div id="paymentDetailsContent" style="line-height: 1.6;">
        </div>
    </div>
</div>

<div id="cartConfirmModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add to Cart</h3>
            <button class="close-btn" onclick="closeModal('cartConfirm')">&times;</button>
        </div>
        <div id="confirmBookDetails" style="display: flex; gap: 1rem; margin: 1.5rem 0; padding: 1rem; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
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

<div id="paymentConfirmModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Order</h3>
            <button class="close-btn" onclick="closeModal('paymentConfirm')">&times;</button>
        </div>
        <div id="paymentConfirmContent" style="line-height: 1.6;">
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button class="btn btn-secondary" onclick="closeModal('paymentConfirm')" style="flex: 1;">Cancel</button>
            <button class="btn btn-success" onclick="finalizeCheckout()" style="flex: 1;" id="confirmPaymentBtn">Place Order</button>
        </div>
    </div>
</div>