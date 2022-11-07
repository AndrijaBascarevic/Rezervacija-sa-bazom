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
        <a href="http://localhost/AB/" class="btn btn-info" role="button">Back to main page</a>
    </div>
    <br>
    <?php
        $nameErr = $emailErr = $filmErr = $termErr = $brErr = "";
        $name = $email = $film = $term = $br = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          if (empty($_POST["name"])) {
            $nameErr = "Ime je obavezno!";
          } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
              $nameErr = "Samo slova i prazna mesta!";
            }
          }
          
          if (empty($_POST["email"])) {
            $emailErr = "Email je neophodan!";
          } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $emailErr = "Los email format!";
            }
          }
            
          if (empty($_POST["film"])) {
            $filmErr="Morate izabrati film!";
          } else {
            $film = test_input($_POST["film"]);
          }
        
          if (empty($_POST["term"])) {
            $termErr = "Morate izabrati termin!";
          } else {
            $term = test_input($_POST["term"]);
          }
        
          if (empty($_POST["br"])) {
            $brErr = "Broj sedista je neophodan!";
          } else {
            if(!is_numeric($_POST["br"]))
            {
              $brErr = "Broj sedista mora biti broj!";
            }
            $br = test_input($_POST["br"]);
          }
          if($brErr == "" && $nameErr == "" && $emailErr == "" && $filmErr == "" && $termErr=="")
          {
            include 'Konekcija na bazu.php';
            /*TABLE
            $sql = "CREATE TABLE RezervacijaB (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                ime VARCHAR(30) NOT NULL,
                prezime VARCHAR(30) NOT NULL,
                email VARCHAR(50) NOT NULL,
                termin VARCHAR(30) NOT NULL,
                film VARCHAR(30) NOT NULL,
                brojs int(5) NOT NULL,
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
            if ($conn->query($sql) === TRUE) {
                echo "Table created successfully <br>";
            } else {
                echo "Error creating table: " . $conn->error;
            }*/
            
            // UBACIVANJE POD
            $ime=explode(" ",$name);
            $stmt = $conn->prepare("INSERT INTO RezervacijaB (ime, prezime, email, termin, film, brojs) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $ime[0], $ime[1], $email, $term, $film, $br);
            $stmt->execute();
            
            $stmt->close();
            $conn->close();
          } 
        } 
        
        function test_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }
    ?>
    <div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group pl-5 pr-5">
                <label for="name">Ime:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name;?>">
                <span class="error"><?php echo $nameErr;?></span>
            </div>
            <div class="form-group pl-5 pr-5">
                <label for="email">E-mail:</label>
                <input type="text" class="form-control" name="email" id="email" value="<?php echo $email;?>">
                <span class="error"><?php echo $emailErr;?></span>
            </div>
            <div class="form-group pl-5 pr-5">
                <label for="film">Film:</label>
                <select class="form-control" name="film" id="film">
                    <option value="The Godfather"> The Godfather </option>
                    <option value="Rush Hour"> Rush Hour </option>
                    <option value="Pulp Fiction"> Pulp Fiction </option>
                </select>
                <span class="error"><?php echo $filmErr;?></span>
            </div>
            <div class="form-group pl-5 pr-5">
                <label for="term">Termini:</label>
                <select class="form-control" name="term" id="term">
                    <option value="18h"> 18h </option>
                    <option value="20h"> 20h </option>
                    <option value="22h"> 22h </option>
                </select>
                <span class="error"><?php echo $termErr;?></span>
            </div>
            <div class="form-group pl-5 pr-5">
                <label for="br">Broj sedista:</label>
                <input type="text" class="form-control" id="br" name="br" value="<?php echo $br;?>">
                <span class="error"><?php echo $brErr;?></span>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary text-center">Rezervisi</button>
            </div>
            <div class="form-group text-center">
              <a role="button" href="Bioskop ispis.php" class="btn btn-secondary text-center">Ispisi unete podatke u tabeli</a>
            </div>
        </form>
    </div>
  </body>
</html>