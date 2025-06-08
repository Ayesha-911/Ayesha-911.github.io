<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "contact_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("<h2>Connection failed: " . $conn->connect_error . "</h2>");
    }

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
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
            <p>We will get back to you at <strong>$email</strong>.</p>
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
    header("Location: contact.html");
    exit();
}
?>
