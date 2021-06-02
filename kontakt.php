<!DOCTYPE html>
<?php
$title = 'INFORMATURA – kontakt';
$desc = 'Wszystkie drogi komunikacji z twórcą tej strony.';
?>

<html lang="pl-PL">

<head>
    <?php include('php/meta.php') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/contact.css" media="all"> 
</head>

<body>
    <?php include('php/header.php') ?>
    <main>
        <div id="big-title" class="center">
            <h1>
                Kontakt
            </h1>
        </div>
        <div id="content" class="main-width">
            <?php include('php/mail-form.php') ?>
            <div id="other-contacts" class="max-width">
                <div class="contact"> 
                    <a href="https://www.facebook.com/informatura"><i class="fa fa-facebook"></i>fb.com/informatura</a>
                    </div>
                <div class="contact">
                    <i class="fa fa-envelope"></i>kontakt@informatura.pl
                </div>
            </div>
        </div>
    </main>
    <?php include('php/footer.php') ?>
</body>
</html>