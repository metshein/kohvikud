<?php
    $kasutaja = "mario";
    $dbserver = "localhost";
    $andmebaas = "kohvikud";
    $pw = "mario";

    $yhendus = mysqli_connect($dbserver, $kasutaja, $pw, $andmebaas);

    if(!$yhendus){
        die("Ei saa ühendust!");
    } 
?>