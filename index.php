<?php

error_reporting(-1);
date_default_timezone_set('Europe/Ljubljana');

define('MYSQL_HOST', '92.244.93.250');
define('MYSQL_USER', 'jakob');
define('MYSQL_PASS', '');
define('MYSQL_DB'  , 'jakob');
$mysqli = mysqli_init();
if(!$mysqli->real_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB))
{
  die('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if(isset($_POST['title']) && isset($_POST['exp-date']) && $_POST['title'] != null && $_POST['exp-date'] != null)
{
  $query = 'INSERT INTO izdelki (naziv, datum_poteka) VALUES (?, ?)';
  
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("ss", $_POST['title'], $_POST['exp-date']);
  $stmt->execute();  
  $stmt->close();
}
if(isset($_POST['removeProduct']))
{
  $query = 'DELETE FROM izdelki WHERE id=?';
  
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $_POST['removeProduct']);
  $stmt->execute();
  $stmt->close();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
    
    <script src="js/jquery-2.1.3.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
   <div class="container">
      <div class="row">
          <div class="col-sm-4">
             <h1 class="main-title">FOOD SAVER</h1>
              <form action="" method="post" id="add-form">
               <fieldset>
                   <legend>Dodaj izdelek</legend>
                    <input type="text" name="title" id="title" placeholder="Naziv izdelka" class="form-control">
                    <br><br>
                    <input type="date" name="exp-date" id="exp-date" class="form-control">
                    <br><br>
                    <input type="submit" value="Dodaj" class="btn btn-info" align="right">
                </fieldset>
            </form>
            
            <br>
            
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                 
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="img/pic1.jpg">
                    </div>
                    <div class="item">
                        <img src="img/pic2.jpg">
                    </div>
                    <div class="item">
                        <img src="img/pic3.jpg">
                    </div>
                </div>
            </div>

          </div>
          <div class="col-sm-8">
              <form action="" method="post">
              <table>
                  <tr>
                      <th>Naziv</th>
                      <th colspan="2">Datum poteka</th>
                  </tr>
                  
                  <?php
                      $query = "SELECT * FROM izdelki ORDER BY datum_poteka";
                      $stmt = $mysqli->stmt_init();
                      $stmt->prepare($query);
                      $stmt->execute();
                      $stmt->bind_result($id, $name, $date);
                      while($stmt->fetch()):
                  ?>
                  <tr <?php if(strtotime($date) < time()) echo 'class="expired"'; ?>>
                      <td><?php echo $name; ?></td>
                      <td><?php echo date('d.m.Y', strtotime($date)); ?></td>
                      <td><button class="btn btn-danger" type="submit" name="removeProduct" value="<?php echo $id; ?>">Delete</button></td>
                  </tr>
                  <?php endwhile; $stmt->close(); $mysqli->close(); ?>
                  
              </table>
            </form>
          </div>
        </div>
    </div>
</body>
</html>
