<?php
    session_start();

    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
        die("Page not available");
    }

    $conn = require_once "common/connection.php";

    function fetchUserInfo($conn, $query, $paramTypes, $params){
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Query preparation failed: " . $conn->error);
        }
        $stmt->bind_param($paramTypes, ...$params);
        
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            return $result->fetch_assoc(); 
        } else {
            return null; 
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $persoonnummer = $_GET['id'];

        $query1 = "SELECT voornaam, tussenvoegsel, achternaam, geboortedatum, email FROM users WHERE persoonnummer = ?";
        $gebruikerinfo = fetchUserInfo($conn, $query1, "s", [$persoonnummer]);

        $query2 = "SELECT username, password FROM accounts WHERE persoonnummer = ?";
        $accountinfo = fetchUserInfo($conn, $query2, "s", [$persoonnummer]);

        if (!$gebruikerinfo) {
            die("User not found.");
        }elseif(!$accountinfo){
            die("Account not found.");
        };
        
    }elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $persoonnummer = $_POST['id'];

        $query1 = "SELECT voornaam, tussenvoegsel, achternaam, geboortedatum, email FROM users WHERE persoonnummer = ?";
        $gebruikerinfo = fetchUserInfo($conn, $query1, "s", [$persoonnummer]);

        $query2 = "SELECT username, password FROM accounts WHERE persoonnummer = ?";
        $accountinfo = fetchUserInfo($conn, $query2, "s", [$persoonnummer]);

        // Prepare update queries based on changes
        $bijgewerkteVeldenUsers = [];
        $paramsUsers = [];
        

        // Check of user velden waarden zijn veranderd.
        if ($_POST['fname'] !== $gebruikerinfo['voornaam']) {
            $bijgewerkteVeldenUsers[] = "voornaam = ?";
            $paramsUsers[] = $_POST['fname'];
        }
        if ($_POST['tname'] !== $gebruikerinfo['tussenvoegsel']) {
            $bijgewerkteVeldenUsers[] = "tussenvoegsel = ?";
            $paramsUsers[] = $_POST['tname'];
        }
        if ($_POST['lname'] !== $gebruikerinfo['achternaam']) {
            $bijgewerkteVeldenUsers[] = "achternaam = ?";
            $paramsUsers[] = $_POST['lname'];
        }
        if ($_POST['dob'] !== $gebruikerinfo['geboortedatum']) {
            $bijgewerkteVeldenUsers[] = "geboortedatum = ?";
            $paramsUsers[] = $_POST['dob'];
        }
        if ($_POST['email'] !== $gebruikerinfo['email']) {
            $bijgewerkteVeldenUsers[] = "email = ?";
            $paramsUsers[] = $_POST['email'];
        }


        $bijgewerkteVeldenAccounts = [];
        $paramsAccounts = [];

        // Check of account velden zijn ingevuld
        if (!empty($_POST['username'])) {
            $bijgewerkteVeldenAccounts[] = "username = ?";
            $paramsAccounts[] = $_POST['username'];
        }
        if (!empty($_POST['password'])) { 
            $bijgewerkteVeldenAccounts[] = "password = ?";
            $wachtwoord = $_POST['password'];
            $salt = "9Q3z8T";
            $saltedWachtwoord = $wachtwoord.$salt;
            $hashedWachtwoord = password_hash($saltedWachtwoord, PASSWORD_DEFAULT);
            $paramsAccounts[] = $hashedWachtwoord;
        }

        if(!empty($bijgewerkteVeldenUsers)||!empty($bijgewerkteVeldenAccounts)){
            $bijgewerkteVeldenAccounts[] = "bijgewerkt = ?";
            $paramsAccounts[] = date("Y-m-d");;

        }

        $minimumwachtwoorlengte = 8;

        //Check of het wachtwoord korter is dan de minimum wachtwoord lengte
        if(strlen($wachtwoord) < $minimumwachtwoorlengte){
            echo "<script type='text/javascript'>
            alert('Wachtwoord moet minimaal ". $minimumwachtwoorlengte. " karakters lang zijn.');
            </script>";
        }else{

            // Voert uit als gebruiker data is ingevoerd
            if (!empty($bijgewerkteVeldenUsers)) {
                $sqlUsers = "UPDATE users SET " . implode(", ", $bijgewerkteVeldenUsers) . " WHERE persoonnummer = ?";
                $stmt = $conn->prepare($sqlUsers);
                $paramsUsers[] = $persoonnummer;
                $stmt->bind_param(str_repeat("s", count($paramsUsers)), ...$paramsUsers);
                $stmt->execute();
                $stmt->close();
            }

            // Voert uit als account data is ingevoerd
            if (!empty($bijgewerkteVeldenAccounts)) {
                $sqlAccounts = "UPDATE accounts SET " . implode(", ", $bijgewerkteVeldenAccounts) . " WHERE persoonnummer = ?";
                $stmt = $conn->prepare($sqlAccounts);
                $paramsAccounts[] = $persoonnummer; 
                $stmt->bind_param(str_repeat("s", count($paramsAccounts)), ...$paramsAccounts);
                $stmt->execute();
                $stmt->close();
            }

                echo "<script type='text/javascript'>
                alert('User is aangepast');
                window.location.href = 'overzicht.php'; 
                </script>";

                exit();

            }
        

        

    }else{
        die("error");
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UpdateUser Page</title>
    <link rel="stylesheet" href="Styles/style.css">
</head>
<body>
<div class = 'insertContainer'>
    <h1>Edit User:</h1>
        <form action="update_user.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($persoonnummer); ?>">
            <div class="row">
                <div class="insert-group">
                    <label for="fname">Voornaam:</label>
                    <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($gebruikerinfo['voornaam']); ?>">
                </div>
                <div class="insert-group">
                    <label for="tname">Tussenvoegsel:</label>
                    <input type="text" id="tname" name="tname" value="<?php echo htmlspecialchars($gebruikerinfo['tussenvoegsel']); ?>">
                </div>
                <div class="insert-group">
                    <label for="lname">Achternaam:</label>
                    <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($gebruikerinfo['achternaam']); ?>">
                </div>
            </div>

            <div class="row">
                <div class="insert-group">
                    <label for="dob">Geboortedatum:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($gebruikerinfo['geboortedatum']); ?>">
                </div>
                <div class="insert-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($gebruikerinfo['email']); ?>">
                </div>
            </div>

            
            <div class="row">
                <div class="insert-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="<?php echo htmlspecialchars($accountinfo['username']); ?>">
                </div>
                <div class="insert-group">
                    <label for="password">New Wachtwoord:</label>
                    <input type="password" id="password" name="password" minlength ="8" placeholder="New Password">
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="submit" >Submit</button>
                <button type="button" class="cancel" onClick="document.location.href='overzicht.php'">Cancel</button>
            </div>
        </form>
    </div>
    
</body>
</html>

