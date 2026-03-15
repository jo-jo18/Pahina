<section id="profile-section" class="section">
    <div class="profile-section">
        <div class="profile-card">
            <div class="profile-header">
                <h2>My Profile</h2>
                <p>Manage your personal information and account settings</p>
            </div>

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

            <div class="profile-tabs">
                <button class="profile-tab active" onclick="showProfileTab('info')">📋 Personal Info</button>
                <button class="profile-tab" onclick="showProfileTab('security')">🔒 Security</button>
                <button class="profile-tab" onclick="showProfileTab('stats')">📊 Statistics</button>
            </div>

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