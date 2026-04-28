@stack('styles') 

<style>
#beautiful-3d-bg{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:-1;
}

body{
    margin:0;
    background:#f5e6d3;
}

    .welcome-perspective-container {
        perspective: 1000px;
        padding: 1rem;
    }

    .welcome-3d-card {
        background: #1a1816;
        border-radius: 40px;
        padding: 3rem;
        display: flex; 
        flex-direction: row;
        align-items: center;
        gap: 3rem;
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.6s ease;
        transform-style: preserve-3d;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        min-height: 420px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .welcome-visual-side {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 10px;
        z-index: 2;
    }
    .visual-book {
        width: 45px;
        border-radius: 4px 4px 0 0;
        box-shadow: 5px 0 15px rgba(0,0,0,0.3);
    }

    .welcome-card-content {
        flex: 1.2;
        text-align: left;
        transform: translateZ(50px);
        position: relative;
        z-index: 10;
        color: white;
    }

    .welcome-title-3d {
        font-size: 3.2rem;
        font-weight: 800;
        margin-bottom: 0.8rem;
        font-family: 'Playfair Display', serif;
        line-height: 1.1;
    }
    .welcome-title-3d span {
        display: block;
        color: #d4a843;
        font-family: 'Cormorant Garamond', serif;
        font-style: italic;
    }

    .welcome-subtitle-3d {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        color: #a0958a;
        max-width: 450px;
        line-height: 1.6;
    }

    .welcome-stats-row {
        display: flex;
        gap: 2rem;
        margin-bottom: 2.5rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 1.5rem;
    }
    .stat-item { flex: 1; }
    .stat-value { font-size: 1.4rem; font-weight: bold; color: #d4a843; display: block; }
    .stat-label { font-size: 0.75rem; color: #666; text-transform: uppercase; letter-spacing: 1px; }

    .welcome-btn {
        padding: 1rem 2.5rem;
        border-radius: 12px;
        background: #d4a843;
        border: none;
        color: #1a1816;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .welcome-btn:hover { transform: scale(1.05); background: #f5c542; }


    .featured-wrapper { margin-top: 2rem; position: relative; padding: 0 0 2rem; }
    .featured-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    

    .featured-header h3 { 
        font-size: 1.8rem; 
        background: linear-gradient(135deg, #8b5a2b, #d4a843); 
        -webkit-background-clip: text; 
        background-clip: text; 
        color: transparent; 
        font-weight: 800; 
    }
    
    .swiper-nav-buttons { display: flex; gap: 0.75rem; }
    .swiper-nav-buttons .swiper-button-prev,
    .swiper-nav-buttons .swiper-button-next {
        position: relative;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.2s;
        color: #d4a843;
        margin: 0;
    }
    .swiper-nav-buttons .swiper-button-prev::after,
    .swiper-nav-buttons .swiper-button-next::after { font-size: 16px; font-weight: bold; }
    .swiper-nav-buttons .swiper-button-prev:hover,
    .swiper-nav-buttons .swiper-button-next:hover { background: #d4a843; color: white; transform: scale(1.05); }

    .featured-swiper { padding: 40px 0 60px; width: 100%; margin: 0 auto; }
    .featured-swiper .swiper-slide {
        width: 280px;
        transition: transform 0.4s;
        border-radius: 20px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(4px);
        box-shadow: 0 20px 35px -10px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    .featured-swiper .swiper-slide-active {
        transform: scale(1.02);
        box-shadow: 0 25px 40px -12px rgba(212, 168, 67, 0.4);
    }
    .featured-swiper .book-card { height: 100%; display: flex; flex-direction: column; background: white; border: none; border-radius: 20px; overflow: hidden; cursor: pointer; }
    .featured-swiper .book-cover {
        height: 260px;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        font-size: 3rem;
        background-color: #f1f5f9;
    }
    .book-condition { position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
    
    .featured-swiper .book-info { padding: 1.2rem; text-align: center; }
    .featured-swiper .book-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.4rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #1e293b; }
    .featured-swiper .book-author { font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem; }
    .featured-swiper .book-price { color: #8b5a2b; font-weight: 800; font-size: 1.1rem; margin-bottom: 0.5rem; }
    
    .stock-badge { font-size: 0.7rem; padding: 3px 8px; border-radius: 50px; display: inline-block; margin-bottom: 10px; font-weight: 600; }
    .in-stock { background: #dcfce7; color: #166534; }
    .low-stock { background: #fef9c3; color: #854d0e; }
    .out-of-stock { background: #fee2e2; color: #991b1b; }

    .featured-swiper .book-actions { padding: 1rem; display: flex; gap: 0.5rem; border-top: 1px solid #f1f5f9; }
    .featured-swiper .btn { flex: 1; padding: 0.6rem; font-size: 0.85rem; border-radius: 8px; font-weight: 600; }

    @media (max-width: 850px) {
        .welcome-3d-card { flex-direction: column; text-align: center; padding: 2rem; min-height: auto; }
        .welcome-visual-side { display: none; }
        .welcome-card-content { text-align: center; }
        .welcome-stats-row { justify-content: center; }
        .welcome-title-3d { font-size: 2.2rem; }
    }
    
    @media (max-width: 640px) {
        .featured-swiper .swiper-slide { width: 240px; }
        .featured-swiper .book-cover { height: 220px; }
    }
</style>

<div id="beautiful-3d-bg"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>

(function() {
    const container = document.getElementById('beautiful-3d-bg');
    if (!container) return;
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xF5E6D3); 
    const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 12;

    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    container.appendChild(renderer.domElement);

    const mainGroup = new THREE.Group();
    scene.add(mainGroup);

    const dustGeometry = new THREE.BufferGeometry();
    const dustCount = 8000;
    const positions = new Float32Array(dustCount * 3);
    const colors = new Float32Array(dustCount * 3);
    const themeColors = [new THREE.Color(0xd4a843), new THREE.Color(0x8b5a2b), new THREE.Color(0xb87c4f)];

    for(let i = 0; i < dustCount * 3; i += 3) {
        positions[i] = (Math.random() - 0.5) * 50;
        positions[i+1] = (Math.random() - 0.5) * 50;
        positions[i+2] = (Math.random() - 0.5) * 50;
        const col = themeColors[Math.floor(Math.random() * themeColors.length)];
        colors[i] = col.r; colors[i+1] = col.g; colors[i+2] = col.b;
    }
    dustGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    dustGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
    const dustMaterial = new THREE.PointsMaterial({ size: 0.04, vertexColors: true, transparent: true, opacity: 0.4 });
    const dustParticles = new THREE.Points(dustGeometry, dustMaterial);
    mainGroup.add(dustParticles);

    const ringGroup = new THREE.Group();
    mainGroup.add(ringGroup);
    const ringMaterial = new THREE.MeshBasicMaterial({ color: 0xd4a843, side: THREE.DoubleSide, transparent: true, opacity: 0.3 });
    
    for(let i = 0; i < 5; i++) {
        const radius = 3 + (i * 1.5);
        const geometry = new THREE.TorusGeometry(radius, 0.015, 16, 100);
        const ring = new THREE.Mesh(geometry, ringMaterial);
        ring.rotation.x = Math.random() * Math.PI;
        ring.rotation.y = Math.random() * Math.PI;
        ring.userData.speedX = (Math.random() - 0.5) * 0.005;
        ring.userData.speedY = (Math.random() - 0.5) * 0.005;
        ringGroup.add(ring);
    }

    function animate() {
        requestAnimationFrame(animate);
        mainGroup.rotation.y += 0.0005;
        ringGroup.children.forEach(r => {
            r.rotation.x += r.userData.speedX;
            r.rotation.y += r.userData.speedY;
        });
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
});
})();

</script>

<script>


<script>

    function showSection(sectionId) {

        const sections = document.querySelectorAll('section.section');
        sections.forEach(sec => {
            sec.style.display = 'none';
            sec.classList.remove('active');
        });


        let targetSection = document.getElementById(sectionId);
        
        if (targetSection) {
            targetSection.style.display = 'block';
            targetSection.classList.add('active');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            console.warn("Section '" + sectionId + "' not found. Ensure your shop container has id='" + sectionId + "'");
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const allSections = document.querySelectorAll('section.section');
        allSections.forEach((sec, index) => {
            if (sec.id !== 'home-section') {
                sec.style.display = 'none';
            }
        });
    });
</script>

<section id="home-section" class="section active">
    <div class="welcome-section">
        <div class="welcome-perspective-container">
            <div class="welcome-3d-card">
                <div id="box-3d-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; border-radius: 40px; overflow: hidden; opacity: 0.4;"></div>
                
                <div class="welcome-visual-side">
                    <div class="visual-book" style="height: 140px; background: #2b548a;"></div>
                    <div class="visual-book" style="height: 190px; background: #d4a843;"></div>
                    <div class="visual-book" style="height: 240px; background: #9c2e2e;"></div>
                    <div class="visual-book" style="height: 210px; background: #2d7a5e;"></div>
                    <div class="visual-book" style="height: 160px; background: #6a4da8;"></div>
                </div>

                <div class="welcome-card-content">
                    <h2 class="welcome-title-3d">Welcome to Pahina, <span>Your trusted Pahinga.</span></h2>
                    <p class="welcome-subtitle-3d">Discover your next favorite book from our curated collection of pre-loved and brand-new titles.</p>
                    
                    <div class="welcome-stats-row">
                        <div class="stat-item"><span class="stat-value">∞</span><span class="stat-label">Real-time</span></div>
                        <div class="stat-item"><span class="stat-value">24/7</span><span class="stat-label">Support</span></div>
                        <div class="stat-item"><span class="stat-value">100%</span><span class="stat-label">Secure</span></div>
                    </div>

                    <button class="welcome-btn" onclick="showSection('shop')">Start Browsing</button>
                </div>
            </div>
        </div>
    </div>

    <div class="featured-wrapper">
        <div class="featured-header">
            <h3>✨ Featured Books</h3>
            <div class="swiper-nav-buttons">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        <div class="swiper featured-swiper">
            <div class="swiper-wrapper" id="featuredBooksSwiper">
                </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    window.loadFeaturedBooks = async function() {
        try {
            const response = await fetch('/api/user/books/featured', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            });
            let featuredBooks = [];
            if (response.ok) {
                featuredBooks = await response.json();
            } else {
                featuredBooks = (typeof books !== 'undefined' && books.length) ? books.slice(0, 6) : [];
            }

            const swiperWrapper = document.getElementById('featuredBooksSwiper');
            if (!swiperWrapper) return;

            if (!featuredBooks.length) {
                swiperWrapper.innerHTML = '<div class="swiper-slide"><p style="text-align:center; padding: 2rem;">No featured books available</p></div>';
                return;
            }

            swiperWrapper.innerHTML = featuredBooks.map(book => `
                <div class="swiper-slide">
                    <div class="book-card" onclick="previewBook('${book.isbn}')">
                        <div class="book-cover" style="${book.image ? `background-image: url('/storage/${book.image}');` : 'background: linear-gradient(135deg, #d4a843, #8b5a2b);'}">
                            ${!book.image ? '📚' : ''}
                            <span class="book-condition">${book.condition}</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">${book.title}</h3>
                            <p class="book-author">by ${book.author}</p>
                            <p class="book-price">₱${parseFloat(book.price).toLocaleString()}</p>
                            <span class="stock-badge ${book.stock > 5 ? 'in-stock' : (book.stock > 0 ? 'low-stock' : 'out-of-stock')}">
                                ${book.stock > 5 ? 'In Stock' : (book.stock > 0 ? `Only ${book.stock} left` : 'Out of Stock')}
                            </span>
                        </div>
                        <div class="book-actions" onclick="event.stopPropagation()">
                            <button class="btn btn-primary" style="background: #8b5a2b; border: none;" onclick="openCartConfirm('${book.isbn}')" ${book.stock === 0 ? 'disabled' : ''}>
                                ${book.stock === 0 ? 'Sold Out' : 'Add to Cart'}
                            </button>
                            <button class="btn btn-outline-secondary" onclick="toggleWishlist('${book.isbn}')">
                                ${(typeof wishlist !== 'undefined' && wishlist.some(w => w.isbn === book.isbn)) ? '❤️' : '🤍'}
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');

            if (window.featuredSwiper) window.featuredSwiper.destroy(true, true);

            window.featuredSwiper = new Swiper('.featured-swiper', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                coverflowEffect: {
                    rotate: 25,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    0: { slidesPerView: 1.2, coverflowEffect: { rotate: 15 } },
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                },
                loop: featuredBooks.length >= 3,
                autoplay: { delay: 4500, disableOnInteraction: false },
            });
        } catch (error) {
            console.error('Error:', error);
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(window.loadFeaturedBooks, 500);
    });


    (function() {
        const cardContainer = document.getElementById('box-3d-canvas');
        if (!cardContainer) return;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, cardContainer.offsetWidth / cardContainer.offsetHeight, 0.1, 1000);
        camera.position.z = 5;

        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(cardContainer.offsetWidth, cardContainer.offsetHeight);
        cardContainer.appendChild(renderer.domElement);

        const particlesGeometry = new THREE.BufferGeometry();
        const particlesCount = 2000;
        const posArray = new Float32Array(particlesCount * 3);

        for(let i = 0; i < particlesCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 10;
        }

        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.005,
            color: '#d4a843',
            transparent: true,
            opacity: 0.8,
            blending: THREE.AdditiveBlending
        });

        const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particlesMesh);

        function animateDust() {
            requestAnimationFrame(animateDust);
            particlesMesh.rotation.y += 0.001;
            particlesMesh.rotation.x += 0.0005;
            renderer.render(scene, camera);
        }
        animateDust();

        window.addEventListener('resize', () => {
            if (cardContainer.offsetWidth > 0) {
                camera.aspect = cardContainer.offsetWidth / cardContainer.offsetHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(cardContainer.offsetWidth, cardContainer.offsetHeight);
            }
        });
    })();
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

    .footer-link {
        transition: color 0.3s ease;
    }
    .footer-link:hover {
        color: #d4a843 !important;
    }

    .social-icon {
        transition: transform 0.3s ease, color 0.3s ease;
    }
    .social-icon:hover {
        color: #f5c542 !important;
        transform: translateY(-3px);
    }
</style>

<div id="legalModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); backdrop-filter: blur(8px); align-items: center; justify-content: center;">
    <div style="background: #f5e6d3; color: #1a1816; padding: 2.5rem; border-radius: 30px; max-width: 700px; width: 90%; max-height: 80vh; overflow-y: auto; position: relative; box-shadow: 0 25px 50px rgba(0,0,0,0.5); border: 2px solid #d4a843;">
        <span onclick="closeLegalModal()" style="position: absolute; top: 20px; right: 25px; font-size: 2rem; cursor: pointer; color: #8b5a2b; font-weight: bold;">&times;</span>
        <div id="modalContent"></div>
        <div style="margin-top: 2rem; text-align: center;">
            <button class="welcome-btn" onclick="closeLegalModal()" style="padding: 0.8rem 2rem;">Understood</button>
        </div>
    </div>
</div>

<footer style="background: #1a1816; color: #a0958a; padding: 5rem 2rem 2rem; margin-top: 5rem; border-top: 1px solid rgba(212, 168, 67, 0.3); position: relative; z-index: 10; font-family: 'Inter', sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 4rem;">
        
        <div>
            <h3 style="color: #d4a843; font-family: 'Playfair Display', serif; margin-bottom: 1.5rem; font-size: 1.8rem;">Pahina</h3>
            <p style="font-size: 0.95rem; line-height: 1.8; margin-bottom: 1.5rem;">
                Your quiet sanctuary for pre-loved and brand-new stories. We believe every page turned is a moment of rest.
            </p>
            <div style="display: flex; gap: 20px; font-size: 1.5rem;">
                <a href="https://www.facebook.com/profile.php?id=61560216636168" target="_blank" class="social-icon" style="color: #d4a843; text-decoration: none;"><i class="fab fa-facebook"></i></a>
                <a href="https://www.instagram.com/pahina_bookstore?igsh=MXBsY2d3d2ttMGtpMA==" target="_blank" class="social-icon" style="color: #d4a843; text-decoration: none;"><i class="fab fa-instagram"></i></a>
                <a href="https://www.reddit.com/u/Pahina_Bookstore/s/7ngvdjoESu" target="_blank" class="social-icon" style="color: #d4a843; text-decoration: none;"><i class="fab fa-reddit"></i></a>
            </div>
        </div>

        <div>
            <h4 style="color: white; margin-bottom: 1.5rem; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">Information</h4>
            <ul style="list-style: none; padding: 0; font-size: 0.95rem; line-height: 2.2;">
                <li><a href="javascript:void(0)" onclick="openLegal('privacy')" class="footer-link" style="color: inherit; text-decoration: none;">Privacy Policy</a></li>
                <li><a href="javascript:void(0)" onclick="openLegal('terms')" class="footer-link" style="color: inherit; text-decoration: none;">Terms of Service</a></li>
                <li><a href="javascript:void(0)" onclick="openLegal('shipping')" class="footer-link" style="color: inherit; text-decoration: none;">Shipping & Returns</a></li>
                <li><a href="javascript:void(0)" onclick="openLegal('faq')" class="footer-link" style="color: inherit; text-decoration: none;">FAQ</a></li>
            </ul>
        </div>

        <div>
            <h4 style="color: white; margin-bottom: 1.5rem; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">Contact Us</h4>
            <ul style="list-style: none; padding: 0; font-size: 0.95rem; line-height: 1.8;">
                <li style="margin-bottom: 10px;">Binalbagan, <br>Negros Occidental, Philippines</li>
                <li style="margin-bottom: 10px;">pahina@bookstore.com</li>
                <li>+63 912 345 6789</li>
            </ul>
        </div>
    </div>

    <div style="max-width: 1200px; margin: 4rem auto 0; padding: 2rem 1rem 0; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: center; align-items: center; flex-direction: column; gap: 1rem; font-size: 0.85rem; color: #A0958A;">
    <p style="margin: 0; text-align: center;">&copy; 2026 Pahina Bookstore. Crafted for book lovers.</p>
    <div style="display: flex; gap: 20px;">
        </div>
</div>
</footer>

<script>
    const legalData = {
        privacy: {
            title: "Privacy Policy",
            body: `<h3>Your Privacy Matters</h3><p>At Pahina, we respect your quiet sanctuary. We only collect essential information like your name and address to deliver your books.</p><ul><li><strong>Data Security:</strong> We use encrypted gateways for all transactions.</li><li><strong>Cookies:</strong> We use minor cookies to remember your cart items.</li><li><strong>Third Parties:</strong> Your data is never sold; it's only shared with our courier partners.</li></ul>`
        },
        terms: {
            title: "Terms of Service",
            body: `<h3>Our Agreement</h3><p>By using Pahina, you agree to our community standards.</p><ul><li><strong>Book Condition:</strong> Pre-loved books are sold as-is; check photos carefully!</li><li><strong>Orders:</strong> Cancellations are only allowed before the book is packed.</li><li><strong>Intellectual Property:</strong> All site designs and 3D effects are property of Pahina.</li></ul>`
        },
        shipping: {
            title: "Shipping & Returns",
            body: `<h3>Delivery Info</h3><p>We ship nationwide within the Philippines.</p><ul><li><strong>Metro Manila:</strong> 2-3 business days.</li><li><strong>Provincial:</strong> 5-7 business days.</li><li><strong>Returns:</strong> We accept returns within 7 days ONLY if the book received does not match the description or photos provided.</li></ul>`
        },
        faq: {
            title: "Frequently Asked Questions",
            body: `<h3>How can we help?</h3><p><strong>Q: Are these books brand new?</strong><br>A: We offer a mix! Look for the "Condition" badge on the book cover.</p><p><strong>Q: Do you offer Cash on Delivery?</strong><br>A: Yes, for Metro Manila and select provincial areas.</p>`
        }
    };

    function openLegal(type) {
        const modal = document.getElementById('legalModal');
        const content = document.getElementById('modalContent');
        const data = legalData[type];

        content.innerHTML = `<h2 style="font-family: 'Playfair Display', serif; color: #8b5a2b; font-size: 2.2rem; margin-bottom: 1rem;">${data.title}</h2><div style="line-height: 1.6; color: #444;">${data.body}</div>`;
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; 
    }

    function closeLegalModal() {
        document.getElementById('legalModal').style.display = 'none';
        document.body.style.overflow = 'auto'; 
    }

    window.onclick = function(event) {
        const modal = document.getElementById('legalModal');
        if (event.target == modal) {
            closeLegalModal();
        }
    }
</script>

@endpush
@include('partials.user.modals')