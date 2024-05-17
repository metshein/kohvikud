<?php include('config.php'); ?>
<!doctype html>
<html lang="et">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <?php
        //otsing
        if (!empty($_GET["s"])) {
            $s = $_GET["s"];
            $paring = 'SELECT * FROM toidukohad WHERE nimi LIKE "%'.$s.'%" ';
        } else {
            //päring mille saadan andmebaasi
            $paring = "SELECT * FROM toidukohad LIMIT 10";
        }
        //saadan soovitud ühendusele minu päringu
            $valjund = mysqli_query($yhendus, $paring);
        //sikutame andmebaasist kõik vastuse
            while($rida = mysqli_fetch_assoc($valjund)){
                print_r($rida);
            }
    ?>
    <div class="container">
        <h1>Valige asutus mida hinnata</h1>
        <table class="table table-sm">
            <tr>
                <th>Nimi</th>
                <th>Asukoht</th>
                <th>Keskmine hinne</th>
                <th>Hinnatud (korda)</th>
            </tr>
            <tr>
                <td>sdg</td>
                <td>sdxfg</td>
                <td>sdf</td>
                <td>dfsdfg</td>
            </tr>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>