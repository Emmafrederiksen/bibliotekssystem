<?php
/** @var PDO $db */
require "settings/init.php";
?>
<!DOCTYPE html>
<html lang="da">
<head>
	<meta charset="utf-8">
	
	<title>Bibliotekssystem</title>
	
	<meta name="robots" content="All">
	<meta name="author" content="Udgiver">
	<meta name="copyright" content="Information om copyright">
	
	<link href="css/styles.css" rel="stylesheet" type="text/css">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div class="container">
    <h1 class="py-5">Oversigt over bøger</h1>
    <div class="row g-4">
        <?php
        $biblioteker = $db->sql("SELECT * FROM books ORDER BY bookId");
        foreach($biblioteker as $bibliotek) {
            ?>
            <div class="col-12 col-md-6">
                <div class="card w-100">
                    <div class="card-header bg-info-subtle text-black py-3">
                        <?php
                        echo "<h2 class='m-0'>".$bibliotek->bookTitle . "<span class='text-secondary'> / " . $bibliotek->bookYear . "</span></h2>";
                        ?>
                    </div>
                    <div class="card-body">
                        <?php
                        echo $bibliotek->bookGenre;
                        ?>
                    </div>
                    <div class="card-footer text-muted text-center">
                        <a class="btn btn-primary text-light stretched-link" href="book.php?bookId=<?php echo $bibliotek->bookId; ?>" role="button">Læs mere</a>
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
