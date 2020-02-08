<?php 
define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/includes/mysql.php'); 
require_once(__ROOT__.'/includes/defines.php'); 


if(isset($_GET['lectie']))
$lectie = intval($_GET['lectie']);

if(isset($_GET['capitol']))
$capitol = intval($_GET['capitol']);

if(isset($_GET['tip']))
$tip = $_GET['tip'];
MySQL::Connect();
	
	switch ($tip) {

		case 'capitole':

			$queryy = "SELECT * FROM `capitole` WHERE `Lectie` = '".$lectie."'";
			$result = MySQL::$ms_hMySQL->query($queryy);
			if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
			if($result->num_rows > 0){	      	

				while($row = $result->fetch_assoc()){
					$optiune = $row["NumeCapitol"].' (ID: '.$row["ID"].')';
					echo '<option value ="'.$row["ID"].'">'.$optiune.'</option>';
				}	
			}
		break;

		case 'continut':

			if($capitol != 0){

				//NUMELE CAPITOLULUI
				$query2 = "SELECT * FROM `capitole` WHERE `ID` = '".$capitol."'";
				$result2 = MySQL::$ms_hMySQL->query($query2);
				if(!$result2) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
				$row2 = $result2->fetch_assoc();

				$out_string = 
					'<div class="obiect-lista" data-dbid="'.$capitol.'">
		              <div class="form-row">
		                <label for="NumeStergereCapitol">Do you want to delete the chapter?</label>
		                <div class="input-group">
		                  <button type="button" class="btn btn-danger" id="delete-capitol">Delete chapter</button>
		                </div>
		              </div>
		            </div>
					<div class="obiect-lista">
						<div class="form-row">
							<label for="NumeCapitol">Chapter name</label>
							<div class="input-group">
								<input type="text" class="form-control" id="NumeCapitol" data-dbid="'.$capitol.'" placeholder="'.$row2["NumeCapitol"].'" value="'.$row2["NumeCapitol"].'">
								<div class="obiect-icons">
									<i class="fas fa-save fa-2x" data-toggle="tooltip" data-placement="bottom" title="Save the new name" id="save-titlu-capitol"></i>
									<i class="fas fa-undo fa-2x" data-toggle="tooltip" data-placement="bottom" title="Restore the old name" id="undo-titlu-capitol"></i>
								</div>
							</div>
					  	</div>
					  </div>
					  ';
				$array = array();
				$calculators = array();
				$images = array();
				$query = "SELECT * FROM `continut` WHERE `Capitol` = '".$capitol."' AND `Lectie`='".$lectie."' ORDER BY `Order` ASC";
				$result = MySQL::$ms_hMySQL->query($query);
				$index = 0;
				$calc_index  = 0;
				if(!$result) trigger_error('Invalid query: '.MySQL::$ms_hMySQL->error);
				if($result->num_rows > 0){	

					while($row = $result->fetch_assoc()){

						array_push($array, array(
							'id' => $index,
							'text' => $row['Text'],
							'dbid' => $row['ID'],
							'order' => $row['Order']
							)
						); 
						$info = "DBID: ".$row["ID"]." Order: ".$row["Order"];
						$modalname = "portfolio-modal-".$row["ID"];
						$label_for = "element-".$row["ID"];

						switch ($row["Type"]) {

							case __TEXT__:
								$out_string.=
								'<div class="obiect-lista" data-dbid="'.$row['ID'].'" data-type="'.__TEXT__.'">
							   		<div class="informatii">'.$info.'</div>
							   		<div class="form-row">
							   			<label for="'.$label_for.'">Text Area</label>
							   			<textarea class="form-control" rows="8" value="12" id="'.$label_for.'">'.$row['Text'].'</textarea>
							   		</div>
							   		<div class="obiect-icons">
								   		<div class="under-obiect">
											<i class="save-icon fas fa-save fa-2x" data-toggle="tooltip" data-placement="bottom" title="Save the new name" id="save-obiect-data"></i>
											<i class="undo-icon fas fa-undo fa-2x" data-toggle="tooltip" data-placement="bottom" title="Restore the old name" id="undo-obiect-data"></i>
											<i class="preview-icon fas fa-glasses fa-2x" data-toggle="tooltip" data-placement="bottom" title="Preview the element" id="preview-obiect" href="#'.$modalname.'"></i>
											<i class="move-up-icon fas fa-arrow-circle-up fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element above" id="move-up-obiect"></i>
											<i class="move-down-icon fas fa-arrow-circle-down fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element below" id="move-down-obiect"></i>
											<i class="delete-icon fas fa-trash fa-2x" data-toggle="tooltip" data-placement="bottom" title="Delete element" id="delete-obiect"></i>
										</div>	
									</div>

									<div class="portfolio-modal mfp-hide" id="'.$modalname.'">
				                        <div class="portfolio-modal-dialog bg-white">
				                          	<a class="close-button d-none d-md-block portfolio-modal-dismiss" href="#">
				                            	<i class="fa fa-3x fa-times"></i>
				                          	</a>
				                          	<div class="container">
				                            	<div class="row">
				                              		<div class="col-lg-12 mx-auto" >
				                              			<div class="text">
				                                			<div class="content">'.$row["Text"].'</div>
				                                		</div>
				                              		</div>
				                              		<div style = "padding: 1.5rem 0;" class="col-lg-12 mx-auto text-center">
				                              			<a class="btn btn-primary btn-lg middle rounded-pill portfolio-modal-dismiss" href="#">
				                                  			<i class="fa fa-close"></i>
				                                  		Close</a>
				                              		</div>
				                            	</div>
				                        	</div>
			                        	</div>
			                        </div>

		                      	</div>';

								break;
							
							case __ELEMENT_LISTA__:

								$out_string.=
								'<div class="obiect-lista" data-dbid="'.$row['ID'].'" data-type="'.__ELEMENT_LISTA__.'">
									<div class="informatii">'.$info.'</div>
									<div class="form-row">
							   			<label for="'.$label_for.'">Text frame</label>
							   			<textarea class="form-control" rows="8" value="12" id="'.$label_for.'">'.$row['Text'].'</textarea>
							   		</div>
							   		<div class="obiect-icons">
								   		<div class="under-obiect">
											<i class="save-icon fas fa-save fa-2x" data-toggle="tooltip" data-placement="bottom" title="Save the new name" id="save-obiect-data"></i>
											<i class="undo-icon fas fa-undo fa-2x" data-toggle="tooltip" data-placement="bottom" title="Restore the old name" id="undo-obiect-data"></i>
											<i class="preview-icon fas fa-glasses fa-2x" data-toggle="tooltip" data-placement="bottom" title="Preview the element" id="preview-obiect" href="#'.$modalname.'"></i>
											<i class="move-up-icon fas fa-arrow-circle-up fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element above" id="move-up-obiect"></i>
											<i class="move-down-icon fas fa-arrow-circle-down fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element below" id="move-down-obiect"></i>
											<i class="delete-icon fas fa-trash fa-2x" data-toggle="tooltip" data-placement="bottom" title="Delete element" id="delete-obiect"></i>
										</div>	
									</div>

									<div class="portfolio-modal mfp-hide" id="'.$modalname.'">
				                        <div class="portfolio-modal-dialog bg-white">
				                          	<a class="close-button d-none d-md-block portfolio-modal-dismiss" href="#">
				                            	<i class="fa fa-3x fa-times"></i>
				                          	</a>
				                          	<div class="container">
				                            	<div class="row">
				                              		<div class="col-lg-12 mx-auto" >
				                                		<li class="list-group-item" style="overflow-x: auto;">
				                                			<div class="text">
				                                				<p class="content">'.$row["Text"].'</p>
				                                			</div>
				                                		</li>
				                                		<p>Foloseste doua semne de dolar($) la inceputul si la sfarsitul ecuatiei/formulei pentru a specifica capetele</p>
				                              		</div>
				                              		<div style = "padding: 1.5rem 0;" class="col-lg-12 mx-auto text-center">
				                              			<a class="btn btn-primary btn-lg middle rounded-pill portfolio-modal-dismiss" href="#">
				                                  			<i class="fa fa-close"></i>
				                                  		Close</a>
				                              		</div>
				                            	</div>
				                        	</div>
			                        	</div>
			                        </div>

							  	</div>';

								break;

							case __GRAFIC__:

								$calculator = "calculator-".$row["ID"];

								array_push($calculators, array(
									'id' => $index,
									'dbid' => $row['ID']
								));

								$out_string.=
								'<div class="obiect-lista" data-dbid="'.$row['ID'].'" data-type="'.__GRAFIC__.'">
							   		<div class="informatii">'.$info.'</div>
							   		<div class="form-row">
							   			<label for="'.$label_for.'">Mathematical formula for the plot. Example: y=x^2</label>
							   			<textarea class="form-control" rows="8" value="12" id="'.$label_for.'">'.$row['Text'].'</textarea>
							   		</div>
							   		<div class="obiect-icons">
								   		<div class="under-obiect">
											<i class="save-icon fas fa-save fa-2x" data-toggle="tooltip" data-placement="bottom" title="Save the new name" id="save-obiect-data"></i>
											<i class="undo-icon fas fa-undo fa-2x" data-toggle="tooltip" data-placement="bottom" title="Restore the old name" id="undo-obiect-data"></i>
											<i class="preview-icon fas fa-glasses fa-2x" data-toggle="tooltip" data-placement="bottom" title="Preview the element" id="preview-obiect" href="#'.$modalname.'"></i>
											<i class="move-up-icon fas fa-arrow-circle-up fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element above" id="move-up-obiect"></i>
											<i class="move-down-icon fas fa-arrow-circle-down fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element below" id="move-down-obiect"></i>
											<i class="delete-icon fas fa-trash fa-2x" data-toggle="tooltip" data-placement="bottom" title="Delete element" id="delete-obiect"></i>
										</div>	
									</div>

									<div class="portfolio-modal mfp-hide" id="'.$modalname.'">
				                        <div class="portfolio-modal-dialog bg-white">
				                          	<a class="close-button d-none d-md-block portfolio-modal-dismiss" href="#">
				                            	<i class="fa fa-3x fa-times"></i>
				                          	</a>
				                          	<div class="container">
				                            	<div class="row">
				                              		<div class="col-lg-12 mx-auto" >
				                              			<div class="text">
				                                			<div id="'.$calculator.'" style="height: 400px;"></div>
				                                		</div>
				                              		</div>
				                              		<div style = "padding: 1.5rem 0;" class="col-lg-12 mx-auto text-center">
				                              			<a class="btn btn-primary btn-lg middle rounded-pill portfolio-modal-dismiss" href="#">
				                                  			<i class="fa fa-close"></i>
				                                  		Close</a>
				                              		</div>
				                            	</div>
				                        	</div>
			                        	</div>
			                        </div>

		                      	</div>';
		                  
								break;


							case __TABEL_STIL_CARD__:


								$sir = $row["Text"];
			                  	//$text = getTextBetweenTags($sir, "<title>");
			                  	preg_match_all('/<title>(.*?)<\/title>/s', $sir, $matches);
				               	$title = implode($matches[1]);
				                preg_match_all('/<body>(.*?)<\/body>/s', $sir, $matches);
				                $body = implode($matches[1]);

								$out_string.=
								'<div class="obiect-lista" data-dbid="'.$row['ID'].'" data-type="'.__TABEL_STIL_CARD__.'">
							   		<div class="informatii">'.$info.'</div>
							   		<div class="form-row">
							   			<label for="'.$label_for.'-1">Card title</label>
							   			<textarea class="form-control" rows="2" value="12" id="'.$label_for.'-1">'.$title.'</textarea>
							   			<label for="'.$label_for.'-2">Card body</label>
							   			<textarea class="form-control" rows="8" value="12" id="'.$label_for.'-2">'.$body.'</textarea>
							   		</div>
							   		<div class="obiect-icons">
								   		<div class="under-obiect">
											<i class="save-icon fas fa-save fa-2x" data-toggle="tooltip" data-placement="bottom" title="Save the new name" id="save-obiect-data"></i>
											<i class="undo-icon fas fa-undo fa-2x" data-toggle="tooltip" data-placement="bottom" title="Restore the old name" id="undo-obiect-data"></i>
											<i class="preview-icon fas fa-glasses fa-2x" data-toggle="tooltip" data-placement="bottom" title="Preview the element" id="preview-obiect" href="#'.$modalname.'"></i>
											<i class="move-up-icon fas fa-arrow-circle-up fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element above" id="move-up-obiect"></i>
											<i class="move-down-icon fas fa-arrow-circle-down fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element below" id="move-down-obiect"></i>
											<i class="delete-icon fas fa-trash fa-2x" data-toggle="tooltip" data-placement="bottom" title="Delete element" id="delete-obiect"></i>
										</div>	
									</div>

									<div class="portfolio-modal mfp-hide" id="'.$modalname.'">
				                        <div class="portfolio-modal-dialog bg-white">
				                          	<a class="close-button d-none d-md-block portfolio-modal-dismiss" href="#">
				                            	<i class="fa fa-3x fa-times"></i>
				                          	</a>
				                          	<div class="container">
				                            	<div class="row">
				                              		<div class="col-lg-12 mx-auto" >
				                              			<div class="text">
				                                			<div class="card">
										                      <div class="card-body">
										                        <h6 class="card-title">'.$title.'</h6>
										                        <div class="card-text">'.$body.'</div>
										                      </div>
										                    </div>
				                                		</div>
				                              		</div>
				                              		<div style = "padding: 1.5rem 0;" class="col-lg-12 mx-auto text-center">
				                              			<a class="btn btn-primary btn-lg middle rounded-pill portfolio-modal-dismiss" href="#">
				                                  			<i class="fa fa-close"></i>
				                                  		Close</a>
				                              		</div>
				                            	</div>
				                        	</div>
			                        	</div>
			                        </div>

		                      	</div>';

								break;

							case __IMAGINE__:

								$link_imagine = "img/".$row["Text"];
								
								array_push($images, array(
									'id' => $index,
									'dbid' => $row['ID'],
									'url_imagine_server' => $link_imagine
								));

								$out_string.=
								'<div class="obiect-lista" data-dbid="'.$row['ID'].'" data-type="'.__IMAGINE__.'">
							   		<div class="informatii">'.$info.'</div>
							   		<div class="form-row">
							   			<label for="'.$label_for.'">Image</label>
							   			<div class="input-group">
							              <div class="custom-file">
							                <input type="file" class="form-control-file" id="'.$label_for.'">
							                <label class="custom-file-label" for="'.$label_for.'">Browse picture</label>
							              </div>						              
							            </div>
							            <div>
							              	<img style="max-width: 100%" class="img-fluid" src="'.$link_imagine.'">
							            </div>
							   		</div>
							   		<div class="obiect-icons">
								   		<div class="under-obiect">
											<i class="save-icon fas fa-save fa-2x" data-toggle="tooltip" data-placement="bottom" title="Save the new name" id="save-obiect-data"></i>
											<i class="undo-icon fas fa-undo fa-2x" data-toggle="tooltip" data-placement="bottom" title="Restore the old name" id="undo-obiect-data"></i>
											<i class="preview-icon fas fa-glasses fa-2x" data-toggle="tooltip" data-placement="bottom" title="Preview the element" id="preview-obiect" href="#'.$modalname.'"></i>
											<i class="move-up-icon fas fa-arrow-circle-up fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element above" id="move-up-obiect"></i>
											<i class="move-down-icon fas fa-arrow-circle-down fa-2x" data-toggle="tooltip" data-placement="bottom" title="Move element below" id="move-down-obiect"></i>
											<i class="delete-icon fas fa-trash fa-2x" data-toggle="tooltip" data-placement="bottom" title="Delete element" id="delete-obiect"></i>
										</div>	
									</div>

									<div class="portfolio-modal mfp-hide" id="'.$modalname.'">
				                        <div class="portfolio-modal-dialog bg-white">
				                          	<a class="close-button d-none d-md-block portfolio-modal-dismiss" href="#">
				                            	<i class="fa fa-3x fa-times"></i>
				                          	</a>
				                          	<div class="container">
				                            	<div class="row">
				                              		<div class="col-lg-12 mx-auto" >
				                              			<div class="text">
				                                			<img style="max-width: 100%" class="img-fluid" src="'.$link_imagine.'">
				                                		</div>
				                              		</div>
				                              		<div style = "padding: 1.5rem 0;" class="col-lg-12 mx-auto text-center">
				                              			<a class="btn middle btn-primary btn-lg rounded-pill portfolio-modal-dismiss" href="#">
				                                  			<i class="fa fa-close"></i>
				                                  		Close</a>
				                              		</div>
				                            	</div>
				                        	</div>
			                        	</div>
			                        </div>

		                      	</div>';

								break;	

							default:
								# code...
								break;
						}
						$index = $index + 1;
					}

				}

				$out_string .=
				'<div class="obiect-lista" data-dbid="-1" data-capitol = "'.$capitol.'">
	              <div class="form-row">
	                <label for="NumeAdaugareCapitol">Do you want to add a new element?</label>
	                <div class="input-group">
	                  <button type="button" class="btn btn-success" id="new-obiect" >Add element</button>
	                </div>
	              </div>
		        </div>';

				$json_array = array( 
					'data' => $array,
					'html' => $out_string,
					'calcinfo' => $calculators,
					'imgs' => $images
					);
				echo json_encode($json_array);

			}
			else echo ' ';

		break;	

	}
	MySQL::CloseConnection();
?>
