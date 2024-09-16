<?php
/** @var PDO $db */
require "settings/init.php";

// Hvis formularen er indsendt, opdater bogens data
if (!empty($_POST['bookId']) && !empty($_POST['data'])) {
    $data = $_POST['data'];
    $db->sql("UPDATE books SET bookTitle = :bookTitle, bookYear = :bookYear, bookGenre = :bookGenre 
    WHERE bookId = :bookId", [
        ":bookTitle" => $data['bookTitle'],
        ":bookYear" => $data['bookYear'],
        ":bookGenre" => $data['bookGenre'],
        ":bookId" => $_POST['bookId']
    ]);

    header("Location: adminBookUpdate.php?succes=1&bookId=" . $_POST['bookId']);
    exit;
}

if (empty($_GET["bookId"])) {
    header("Location: adminBooks.php");
}
$bookId = $_GET["bookId"];

// Hent bogens oplysninger fra databasen
$book = $db->sql("SELECT * FROM books WHERE bookId = :bookId", [":bookId" => $bookId]);
$book = $book[0]; // Vælg den første (og eneste) række fra resultatet

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Admin - Opdater bog</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="container mt-4">

    <?php
    if (!empty($_GET["succes"]) && $_GET['succes'] == 1) {
        echo "<h4>Eventet er opdateret</h4>";
    }
    ?>

    <h1 class="py-3">Opdater Bog</h1>

    <!-- Formular til opdatering af bog -->
    <form action="adminBookUpdate.php" method="post">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label for="bookTitle" class="form-label">Titel</label>
                <input type="text" class="form-control" id="bookTitle" name="data[bookTitle]" value="<?php echo $book->bookTitle; ?>" required>
            </div>
            <div class="col-12 col-md-4">
                <label for="bookYear" class="form-label">Udgivelsesår</label>
                <input type="number" class="form-control" id="bookYear" name="data[bookYear]" value="<?php echo $book->bookYear; ?>" required>
            </div>
            <div class="col-12 col-md-4">
                <label for="bookGenre" class="form-label">Genre</label>
                <input type="text" class="form-control" id="bookGenre" name="data[bookGenre]" value="<?php echo $book->bookGenre; ?>" required>
            </div>
            <div class="col-12 col-md-4">
                <input type="hidden" name="bookId" value="<?php echo $bookId; ?>">
                <button type="submit" class="btn btn-primary w-25">Opdater</button>
            </div>
        </div>
    </form>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
