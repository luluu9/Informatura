<?php
$title = isset($title) ? $title : 'INFORMATURA – dla każdego';
$desc = isset($desc) ? $desc : 'Znajdziesz na tej stronie rozwiązania wielu arkuszy maturalnych z informatyki - wszystko dokładnie wytłumaczone, a także sporo innych, wartych uwagi treści!';
echo "
    <meta charset='utf-8'>
    <title>{$title}</title>
    <meta name='description' content='{$desc}'>
    <meta name='author' content='Mateusz Naklicki'>
    <meta http-equiv='X-Ua-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta property='og:type' content='website' />
    <meta property='og:title' content='INFORMATURA – dla każdego' />
    <meta property='og:image' content='http://informatura.pl/icon/og_logo_icon.png' />
    <meta property='og:image:secure_url' content='https://informatura.pl/icon/og_logo_icon.png' />
    <meta property='og:image:width' content='1200' />
    <meta property='og:image:height' content='1200' /> 
    <meta property='og:image:type' content='image/png' />   
    <meta property='og:description' content='Znajdziesz na tej stronie stale rozwijającą się bazę zadań z informatyki wraz z dokładnym wytłumaczeniem każdego z etapów!' />
    <link rel='canonical' href='https://informatura.pl'/>
    <link rel='stylesheet' type='text/css' href='/css/style.css' media='all'/>
    <link rel='stylesheet' type='text/css' href='/css/font.css' media='all'/>
    <link rel='stylesheet' type='text/css' href='/css/menu.css' media='all'/>
    <link rel='apple-touch-icon' sizes='180x180' href='/icon/apple-touch-icon.png'>
    <link rel='icon' type='image/png' sizes='32x32' href='/icon/favicon-32x32.png'>
    <link rel='icon' type='image/png' sizes='16x16' href='/icon/favicon-16x16.png'>
    <link rel='manifest' href='/icon/site.webmanifest'>
    "
?>