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
        // otsing
        if (!empty($_GET["s"])) {
            $s = $_GET["s"];
            $paring = 'SELECT * FROM toidukohad WHERE nimi LIKE "%' . $s . '%" ';
        } else {
            $algus = 0;

            if (isset($_GET['next'])) {
                $algus = $_GET['next'];
            } else if (isset($_GET['prev'])) {
                $algus = $_GET['prev'] - 10;
            }

            if ($algus < 0) $algus = 0;

            // päring mille saadan andmebaasi
            $paring = "SELECT * FROM toidukohad LIMIT $algus,10";

            // küsin kogu toidukohtade arvu
            $toidukohad_kokku_paring = mysqli_query($yhendus, "SELECT COUNT(*) as kokku FROM toidukohad");
            $toidukohad_kokku = mysqli_fetch_assoc($toidukohad_kokku_paring)['kokku'];

            $next = $algus + 10;
            $prev = $algus - 10;

            if ($prev < 0) $prev = 0;
            if ($next >= $toidukohad_kokku) $next = $toidukohad_kokku - ($toidukohad_kokku % 10);
        }

        // saadan soovitud ühendusele minu päringu
        $valjund = mysqli_query($yhendus, $paring);
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
        <?php
        // sikutame andmebaasist kõik vastuse
        while($rida = mysqli_fetch_assoc($valjund)){
        ?>
            <tr>
                <td><?php echo $rida['nimi']; ?></td>
                <td><?php echo $rida['asukoht']; ?></td>
                <td><?php echo $rida['keskmine_hinne']; ?></td>
                <td><?php echo $rida['hinnatud']; ?></td>
            </tr>
        <?php
        }
        ?>
        </table>
        <div class="d-flex justify-content-end">
            <a href="?prev=<?php echo $prev; ?>" class="btn btn-primary <?php if ($algus == 0) echo 'disabled'; ?>" role="button" aria-disabled="<?php if ($algus == 0) echo 'true'; ?>">&lt;&lt; Eelmised</a>
            <a href="?next=<?php echo $next; ?>" class="btn btn-primary ms-2 <?php if ($algus + 10 >= $toidukohad_kokku) echo 'disabled'; ?>" role="button" aria-disabled="<?php if ($algus + 10 >= $toidukohad_kokku) echo 'true'; ?>">Järgmised &gt;&gt;</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
