<?php include 'includes/header.php'; ?>

<main class="info-page">
    <section class="info-hero about-hero">
        <div class="info-hero-content">
            <p class="eyebrow">Kitchener comic shop</p>
            <h1>The Comic Shop</h1>
            <p>Curated comics, graphic novels, and collector picks for readers who want their next story to hit hard.</p>
            <a href="shop.php" class="vbutton">Browse Comics</a>
        </div>
    </section>

    <section class="info-section mid80">
        <div class="split-layout">
            <div>
                <p class="eyebrow">Since 1998</p>
                <h2>Built by comic readers, for comic readers.</h2>
            </div>
            <div class="copy-stack">
                <p>The Comic Shop started as a small Kitchener storefront with one job: help readers find stories worth keeping. Today, the shop brings that same collector-first taste online.</p>
                <p>We focus on superhero staples, graphic novels, indie releases, and standout issues that make a shelf feel personal.</p>
            </div>
        </div>
    </section>

    <section class="info-band">
        <div class="mid80 value-grid">
            <article>
                <span>01</span>
                <h3>Curated Collection</h3>
                <p>Every title earns its place, from iconic arcs to new-reader friendly picks.</p>
            </article>
            <article>
                <span>02</span>
                <h3>Collector Friendly</h3>
                <p>Browse clear categories, compare stories quickly, and keep your cart simple.</p>
            </article>
            <article>
                <span>03</span>
                <h3>Community Focused</h3>
                <p>Made for fans who care about the stories, artists, characters, and conversations.</p>
            </article>
        </div>
    </section>

    <section class="info-section mid80">
        <div class="mission-panel">
            <h2>Our Mission</h2>
            <p>Make comic discovery easier, friendlier, and more exciting, whether you are chasing a favorite hero or starting your first pull list.</p>
        </div>
    </section>

    <section class="margin30">
        <div class="newsletter">
            <h2>Join the Comic Book Shop Community!</h2>
            <p>Get the latest news, releases, and exclusive content delivered right to your inbox.</p>
            <form action="subscribe.php" method="post">
                <input type="email" name="email" placeholder="Log in to use your account email" value="<?= htmlspecialchars($newsletterEmail) ?>" <?= $newsletterEmail ? 'readonly' : '' ?> required>
                <input type="submit" value="Subscribe">
            </form>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>
