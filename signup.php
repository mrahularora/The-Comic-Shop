 <?php
    include 'session_start.php';
    include_once 'database.php';
    include_once 'classes.php';
    include_once 'functions.php';
    redirectIfLoggedIn();
    ?>
    <?php include 'includes/header.php'; ?>

    <main class="signup-main">
        <section class="signup-section">
            <div class="signup-container">
                <h2>Create a New Account</h2><br />
                <form id="signupForm" class="signup-form">
                    <div class="form-group">
                        <label for="name">Username:</label>
                        <input type="text" id="name" name="name" placeholder="Enter your username" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="text" id="email" name="email" placeholder="Enter your email address" />
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" />
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" />
                    </div>
                    <button type="submit" class="button">Sign Up</button>
                    <p class="login-prompt">Already have an account? <a href="login.php" class="login-link">Login</a></p>
                </form>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
   document.getElementById('signupForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value.trim();
    var confirmPassword = document.getElementById('confirm_password').value.trim();

    // Basic validation for empty fields
    if (name === '') {
        alert("Please enter your full name.");
        return; // Stop the form submission
    }

    if (email === '') {
        alert("Please enter your email address.");
        return; // Stop the form submission
    }

    // Email format validation
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return; // Stop the form submission
    }

    if (password === '') {
        alert("Please enter your password.");
        return; // Stop the form submission
    }

    // Password validation
    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!passwordPattern.test(password)) {
        alert("Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one number.");
        return; // Stop the form submission
    }

    if (confirmPassword === '') {
        alert("Please confirm your password.");
        return; // Stop the form submission
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return; // Stop the form submission
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE) {
            if (this.status === 201) {
                try {
                    var result = JSON.parse(this.responseText);
                    if (result.status === "ok") {
                        alert("Sign up successful! Please log in.");
                        window.location.href = 'login.php';
                    } else {
                        alert(result.message);
                    }
                } catch (e) {
                    alert("Error parsing server response.");
                }
            } else if (this.status === 409) {
                var result = JSON.parse(this.responseText);
                alert("Error: " + result.message);
            } else {
                alert("Error: " + this.statusText);
            }
        }
    };

    xhttp.open("POST", "api/signup", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send(JSON.stringify({
        name: name,
        email: email,
        password: password
    }));
});
</script>