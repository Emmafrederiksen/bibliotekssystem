<?php
/** @var PDO $db */
require "settings/init.php";

if(empty($_GET["authorId"])) {
    header("Location: index.php");
    exit;
}

$authorId = $_GET["authorId"];

// Hent forfatterens data
$author = $db->sql("SELECT * FROM author WHERE authId = :authorId", [':authorId' => $authorId]);
$author = $author[0]; // Sørg for, at du henter den første række korrekt

// Hent andre bøger skrevet af forfatteren
$books = $db->sql("SELECT bookId, bookTitle, bookImage FROM books 
                   INNER JOIN book_author_con ON books.bookId = book_author_con.boAuCBookId 
                   WHERE book_author_con.boAuCAuthorId = :authorId", [':authorId' => $authorId]);

?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <title><?php echo $author->authName; ?></title>
    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="container mt-4">
    <h1>Forfatter: <?php echo $author->authName; ?></h1>

    <!-- Vis forfatterens andre bøger -->
    <h4 class="pt-5">Andre bøger skrevet af denne forfatter:</h4>
    <div class="row g-4 mt-2">
        <?php foreach($books as $book) { ?>
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo $book->bookTitle; ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($book->bookImage)) { ?>
                            <img src="img/<?php echo $book->bookImage; ?>" alt="<?php echo $book->bookTitle; ?>" class="d-block mx-auto" style="max-width: 100%; height: 200px;">
                        <?php } else { ?>
                            <p>Intet billede tilgængeligt</p>
                        <?php } ?>
                    </div>
                    <div class="card-footer">
                        <a href="book.php?bookId=<?php echo $book->bookId; ?>" class="btn btn-outline-secondary">Læs mere</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
