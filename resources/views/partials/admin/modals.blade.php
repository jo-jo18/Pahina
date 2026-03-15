<!-- Add/Edit Book Modal -->
<div id="bookModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="bookModalTitle">Add New Book</h3>
            <button class="close-btn" onclick="closeModal('book')">&times;</button>
        </div>
        <form onsubmit="saveBook(event)">
            <input type="hidden" id="bookId" value="">
            
            <!-- Image Upload Section -->
            <div class="form-group">
                <label>Book Cover Image</label>
                <div class="image-upload-container" onclick="document.getElementById('bookImage').click()">
                    <div class="upload-icon">📷</div>
                    <p style="color: #475569;">Click to upload book cover image</p>
                    <p style="font-size: 0.8rem; color: #94a3b8;">Supported: JPG, PNG, GIF (Max 2MB)</p>
                    <input type="file" id="bookImage" accept="image/*" style="display: none;" onchange="previewImage(this)">
                </div>
                <img id="imagePreview" class="image-preview" src="#" alt="Book cover preview">
            </div>

            <div class="form-group">
                <label for="bookISBN">ISBN</label>
                <input type="text" id="bookISBN" placeholder="Enter ISBN" required>
            </div>
            <div class="form-group">
                <label for="bookTitle">Title</label>
                <input type="text" id="bookTitle" placeholder="Enter book title" required>
            </div>
            <div class="form-group">
                <label for="bookAuthor">Author</label>
                <input type="text" id="bookAuthor" placeholder="Enter author name" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="bookPrice">Price ($)</label>
                    <input type="number" id="bookPrice" step="0.01" min="0" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label for="bookStock">Stock Quantity</label>
                    <input type="number" id="bookStock" min="0" placeholder="0" required>
                </div>
            </div>
            <div class="form-group">
                <label for="bookSynopsis">Synopsis</label>
                <textarea id="bookSynopsis" rows="4" placeholder="Enter book description" required></textarea>
            </div>
            <div class="form-group">
                <label for="bookCondition">Condition</label>
                <select id="bookCondition" required>
                    <option value="new">Brand New</option>
                    <option value="like-new">Like New</option>
                    <option value="good">Good</option>
                    <option value="acceptable">Acceptable</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Save Book</button>
        </form>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="userModalTitle">Add New User</h3>
            <button class="close-btn" onclick="closeModal('user')">&times;</button>
        </div>
        <form onsubmit="saveUser(event)">
            <input type="hidden" id="userId" value="">
            <div class="form-group">
                <label for="userName">Full Name</label>
                <input type="text" id="userName" placeholder="Enter full name" required>
            </div>
            <div class="form-group">
                <label for="userEmail">Email Address</label>
                <input type="email" id="userEmail" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="userPassword">Password</label>
                <input type="password" id="userPassword" placeholder="Enter password" required>
            </div>
            <div class="form-group">
                <label for="userType">User Type</label>
                <select id="userType" required>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">Save User</button>
        </form>
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

<div id="paymentApproveModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Payment</h3>
            <button class="close-btn" onclick="closeModal('paymentApprove')">&times;</button>
        </div>
        <div id="paymentApproveContent" style="line-height: 1.6;">
        </div>
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button class="btn btn-secondary" onclick="closeModal('paymentApprove')" style="flex: 1;">Cancel</button>
            <button class="btn btn-success" onclick="approvePayment()" style="flex: 1;" id="approvePaymentBtn">Confirm Payment</button>
        </div>
    </div>
</div>

<div id="viewUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>User Details</h3>
            <button class="close-btn" onclick="closeModal('viewUser')">&times;</button>
        </div>
        <div id="userDetails">
        </div>
    </div>
</div>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Admin Login</h3>
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
            <p style="text-align: center; margin-top: 1rem; color: #64748b;">
            </p>
        </form>
    </div>
</div>