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