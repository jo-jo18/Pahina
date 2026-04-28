<<<<<<< HEAD
<!-- Authentication Hero Section (exactly as in screenshot) -->
<div class="auth-hero-section">
    <div class="auth-hero-content">
        <h1 class="auth-title">Pahina Admin</h1>
        <p class="auth-subtitle">Manage your bookstore</p>
        <div class="auth-buttons">
            <!-- Admin portal button removed -->
        </div>
        <div class="hero-decoration">✦ ✦ ✦</div>
    </div>
</div>


<!-- 3D Canvas Container (background) -->
<div id="three-canvas-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; pointer-events: none;"></div>

<style>
    /* Reset & Global */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        background: #f9efE6;
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    /* Main Content Container */
    .content-wrapper {
        position: relative;
    z-index: 10;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1.5rem;
    pointer-events: auto;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin-top: -200px;
    }

    /* Auth Hero Section - NASA GITNA */
    .auth-hero-section {
        background: rgba(252, 205, 172, 0.85);
        backdrop-filter: blur(8px);
        border-radius: 40px;
        padding: 2rem 2rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(174, 127, 98, 0.4);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
    }

    .auth-hero-section:hover {
        transform: translateY(-4px);
        box-shadow: 0 28px 44px rgba(0, 0, 0, 0.25);
        background: rgba(252, 205, 172, 0.92);
    }

    .auth-hero-content {
        max-width: 600px;
        margin: 0 auto;
    }

    .auth-title {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #613D28 0%, #2f1a0e 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 0.75rem;
        letter-spacing: -1px;
    }

    .auth-subtitle {
        font-size: 1.3rem;
        color: #3e2a1c;
        margin-bottom: 1rem;
        font-style: italic;
        font-weight: 500;
    }

    .hero-decoration {
        font-size: 1rem;
        color: #8b5e3c;
        letter-spacing: 4px;
        opacity: 0.6;
    }

    .auth-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    /* FEATURED COLLECTION CARDS */
    .featured-collection {
        margin-top: 4rem;
    }

    .section-badge {
        display: inline-block;
        background: rgba(97, 61, 40, 0.2);
        backdrop-filter: blur(4px);
        padding: 0.3rem 1.2rem;
        border-radius: 40px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        color: #4a2c1a;
        margin-bottom: 1rem;
        border: 1px solid rgba(174,127,98,0.5);
    }

    .featured-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c1a0f;
        margin-bottom: 1rem;
        letter-spacing: -0.3px;
    }

    .featured-desc {
        color: #4a3628;
        max-width: 600px;
        margin-bottom: 2rem;
        font-size: 1.05rem;
        line-height: 1.4;
        margin-left: auto;
        margin-right: auto;
    }

    .cards-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
    }

    .feature-card {
        background: rgba(255, 248, 240, 0.85);
        backdrop-filter: blur(6px);
        border-radius: 32px;
        width: 280px;
        padding: 1.8rem 1.5rem;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        cursor: default;
        border: 1px solid rgba(174, 127, 98, 0.3);
        box-shadow: 0 12px 24px -12px rgba(0, 0, 0, 0.2);
    }

    .feature-card:hover {
        transform: translateY(-10px) scale(1.02);
        background: rgba(255, 248, 240, 0.96);
        border-color: rgba(234, 88, 12, 0.5);
        box-shadow: 0 24px 36px -12px rgba(0, 0, 0, 0.3);
    }

    .card-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .feature-card h3 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #613D28;
        margin-bottom: 0.75rem;
    }

    .feature-card p {
        font-size: 0.95rem;
        color: #4f3a2a;
        line-height: 1.4;
        margin-bottom: 1.5rem;
    }

    .card-tag {
        display: inline-block;
        background: #ea580c20;
        padding: 0.3rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #c2410c;
        backdrop-filter: blur(2px);
    }

    /* best sellers section */
    .bestsellers-section {
        margin-top: 3rem;
        margin-bottom: 2rem;
        background: rgba(250, 240, 230, 0.7);
        backdrop-filter: blur(8px);
        border-radius: 48px;
        padding: 2rem 1.5rem;
        border: 1px solid rgba(174, 127, 98, 0.3);
    }

    .section-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #2c1a0f;
        font-weight: 700;
    }

    .bestseller-list {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: center;
    }

    .book-item {
        background: #fffaf5;
        border-radius: 28px;
        padding: 1rem 1.8rem;
        min-width: 180px;
        text-align: center;
        box-shadow: 0 6px 14px rgba(0,0,0,0.05);
        border: 1px solid #edd9c8;
        transition: all 0.2s;
    }

    .book-item strong {
        display: block;
        font-size: 1.1rem;
        color: #613D28;
    }

    .book-item span {
        font-size: 0.85rem;
        color: #b45f2b;
    }

    .loading-message {
        text-align: center;
        padding: 2rem;
        color: #5a3a28;
        font-style: italic;
    }

    @media (min-width: 640px) {
        .auth-buttons {
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
        }
        .featured-title {
            font-size: 2.6rem;
        }
        .auth-title {
            font-size: 4rem;
        }
    }

    @media (max-width: 700px) {
        .cards-grid {
            gap: 1rem;
        }
        .feature-card {
            width: calc(50% - 1rem);
            min-width: 160px;
            padding: 1.2rem;
        }
        .feature-card h3 {
            font-size: 1.3rem;
        }
        .auth-title {
            font-size: 2.5rem;
        }
        .auth-subtitle {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .feature-card {
            width: 100%;
        }
        .auth-hero-section {
            padding: 1.5rem 1.5rem;
        }
    }

    #three-canvas-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        pointer-events: none;
    }
</style>
</head>
<body>

    <div id="three-canvas-container"></div>

    <div class="content-wrapper">
        <!-- Pahina Admin - NASA GITNA -->
        <div class="auth-hero-section">
            <div class="auth-hero-content">
                <h1 class="auth-title">Pahina Admin</h1>
                <p class="auth-subtitle">Manage your bookstore</p>
                <div class="auth-buttons">
                    <!-- Admin portal button removed -->
                </div>
                <div class="hero-decoration">✦ ✦ ✦</div>
            </div>
        </div>

        <!-- FEATURED COLLECTION - NASA IBABA -->
        <div class="featured-collection">
            <div style="text-align: center;">
                <span class="section-badge">✦ Limited Curations ✦</span>
                <h2 class="featured-title">The Atrium Collection</h2>
                <p class="featured-desc">Where words become timeless artifacts. Handpicked editions for the discerning reader.</p>
            </div>
            <div class="cards-grid">
                <div class="feature-card">
                    <div class="card-icon">📜</div>
                    <h3>Illuminated Manuscripts</h3>
                    <p>Rediscover medieval artistry with restored vellum folios & gilded illustrations.</p>
                    <div class="card-tag">Rare Finds</div>
                </div>
                <div class="feature-card">
                    <div class="card-icon">🌙</div>
                    <h3>Nocturnal Verses</h3>
                    <p>Poetry that haunts & heals. Signed first editions from modern bards.</p>
                    <div class="card-tag">Poetry Vault</div>
                </div>
                <div class="feature-card">
                    <div class="card-icon">🔮</div>
                    <h3>Futurist Folios</h3>
                    <p>Speculative fiction wrapped in visionary design. Limited runs of 500.</p>
                    <div class="card-tag">Pre-Order</div>
                </div>
                <div class="feature-card">
                    <div class="card-icon">📖</div>
                    <h3>The Binding Trilogy</h3>
                    <p>Leather-bound collector's set with author annotations & archival inserts.</p>
                    <div class="card-tag">Collector's Dream</div>
                </div>
            </div>
        </div>

        <!-- Best Sellers Section - NASA IBABA -->
        <div class="bestsellers-section" id="bestsellers-container">
            <div class="section-header">
                <h2>📚 Bestsellers of the Season</h2>
                <p style="color: #6b4c34;">curated from the realms of imagination</p>
            </div>
            <div id="bestsellers-list" class="bestseller-list">
                <div class="loading-message">✨ summoning literary treasures ...</div>
            </div>
        </div>
    </div>

    <!-- Three.js Background Scripts -->
    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.128.0/build/three.module.js"
            }
        }
    </script>

    <script type="module">
        import * as THREE from 'three';

        const container = document.getElementById('three-canvas-container');
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0xf9efe6);
        scene.fog = new THREE.FogExp2(0xf9efe6, 0.008);

        const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.set(0, 2, 12);
        camera.lookAt(0, 0, 0);

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: false });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        container.appendChild(renderer.domElement);

        const ambientLight = new THREE.AmbientLight(0x4a3a2a, 0.55);
        scene.add(ambientLight);
        
        const mainLight = new THREE.DirectionalLight(0xfbe6c2, 1.2);
        mainLight.position.set(5, 10, 7);
        scene.add(mainLight);
        
        const fillLight = new THREE.PointLight(0xd9b48b, 0.6);
        fillLight.position.set(-3, 2, 4);
        scene.add(fillLight);
        
        const backLight = new THREE.PointLight(0xffbc7a, 0.5);
        backLight.position.set(0, 3, -5);
        scene.add(backLight);
        
        const rim = new THREE.PointLight(0xe2a66b, 0.4);
        rim.position.set(2, 3, -4);
        scene.add(rim);

        const particleCount = 800;
        const particlesGeometry = new THREE.BufferGeometry();
        const particlePositions = new Float32Array(particleCount * 3);
        for (let i = 0; i < particleCount; i++) {
            particlePositions[i*3] = (Math.random() - 0.5) * 28;
            particlePositions[i*3+1] = (Math.random() - 0.5) * 12 + 1;
            particlePositions[i*3+2] = (Math.random() - 0.5) * 15 - 5;
        }
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(particlePositions, 3));
        const particleMaterial = new THREE.PointsMaterial({
            color: 0xd4895a,
            size: 0.08,
            transparent: true,
            opacity: 0.5,
            blending: THREE.AdditiveBlending
        });
        const particleSystem = new THREE.Points(particlesGeometry, particleMaterial);
        scene.add(particleSystem);
        
        const orbCount = 180;
        const orbGroup = new THREE.Group();
        const orbGeom = new THREE.SphereGeometry(0.05, 6, 6);
        for (let i = 0; i < orbCount; i++) {
            const materialGlow = new THREE.MeshStandardMaterial({
                color: new THREE.Color().setHSL(0.08, 0.9, 0.65),
                emissive: 0xc25a2a,
                emissiveIntensity: 0.4
            });
            const orb = new THREE.Mesh(orbGeom, materialGlow);
            orb.userData = { phase: Math.random() * Math.PI * 2 };
            orb.position.x = (Math.random() - 0.5) * 18;
            orb.position.y = (Math.random() - 0.5) * 8 + 1;
            orb.position.z = (Math.random() - 0.5) * 12 - 4;
            orbGroup.add(orb);
        }
        scene.add(orbGroup);
        
        const ringGeometry = new THREE.TorusGeometry(1.8, 0.05, 32, 200);
        const goldRingMat = new THREE.MeshStandardMaterial({ color: 0xc97e5a, emissive: 0x7a3e1a, emissiveIntensity: 0.2, metalness: 0.7 });
        const ringLeft = new THREE.Mesh(ringGeometry, goldRingMat);
        ringLeft.position.set(-3.2, 1.2, -2);
        ringLeft.rotation.x = Math.PI / 2.5;
        ringLeft.rotation.z = 0.6;
        scene.add(ringLeft);
        
        const ringRight = new THREE.Mesh(ringGeometry, goldRingMat);
        ringRight.position.set(3.5, 1.5, -1.8);
        ringRight.rotation.x = Math.PI / 2.2;
        ringRight.rotation.z = -0.5;
        scene.add(ringRight);
        
        const cubeGroup = new THREE.Group();
        const colorsBook = [0xb87c4f, 0x9b5e3a, 0xc4875c, 0xd1946a];
        for (let i = 0; i < 70; i++) {
            const size = 0.09 + Math.random() * 0.1;
            const geometry = new THREE.BoxGeometry(size, size * 1.4, size * 0.6);
            const material = new THREE.MeshStandardMaterial({ color: colorsBook[Math.floor(Math.random() * colorsBook.length)], roughness: 0.3 });
            const cube = new THREE.Mesh(geometry, material);
            cube.position.set((Math.random() - 0.5) * 20, (Math.random() - 0.5) * 9 + 1, (Math.random() - 0.5) * 14 - 5);
            cubeGroup.add(cube);
        }
        scene.add(cubeGroup);
        
        const starFieldGeom = new THREE.BufferGeometry();
        const starPositions = new Float32Array(400 * 3);
        for (let i = 0; i < 400; i++) {
            starPositions[i*3] = (Math.random() - 0.5) * 40;
            starPositions[i*3+1] = (Math.random() - 0.5) * 20;
            starPositions[i*3+2] = (Math.random() - 0.5) * 20 - 10;
        }
        starFieldGeom.setAttribute('position', new THREE.BufferAttribute(starPositions, 3));
        const starMaterial = new THREE.PointsMaterial({ color: 0xffbb88, size: 0.04, transparent: true, blending: THREE.AdditiveBlending });
        const stars = new THREE.Points(starFieldGeom, starMaterial);
        scene.add(stars);
        
        const linesCount = 12;
        const lineGroup = new THREE.Group();
        for (let i = 0; i < linesCount; i++) {
            const points = [];
            for (let j = -3; j <= 3; j += 0.4) {
                points.push(new THREE.Vector3(j * 0.8, Math.sin(j * 1.2 + i) * 0.5, (i - 6) * 0.8));
            }
            const lineGeo = new THREE.BufferGeometry().setFromPoints(points);
            const lineMat = new THREE.LineBasicMaterial({ color: 0xd4966e, transparent: true, opacity: 0.25 });
            const lineObj = new THREE.Line(lineGeo, lineMat);
            lineObj.position.y = -1 + i * 0.5;
            lineObj.position.z = -4;
            lineGroup.add(lineObj);
        }
        scene.add(lineGroup);
        
        let time = 0;
        
        function animate() {
            requestAnimationFrame(animate);
            time += 0.012;
            
            ringLeft.rotation.z += 0.005;
            ringLeft.rotation.x += 0.003;
            ringRight.rotation.z -= 0.004;
            ringRight.rotation.x += 0.002;
            
            cubeGroup.children.forEach((cube, idx) => {
                cube.position.y += Math.sin(time * 1.2 + idx) * 0.003;
                cube.rotation.x += 0.01;
                cube.rotation.z += 0.008;
            });
            
            orbGroup.children.forEach((orb, i) => {
                orb.position.x += Math.sin(time * 0.7 + i) * 0.003;
                orb.position.y += Math.cos(time * 0.9 + i) * 0.002;
                orb.position.z += Math.sin(time * 0.5 + i * 0.7) * 0.002;
            });
            
            particleSystem.rotation.y += 0.0005;
            stars.rotation.x += 0.0003;
            stars.rotation.y += 0.0002;
            lineGroup.rotation.z = Math.sin(time * 0.2) * 0.05;
            lineGroup.rotation.x = Math.sin(time * 0.15) * 0.03;
            
            camera.position.x += (0 - camera.position.x) * 0.02;
            camera.position.y += (Math.sin(time * 0.2) * 0.05 - camera.position.y) * 0.03;
            camera.lookAt(0, 1.2, 0);
            
            renderer.render(scene, camera);
        }
        
        animate();
        
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
    </script>

    <script>
        async function loadBestSellers() {
            const container = document.getElementById('bestsellers-list');
            if (!container) return;
            
            const mockBestsellers = [
                { title: "The Midnight Library", author: "Matt Haig" },
                { title: "Tomorrow, and Tomorrow, and Tomorrow", author: "Gabrielle Zevin" },
                { title: "Lessons in Chemistry", author: "Bonnie Garmus" },
                { title: "The Covenant of Water", author: "Abraham Verghese" },
                { title: "Fourth Wing", author: "Rebecca Yarros" },
                { title: "Demon Copperhead", author: "Barbara Kingsolver" }
            ];
            
            try {
                await new Promise(resolve => setTimeout(resolve, 400));
                const books = mockBestsellers;
                
                if (books && books.length) {
                    container.innerHTML = '';
                    books.forEach(book => {
                        const bookDiv = document.createElement('div');
                        bookDiv.className = 'book-item';
                        bookDiv.innerHTML = `<strong>📖 ${book.title}</strong><span> by ${book.author}</span>`;
                        container.appendChild(bookDiv);
                    });
                } else {
                    container.innerHTML = '<div class="loading-message">✨ no best sellers at the moment ✨</div>';
                }
            } catch (error) {
                container.innerHTML = '<div class="loading-message">📚 unable to load collection</div>';
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            loadBestSellers();
        });
        
        window.loadBestSellers = loadBestSellers;
    </script>
</body>
=======
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">

<div id="loginHeroContainer" class="lh-wrap">

    {{-- Ambient background layers --}}
    <div class="lh-bg">
        <div class="lh-bg__orb lh-bg__orb--1"></div>
        <div class="lh-bg__orb lh-bg__orb--2"></div>
        <div class="lh-bg__orb lh-bg__orb--3"></div>
        <div class="lh-bg__grain"></div>
        <div class="lh-bg__lines"></div>
    </div>

    <div class="lh-inner">

        {{-- LEFT: 3D Bookshelf Scene --}}
        <div class="lh-scene" aria-hidden="true">
            <div class="lh-particles">
                <span class="lh-p lh-p--1"></span><span class="lh-p lh-p--2"></span>
                <span class="lh-p lh-p--3"></span><span class="lh-p lh-p--4"></span>
                <span class="lh-p lh-p--5"></span><span class="lh-p lh-p--6"></span>
            </div>

            <div class="lh-stage">
                <div class="lh-rotator">

                    <div class="lh-book lh-book--1">
                        <div class="lh-book__left"></div><div class="lh-book__right"></div>
                        <div class="lh-book__top"></div><div class="lh-book__bottom"></div>
                        <div class="lh-book__front"><div class="lh-book__front-inner">
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__label">Noli Me<br>Tángere</span>
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__author">José Rizal</span>
                        </div></div>
                        <div class="lh-book__back"></div>
                    </div>

                    <div class="lh-book lh-book--2">
                        <div class="lh-book__left"></div><div class="lh-book__right"></div>
                        <div class="lh-book__top"></div><div class="lh-book__bottom"></div>
                        <div class="lh-book__front"><div class="lh-book__front-inner">
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__label">El<br>Filibusterismo</span>
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__author">José Rizal</span>
                        </div></div>
                        <div class="lh-book__back"></div>
                    </div>

                    <div class="lh-book lh-book--3">
                        <div class="lh-book__left"></div><div class="lh-book__right"></div>
                        <div class="lh-book__top"></div><div class="lh-book__bottom"></div>
                        <div class="lh-book__front"><div class="lh-book__front-inner">
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__label">Florante<br>at Laura</span>
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__author">Francisco Balagtas</span>
                        </div></div>
                        <div class="lh-book__back"></div>
                    </div>

                    <div class="lh-book lh-book--4">
                        <div class="lh-book__left"></div><div class="lh-book__right"></div>
                        <div class="lh-book__top"></div><div class="lh-book__bottom"></div>
                        <div class="lh-book__front"><div class="lh-book__front-inner">
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__label">Ibong<br>Adarna</span>
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__author">Anonymous</span>
                        </div></div>
                        <div class="lh-book__back"></div>
                    </div>

                    <div class="lh-book lh-book--5">
                        <div class="lh-book__left"></div><div class="lh-book__right"></div>
                        <div class="lh-book__top"></div><div class="lh-book__bottom"></div>
                        <div class="lh-book__front"><div class="lh-book__front-inner">
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__label">Banaag<br>at Sikat</span>
                            <div class="lh-book__deco-line"></div>
                            <span class="lh-book__author">Lope K. Santos</span>
                        </div></div>
                        <div class="lh-book__back"></div>
                    </div>

                </div>

                <div class="lh-shelf">
                    <div class="lh-shelf__surface"></div>
                    <div class="lh-shelf__edge"></div>
                    <div class="lh-shelf__reflection"></div>
                </div>
                <div class="lh-drop-shadow"></div>
            </div>

            <div class="lh-scene__label"><span>Philippine Literary Classics</span></div>
        </div>

        {{-- RIGHT: Auth Panel --}}
        <div class="lh-panel">
            <div class="lh-panel__eyebrow">
                <span class="lh-pulse"></span>
                <span>Pahina Admin Portal</span>
            </div>

            <h1 class="lh-panel__heading">
                Your bookstore,<br>
                <em>brilliantly</em><br>
                managed.
            </h1>

            <p class="lh-panel__sub">
                Oversee every order, track every shelf, and grow Pahina — all from one elegant dashboard.
            </p>

            <div class="lh-metrics">
                <div class="lh-metric">
                    <div class="lh-metric__value">∞</div>
                    <div class="lh-metric__label">Real-time stock</div>
                </div>
                <div class="lh-metric__sep"></div>
                <div class="lh-metric">
                    <div class="lh-metric__value">24/7</div>
                    <div class="lh-metric__label">Order tracking</div>
                </div>
                <div class="lh-metric__sep"></div>
                <div class="lh-metric">
                    <div class="lh-metric__value">360°</div>
                    <div class="lh-metric__label">Sales reports</div>
                </div>
            </div>

            <button class="lh-cta" onclick="openLoginModal()">
                <span class="lh-cta__bg"></span>
                <span class="lh-cta__content">
                    <i class="fas fa-user-shield lh-cta__icon"></i>
                    <span class="lh-cta__text">Login as Admin</span>
                    <span class="lh-cta__arrow">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </span>
            </button>

            <div class="lh-hint">
                <svg width="11" height="13" viewBox="0 0 11 13" fill="none">
                    <rect x="1" y="5" width="9" height="7.5" rx="1.5" stroke="currentColor" stroke-width="1.2"/>
                    <path d="M3.5 5V3.5a2 2 0 0 1 4 0V5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
                Restricted to authorized administrators only
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --c-ink:        #1A0F08;
    --c-bark:       #3D1F0D;
    --c-mahogany:   #613D28;
    --c-saddle:     #8B5E3C;
    --c-tan:        #AE7F62;
    --c-cream:      #F7EDD8;
    --c-parchment:  #FAEFD8;
    --c-gold:       #C8974A;
    --c-gold-light: #E8B86D;
    --ff-display:   'Playfair Display', Georgia, serif;
    --ff-body:      'Cormorant Garamond', Georgia, serif;
    --depth:        28px;
}

.lh-wrap {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90vh;
    overflow: hidden;
    background: #140A04;
    font-family: var(--ff-body);
}

.lh-bg { position: absolute; inset: 0; pointer-events: none; z-index: 0; }

.lh-bg__orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
}
.lh-bg__orb--1 {
    width: 600px; height: 600px; top: -100px; left: -80px;
    background: radial-gradient(circle, rgba(97,61,40,0.55) 0%, transparent 70%);
    animation: orbDrift1 14s ease-in-out infinite alternate;
}
.lh-bg__orb--2 {
    width: 500px; height: 500px; bottom: -120px; right: -60px;
    background: radial-gradient(circle, rgba(61,31,13,0.6) 0%, transparent 70%);
    animation: orbDrift2 18s ease-in-out infinite alternate;
}
.lh-bg__orb--3 {
    width: 350px; height: 350px; top: 40%; left: 45%;
    background: radial-gradient(circle, rgba(200,151,74,0.12) 0%, transparent 70%);
    animation: orbDrift3 10s ease-in-out infinite alternate;
}

@keyframes orbDrift1 { from{transform:translate(0,0)} to{transform:translate(40px,30px)} }
@keyframes orbDrift2 { from{transform:translate(0,0)} to{transform:translate(-30px,-40px)} }
@keyframes orbDrift3 { from{transform:translate(0,0) scale(1)} to{transform:translate(20px,-20px) scale(1.2)} }

.lh-bg__grain {
    position: absolute; inset: 0; opacity: 0.4;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
}
.lh-bg__lines {
    position: absolute; inset: 0;
    background-image: repeating-linear-gradient(90deg, transparent, transparent 80px, rgba(255,255,255,0.012) 80px, rgba(255,255,255,0.012) 81px);
}

.lh-inner {
    position: relative; z-index: 1;
    display: flex; align-items: center; gap: 6rem;
    max-width: 1100px; width: 100%; padding: 4rem 2.5rem;
    animation: fadeUp 1s cubic-bezier(0.22,1,0.36,1) both;
}
@keyframes fadeUp { from{opacity:0;transform:translateY(32px)} to{opacity:1;transform:translateY(0)} }

/* ── Scene ── */
.lh-scene { flex-shrink: 0; display:flex; flex-direction:column; align-items:center; gap:1.5rem; position:relative; }

.lh-particles { position:absolute; inset:0; pointer-events:none; }
.lh-p {
    position: absolute; border-radius: 50%;
    background: var(--c-gold); opacity: 0;
    animation: particleFloat linear infinite;
}
.lh-p--1{width:3px;height:3px;left:15%;top:70%;animation-duration:8s;animation-delay:0s}
.lh-p--2{width:2px;height:2px;left:30%;top:80%;animation-duration:11s;animation-delay:2s}
.lh-p--3{width:4px;height:4px;left:55%;top:75%;animation-duration:9s;animation-delay:4s}
.lh-p--4{width:2px;height:2px;left:70%;top:85%;animation-duration:13s;animation-delay:1s}
.lh-p--5{width:3px;height:3px;left:80%;top:60%;animation-duration:7s;animation-delay:3s}
.lh-p--6{width:2px;height:2px;left:10%;top:55%;animation-duration:10s;animation-delay:5s}

@keyframes particleFloat {
    0%{opacity:0;transform:translateY(0) scale(0)}
    20%{opacity:0.6}
    80%{opacity:0.3}
    100%{opacity:0;transform:translateY(-120px) scale(1.5)}
}

.lh-stage { position:relative; width:340px; height:380px; perspective:1100px; }

.lh-rotator {
    position:relative; width:100%; height:100%;
    transform-style:preserve-3d;
    animation: sceneFloat 8s ease-in-out infinite;
}
@keyframes sceneFloat {
    0%,100%{transform:rotateX(10deg) rotateY(-22deg)}
    50%{transform:rotateX(10deg) rotateY(-22deg) translateY(-18px)}
}

/* Book base styles */
.lh-book { position:absolute; transform-style:preserve-3d; }
.lh-book__front,.lh-book__back,.lh-book__left,.lh-book__right,.lh-book__top,.lh-book__bottom {
    position:absolute; backface-visibility:hidden;
}
.lh-book__front { display:flex; align-items:center; justify-content:center; }
.lh-book__front-inner { display:flex; flex-direction:column; align-items:center; gap:6px; padding:12px 8px; width:100%; }
.lh-book__deco-line { width:60%; height:1px; background:rgba(252,205,172,0.4); }
.lh-book__label {
    font-family:var(--ff-display); font-size:0.62rem; font-weight:700;
    color:rgba(252,205,172,0.92); text-align:center; line-height:1.4;
    letter-spacing:0.04em; text-shadow:0 1px 4px rgba(0,0,0,0.5);
}
.lh-book__author {
    font-family:var(--ff-body); font-size:0.5rem; font-weight:300; font-style:italic;
    color:rgba(252,205,172,0.5); text-align:center; letter-spacing:0.06em;
}

/* Book 1 — deep crimson, tallest */
.lh-book--1 { width:100px; height:190px; bottom:38px; left:115px; z-index:5; }
.lh-book--1 .lh-book__front  { width:100px; height:190px; background:linear-gradient(175deg,#8B2020 0%,#4A0E0E 60%,#2A0808 100%); transform:translateZ(14px); border-radius:1px 4px 4px 1px; box-shadow:inset -4px 0 12px rgba(0,0,0,0.4),inset 4px 0 8px rgba(255,255,255,0.04); }
.lh-book--1 .lh-book__back   { width:100px; height:190px; background:#1A0505; transform:rotateY(180deg) translateZ(14px); }
.lh-book--1 .lh-book__left   { width:28px; height:190px; background:linear-gradient(90deg,#3A0C0C,#5A1515); transform:rotateY(-90deg) translateZ(0) translateX(-28px); }
.lh-book--1 .lh-book__right  { width:28px; height:190px; background:linear-gradient(90deg,#5A1515,#3A0C0C); transform:rotateY(90deg) translateZ(100px); }
.lh-book--1 .lh-book__top    { width:100px; height:28px; background:linear-gradient(180deg,#9A2525,#6A1818); transform:rotateX(90deg) translateZ(0) translateY(-28px); }
.lh-book--1 .lh-book__bottom { width:100px; height:28px; background:#280A0A; transform:rotateX(-90deg) translateZ(190px); }

/* Book 2 — warm amber */
.lh-book--2 { width:88px; height:165px; bottom:38px; left:28px; z-index:4; transform:rotateZ(-2deg); }
.lh-book--2 .lh-book__front  { width:88px; height:165px; background:linear-gradient(175deg,#C8974A 0%,#8B5A1C 60%,#5A3A0C 100%); transform:translateZ(14px); border-radius:1px 4px 4px 1px; box-shadow:inset -4px 0 12px rgba(0,0,0,0.35),inset 4px 0 6px rgba(255,255,255,0.06); }
.lh-book--2 .lh-book__back   { width:88px; height:165px; background:#2A1A06; transform:rotateY(180deg) translateZ(14px); }
.lh-book--2 .lh-book__left   { width:28px; height:165px; background:linear-gradient(90deg,#4A2C0A,#7A4A1A); transform:rotateY(-90deg) translateZ(0) translateX(-28px); }
.lh-book--2 .lh-book__right  { width:28px; height:165px; background:linear-gradient(90deg,#7A4A1A,#4A2C0A); transform:rotateY(90deg) translateZ(88px); }
.lh-book--2 .lh-book__top    { width:88px; height:28px; background:linear-gradient(180deg,#D4A460,#9A6A25); transform:rotateX(90deg) translateZ(0) translateY(-28px); }
.lh-book--2 .lh-book__bottom { width:88px; height:28px; background:#1E1005; transform:rotateX(-90deg) translateZ(165px); }

/* Book 3 — forest green */
.lh-book--3 { width:92px; height:178px; bottom:38px; left:207px; z-index:3; transform:rotateZ(3deg); }
.lh-book--3 .lh-book__front  { width:92px; height:178px; background:linear-gradient(175deg,#2A5C3A 0%,#163D25 60%,#0A2016 100%); transform:translateZ(14px); border-radius:1px 4px 4px 1px; box-shadow:inset -4px 0 10px rgba(0,0,0,0.4),inset 4px 0 6px rgba(255,255,255,0.03); }
.lh-book--3 .lh-book__back   { width:92px; height:178px; background:#081A0E; transform:rotateY(180deg) translateZ(14px); }
.lh-book--3 .lh-book__left   { width:28px; height:178px; background:linear-gradient(90deg,#0C2818,#1E4A2A); transform:rotateY(-90deg) translateZ(0) translateX(-28px); }
.lh-book--3 .lh-book__right  { width:28px; height:178px; background:linear-gradient(90deg,#1E4A2A,#0C2818); transform:rotateY(90deg) translateZ(92px); }
.lh-book--3 .lh-book__top    { width:92px; height:28px; background:linear-gradient(180deg,#346A46,#1E4A2A); transform:rotateX(90deg) translateZ(0) translateY(-28px); }
.lh-book--3 .lh-book__bottom { width:92px; height:28px; background:#06140A; transform:rotateX(-90deg) translateZ(178px); }

/* Book 4 — navy indigo */
.lh-book--4 { width:80px; height:148px; bottom:38px; left:-36px; z-index:2; transform:rotateZ(-4deg); }
.lh-book--4 .lh-book__front  { width:80px; height:148px; background:linear-gradient(175deg,#1E2E5C 0%,#101A3A 60%,#080E20 100%); transform:translateZ(14px); border-radius:1px 4px 4px 1px; box-shadow:inset -4px 0 10px rgba(0,0,0,0.4); }
.lh-book--4 .lh-book__back   { width:80px; height:148px; background:#060A14; transform:rotateY(180deg) translateZ(14px); }
.lh-book--4 .lh-book__left   { width:28px; height:148px; background:linear-gradient(90deg,#0A1228,#16204A); transform:rotateY(-90deg) translateZ(0) translateX(-28px); }
.lh-book--4 .lh-book__right  { width:28px; height:148px; background:linear-gradient(90deg,#16204A,#0A1228); transform:rotateY(90deg) translateZ(80px); }
.lh-book--4 .lh-book__top    { width:80px; height:28px; background:linear-gradient(180deg,#263870,#1A2858); transform:rotateX(90deg) translateZ(0) translateY(-28px); }
.lh-book--4 .lh-book__bottom { width:80px; height:28px; background:#04080F; transform:rotateX(-90deg) translateZ(148px); }

/* Book 5 — deep plum */
.lh-book--5 { width:85px; height:155px; bottom:38px; left:290px; z-index:1; transform:rotateZ(6deg); }
.lh-book--5 .lh-book__front  { width:85px; height:155px; background:linear-gradient(175deg,#4A1E5A 0%,#2C1038 60%,#180820 100%); transform:translateZ(14px); border-radius:1px 4px 4px 1px; box-shadow:inset -4px 0 10px rgba(0,0,0,0.45); }
.lh-book--5 .lh-book__back   { width:85px; height:155px; background:#100618; transform:rotateY(180deg) translateZ(14px); }
.lh-book--5 .lh-book__left   { width:28px; height:155px; background:linear-gradient(90deg,#1E0C28,#3A1848); transform:rotateY(-90deg) translateZ(0) translateX(-28px); }
.lh-book--5 .lh-book__right  { width:28px; height:155px; background:linear-gradient(90deg,#3A1848,#1E0C28); transform:rotateY(90deg) translateZ(85px); }
.lh-book--5 .lh-book__top    { width:85px; height:28px; background:linear-gradient(180deg,#5C2870,#3E1A50); transform:rotateX(90deg) translateZ(0) translateY(-28px); }
.lh-book--5 .lh-book__bottom { width:85px; height:28px; background:#0C0414; transform:rotateX(-90deg) translateZ(155px); }

/* Shelf */
.lh-shelf { position:absolute; bottom:0; left:-60px; right:-60px; transform-style:preserve-3d; }
.lh-shelf__surface { height:38px; background:linear-gradient(180deg,#3D1F0D 0%,#2A1208 100%); border-radius:3px; box-shadow:inset 0 2px 8px rgba(255,255,255,0.06),inset 0 -2px 6px rgba(0,0,0,0.5); }
.lh-shelf__edge { height:8px; background:linear-gradient(180deg,#2A1208,#1A0B06); }
.lh-shelf__reflection { position:absolute; top:0; left:10%; right:10%; height:1px; background:linear-gradient(90deg,transparent,rgba(200,151,74,0.3),transparent); }
.lh-drop-shadow { position:absolute; bottom:-24px; left:0; right:0; height:24px; background:radial-gradient(ellipse 60% 40% at 50% 0%,rgba(0,0,0,0.6),transparent); }

.lh-scene__label { margin-top:1.2rem; }
.lh-scene__label span {
    font-family:var(--ff-body); font-size:0.72rem; font-weight:300; font-style:italic;
    letter-spacing:0.18em; text-transform:uppercase; color:rgba(174,127,98,0.5);
}

/* ── Panel ── */
.lh-panel {
    flex:1; min-width:0;
    padding:3rem 2.5rem;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(174,127,98,0.15);
    border-radius:20px;
    backdrop-filter:blur(20px);
    position:relative; overflow:hidden;
    animation:panelIn 1s cubic-bezier(0.22,1,0.36,1) 0.2s both;
}
@keyframes panelIn { from{opacity:0;transform:translateX(30px)} to{opacity:1;transform:translateX(0)} }

.lh-panel::before { content:''; position:absolute; top:0; left:0; right:0; height:1px; background:linear-gradient(90deg,transparent,rgba(200,151,74,0.5),transparent); }
.lh-panel::after  { content:''; position:absolute; top:-60px; right:-60px; width:200px; height:200px; background:radial-gradient(circle,rgba(200,151,74,0.08),transparent 60%); pointer-events:none; }

.lh-panel__eyebrow {
    display:inline-flex; align-items:center; gap:8px;
    background:rgba(200,151,74,0.1); border:1px solid rgba(200,151,74,0.25);
    color:var(--c-gold-light); font-family:var(--ff-body);
    font-size:0.72rem; font-weight:400; letter-spacing:0.14em;
    text-transform:uppercase; padding:5px 14px 5px 10px;
    border-radius:999px; margin-bottom:1.8rem;
}

.lh-pulse { width:7px; height:7px; border-radius:50%; background:var(--c-gold); box-shadow:0 0 8px var(--c-gold); animation:pulseGlow 2.5s ease-in-out infinite; }
@keyframes pulseGlow { 0%,100%{opacity:1;box-shadow:0 0 8px var(--c-gold)} 50%{opacity:0.4;box-shadow:0 0 2px var(--c-gold)} }

.lh-panel__heading {
    font-family:var(--ff-display); font-size:clamp(2.2rem,3.5vw,3.2rem);
    font-weight:900; color:var(--c-parchment);
    line-height:1.1; letter-spacing:-0.02em; margin:0 0 1.2rem;
}
.lh-panel__heading em {
    font-style:italic; color:var(--c-gold-light); font-weight:400; position:relative;
}
.lh-panel__heading em::after {
    content:''; position:absolute; bottom:2px; left:0; right:0; height:1px;
    background:linear-gradient(90deg,var(--c-gold),transparent);
}

.lh-panel__sub { font-family:var(--ff-body); font-size:1.05rem; font-weight:300; color:rgba(247,237,216,0.6); line-height:1.75; margin:0 0 2.2rem; max-width:380px; }

.lh-metrics { display:flex; align-items:center; gap:1.5rem; margin-bottom:2.5rem; padding:1.2rem 1.5rem; background:rgba(0,0,0,0.2); border:1px solid rgba(174,127,98,0.12); border-radius:12px; }
.lh-metric  { display:flex; flex-direction:column; align-items:center; gap:3px; flex:1; }
.lh-metric__value { font-family:var(--ff-display); font-size:1.5rem; font-weight:700; color:var(--c-gold-light); line-height:1; }
.lh-metric__label { font-family:var(--ff-body); font-size:0.7rem; font-weight:300; letter-spacing:0.06em; text-transform:uppercase; color:rgba(174,127,98,0.65); text-align:center; }
.lh-metric__sep   { width:1px; height:36px; background:rgba(174,127,98,0.2); }

.lh-cta {
    position:relative; display:inline-flex; align-items:center;
    border:none; padding:0; border-radius:12px; cursor:pointer; overflow:hidden;
    margin-bottom:1.2rem;
    transition:transform 0.2s ease,box-shadow 0.2s ease;
    box-shadow:0 4px 24px rgba(200,151,74,0.2),0 0 0 1px rgba(200,151,74,0.2);
}
.lh-cta:hover { transform:translateY(-2px); box-shadow:0 8px 36px rgba(200,151,74,0.35),0 0 0 1px rgba(200,151,74,0.35); }
.lh-cta:active { transform:translateY(0); }

.lh-cta__bg { position:absolute; inset:0; background:linear-gradient(135deg,#8B5E1C 0%,#5A3A0C 50%,#3A2008 100%); transition:opacity 0.2s; }
.lh-cta:hover .lh-cta__bg { background:linear-gradient(135deg,#A07030 0%,#6A4818 50%,#4A2A12 100%); }

.lh-cta__content { position:relative; z-index:1; display:flex; align-items:center; gap:10px; padding:15px 28px; color:var(--c-gold-light); font-family:var(--ff-body); font-size:1rem; font-weight:600; letter-spacing:0.04em; }
.lh-cta__icon  { font-size:0.9rem; opacity:0.85; }
.lh-cta__arrow { display:flex; align-items:center; transition:transform 0.2s ease; margin-left:4px; }
.lh-cta:hover .lh-cta__arrow { transform:translateX(5px); }

.lh-hint { display:flex; align-items:center; gap:6px; font-family:var(--ff-body); font-size:0.75rem; font-weight:300; color:rgba(174,127,98,0.45); letter-spacing:0.02em; }

@media (max-width:900px) {
    .lh-inner { flex-direction:column; gap:3.5rem; padding:3rem 1.5rem; }
    .lh-panel { padding:2rem 1.5rem; }
    .lh-rotator { animation:sceneFloatMobile 8s ease-in-out infinite; }
    @keyframes sceneFloatMobile {
        0%,100%{transform:rotateX(8deg) rotateY(-14deg)}
        50%{transform:rotateX(8deg) rotateY(-14deg) translateY(-12px)}
    }
}
@media (max-width:480px) {
    .lh-stage { width:280px; height:300px; }
    .lh-panel__heading { font-size:2rem; }
    .lh-metrics { gap:1rem; padding:1rem; }
}
</style>
>>>>>>> f522be290f42bb1825caa421702f3fe59de057d1
