<?php
header('Content-Type: application/json');

$conn = require_once "common/connection.php";

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['persoonnummer'])) {
    $persoonnummer = $data['persoonnummer'];
    
    $stmt1 = $conn->prepare("DELETE FROM accounts WHERE persoonnummer = ?");
    $stmt1->bind_param("s", $persoonnummer);
    $stmt1->execute();

    if($stmt1->affected_rows > 0){

        $stmt2 = $conn->prepare("DELETE FROM users WHERE persoonnummer = ?");
        $stmt2->bind_param("s",$persoonnummer);

        if ($stmt2->execute()) {
            echo json_encode(["Gebruiker is verwijderd"]);
        } else {
            echo json_encode(["Verwijdering mislukt"]);
        }
        $stmt2->close();
    }
    $stmt1->close();
    $conn->close();
    

    
}
?>
