<?php
include_once('php/functions.php');
$sheets = (new Sheets())->get_sheets();
$years = array();
foreach ($sheets as $sheet) {
    $years[$sheet->year][] = 1;
}
?>
<footer>
    <div id="nav-footer" class="center">
        <div class="main-width max-width">
            <h3>ARKUSZE</h3>
            <ul>
                <?php
                foreach ($years as $year => $sheet) {
                    echo "<li><a href=/arkusze#show{$year}>{$year}</a></li>";   
                }
                ?>
            </ul>
        </div>
    </div>
    <div id="inner-footer" class="center">
        <div class="main-width max-width">
            <div id="inner-container">
                © Copyright <?php echo date("Y") ?> | InforMatura
                <span style="float: right;">
                <a href="/kontakt">Kontakt</a> | 
                <a href="https://www.facebook.com/informatura">fb.com/informatura</a> 
                </span>
            </div>
        </div>
    </div>
</footer>