<?php 
    include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
            clear: both;
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
            color: #43d037;  
        }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label {
            color: #43d000;
        }
    </style>
</head>
<body>
   <div class="container">
<?php
    if(!empty($_GET['id'])){
        $id = $_GET['id'];
        $paring = "SELECT * FROM toidukohad WHERE id='$id'";
        $valjund = mysqli_query($yhendus, $paring);
        $rida = mysqli_fetch_assoc($valjund);
        // var_dump($rida['nimi']);
        echo "<h1>Hinda kohvikut: ". $rida['nimi']."</h1>";
    } else {
        echo "Kohvikut ei leitud!";
    }

    if (!empty($_GET['rate'])) {
        $id = $_GET['id'];
        $rate = $_GET['rate'];
        $nimi = $_GET['nimi'];
        $kommentaar = $_GET['kommentaar'];
        $paring = "INSERT INTO hinnangud (nimi, kommentaar, hinnang, toidukohad_id) VALUES ('$nimi', '$kommentaar', '$rate', '$id')";
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

        if ($valjund) {
            echo "Hinnang edukalt lisatud!";
            header('Location: kohvik.php?id=' . $id);
        } else {
            echo "Hinnangu lisamine ebaõnnestus!";
        }
    }
?>

    <form action="" method="get">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <label for="nimi">Nimi</label>
                <input type="text" id="nimi" name="nimi" require><br>
                <label for="kommentaar">Kommentaar</label>
                <textarea required name="kommentaar" id="kommentaar" rows="4" cols="50"></textarea>
                <div class="rate">
                    <input type="radio" id="star10" name="rate" value="10" />
                    <label for="star10" title="text">5 stars</label>
                    <input type="radio" id="star9" name="rate" value="9" />
                    <label for="star9" title="text">4 stars</label>
                    <input type="radio" id="star8" name="rate" value="8" />
                    <label for="star8" title="text">3 stars</label>
                    <input type="radio" id="star7" name="rate" value="7" />
                    <label for="star7" title="text">2 stars</label>
                    <input type="radio" id="star6" name="rate" value="6" />
                    <label for="star6" title="text">1 star</label>
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
                <br>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" value="Hinda" class="btn btn-success">
            </div>
            <div class="col-sm-3"></div>
        </div>
    </form>
    <h2>Teiste hinnangud</h2>
    <?php
        $paring = "SELECT * FROM hinnangud WHERE toidukohad_id='$id' ORDER BY id DESC";
        $valjund = mysqli_query($yhendus, $paring);
        while($rida = mysqli_fetch_assoc($valjund)){
            echo "<p><strong>".$rida['nimi']." (".$rida['hinnang']."/10)</strong></p>";
            echo "<p>".$rida['kommentaar']."</p>";
        }

        ?>





    </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>