<?php

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn =  require_once "common/connection.php";
    $gebruikersnaam = $_POST['username'];
    $wachtwoord = $_POST['password'];

    $salt = "9Q3z8T";
    $saltedWachtwoord = $wachtwoord.$salt;

    $stmt = $conn->prepare("SELECT * FROM accounts WHERE username = ?");
    $stmt->bind_param("s", $gebruikersnaam); 
    $stmt->execute();

    $result = $stmt->get_result();
    $gebruiker = $result->fetch_assoc();
    

    if ($gebruiker && password_verify($saltedWachtwoord, $gebruiker['password'])) {
        $_SESSION['persoonnummer_id'] = $gebruiker['persoonnummer'];
        $_SESSION['is_logged_in'] = true;
        header('location: overzicht.php');
        exit;
    } else {
        header('location: login.php?error=Verkeerde Inloggegevens');
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MainPage</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="login.php" method="POST">
            <?php 
            if (isset($_GET['error'])) {
                echo "<div class='error'>".htmlspecialchars($_GET['error'])."</div>";
            } 
            ?>
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder="Gebruikersnaam" required>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Wachtwoord" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
