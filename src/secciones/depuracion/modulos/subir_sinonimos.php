<?php

include($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');

$path = $_FILES ['sube_sinonimos']['tmp_name'];

/*
  $sinonimos = simplexml_load_file($path);
 */
$content = file_get_contents($path);
$content = str_replace(' xmlns="Dataset Sinonimos"', '', $content);
$content = str_replace('&#x0;', '', $content);

$xml = simplexml_load_string($content);

$values = '';
foreach ($xml->Sinonimos as $p) {
    if (utf8_decode($p->Palabra) != utf8_decode($p->Representante)) {
        if ($values == '')
            $values = "('$p->Palabra','$p->Representante')";
        else
            $values .= ",('$p->Palabra','$p->Representante')";
    }
}

if ($values != '')
    $mysqli->query("insert IGNORE  into sinonimos (palabra,representante) values " . utf8_decode($values));


