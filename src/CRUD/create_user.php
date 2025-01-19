<?php 
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $conn = require_once "common/connection.php";

    $voornaam = $_POST['fname'];
    $tussenvoegsel = $_POST['tname'];
    $achternaam = $_POST['lname'];

    if(!empty($_POST['dob'])){
        $geboortedatum = $_POST['dob'];
    }else{
        $geboortedatum = NULL;
    }
    
    $email = $_POST['email'];

    $stmt1 = $conn->prepare("INSERT INTO users (persoonnummer, voornaam, tussenvoegsel, achternaam, geboortedatum, email) VALUES (NULL,?,?,?,?,?)");
    $stmt1->bind_param("sssss", $voornaam, $tussenvoegsel, $achternaam,$geboortedatum,$email);
    $stmt1->execute();
    if($stmt1->affected_rows > 0){
        $userID = $conn->insert_id;

        $gebruikersnaam = $_POST['username'];
        $wachtwoord = $_POST['password'];

        $salt = "9Q3z8T";
        $saltedWachtwoord = $wachtwoord.$salt;
        $hasedWachtwoord = password_hash($saltedWachtwoord, PASSWORD_DEFAULT);

        $aanmaking_account = date("Y-m-d");

        // $rol = $_POST['UserRol'];

        $stmt2 = $conn->prepare("INSERT INTO accounts (idaccount, username, password, aangemaakt, persoonnummer) VALUES (NULL,?,?,?,?)");
        $stmt2->bind_param('sssi', $gebruikersnaam, $hasedWachtwoord, $aanmaking_account, $userID);
        $stmt2->execute();
        $stmt2->close();
    }
    $stmt1->close();
    $conn->close();
    header('location: overzicht.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InsertUser Page</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>
<body>
    <div class = 'insertContainer'>
        <form action="create_user.php" method="POST">
        <div class="row">
                <div class="insert-group">
                    <label for="fname">Voornaam:</label>
                    <input type="text" id="fname" name="fname">
                </div>
                <div class="insert-group">
                    <label for="tname">Tussenvoegsel:</label>
                    <input type="text" id="tname" name="tname">
                </div>
                <div class="insert-group">
                    <label for="lname">Achternaam:</label>
                    <input type="text" id="lname" name="lname">
                </div>
            </div>

            <!-- Second Row -->
            <div class="row">
                <div class="insert-group">
                    <label for="dob">Geboortedatum:</label>
                    <input type="date" id="dob" name="dob">
                </div>
                <div class="insert-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>
            </div>

            <!-- Third Row -->
            <div class="row">
                <div class="insert-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username">
                </div>
                <div class="insert-group">
                    <label for="password">Wachtwoord:</label>
                    <input type="password" id="password" name="password">
                </div>
            </div>
            <!-- Buttons -->
            <div class="button-group">
                <button type="submit" class="submit">Submit</button>
                <button type="button" class="cancel" onClick="document.location.href='overzicht.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
