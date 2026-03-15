<section id="reports-section" class="section">
    <div class="admin-header">
        <h2>📊 Sales Reports</h2>
        <div class="admin-actions">
            <button class="btn btn-secondary" onclick="exportData()">
                <span style="margin-right: 0.3rem;">📥</span> Export Reports
            </button>
        </div>
    </div>

    <div class="report-section">
        <div class="report-header">
            <span class="report-title">Revenue Overview</span>
            <div class="report-filters">
                <select class="report-filter" id="reportPeriod" onchange="loadSalesReports()">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                    <option value="all">All Time</option>
                </select>
            </div>
        </div>
        <div class="stats-grid" id="revenueStats">
        </div>
    </div>

    <div class="report-section">
        <div class="report-header">
            <span class="report-title">🏆 Top Selling Books</span>
        </div>
        <div class="top-books" id="topBooks">
        </div>
    </div>
</section>