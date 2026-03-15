<section id="users-section" class="section">
    <div class="admin-header">
        <h2>👥 User Manager</h2>
        <div class="admin-actions">
            <button class="btn btn-primary" onclick="showAddUserModal()">
                <span style="margin-right: 0.3rem;">➕</span> Add User
            </button>
            <button class="btn btn-secondary" onclick="exportData()">
                <span style="margin-right: 0.3rem;">📥</span> Export Users
            </button>
        </div>
    </div>

    <div class="user-filters">
        <input type="text" class="user-search" id="userSearch" placeholder="Search users by name or email..." onkeyup="filterUsers()">
        <select class="user-type-filter" id="userTypeFilter" onchange="filterUsers()">
            <option value="all">All Users</option>
            <option value="customers">Customers Only</option>
            <option value="admins">Admins Only</option>
        </select>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Orders</th>
                    <th>Total Spent</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersBody">
            </tbody>
        </table>
    </div>
</section>