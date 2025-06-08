<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent XSS
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    // Basic validation - check required fields
    if (empty($name) || empty($email) || empty($message)) {
        echo "<h2>Error: Please fill in all the fields.</h2>";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h2>Error: Invalid email address.</h2>";
        exit();
    }

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "contact_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("<h2>Connection failed: " . $conn->connect_error . "</h2>");
    }

    // Prepare and bind statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");

    if (!$stmt) {
        die("<h2>Prepare failed: " . $conn->error . "</h2>");
    }

    $stmt->bind_param("sss", $name, $email, $message);

    // Execute and check
    if ($stmt->execute()) {
        // Success: show thank you page (same as your current one)
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
          <meta charset='UTF-8'>
          <title>Thank You - Ayesha</title>
          <link rel='stylesheet' href='style.css'>
        </head>
        <body>
          <div id='particles-js'></div>
          <div class='container'>
            <h2>Thank you for your message!</h2>
            <p>We will get back to you at <strong>" . htmlspecialchars($email) . "</strong>.</p>
          </div>
          <script src='particles.min.js'></script>
          <script>
            particlesJS.load('particles-js', 'particles-config.json', function() {
              console.log('Particles.js loaded – Galaxy stars active!');
            });
          </script>
        </body>
        </html>";
    } else {
        echo "<h2>Error: " . $stmt->error . "</h2>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to contact form if accessed directly
    header("Location: contact.html");
    exit();
}
?>
