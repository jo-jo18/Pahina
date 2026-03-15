<section id="orders-section" class="section">
    <div class="admin-header">
        <h2>📦 Order Management</h2>
        <div class="admin-actions">
            <button class="btn btn-secondary" onclick="exportData()">
                <span style="margin-right: 0.3rem;">📥</span> Export Orders
            </button>
        </div>
    </div>

    <div class="section-header">
        <h3>📦 All Orders <span id="totalOrdersCount">0</span></h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
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
            <tbody id="ordersBody">
            </tbody>
        </table>
    </div>
</section>