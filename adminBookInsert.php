<?php
/** @var PDO $db */
require "settings/init.php";

if (!empty($_POST)) {
    // Hent input fra formularen og indsæt det i databasen
    $db->sql("INSERT INTO events (evenName, evenDescription, evenDateTime, evenLocation) 
              VALUES (:evenName, :evenDescription, :evenDateTime, :evenLocation)", [
        ":evenName" => $_POST["evenName"],
        ":evenDescription" => $_POST["evenDescription"],
        ":evenDateTime" => $_POST["evenDateTime"],
        ":evenLocation" => $_POST["evenLocation"]
    ]);

    // Redirect tilbage til adminEvents.php
    header("Location: adminEvents.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Admin - Tilføj ny bog</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div class="container">
    <h1>Tilføj nyt event</h1>
    <form method="post" action="adminEventsInsert.php">
        <div class="mb-3 col-12 col-md-6">
            <label for="evenName" class="form-label">Event Navn</label>
            <input type="text" name="evenName" id="evenName" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="evenDescription" class="form-label">Beskrivelse</label>
            <textarea name="evenDescription" id="evenDescription" class="form-control" required></textarea>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="evenDateTime" class="form-label">Dato og tid</label>
            <input type="datetime-local" name="evenDateTime" id="evenDateTime" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="evenLocation" class="form-label">Lokation</label>
            <input type="text" name="evenLocation" id="evenLocation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tilføj event</button>
    </form>
</div>


<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
