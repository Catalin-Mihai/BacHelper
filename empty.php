if(file_exists($path)){ //Exista directorul L_*

	//file_put_contents(__ROOT__.'/img'.$url, $data);
}
else {

	trigger_error("Verificare director /L_*");
	if (!mkdir($path, 0777, true)) {
	    trigger_error('Nu s-a putut crea directorul /L_*');
	}
	else {
		
		trigger_error("Verificare director /C_*");
		if (!mkdir($path."/C_".$capitol, 0777, true)) {
	   	 	trigger_error('Nu s-a putut crea directorul /C_*');
		}
		else {

			file_put_contents(__ROOT__.'/img'.$url, $data);
		}
	}
}