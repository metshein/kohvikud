<?php include('../config.php'); ?>
<!doctype html>
<html lang="et">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kohvikud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
       *{
            margin: 0;
            padding: 0;
        }
        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
        }
        .rate:not(:checked) > input {
            position:absolute;
            top:-9999px;
        }
        .rate:not(:checked) > label {
            float:right;
            width:1em;
            overflow:hidden;
            white-space:nowrap;
            cursor:pointer;
            font-size:30px;
            color:#ccc;
        }
        .rate:not(:checked) > label:before {
            content: '★ ';
        }
        .rate > input:checked ~ label {
            color: #43d037;    
        }
        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label {
            color: #43c037;  
        }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label {
            color: #43d000;
        }

/* Modified from: https://github.com/mukulkant/Star-rating-using-pure-css */
    </style>
</head>
  <body>
  <div class="container">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
    <?php
    //hinnangu kustutamine
        if (!empty($_GET["del"])) {
            $del = $_GET["del"];
            $id = $_GET["id"];
            $paring = 'DELETE FROM hinnangud WHERE id=' . $del;
            $valjund = mysqli_query($yhendus, $paring);
            header('Location: kohvik.php?id=' . $id);
        }

    //hinnangu lisamine
        if (!empty($_GET["nimi"]) && !empty($_GET["kommentaar"]) && !empty($_GET["rate"])) {
            $nimi = $_GET["nimi"];
            $kommentaar = $_GET["kommentaar"];
            $rate = $_GET["rate"];
            $id = $_GET["id"];
            $paring = 'INSERT INTO hinnangud (nimi, kommentaar, hinnang, toidukohad_id) VALUES ("' . $nimi . '", "' . $kommentaar . '", ' . $rate . ', ' . $id . ')';
            $valjund = mysqli_query($yhendus, $paring);

            // Hindajate arvu ja keskmise hinde uuendamine
            $hindajate_arv_paring = "SELECT hinnatud, keskmine_hinne FROM toidukohad WHERE id=" . $id;
            $hindajate_arv_valjund = mysqli_query($yhendus, $hindajate_arv_paring);
            $toidukoht = mysqli_fetch_assoc($hindajate_arv_valjund);

            $hindajate_arv = $toidukoht['hinnatud'];
            $olemasolev_keskmine = $toidukoht['keskmine_hinne'];

            $uus_hindajate_arv = $hindajate_arv + 1;
            $uus_keskmine = round((($olemasolev_keskmine * $hindajate_arv) + $rate) / $uus_hindajate_arv,2);

            $paring = 'UPDATE toidukohad SET hinnatud = ' . $uus_hindajate_arv . ', keskmine_hinne = ' . $uus_keskmine . ' WHERE id=' . $id;
            $valjund = mysqli_query($yhendus, $paring);
            header('Location: kohvik.php?id=' . $id);
        }
    //hinnangute kuvamine
        if (!empty($_GET["id"])) {
            $id = $_GET["id"];
            $paring = 'SELECT * FROM toidukohad WHERE id=' . $id;
            $valjund = mysqli_query($yhendus, $paring);
            $ettevotte_nimi = mysqli_fetch_assoc($valjund);
        } else{
            header('Location: index.php');
        }
    ?>
      <h1>Hinda kohvikut <strong><?php echo $ettevotte_nimi['nimi'];  ?></strong></h1>
    <form action="" method="get">
        <div class="row">
            <div class="col-sm-4">Nimi:</div>
            <div class="col-sm-8"><input required type="text" name="nimi"></div>
        </div>
        <div class="row">
            <div class="col-sm-4">Kommentaar:</div>
            <div class="col-sm-8"><textarea required name="kommentaar" rows="4" cols="50"></textarea></div>
        </div>
        <div class="row">
            <div class="col-sm-4">Hinnang:</div>
            <div class="col-sm-8">
                <!-- radionuppudega hinnang -->
                <div class="rate">
                    <input type="radio" id="star10" name="rate" value="10" required/>
                    <label for="star10" title="text">10 stars</label>
                    <input type="radio" id="star9" name="rate" value="9" />
                    <label for="star9" title="text">9 stars</label>
                    <input type="radio" id="star8" name="rate" value="8" />
                    <label for="star8" title="text">8 stars</label>
                    <input type="radio" id="star7" name="rate" value="7" />
                    <label for="star7" title="text">7 stars</label>
                    <input type="radio" id="star6" name="rate" value="6" />
                    <label for="star6" title="text">6 star</label>
                    <input type="radio" id="star5" name="rate" value="5" />
                    <label for="star5" title="text">5 stars</label>
                    <input type="radio" id="star4" name="rate" value="4" />
                    <label for="star4" title="text">4 stars</label>
                    <input type="radio" id="star3" name="rate" value="3" />
                    <label for="star3" title="text">3 stars</label>
                    <input type="radio" id="star2" name="rate" value="2" />
                    <label for="star2" title="text">2 stars</label>
                    <input type="radio" id="star1" name="rate" value="1" />
                    <label for="star1" title="text">1 star</label>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><input class="btn btn-danger" type="submit" value="Hinda"></div>
    </form>
    <div class="row">
            <div class="col-sm-4"> <a class="btn btn-primary btn-sm" href="index.php">Tagasi</a></div>
    </div>

    <table class="table table-sm">
          <tr>
                <th>Nimi</th>
                <th>Kommentaar</th>
                <th>Hinnang</th>
          </tr>
          <?php
                $paring = 'SELECT hinnangud.id as hinnangud_id, toidukohad.nimi as ettevotte_nimi, hinnangud.nimi as hindaja_nimi, hinnangud.kommentaar, hinnangud.hinnang, hinnangud.toidukohad_id 
                FROM toidukohad
                INNER JOIN hinnangud ON hinnangud.toidukohad_id=toidukohad.id
                WHERE toidukohad_id=' . $id;
                $valjund = mysqli_query($yhendus, $paring);
                while ($rida = mysqli_fetch_assoc($valjund)) {
                 echo '<tr>';
                 echo '<td>' . $rida['hindaja_nimi'] . '</td>';
                 echo '<td>' . $rida['kommentaar'] . '</td>';
                 echo '<td>' . $rida['hinnang'] . '/10</td>';
                 echo '<td><a href="kohvik.php?del=' . $rida['hinnangud_id'] . '&id='.$id.'"><span class="badge text-bg-danger">x</span></a></td>';
                 echo '</tr>';
                }
          ?>
          </table>





</div>
            <div class="col-2"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
