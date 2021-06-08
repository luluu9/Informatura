<!DOCTYPE html>
<?php
$title = 'INFORMATURA – wyślij';
$desc = 'Udostępnianie rozwiązań zadań';
$uploaddir = 'D:/INFORMATURA/strona/uploads/'; // set valid on deploy
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
        W tym miejscu możesz podzielić się swoimi rozwiązaniami zadań. Zostaną one sprawdzone, a później udostępnione na stronie. Rozmiar pliku jest ograniczony do 5 MB.
            <form id='upload-form' method='post' enctype=multipart/form-data class='max-width'>
                <div id='upload-details'>  
                    <input type="hidden" name="max_file_size" value="5242880">
                    <input type='file' name='upload-file' id='upload-file'>
                    <label for="upload-file">Wybierz plik</label>
                    <input type='text' id='upload-author' name='upload-author' placeholder='Autor'>
                </div>    
                <input type='text' id='upload-sheet' name='upload-sheet' placeholder='Rok matury, rodzaj...'>
                <input type='text' id='upload-info' name='upload-info' placeholder='Dodatkowe informacje, uwagi...'>
                <input type='submit' value='Wyślij' name='submit'>
            </form>
            <?php
                if (!empty($_FILES)) {
                    if ($_FILES['upload-file']['error'] == UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES['upload-file']['tmp_name'];
                        $uploaded_name = basename($_FILES['upload-file']['name']);
                        $ext = strtolower(substr($uploaded_name, strripos($uploaded_name, '.')+1));
                        $new_name = hash_file('sha256', $tmp_name ) . '.' . $ext;
                        move_uploaded_file($tmp_name, $uploaddir . $new_name);
                    }
                    else if ($_FILES['upload-file']['error'] == UPLOAD_ERR_INI_SIZE || $_FILES['upload-file']['error'] == UPLOAD_ERR_FORM_SIZE)  { 
                        // set maximum file size in the php.ini file: upload_max_filesize 
                        $max_filesize = ini_get("upload_max_filesize");
                        $max_filesize = (int) filter_var($max_filesize, FILTER_SANITIZE_NUMBER_INT); 
                        echo "Plik jest za duży. Maksymalny rozmiar wynosi " . $max_filesize . " MB";
                    }
                    else {
                        echo "Coś poszło nie tak. Spróbuj ponownie";
                    }
                }
                /*
                echo '<pre>';
                print_r($_FILES);
                print "</pre>";
                */
            ?>
        </div>
    </main>
    <?php include('php/footer.php') ?>
</body>
</html>