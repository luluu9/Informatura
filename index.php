<!DOCTYPE html>
<?php 
include('php/functions.php');
$tag = _GET('tag');
$problems_query = "SELECT id FROM zadania ORDER BY data_utworzenia DESC LIMIT 5";
$result = get_db()->query($problems_query);
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
                Matura z informatyki? </br>
                Nic prostszego!
            </h1>
        </div>
        <div id="content" class="main-width">
            Będziesz zdawać maturę z informatyki? Rozwiązywanie zadań z arkuszy sprawia Ci problem?
            A może chciałbyś po prostu dowiedzieć się, jak wyglądają takie zadania?
            Znajdziesz na tej stronie stale rozwijającą się bazę zadań wraz z dokładnym wytłumaczeniem każdego z etapów!
            <h2>Najnowsze:</h2>
            <?php print_problems($result); ?>
            <h3><a href="/rozwiazania">Pokaż więcej...</a></h3>
        </div>
    </main>
    <?php include('php/footer.php')?>
</body>
</html>