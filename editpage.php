<?php 
define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/includes/mysql.php'); 
require_once(__ROOT__.'/includes/defines.php'); 
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Editare</title>


    <!-- Bootstrap core JavaScript -->
    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
    <!-- Plugin JavaScript -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="js/mathjax_custom.js"></script>
	<script type="text/javascript" async
	  src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML" onload="loadedMathJax();">
	</script>
    <script src="https://www.desmos.com/api/v1.1/calculator.js?apiKey=dcb31709b452b1cf9dc26972add0fda6"></script>

    <!-- Custom fonts for this template -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

	<!-- Plugin CSS -->
    <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/editpage.css" rel="stylesheet" media="screen">

  </head>

  <body>

  	<header>
  		<h1 class="titlu">Modul editare</h1>
  		<div class="notificare alert alert-success alert-dismissible fade show text-center" role="alert" id="success-alert">
		  <strong>Salvat!</strong>
		</div>
		<div id="LectieCapitolSelectors">
		  	<form id = "LectieSelectArea">
			  <div class="form-group">
			    <label for="LectieSelect">Selecteaza lectia</label>
			    <select class="form-control" id="LectieSelect">
			    	<option selected disabled value = "0">Click pentru a selecta</option>
			      	<?php
				      	MySQL::Connect();
				      	$queryy = "SELECT * FROM `lectii`";
				      	$result = MySQL::$ms_hMySQL->query($queryy);
				      	if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
				      	if($result->num_rows > 0){	      		
				      		while($row = $result->fetch_assoc()){
				      			$optiune = $row["Nume"].' (ID: '.$row["ID"].')';
				      			echo '<option value ="'.$row["ID"].'">'.$optiune.'</option>';
				      		}
				      	}
				      	MySQL::CloseConnection();
				      	echo '<option value="-1">(+) Adauga lectie noua</option>';
			      	?>
			    </select>
			  </div>
			</form>
			<form id="CapitolSelectArea">
			  <div class="form-group">
			    <label for="CapitolSelect">Selecteaza capitolul</label>
			    <select class="form-control" disabled id="CapitolSelect">
			    	<option selected disabled>Click pentru a selecta</option>
			    </select>
			  </div> 
			</form>
		</div>	
	</header>	
		<div class="corp">
			<h1 class="titlu" id="titlu-lectie">Lectie</h1>
			<div id="continutLectie"></div>	
			<h1 class="titlu" id="titlu-capitole">Capitole</h1>	
			<div id="continutCapitol"></div>
		</div>
		<script src="js/mathjax_custom.js"></script>
		<!-- Plugin JavaScript -->
		<script src="mathscribe/jqmath-etc-0.4.6.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="vendor/jquery-easing/jquery.easing.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="js/editpage.js"></script>
  	</header>
  	
  </body>

</html>