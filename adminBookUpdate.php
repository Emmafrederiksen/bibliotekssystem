<?php
/** @var PDO $db */
require "settings/init.php";

// Hvis formularen er indsendt, opdater bogens data
if (!empty($_POST['bookId']) && !empty($_POST['data'])) {
    $data = $_POST['data'];

    // Opdater bogens data, inklusive bookDescription
    $db->sql("UPDATE books SET bookTitle = :bookTitle, bookYear = :bookYear, bookGenre = :bookGenre, bookDescription = :bookDescription 
    WHERE bookId = :bookId", [
        ":bookTitle" => $data['bookTitle'],
        ":bookYear" => $data['bookYear'],
        ":bookGenre" => $data['bookGenre'],
        ":bookDescription" => $data['bookDescription'],
        ":bookId" => $_POST['bookId']
    ]);

    // Opdater forfattere (først slet eksisterende relationer)
    $db->sql("DELETE FROM book_author_con WHERE boAuCBookId = :bookId", [":bookId" => $_POST['bookId']]);

    // Indsæt nye forfattere
    $authors = explode(",", $_POST['authors']); // Adskiller forfattere med komma
    foreach ($authors as $authorName) {
        $authorName = trim($authorName); // Fjern unødvendige mellemrum

        // Find forfatteren, eller opret en ny
        $author = $db->sql("SELECT authId FROM author WHERE authName = :authName", [":authName" => $authorName]);
        if (empty($author)) {
            $db->sql("INSERT INTO author (authName) VALUES (:authName)", [":authName" => $authorName]);
            $authorId = $db->lastInsertId();
        } else {
            $authorId = $author[0]->authId;
        }

        // Opret relationen mellem bogen og forfatteren
        $db->sql("INSERT INTO book_author_con (boAuCBookId, boAuCAuthorId) VALUES (:bookId, :authorId)", [
            ":bookId" => $_POST['bookId'],
            ":authorId" => $authorId
        ]);
    }

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

// Hent forfattere for bogen
$authors = $db->sql("SELECT authName FROM author 
                    INNER JOIN book_author_con ON author.authId = book_author_con.boAuCAuthorId
                    WHERE book_author_con.boAuCBookId = :bookId", [':bookId' => $bookId]);

// Konverter forfattere til en streng adskilt med komma
$authorNames = [];
foreach ($authors as $author) {
    $authorNames[] = $author->authName;
}
$authorsString = implode(", ", $authorNames);
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
        echo "<h4>Bogen er opdateret</h4>";
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
                <label for="authors" class="form-label">Forfattere (adskilt med komma)</label>
                <input type="text" name="authors" id="authors" class="form-control" value="<?php echo $authorsString; ?>" required>
            </div>
            <div class="col-12 col-md-4">
                <label for="bookDescription" class="form-label">Bogbeskrivelse</label>
                <input type="text" name="data[bookDescription]" id="bookDescription" class="form-control" value="<?php echo $book->bookDescription; ?>" required>
            </div>
            <div class="col-12 col-md-4 my-5">
                <input type="hidden" name="bookId" value="<?php echo $bookId; ?>">
                <button type="submit" class="btn btn-primary">Opdater</button>
            </div>
        </div>
    </form>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
