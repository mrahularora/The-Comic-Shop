<?php
include 'session_start.php';
include_once 'database.php';

?>
<?php include 'includes/header.php'; ?>

<main>
    <section class="mid80 marginbottom30">
        <div class="margin70">
            <h1 class="center">Contact Us</h1>
            <p class="center">We'd love to hear from you! Whether you have a question, need assistance, or just want to say hi, feel free to reach out to us through any of the following methods:</p><br /><br />
            
        </div>
  
        <div class="faq">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-item">
                <h3>What is your return policy?</h3>
                <p>We offer a 30-day return policy on most items. Please visit our <a href="returns-policy.php">Returns Policy</a> page for more details.</p>
            </div>

            <div class="faq-item">
                <h3>How can I track my order?</h3>
                <p>Once your order has shipped, you will receive a tracking number via email. You can also track your order on our <a href="order-tracking.php">Order Tracking</a> page.</p>
            </div>

            <div class="faq-item">
                <h3>Do you ship internationally?</h3>
                <p>Yes, we offer international shipping. Please check our <a href="shipping-info.php">Shipping Information</a> page for details on shipping rates and delivery times.</p>
            </div>
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

