<!DOCTYPE html>
<html lang="en">
  <head>
    <title>AB</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  <div class="back">
        <a href="Rezervacija bioskopskih karata sa bazom.php" class="btn btn-info" role="button">Vrati se nazad</a>
    </div>
    <?php
    $id="";
    $idErr="";
    $i=0;
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_POST["id"]))
        {
            $idErr="ID mora biti ispunjen!";
        } else
        {
            if(!is_numeric($_POST["id"]))
            {
              $idErr = "ID mora biti broj!";
            }
            $id= test_input($_POST["id"]);
        }
        include "Konekcija na bazu.php";
        $sql = "SELECT id FROM RezervacijaB";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              if($row["id"]==$id)
              {
                $i++;       
              } else
              {
                $idErr="ID ne postoji!";
              }
              if($i==1)
              {
                $idErr="";
                break;
              }
            }
        }
        if($idErr=="")
        {
            $sql="DELETE FROM RezervacijaB WHERE id='$id'";
            $conn->query($sql);
        }
        $conn->close();
    }
    include "Konekcija na bazu.php";
    $sql = "SELECT id, ime, prezime, email, termin, film, brojs FROM RezervacijaB";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "id: " . $row["id"]. "<br>Ime: " . $row["ime"]. " " . $row["prezime"]. "<br>Termin: " . $row["termin"] ."<br>Film: " . $row["film"] . "<br>Broj sedista: " . $row["brojs"] . "<br><br>";
    }
  } else {
    echo "0 results";
  }
  $conn->close();
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group pl-5 pr-5">
                <label for="id">Izaberi id reda koji hoces izbrisati:</label>
                <input type="text" class="form-control" id="id" name="id" value="<?php echo $id;?>">
                <span class="error"><?php echo $idErr;?></span>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary text-center">Izbrisi</button>
            </div>
        </form>
  </body>
</html>
