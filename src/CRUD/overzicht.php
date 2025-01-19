<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht Page</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>
<body>
    <script src="Popup.js"></script>
    <div class = 'container'>
        <h1>Personen</h1>
        <table>
            <tr>
                <th>persoonnummer</th>
                <th>voornaam</th>
                <th>tussenvoegsel</th>
                <th>achternaam</th>
                <th>geboortedatum</th>
                <th>email</th>
            </tr>
            <?php
            $conn =  require_once "common/connection.php";

            $stmt = $conn->prepare("SELECT persoonnummer,voornaam,tussenvoegsel,achternaam,geboortedatum,email FROM users;");
            $stmt->execute();
            $result = $stmt->get_result();

            // if ($result->num_rows == 0) {
            //     exit('No rows');
            // }

            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td> <a href='details.php?id=" . $row['persoonnummer'] . "'>" . $row['persoonnummer'] . "</a></td>";
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
        <button class = 'updateButton' onClick="document.location.href='create_user.php'">Voeg persoon toe</button>
    </div>
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <p>Weet je zeker dat je deze gebruiker wilt verwijderen?</p>
        <button class="popup-button" onclick="deleteRecord()">Verwijderen</button>
        <button class="close-popup" onclick="closePopup()" >Cancel</button>
    </div>
    
</body>
</html>