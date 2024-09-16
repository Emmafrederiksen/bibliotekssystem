<?php
/** @var PDO $db */
require "settings/init.php";

// Hvis slet-funktionen er aktiveret, slet bogen
if (!empty($_GET["delete"]) && $_GET["delete"] == "1" && !empty($_GET["bookId"])) {
    $db->sql("DELETE FROM books WHERE bookId = :bookId", [":bookId" => $_GET["bookId"]]);
    header("Location: adminBooks.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">

    <title>Admin - Bøger</title>

    <meta name="robots" content="All">
    <meta name="author" content="Udgiver">
    <meta name="copyright" content="Information om copyright">

    <link href="css/styles.css" rel="stylesheet" type="text/css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div class="container">
    <div class="table-responsive">

        <h1>Rediger eller tilføj bøger</h1>

        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col" colspan="3">Bog Titel</th>
                <th scope="col" class="text-end fw-normal"><a href="adminBooksInsert.php" target="_blank">Opret bog</a></th>
            </tr>
            </thead>

            <tbody>
            <?php
            // Hent alle bøger fra databasen
            $books = $db->sql("SELECT bookId, bookTitle FROM books ORDER BY bookTitle ASC");
            foreach ($books as $book) {
                ?>

                <tr>
                    <td><?php echo $book->bookTitle; ?></td>
                    <td class="text-end"><a href="adminBookUpdate.php?bookId=<?php echo $book->bookId; ?>">Rediger</a></td>
                    <td class="text-end"><a href="adminBooks.php?delete=1&bookId=<?php echo $book->bookId; ?>" class="deleteLink">Slet</a></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>

    const deleteLink = document.querySelectorAll(".deleteLink");

    deleteLink.forEach(function (link) {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            if(confirm("Er du sikker på, at du vil slette denne bog?")) {
                window.location.href = this.href;
            }
        });
    });

</script>

</body>
</html>

