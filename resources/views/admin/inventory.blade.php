<section id="inventory-section" class="section">
    <div class="admin-header">
        <h2>📚 Inventory Manager</h2>
        <div class="admin-actions">
            <button class="btn btn-primary" onclick="openAddBookModal()">
                <span style="margin-right: 0.3rem;">➕</span> Add New Book
            </button>
            <button class="btn btn-secondary" onclick="exportData()">
                <span style="margin-right: 0.3rem;">📥</span> Export Data
            </button>
        </div>
    </div>

    <div class="section-header">
        <h3>📚 All Books <span id="totalBooksCount">0</span></h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Condition</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="inventoryBody">
            </tbody>
        </table>
    </div>
</section>