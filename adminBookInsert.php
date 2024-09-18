<?php
/** @var PDO $db */
require "settings/init.php";

if (!empty($_POST)) {
    // Hent input fra formularen og indsæt det i databasen
    $db->sql("INSERT INTO books (bookTitle, bookYear, bookGenre) 
              VALUES (:bookTitle, :bookYear, :bookGenre)", [
        ":bookTitle" => $_POST["bookTitle"],
        ":bookYear" => $_POST["bookYear"],
        ":bookGenre" => $_POST["bookGenre"],
    ]);

    // Hent den sidste indsatte bookId
    $book = $db->sql("SELECT bookId FROM books ORDER BY bookId DESC LIMIT 1");
    $bookId = $book[0]->bookId;

    // Håndter forfattere, hvis der er indtastet nogen
    if (!empty($_POST['authors'])) {
        $authors = explode(',', $_POST['authors']); // Split forfatternavnene ved komma

        foreach ($authors as $authorName) {
            $authorName = trim($authorName); // Fjern mellemrum før og efter navnet

            if (!empty($authorName)) {
                // Tjek om forfatteren allerede findes i databasen
                $existingAuthor = $db->sql("SELECT authId FROM author WHERE authName = :authName", [
                    ":authName" => $authorName
                ]);

                if (empty($existingAuthor)) {
                    // Hvis forfatteren ikke findes, indsæt den i author-tabellen
                    $db->sql("INSERT INTO author (authName) VALUES (:authName)", [
                        ":authName" => $authorName
                    ]);

                    // Hent den sidste indsatte authId
                    $author = $db->sql("SELECT authId FROM author ORDER BY authId DESC LIMIT 1");
                    $authorId = $author[0]->authId;
                } else {
                    // Hvis forfatteren allerede findes, brug den eksisterende authId
                    $authorId = $existingAuthor[0]->authId;
                }

                // Opret relationen mellem bogen og forfatteren i book_author_con
                $db->sql("INSERT INTO book_author_con (boAuCBookId, boAuCAuthorId) 
                          VALUES (:bookId, :authorId)", [
                    ":bookId" => $bookId,
                    ":authorId" => $authorId
                ]);
            }
        }
    }


    // Redirect tilbage til adminBooks.php efter at have oprettet bogen
    header("Location: adminBooks.php");
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
    <h1>Tilføj ny bog</h1>
    <form method="post" action="adminBookInsert.php">
        <div class="mb-3 col-12 col-md-6">
            <label for="bookTitle" class="form-label">Bogtitel</label>
            <input type="text" name="bookTitle" id="bookTitle" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="bookYear" class="form-label">Udgivelsesår</label>
            <input type="number" name="bookYear" id="bookYear" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="bookGenre" class="form-label">Genre</label>
            <input type="text" name="bookGenre" id="bookGenre" class="form-control" required>
        </div>
        <div class="mb-3 col-12 col-md-6">
            <label for="authors" class="form-label">Forfattere (adskilt med komma)</label>
            <input type="text" name="authors" id="authors" class="form-control" placeholder="Fx: J.K. Rowling, George R.R. Martin">
        </div>

        <button type="submit" class="btn btn-primary">Tilføj bog</button>
    </form>
</div>


<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
