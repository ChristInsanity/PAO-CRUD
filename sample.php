<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pacific Northwest X-Ray Inc.</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

html {
    scroll-behavior: smooth;
    scroll-snap-type: y mandatory;
}

.section {
    min-height: 100vh;
    padding: 100px 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    scroll-snap-align: start;
}

/* ===== NAVBAR ===== */
.header {
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 100;
    padding: 15px 60px;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    padding: 12px 25px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: bold;
    color: #0a2463;
}

.logo-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: radial-gradient(circle, #3e92cc, #0a2463);
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-circle img {
    width: 70%;
}

.nav {
    display: flex;
    gap: 25px;
}

.nav a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    position: relative;
}

.nav a::after {
    content: "";
    width: 0%;
    height: 2px;
    background: #0a2463;
    position: absolute;
    bottom: -5px;
    left: 0;
    transition: 0.3s;
}

.nav a:hover::after {
    width: 100%;
}

/* ===== HERO ===== */
.hero {
    position: relative;
    color: white;
    text-align: center;
    flex-direction: column;

    /* UPDATED BACKGROUND */
    background: url('istockphoto-1543495721-612x612.jpg') center/cover no-repeat fixed;
}

.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(10, 36, 99, 0.70);
}

.hero > * {
    position: relative;
    z-index: 1;
}

.hero h1 {
    font-size: 56px;
    max-width: 800px;
}

.hero p {
    margin: 20px 0;
    font-size: 18px;
    max-width: 600px;
}

.hero-buttons {
    margin-top: 20px;
}

.btn-primary {
    padding: 14px 30px;
    background: #3e92cc;
    color: white;
    border-radius: 30px;
    border: none;
    margin-right: 10px;
    cursor: pointer;
}

.btn-secondary {
    padding: 14px 30px;
    border-radius: 30px;
    border: 2px solid white;
    background: transparent;
    color: white;
    cursor: pointer;
}

/* ===== TITLES ===== */
.title {
    font-size: 38px;
    color: #0a2463;
    margin-bottom: 40px;
    text-align: center;
}

/* ===== GRID ===== */
.grid {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
}

/* FIXED TRUST GRID (3 columns aligned) */
.trust-grid {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.tech-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

/* ===== CARD ===== */
.card {
    background: white;
    padding: 35px 25px;
    border-radius: 14px;
    text-align: center;
    transition: 0.3s;
    border: 2px solid rgba(10, 36, 99, 0.15);
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
}

.card:hover {
    transform: translateY(-8px);
    border-color: rgba(62, 146, 204, 0.8);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.card i {
    font-size: 38px;
    color: #3e92cc;
    margin-bottom: 15px;
}

.card h3 {
    margin-bottom: 10px;
    font-size: 20px;
}

.card p {
    color: #555;
    font-size: 14px;
}

/* ===== FOOTER ===== */
.footer-section {
    background: #f8fafc;
    padding: 80px 80px 30px;
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 40px;
    margin-bottom: 50px;
}

.footer-grid h3 {
    color: #0a2463;
    margin-bottom: 15px;
    font-size: 16px;
    letter-spacing: 1px;
}

.footer-grid p,
.footer-grid a {
    color: #5b6b88;
    font-size: 14px;
    margin-bottom: 8px;
    text-decoration: none;
    display: block;
}

.footer-bottom {
    border-top: 1px solid #e5eaf2;
    padding-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.fda {
    background: #e7f6ea;
    color: #0a7a2f;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){
    .grid,
    .tech-grid,
    .trust-grid,
    .footer-grid {
        grid-template-columns: 1fr;
    }

    .hero h1 {
        font-size: 40px;
    }

    .footer-bottom {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="header">
    <div class="navbar">
        <div class="logo">
            <div class="logo-circle">
                <img src="pnwx_ani.gif">
            </div>
            Pacific Northwest X-Ray Inc.
        </div>

        <div class="nav">
            <a href="#hero">Home</a>
            <a href="#categories">Categories</a>
            <a href="#trust">Support</a>
            <a href="#tech">Technology</a>
        </div>
    </div>
</div>

<!-- HERO -->
<div id="hero" class="section hero">
    <h1>Precision Imaging Solutions for Every Clinical Need</h1>
    <p>Trusted supplier of diagnostic equipment and radiation safety solutions for modern healthcare facilities.</p>

    <div class="hero-buttons">
        <button class="btn-primary">Browse Catalog</button>
        <button class="btn-secondary">Request Quote</button>
    </div>
</div>

<!-- CATEGORIES -->
<div id="categories" class="section">
    <div style="width:100%;">
        <h2 class="title">Equipment Categories</h2>

        <div class="grid">
            <div class="card">
                <i class="fa-solid fa-user-shield"></i>
                <h3>Lead Apparel</h3>
                <p>Protective aprons and safety gear.</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-flask"></i>
                <h3>Phantoms & Tools</h3>
                <p>Calibration instruments.</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-shield-halved"></i>
                <h3>Radiation Protection</h3>
                <p>Shielding systems.</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-screwdriver-wrench"></i>
                <h3>X-Ray Accessories</h3>
                <p>Essential tools.</p>
            </div>
        </div>
    </div>
</div>

<!-- TRUST -->
<div id="trust" class="section">
    <div style="width:100%;">
        <h2 class="title">Why Choose Us</h2>

        <!-- FIXED GRID -->
        <div class="trust-grid">
            <div class="card">
                <i class="fa-solid fa-user-doctor"></i>
                <h3>Expert Support</h3>
                <p>Professional guidance from specialists.</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-truck-fast"></i>
                <h3>Fast Shipping</h3>
                <p>Reliable delivery nationwide.</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-certificate"></i>
                <h3>Quality Guaranteed</h3>
                <p>Certified medical-grade products.</p>
            </div>
        </div>
    </div>
</div>

<!-- TECHNOLOGY -->
<div id="tech" class="section">
    <div style="width:100%;">
        <h2 class="title">Clinical Technology</h2>

        <div class="tech-grid">
            <div class="card">
                <i class="fa-solid fa-desktop"></i>
                <h3>PACS Systems</h3>
                <p>Advanced imaging management systems.</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-microchip"></i>
                <h3>DR Retrofit</h3>
                <p>Upgrade analog to digital systems.</p>
            </div>

            <div class="card">
                <i class="fa-solid fa-heart-pulse"></i>
                <h3>Dose Monitoring</h3>
                <p>Real-time safety tracking.</p>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<div class="footer-section">
    <div class="footer-grid">

        <div>
            <h3>PRECISION SCIENTIFIC</h3>
            <p>P.O. BOX 625</p>
            <p>GRESHAM, OR 97030</p>
            <p>USA</p>
        </div>

        <div>
            <h3>SUPPORT</h3>
            <a href="#">Contact Support</a>
            <a href="#">Service & Calibration</a>
            <a href="#">Resource Center</a>
        </div>

        <div>
            <h3>CORPORATE</h3>
            <a href="#">Sustainability</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>

        <div>
            <h3>CONTACT US</h3>
            <p>P: (503) 667-3000</p>
            <p>F: (503) 666-3001</p>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© 2024 PRECISION SCIENTIFIC. ALL RIGHTS RESERVED.</p>
        <div class="fda">FDA REGISTERED</div>
    </div>
</div>

</body>
</html>