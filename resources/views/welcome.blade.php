<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pahina | User Dashboard</title>
    
    <!-- This links to the file you just created in public/css/user.css -->
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>

    <!-- Header based on your CSS classes -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <h1>Pahina</h1>
                <p>Reading made easy</p>
            </div>
            
            <nav class="nav-buttons">
                <button class="nav-btn active">Home</button>
                <button class="nav-btn">Books</button>
                <div class="user-info">
                    <span class="user-name">Estella Mariz</span>
                    <div class="profile-avatar">EM</div>
                </div>
            </nav>
        </div>
    </header>

    <main class="container">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <h2>Welcome Back!</h2>
            <p>Find your next favorite book today.</p>
            <div class="search-section">
                <input type="text" class="search-input" placeholder="Search for books...">
                <button class="search-btn">Search</button>
            </div>
        </section>

        <!-- Example Book Grid -->
        <div class="book-grid">
            <div class="book-card">
                <div class="book-cover default-cover">📖</div>
                <div class="book-info">
                    <div class="book-title">The Laravel Guide</div>
                    <div class="book-author">By PHP Master</div>
                    <div class="book-price">₱500.00</div>
                    <span class="stock-badge in-stock">In Stock</span>
                </div>
                <div class="book-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                </div>
            </div>
        </div>
    </main>

</body>
</html>