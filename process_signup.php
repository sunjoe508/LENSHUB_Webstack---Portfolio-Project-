<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = "";     // Your database password
$dbname = "lenshub"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch form data
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password
$phoneNumber = $_POST['phoneNumber'];

// Check if user already exists
$sql = "SELECT * FROM clients WHERE email = '$email' OR username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "You have already signed up. Please log in.";
    echo "<a href='login.html'>Log in here</a>";
    exit();
}

// Insert new user into the database
$sql = "INSERT INTO clients (fullName, email, username, password, phoneNumber) 
        VALUES ('$fullName', '$email', '$username', '$password', '$phoneNumber')";

if ($conn->query($sql) === TRUE) {
    // Send signup details to the user's email
    $to = $email;
    $subject = "Welcome to Lenshub!";
    $message = "Hi $fullName,\n\nThank you for signing up on Lenshub!\n\nHere are your login details:\nUsername: $username\n\nPlease keep this information secure.";
    $headers = "From: noreply@lenshub.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Signup successful! Your login details have been sent to your email.";
    } else {
        echo "Signup successful! However, we could not send an email.";
    }
    echo "<a href='login.html'>Log in here</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
