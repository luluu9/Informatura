<!DOCTYPE html>
<?php 
include('php/functions.php');
$tag = _GET('tag');
$result = null;
if ($tag) {
    $args = ['s'=>$tag];
    $problems_query = "SELECT id, tag FROM zadania INNER JOIN tagi ON tagi.id_zadania=zadania.id WHERE tagi.tag=? ORDER BY id_arkusza DESC, filename ASC";
    $result = get_db()->query($problems_query, $args);
}
else {
    $problems_query = "SELECT zadania.id FROM zadania INNER JOIN arkusze ON arkusze.id=zadania.id_arkusza ORDER BY rok DESC, filename ASC";
    $result = get_db()->query($problems_query);
}

$title = "Rozwiązane zadania z informatyki";
$desc = "Baza wiedzy dla osób przygotowujących się do matury z informatyki: zbiór rozwiązanych zadań" . (empty($tag) ? "." : "o temacie {$tag}." );
?>

<html lang="pl-PL">

<head>
    <?php include('php/meta.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/solutions_page.css" media="all">
</head>

<body>
    <?php include('php/header.php') ?>
    <main>
        <div id="big-title" class="center">
            <h1>
                Rozwiązane zadania
            <?php 
                if ($tag) { echo "</br> <span style='font-size: 3rem;'> temat: " . $tag . "</span>"; }
            ?>
            </h1>
        </div>
        <div id="content" class="main-width">
            <?php print_problems($result); ?>
        </div>
    </main>
    <?php include('php/footer.php') ?>
</body>
</html>