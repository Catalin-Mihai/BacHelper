<?php 
define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/includes/mysql.php'); 
require_once(__ROOT__.'/includes/defines.php'); 

MySQL::Connect();

if(isset($_POST['tip_salvare']))
$tip_salvare = intval($_POST['tip_salvare']);

if(isset($_POST['id']))
$id = intval($_POST['id']);

if(isset($_POST['componenta']))
$componenta = intval($_POST['componenta']);

if(isset($_POST['extra']))
{
	if(is_string($_POST['extra']))
	$extra = MySQL::$ms_hMySQL->escape_string($_POST['extra']);
	else $extra = $_POST['extra'];
}

if(isset($_POST['valoare']))
{
	if(is_string($_POST['valoare']))
	$valoare = MySQL::$ms_hMySQL->escape_string($_POST['valoare']);
	else $valoare = $_POST['valoare'];
}

switch ($tip_salvare) {
	case __SALVARE_NUME_CAPITOL__:

			if($id == -1) {

				$query = "INSERT INTO capitole (NumeCapitol, Lectie) VALUES ('".$valoare."', '".$extra."')";
				$result = MySQL::$ms_hMySQL->query($query);
				if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
				else {
					$last_id = MySQL::$ms_hMySQL->insert_id;
					$tag = substr($valoare, 0, 4);
					$tag = $tag.$last_id;
					echo $last_id;

					$query2 = "UPDATE `capitole` SET `Tag` = '".$tag."' WHERE ID = '".$last_id."'";
					$result2 = MySQL::$ms_hMySQL->query($query2);
					if(!$result2) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);

					$query3 = "SELECT * FROM `capitole` WHERE Lectie = '".$extra."'";
					$result3 = MySQL::$ms_hMySQL->query($query3);
					if(!$result3) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
					$nr_capitol = $result3->num_rows; //are deja +1 de la insert :D

					$query4 = "UPDATE `capitole` SET `Capitol` = '".$nr_capitol."' WHERE ID = '".$last_id."'";
					$result4 = MySQL::$ms_hMySQL->query($query4);
					if(!$result4) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
				}

				//Fotografie default
				$source = __ROOT__.'/img/default-lectie.png';
				$dest = __ROOT__.'/img/Lectie_'.$last_id.'.png';			
				if (!copy($source, $dest)) {
				    trigger_error("Nu s-a copiat");
				}
			}
			else {

				$query = "UPDATE `capitole` SET `NumeCapitol` = '".$valoare."' WHERE `ID` = '".$id."'";
				$result = MySQL::$ms_hMySQL->query($query);
				if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
			}


		break;
	
	case __SALVARE_NUME_LECTIE__:

			if($id == -1) {
				
				$query = "INSERT INTO lectii (Nume) VALUES ('".$valoare."')";
				$result = MySQL::$ms_hMySQL->query($query);
				if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
				else {
					$last_id = MySQL::$ms_hMySQL->insert_id;
					echo $last_id;
				}

				//Fotografie default
				$source = __ROOT__.'/img/default-lectie.png';
				$dest = __ROOT__.'/img/Lectie_'.$last_id.'.png';			
				if (!copy($source, $dest)) {
				    trigger_error("Nu s-a copiat");
				}
			}
			else{

				$query = "UPDATE `lectii` SET `Nume` = '".$valoare."' WHERE `ID` = '".$id."'";
				$result = MySQL::$ms_hMySQL->query($query);
				if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
			}
			
		break;

	case __STERGERE_OBIECT__:

		//Sterge obiect din `continut`
		$query = "DELETE FROM `continut` WHERE `ID` = '".$id."'";
		$result = MySQL::$ms_hMySQL->query($query);
		if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);	
			
		break;

	case __STERGERE_LECTIE__:

		//Sterge continutul specific lectiei din `continut`
		$query = "DELETE FROM `continut` WHERE `Lectie` = '".$id."'";
		$result = MySQL::$ms_hMySQL->query($query);
		if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);	

		//Sterge capitolele lectiei din `capitole`
		$query = "DELETE FROM `capitole` WHERE `Lectie` = '".$id."'";
		$result = MySQL::$ms_hMySQL->query($query);
		if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);	

		//Sterge lectia din `lectii`
		$query = "DELETE FROM `lectii` WHERE `ID` = '".$id."'";
		$result = MySQL::$ms_hMySQL->query($query);
		if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);	
			
		break;

	case __STERGERE_CAPITOL__:

		//Sterge continutul specific capitolului din `continut`
		$query = "DELETE FROM `continut` WHERE `Capitol` = '".$id."'";
		$result = MySQL::$ms_hMySQL->query($query);
		if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);	

		//Sterge capitolele din `capitole`
		$query = "DELETE FROM `capitole` WHERE `ID` = '".$id."'";
		$result = MySQL::$ms_hMySQL->query($query);
		if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);	
			
		break;		

	case __SALVARE_IMAGINE_LECTIE__:

		$data = $valoare;
		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);

		file_put_contents(__ROOT__.'/img/Lectie_'.$id.'.png', $data);

		break;

	case __SALVARE_CONTINUT_OBIECT_CAPITOL__:

		if($id == -1) { //Inserare
			
			list($lectie, $capitol) = explode(',', $extra);

			switch ($componenta) {

				case __IMAGINE__:
					
					$data = $valoare;
					list($type, $data) = explode(';', $data);
					list(, $data)      = explode(',', $data);
					$data = base64_decode($data);

					$query = "INSERT INTO `continut` (`Lectie`, `Capitol`, `Type`) VALUES ('".$lectie."', '".$capitol."', '".$componenta."')";
					$result = MySQL::$ms_hMySQL->query($query);
					if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
					else {

						$last_id = MySQL::$ms_hMySQL->insert_id;
						$url = '/lectii/L_'.$lectie."/C_".$capitol."/IMG_".$last_id.'.png';
						$path = __ROOT__.'/img/lectii/L_'.$lectie."/C_".$capitol;

						if(file_exists($path)){ //Exista directorul L_*

							file_put_contents(__ROOT__.'/img'.$url, $data);
							//trigger_error('Exista path');
						}
						else {

							if (!mkdir($path, 0777, true)) {
							   	trigger_error('Nu s-a putut crea directorul: '.$path);
							}
							else {

								file_put_contents(__ROOT__.'/img'.$url, $data);
							}
						}

						$query4 = "UPDATE `continut` SET `Text` = '".$url."' WHERE ID = '".$last_id."'";
						$result4 = MySQL::$ms_hMySQL->query($query4);
						if(!$result4) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);

						$query2 = "SELECT MAX(`Order`) AS MaxOrder FROM `continut` WHERE `Lectie` = '".$lectie."' AND `Capitol` = '".$capitol."' LIMIT 1;";
						$result2 = MySQL::$ms_hMySQL->query($query2);
						if(!$result2) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
						else {

							$value = $result2->fetch_assoc();
							$value["MaxOrder"] = $value["MaxOrder"] + 1;

							$query3 = "UPDATE `continut` SET `Order` = '".$value["MaxOrder"]."' WHERE ID = '".$last_id."'";
							$result3 = MySQL::$ms_hMySQL->query($query3);
							if(!$result3) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);

							echo $last_id;
						}
					}
								
					break;
				
				default:

					$query = "INSERT INTO `continut` (`Lectie`, `Capitol`, `Type`, `Text`) VALUES ('".$lectie."', '".$capitol."', '".$componenta."', '".$valoare."')";
					$result = MySQL::$ms_hMySQL->query($query);
					if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
					else {

						$last_id = MySQL::$ms_hMySQL->insert_id;
						$query2 = "SELECT MAX(`Order`) AS MaxOrder FROM `continut` WHERE `Lectie` = '".$lectie."' AND `Capitol` = '".$capitol."' LIMIT 1;";
						$result2 = MySQL::$ms_hMySQL->query($query2);
						if(!$result2) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
						else {

							$value = $result2->fetch_assoc();
							$value["MaxOrder"] = $value["MaxOrder"] + 1;
							$query3 = "UPDATE `continut` SET `Order` = '".$value["MaxOrder"]."' WHERE ID = '".$last_id."'";
							$result3 = MySQL::$ms_hMySQL->query($query3);
							if(!$result3) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);

							echo $last_id;
						}
					}

					break;
			}
		}
		else {
				
			switch ($componenta) {

				case __IMAGINE__:
					
					$data = $valoare;
					list($type, $data) = explode(';', $data);
					list(, $data)      = explode(',', $data);
					$data = base64_decode($data);

					list($lectie, $capitol) = explode(',', $extra);
					$url = '/lectii/L_'.$lectie."/C_".$capitol."/IMG_".$id.'.png';
					file_put_contents(__ROOT__.'/img'.$url, $data);

					$query = "UPDATE `continut` SET `Text` = '".$url."' WHERE `ID` = '".$id."'";
					$result = MySQL::$ms_hMySQL->query($query);
					if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
					
					break;
				
				default:

					$query = "UPDATE `continut` SET `Text` = '".$valoare."' WHERE `ID` = '".$id."'";
					$result = MySQL::$ms_hMySQL->query($query);
					if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);

					break;
			}	
		}
		
		break;

	case __SALVARE_ORDINE_OBIECT_CAPITOL__:

		$query = "UPDATE `continut` SET `Order` = '".$valoare."' WHERE `ID` = '".$id."'";
		$result = MySQL::$ms_hMySQL->query($query);
		if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);

		break;	
	
	default:
		echo 'b';
		break;
	}
	
MySQL::CloseConnection();
?>
