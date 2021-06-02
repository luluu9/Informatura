<!DOCTYPE html>
<?php 
include('php/functions.php');
$problem_id = _GET('zadanie', 'i');
$problem = new Problem($problem_id); 
$title = "Zadanie {$problem->filename} – {$problem->year}, matura {$problem->description}: {$problem->get_main_problem($only_title=True)}";
$desc = "Rozwiązanie zadania {$problem->filename} – {$problem->get_main_problem($only_title=True)} z arkusza maturalnego {$problem->year} – matura {$problem->description}";
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
                Rozwiązanie</br>
                <?php echo "Zadanie {$problem->filename} – matura {$problem->year}, {$problem->description}"?>
            </h1>
        </div>
        <div id="content" class="main-width">
            <?php  
                print_problem($problem);
                echo "<a href='/arkusz/{$problem->sheet_id}' class='more-problems'>Wyszukaj więcej zadań z tego arkusza...</a>";
            ?>
        </div>
    </main>
    <?php include('php/footer.php')?>
</body>
</html>