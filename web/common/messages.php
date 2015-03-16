<?php
$messages['otrsMail'] = <<<'TEXT'
Beste OTRS-vrijwillger,
zojuist is er op http://www.wikiportret.nl een nieuwe foto geupload.

Deze foto heeft als titel '{{title}}', gemaakt door '{{source}}' onder de licentie 'CC-BY-SA 3.0' met als omschrijving '{{desc}}'

De uploader heeft het volgende IP-adres: '{{ip}}'

Je kunt de foto bekijken op Wikiportret en de foto daar afwijzen, of een tekst genereren die je kan copy-pasten om een e-mail te schrijven.

Klik op deze link:
https://www.wikiportret.nl/images/single.php?id={{imageId}}


Als je vragen hebt over de uploadwizard kun je terecht bij JurgenNL via https://commons.wikimedia.org/wiki/User:JurgenNL of eventueel via jurgennl.wp@gmail.com.

Al vast heel erg bedankt voor je medewerking!
TEXT;

$messages['otrsHeaders'] = <<<'TEXT'
Reply-To: {{name}} <{{email}}>
Return-Path: {{name}} <{{email}}>
From: Wikiportret <{{mailOrigin}}>
MIME-Version: 1.0
Content-type: text/html; charset=utf-8
TEXT;

$messages['otrsSubject'] = "[Wikiportret] {{title}} is geüpload op Wikiportret";