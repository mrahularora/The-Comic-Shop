<?php
include 'includes/session_start.php';
include_once 'config/database.php';

?>
<?php include 'includes/header.php'; ?>

<main>

    <section class="mid80 marginbottom30">
    <div>
        <h1 class="margin70 center">About Us </h1><br />
        <p>Welcome to The Comic Shop, where everything comic book-related is single-handedly curated. Our love for storytelling and art inspires us to find and bring the best comic books from around the world right to you. Whether it be superheroes, manga, graphic novels, or indie comics, we have something for every reader.</p><br />
        
        <h2>Our Story</h2><br />
        <p>Established in 1998, The Comic Shop started with a small physical location in Kitchener with the vision of linking enthusiasts to their favorite comics. Over the years, we have grown online so that now, we can share this zeal for comics with the world.</p><br />
        
        <h2>Our Mission</h2><br />
        <p> The Comic Shop strongly believes in the power of stories to inspire, entertain, and bring people together. We imagine a platform in which comic book lovers may discover, explore, and be a part of the comic world. We promise to provide a broad diversity of titles, independence for the creators, and an excellent shopping experience.</p><br />
        
        <h2>Why Shop with Us?</h2><br />
                <ul>
                    <li><strong>Curated Collection:</strong> Out of an expansive repository, we cherry-pick each title to make sure we offer our best from the best.
                    <li><strong>Exclusive Releases:</strong> Gain access to limited edition comics, signed copies, and exclusive merchandise only available from our store.</li>
                    <li><strong>Community Focused:</strong> More than just a store, we are a community. Attend our events, connect with fellow fans, and stay up to date on the latest comic book news.</li>
                    <li><strong>Customer Satisfaction:</strong> Your satisfaction matters, and we understand the importance of it. Therefore, we offer super-fast shipping, hassle-free returns, and aggressive customer support to ensure the best shopping experience.</li>
        </ul>
    </div>
    </section>

    <section class="margin30">
        <div class="newsletter">
            <h2>Join the Comic Book Shop Community!</h2>
            <p>Get the latest news, releases, and exclusive content delivered right to your inbox.</p>
            <form action="subscribe" method="post">
                <input type="email" name="email" placeholder="Enter your email address" required>
                <input type="submit" value="Subscribe">
            </form>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>
