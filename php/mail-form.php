<?php 
include_once('php/functions.php');
$mailOK = True;
if (isset($_POST['submit'])) {
    $email = isset($_POST['email']) ?  filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : "brak@brak.brak";
    $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "Nieznajomy";
    $message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "Brak wiadomości";
    $content = "{$email} ({$name}) napisał: \n" . $message;
    $recipient = "kontakt@example.com"; // set on deploy
    $subject = "Formularz kontaktowy od {$email}";
    $mailheader = "Od: {$email} \r\n";
    $mailOK = mail($recipient, $subject, $content, $mailheader);
}
echo "
<div class='max-width'><h2>" . (isset($_POST['submit']) ? $mailOK ? 'Wysłano!' : 'Wystąpił błąd przy wysyłaniu wiadomości' : 'Formularz kontaktowy') . "</h2></div>
<form id='contact-form' method='POST' class='max-width'>
    <div id='contact-details'> 
        <input type='text' name='name' placeholder='Imię'>
        <input id='email' type='email' name='email' placeholder='Twój adres email' required>
    </div>
    <textarea name='message' rows='6' cols='25' placeholder='Wiadomość' required></textarea><br />
    <input type='submit' value='Wyślij' name='submit'>
</form>"
?>