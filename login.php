<?php
include 'includes/session_start.php';
include_once 'includes/functions.php';
redirectIfLoggedIn();
?>
<?php include 'includes/header.php'; ?>

<main class="login-main">
    <section class="login-section">
        <div class="login-container">
            <h2>Login to Your Account</h2><br />
            <form id="loginForm" class="login-form">
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" autocomplete="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                </div>
                <button type="submit" class="button">Login</button>
                <p class="signup-prompt">Don't have an account? <a href="signup.php" class="signup-link">Sign up</a></p>
            </form>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value.trim();

    // Basic validation for empty fields
    if (email === '') {
        alert("Please enter your email address.");
        return; // Stop the form submission
    }

    if (password === '') {
        alert("Please enter your password.");
        return; // Stop the form submission
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE) {
            if (this.status === 200) {
                alert("Login successful!");
                
                window.location.href = 'index.php';
            } else {
                alert("Login Failed. Error: " + this.statusText);
            }
        }
    };

    xhttp.open("POST", "api/login", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.setRequestHeader("X-CSRF-Token", "<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>");
    xhttp.send(JSON.stringify({
        email: email,
        password: password
    }));
});
</script>
