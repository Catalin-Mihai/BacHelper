<?php 
define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/includes/mysql.php'); 
require_once(__ROOT__.'/includes/defines.php'); 
if(isset($_GET['lectie']))
$lectie = intval($_GET['lectie']);
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Formule matematice</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="js/mathjax_custom.js"></script>
    <script type="text/javascript" async
    src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML" onload="loadedMathJax();">
    </script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <!--<script src="mathscribe/jqmath-etc-0.4.6.min.js" charset="utf-8"></script>-->
    <!--<script src="https://www.desmos.com/api/v1.1/calculator.js?apiKey=dcb31709b452b1cf9dc26972add0fda6"></script>-->
    <link rel="preload" href="https://www.desmos.com/api/v1.1/calculator.js?apiKey=dcb31709b452b1cf9dc26972add0fda6" as="script">

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Plugin CSS -->
    <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/freelancer.css" rel="stylesheet">


  </head>

  <body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-default bg-secondary fixed-top text-uppercase" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="index.php">Matematica</a>
        <button class="navbar-toggler navbar-toggler-right text-uppercase bg-primary text-white rounded" type="button" id = "ButtonClick" data-toggle="collapse" data-target="#navbarResponsive2" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive2">
          <ul class="navbar-nav ml-auto p-auto">
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#page-top"><i class="fa fa-home"></i> Numere reale</a>
            </li>
            <?php
            MySQL::Connect();
            $query = "SELECT `Tag`, `NumeCapitol` FROM `capitole` WHERE `Lectie` = '$lectie'";
            $result = MySQL::$ms_hMySQL->query($query);
            $a = array();
            if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
            if($result->num_rows > 0){
              while($row = $result->fetch_assoc()){
                echo "<li class=\"nav-item mx-0 mx-lg-1\">
                <a class=\"nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger\" id = \"MenuItem\" href=\"#".$row["Tag"]."\">".$row["NumeCapitol"]."</a>";
                array_push($a, $row["NumeCapitol"]);
              }
            }
            $rezumat = implode(' - ', $a);

            //facem header-ul ala de la inceput

            $query = "SELECT `Nume` FROM `lectii` WHERE `ID` = '".$lectie."' limit 1";
            $result = MySQL::$ms_hMySQL->query($query);

            $nume_lectie = mysqli_fetch_assoc($result);

            MySQL::CloseConnection();
            ?>
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="editpage.php"><i class="fa fa-edit"></i> Edit</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <header class="masthead bg-third text-white text-center">
      <div class="container">
        <h1 class="text-uppercase mb-0"><?=$nume_lectie['Nume']?></h1>
        <hr class="star-blue">
        <h2 class="font-weight-light mb-0"><?=$rezumat?></h2>
      </div>
    </header>
    <div class="continut">
    <?php
    function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }

    function stringInsert($str,$insertstr,$pos)
                  {
                       $str = substr($str, 0, $pos) . $insertstr . substr($str, $pos);
                       return $str;
              }  
    function getTextBetweenTags($string, $tagname)
   {
      $pattern = "/<$tagname>(.*?)<\/$tagname>/";
      preg_match($pattern, $string, $matches);
      if ( ! isset($matches[1])) {
        $matches[1] = null;
        }
      return $matches[1];
   }
    MySQL::Connect();
    $query = "SELECT `ID`, `Tag`, `NumeCapitol`, `Capitol` FROM `capitole` WHERE `Lectie` = '$lectie'";
    $result = MySQL::$ms_hMySQL->query($query);
    
    if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
    if($result->num_rows > 0){
          while($row = $result->fetch_assoc()){ //Gaseste toate capitolele //Sume remarcabile.. tralalala din lectia respectiva
            $nume_capitol = $row["NumeCapitol"];
            //debug_to_console($nume_capitol);
            echo '<div class="capitol2 bg-primary text-white">
                    <h1 class="text-uppercase d-block mb-0" id="'.$row["Tag"].'">'.$nume_capitol.'</h1>    
                  </div>';
            echo "<section id=\"capitole\">
            <div class=\"container\">";
            ?>

            <script src="https://www.desmos.com/api/v1.1/calculator.js?apiKey=dcb31709b452b1cf9dc26972add0fda6"></script>
            
            <?php
            $query2 = "SELECT `ID`, `Text`, `Type` FROM `continut` WHERE `Lectie` = '$lectie' AND `Capitol` =".$row["ID"]." ORDER BY `Order` ASC";
            $result2 = MySQL::$ms_hMySQL->query($query2);
            if(!$result2) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
            while($row2 = $result2->fetch_assoc()){ //Gaseste toate formulele/continutul capitolelor

              switch ($row2["Type"]) {

                case __TEXT__:
                  $sir = $row2["Text"];
                  $row2["Text"] = str_replace("+-", "±", $row2["Text"]); //plus-minus
                  $row2["Text"] = str_replace("-+", "∓", $row2["Text"]); //minus-plus
                  
                  echo '<div class="content" style="overflow-x: auto; overflow-y: hidden;">'.$row2["Text"].'</div>';
                      
                  break;

                case __TABEL_STIL_CARD__:
                  $sir = $row2["Text"];
                  //$text = getTextBetweenTags($sir, "<title>");
                  preg_match_all('/<title>(.*?)<\/title>/s', $sir, $matches);
                  $title = implode($matches[1]);
                  preg_match_all('/<body>(.*?)<\/body>/s', $sir, $matches);
                  $body = implode($matches[1]);
                  echo 
                   '<div class="card">
                      <div class="card-body">
                        <h6 class="card-title">'.$title.'</h6>
                        <div class="card-text" style="overflow-x: auto; overflow-y: hidden;">'.$body.'</div>
                      </div>
                    </div>';

                  break;

                case __ELEMENT_LISTA__:
                  $sir = $row2["Text"];
                  $row2["Text"] = str_replace("+-", "±", $row2["Text"]); //plus-minus
                  $row2["Text"] = str_replace("-+", "∓", $row2["Text"]); //minus-plus
                  
                  echo '<li class="list-group-item" style="overflow-x: auto; overflow-y: hidden;">'.$row2["Text"].'</li>'; //termenii listei
                  break;

                case __IMAGINE__:
                  $link_imagine = "img/".$row2["Text"];
                  $modalname = "portfolio-modal-".$row2["ID"];
                  echo "
                      <section style=\"padding: 2rem 0;\"class=\"portfolio\" id=\"portfolio\">      
                        <div class=\"col-md-6 col-lg-4\">   
                        <a class= \"portfolio-item d-block mx-auto\" href=\"#".$modalname."\" >
                          <div class=\"portfolio-item-caption d-flex position-absolute h-100 w-100\" id = \"".$row2["ID"]."a1"."\">
                            <div class=\"portfolio-item-caption-content my-auto w-100 text-center text-white\">
                              <i class=\"fa fa-search-plus fa-2x\"></i>
                            </div>
                          </div>
                          <img style=\"max-width: 100%\" class=\"img-fluid\" src=\"".$link_imagine."\" id = \"".$row2["ID"]."a2"."\">
                        </a>   
                        </div>  
                  </section>
                  ";
                  //Modal            
                  echo '<div class="portfolio-modal mfp-hide" id="'.$modalname.'">
                        <div class="portfolio-modal-dialog bg-white">
                          <a class="close-button d-none d-md-block portfolio-modal-dismiss" href="#">
                            <i class="fa fa-3x fa-times"></i>
                          </a>
                          <div class="container text-center">
                            <div class="row">
                              <div class="col-lg-12 mx-auto" >
                                <img class="img-fluid mb-6" src="'.$link_imagine.'" alt="">
                              </div>
                              <div style = "padding: 1.5rem 0;" class="col-lg-12 mx-auto">
                              <a class="btn btn-primary btn-lg middle rounded-pill portfolio-modal-dismiss" href="#">
                                  <i class="fa fa-close"></i>
                                  Close</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      ';

                  break;
                case __GRAFIC__:
                  
                    $modalname = "portfolio-modal-".$row2["ID"];
                    $link_imagine = "img/vezi_grafic_pic.png";
                    echo "
                      <section style=\"padding: 2rem 0;\"class=\"portfolio\" id=\"portfolio\">      
                        <div class=\"col-md-6 col-lg-4\">   
                        <a class= \"portfolio-item d-block mx-auto\" href=\"#".$modalname."\" >
                          <div class=\"portfolio-item-caption d-flex position-absolute h-95 w-100\" id = \"".$row2["ID"]."a1"."\">
                            <div class=\"portfolio-item-caption-content my-auto w-100 text-center text-white\">
                              <i class=\"fa fa-search-plus fa-2x\"></i>
                            </div>
                          </div>
                          <img style=\"max-width: 100%\" class=\"img-fluid\" src=\"".$link_imagine."\">
                        </a>   
                        </div>  
                  </section>
                  ";

                    echo '<div class="portfolio-modal mfp-hide" id="'.$modalname.'">
                        <div class="portfolio-modal-dialog bg-white">
                          <a class="close-button d-none d-md-block portfolio-modal-dismiss" href="#">
                            <i class="fa fa-3x fa-times"></i>
                          </a>
                          <div class="container text-center">
                            <div class="row">
                              <div class="col-lg-12 mx-auto">
                                <div id="calculator" style="height: 400px;"></div>
                              </div>
                              <div style = "padding: 1.5rem 0;" class="col-lg-12 mx-auto">
                              <a class="btn btn-primary btn-lg middle rounded-pill portfolio-modal-dismiss" href="#">
                                  <i class="fa fa-close"></i>
                                  Close</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      ';

                    echo "<script>
                    var elt = document.getElementById('calculator');
                    var calculator = Desmos.GraphingCalculator(elt);
                    calculator.setExpression({id:'graph1', latex:'".$row2["Text"]."'});
                  </script>";

                  break;  

                default:
                  echo '<li class="list-group-item">$'.$row2["Text"].'$</li>';
                  
                  break;
              }

              //echo "<p>id: ".$row["ID"]." Formula:".$row["Nume"]."</p>";
              //echo "<p>id: ".$row2["ID"]." Formula:".$row2["Text"]."</p>";

            }
            echo "
            </div>
            </section>";
          }
        }

    ?> 
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/freelancer.js"></script>

  </body>


</html>
