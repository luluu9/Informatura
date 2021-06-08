<!DOCTYPE html>
<?php
$title = 'INFORMATURA – kontakt';
$desc = 'Wszystkie drogi komunikacji z twórcą tej strony.';
?>

<html lang="pl-PL">

<head>
    <?php include('php/meta.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/upload.css" media="all"> 
    <script
			  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
			  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
			  crossorigin="anonymous"></script>
    <script>
        $(document).on('change', "#upload-form input[type='file']", function(){
        if ($(this).val()) {
            var filename = $(this).val().split("\\");
            filename = filename[filename.length-1];
            $('#upload-form label').text(filename);
        }
 });
    </script>
</head>

<body>
    <?php include('php/header.php') ?>
    <main>
        <div id="big-title" class="center">
            <h1>
                Wyślij
            </h1>
        </div>
        <div id="content" class="main-width">
        W tym miejscu możesz podzielić się swoimi rozwiązaniami zadań. Zostaną one sprawdzone, a później udostępnione na stronie.
            <form id='upload-form' method='POST' enctype=multipart/form-data class='max-width'>
                <div id='upload-details'>  
                    <input type='file' name='upload-file' id='upload-file'>
                    <label for="upload-file">Wybierz plik</label>
                    <input type='text' id='upload-author' name='upload-author' placeholder='Autor'>
                </div>    
                <input type='text' id='upload-sheet' name='upload-sheet' placeholder='Rok matury, rodzaj...'>
                <input type='text' id='upload-info' name='upload-info' placeholder='Dodatkowe informacje, uwagi...'>
                <input type='submit' value='Wyślij' name='submit'>
            </form>
        </div>
    </main>
    <?php include('php/footer.php') ?>
</body>
</html>