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
        $hashedWachtwoord = password_hash($saltedWachtwoord, PASSWORD_DEFAULT);

        $aanmaking_account = date("Y-m-d");
        $gebruikerrol = $_POST['UserRol'];

        $stmt2 = $conn->prepare("INSERT INTO accounts (idaccount, username, password, aangemaakt, gebruikerrol, persoonnummer) VALUES (NULL,?,?,?,?,?)");
        $stmt2->bind_param('sssii', $gebruikersnaam, $hashedWachtwoord, $aanmaking_account, $gebruikerrol, $userID);
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
    <h1>Add User:</h1>
        <form action="create_user.php" method="POST">
        <div class="row">
                <div class="insert-group">
                    <label for="fname">Voornaam:</label>
                    <input type="text" id="fname" name="fname" required>
                </div>
                <div class="insert-group">
                    <label for="tname">Tussenvoegsel:</label>
                    <input type="text" id="tname" name="tname">
                </div>
                <div class="insert-group">
                    <label for="lname">Achternaam:</label>
                    <input type="text" id="lname" name="lname" required>
                </div>
            </div>

            <div class="row">
                <div class="insert-group">
                    <label for="dob">Geboortedatum:</label>
                    <input type="date" id="dob" name="dob">
                </div>
                <div class="insert-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="insert-group">
                    <label for="password">account privilege:</label>
                    <select name="UserRol" id="UserRol" required>
                        <?php
                        $conn =  require_once "common/connection.php";

                        $query1 = $conn->prepare("SELECT idgebruikerrollen, naamrol FROM gebruikerrollen;");
                        $query1->execute();
                        $result1 = $query1->get_result();

                        if ($result1->num_rows == 0) {
                            exit('No rows');
                        }

                        while ($row = $result1->fetch_assoc()) {
                            echo "<option value='" . $row['idgebruikerrollen'] . "'>" . htmlspecialchars($row['naamrol']) . "</option>";
                        }
                        $query1->close();
                        $conn->close();
                        ?>
                    </select>
                </div>
            </div>

            
            <div class="row">
                <div class="insert-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="insert-group">
                    <label for="password">Wachtwoord:</label>
                    <input type="password" id="password" name="password" minlength ="8" required>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="submit">Submit</button>
                <button type="button" class="cancel" onClick="document.location.href='overzicht.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
