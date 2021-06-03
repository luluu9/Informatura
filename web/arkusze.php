<!DOCTYPE html>
<?php 
include('php/functions.php');
$sheets = (new Sheets())->get_sheets();
$years = array();
foreach($sheets as $sheet) {
    $years[$sheet->year][] = $sheet;
}
$title = "Arkusze maturalne z informatyki";
$desc = "Wszystkie zgromadzone arkusze maturalne z informatyki z rozwiązaniami do ich zadań. Znajdziesz tutaj zarówno teorię, jak i praktykę.";
?>

<html lang="pl-PL">

<head>
    <?php include('php/meta.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sheets_page.css" media="all">
</head>

<body>
    <?php include('php/header.php') ?>
    <main>
        <div id="big-title" class="center">
            <h1>
                Arkusze maturalne
            </h1>
        </div>
        <div id="content" class="main-width">
            <?php 
            foreach($years as $year => $sheets) {
                echo "<div class='sheet'>
                        <a name='show{$year}'><h2>{$year}</h2></a>
                        <div class='sheet-details'>";
                foreach($sheets as $sheet) {
                    $url = $sheet->problem_exist() ? "/arkusz/" . $sheet->sheet_id : "";
                    $sheet_path = "pliki-arkusze/{$year}/{$sheet->description}/arkusz.zip";
                    $url_sheet = is_file($sheet_path) ? $sheet_path : "";
                    echo "<div class='sheet-hrefs'>
                            <h3>{$sheet->description}:</h3>
                            <a href='/{$url_sheet}' " . (empty($url_sheet) ? "class='line-through'" : "") . ">arkusz</a>" . 
                            (empty($url) ? "<span class='line-through'>rozwiązanie</span>" : "<a href='{$url}'>rozwiązanie</a>") . 
                        "</div>";
                    }
                echo "</div>
                    </div>";
            }
            ?>
        </div>
    </main>
    <?php include('php/footer.php')?>
</body>
</html>