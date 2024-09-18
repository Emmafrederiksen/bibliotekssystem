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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="container mt-4">
    <h1>Bog: <?php echo $book->bookTitle; ?></h1>

    <div class="row pt-4">
        <!-- Billedet til venstre -->
        <div class="col-md-6">
            <?php if (!empty($book->bookImage)) { ?>
                <img src="img/<?php echo $book->bookImage; ?>" alt="<?php echo $book->bookTitle; ?>" style="max-width: 100%; height: 400px;">
            <?php } else { ?>
                <p>Intet billede tilgængeligt</p>
            <?php } ?>
        </div>

        <!-- Stjerner, genre og beskrivelse til højre -->
        <div class="col-md-4 mt-md-0 mt-4">
            <!-- Indsæt stjerner over genre -->
            <p><strong>Rating:</strong></p>
            <div class="book-rating pb-3">
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
            </div>

            <!-- Genre -->
            <p><strong>Genre:</strong> <?php echo $book->bookGenre; ?></p>

            <!-- Beskrivelse -->
            <p><strong>Beskrivelse:</strong> <?php echo $book->bookDescription; ?></p>
        </div>
    </div>

    <!-- Forfattere af denne bog -->
    <h4 class="mt-5">Forfattere af denne bog:</h4>
    <div class="row g-4 py-3">
        <?php foreach($authors as $author) { ?>
            <div class="col-12 col-md-4">
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
