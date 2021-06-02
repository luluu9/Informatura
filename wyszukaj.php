<!DOCTYPE html>
<?php 
include('php/functions.php');
$result = "";
if (isset($_POST['search-type'])) {
    $phrases = $_POST['search-type'];
    $args = ['s'=>$phrases];
    $problems_query = "SELECT id FROM zadania WHERE MATCH (tresc,rozwiazanie) AGAINST (? IN NATURAL LANGUAGE MODE ) ";
    $result = get_db()->query($problems_query, $args);
}
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
                Wyniki wyszukiwania
            </h1>
        </div>
        <div id="content" class="main-width">
            <?php 
            if($result) {
                if (!print_problems($result)) {
                    echo("<div class='center'><h2>Nie znaleziono!</h2></div>"); 
                }
            } 
            else {
                echo("<div class='center'><h2>Nie znaleziono!</h2></div>"); 
            }?>
        </div>
    </main>
    <?php include('php/footer.php')?>
</body>
</html>