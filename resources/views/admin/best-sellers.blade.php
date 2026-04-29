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