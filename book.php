<?php
/** @var PDO $db */
require "settings/init.php";

if(empty($_GET["bookId"])) {
    header("Location: index.php");
}


$bookId = $_GET["bookId"];
$book = $db->sql("SELECT * FROM books WHERE bookId = :bookId", [":bookId" => $bookId]);
$book = $book[0];


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

        <div class="display-3"><?php echo $book->bookTitle; ?></div>

        <h4>Forfattere af denne bog:</h4>

        <div class=""><?php echo $book->bookImg; ?></div>

        <div class="row g-4 mt-2">
            <?php
            // Hent alle forfattere som har skrevet den samme bog
            $author = $db->sql("SELECT authId, authName FROM author INNER JOIN book_author_con ON authId = boAuCAuthorId WHERE boAuCBookId = :bookId", [":bookId" => $bookId]);

            foreach($author as $auth) {
                ?>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $auth->authName; ?></h5>
                            <a href="book.php?eventId=<?php echo $auth->authId; ?>" class="btn btn-primary">Læs mere</a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>



    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
<?php

