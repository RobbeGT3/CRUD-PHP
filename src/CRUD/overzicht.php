<?php 
session_start();

//Checked of the gebruiker is ingelogd
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    die("Page not available");
}

$conn =  require_once "common/connection.php";


//Data voor het overzicht
$stmt1 = $conn->prepare("SELECT persoonnummer,voornaam,tussenvoegsel,achternaam,geboortedatum,email FROM users;");
$stmt1->execute();
$result1 = $stmt1->get_result();


//Gegevens voor customized text per gebruiker.
$stmt2 = $conn->prepare("SELECT voornaam,tussenvoegsel,achternaam FROM users WHERE persoonnummer = ?;");
$stmt2->bind_param("i", $_SESSION['persoonnummer_id']);
$stmt2->execute();
$result2 = $stmt2->get_result();
$gebruiker = $result2->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht Page</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>
<body>
    <script src="script.js"></script>
    <div class = 'container'>
        <h1>Personen</h1>
        <p>Welcome, <?php echo $gebruiker['voornaam']." ".$gebruiker['tussenvoegsel']." ".$gebruiker['achternaam']?></p>
        <button onClick="window.location.href='logout.php'">uitloggen</button>
        <table>
            <tr>
                <th>Persoonnummer</th>
                <th>Voornaam</th>
                <th>Tussenvoegsel</th>
                <th>Achternaam</th>
                <th>Geboortedatum</th>
                <th>Email</th>
            </tr>
            <?php

            while($row = $result1->fetch_assoc()){
                echo "<tr>";
                echo "<td> <a href='update_user.php?id=" . $row['persoonnummer'] . "'>" . $row['persoonnummer'] . "</a></td>";
                echo "<td>" . $row['voornaam'] . "</td>";
                echo "<td>" . $row['tussenvoegsel'] . "</td>";
                echo "<td>" . $row['achternaam'] . "</td>";
                echo "<td>" . $row['geboortedatum'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>"."<button class='deleteButton' id='row".$row['persoonnummer'] ."' onclick='openPopup(" . $row['persoonnummer'] . ")'>Verwijder</button>"."</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <button class = 'updateButton' onClick="window.location.href='create_user.php'">Voeg persoon toe</button>
    </div>
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <p>Weet je zeker dat je deze gebruiker wilt verwijderen?</p>
        <button class="popup-button" onclick="deleteRecord()">Verwijderen</button>
        <button class="close-popup" onclick="closePopup()" >Cancel</button>
    </div>
    
    
</body>
</html>