<?php
/** @var PDO $db */
require "settings/init.php";

if(empty($_GET["bookId"])) {
    header("Location: index.php");
    exit;
}

$bookId = $_GET["bookId"];

// Hent bogens data
$book = $db->sql("SELECT * FROM books WHERE bookId = :bookId", [":bookId" => $bookId]);
$book = $book[0];

// Hent forfatterne af bogen
$authors = $db->sql("SELECT authId, authName FROM author 
                    INNER JOIN book_author_con ON author.authId = book_author_con.boAuCAuthorId
                    WHERE book_author_con.boAuCBookId = :bookId", [":bookId" => $bookId]);

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <title><?php echo $book->bookTitle; ?></title>
    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="container mt-4">
    <h1>Bog: <?php echo $book->bookTitle; ?></h1>

    <!-- Viser bogens billede -->
    <div class="my-3">
        <?php if (!empty($book->bookImage)) { ?>
            <img src="img/<?php echo $book->bookImage; ?>" alt="<?php echo $book->bookTitle; ?>" style="max-width: 100%; height: 400px;">
        <?php } else { ?>
            <p>Intet billede tilgængeligt</p>
        <?php } ?>
    </div>

    <h4>Forfattere af denne bog:</h4>
    <div class="row g-4 mt-2">
        <?php foreach($authors as $author) { ?>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $author->authName; ?></h5>
                        <!-- Knap til at vise mere om forfatteren -->
                        <a href="author.php?authorId=<?php echo $author->authId; ?>" class="btn btn-outline-secondary">Se mere</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
