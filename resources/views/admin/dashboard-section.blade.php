<section id="dashboard-section" class="section">
    <div class="admin-header">
        <h2>📊 Dashboard Overview</h2>
        <div class="admin-actions">
            <button class="btn btn-primary" onclick="openAddBookModal()">
                <span style="margin-right: 0.3rem;">➕</span> Add New Book
            </button>
            <button class="btn btn-secondary" onclick="exportData()">
                <span style="margin-right: 0.3rem;">📥</span> Export Data
            </button>
        </div>
    </div>

    <div class="stats-grid" id="adminStats">
    </div>

    <div class="section-header">
        <h3>📊 Quick Overview</h3>
    </div>

    <div class="section-header">
        <h3>📦 Recent Orders <span>Last 5 Orders</span></h3>
        <button class="btn btn-secondary" onclick="showSection('orders-section')">View All Orders</button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="recentOrdersPreview">
            </tbody>
        </table>
    </div>

    <div class="section-header">
        <h3>⚠️ Low Stock Alert <span>Books with low inventory</span></h3>
        <button class="btn btn-secondary" onclick="showSection('inventory-section')">Manage Inventory</button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="lowStockPreview">
            </tbody>
        </table>
    </div>
</section>