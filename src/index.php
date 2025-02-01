<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <?php

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $minLength = 5;

    if (strlen($username) < $minLength) {
      echo "<script type='text/javascript'>
      alert('Wachtwoord moet minimaal ". $minLength. " karakters lang zijn.');
      </script>";
    } else {
        echo "Form submitted successfully!";
        echo "<script type='text/javascript'>
        alert('Succesfull submitted');
        </script>";
        exit;
    }
}
?>

<form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <span style="color:red;"><?= $error ?></span>
    <button type="submit">Submit</button>
</form>

  ?>

</body>

</html>