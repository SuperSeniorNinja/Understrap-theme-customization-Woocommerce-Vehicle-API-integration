<?php
/**
 * Template Name: Full report
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>
<?php
	global $wpdb;
	if(isset($_GET['reg']) && isset($_GET['token']))
	{	
		$vrm_key = $_GET['reg'];
		//check the token for authentication of watching full vehicle report.
		$vrm_key = base64_decode(base64_decode(base64_decode($vrm_key)));
		$token = base64_decode(base64_decode(base64_decode($_GET['token'])));		
		$authentication_result = $vrm_key.'fullpaid+';	
		//replace " " with "+" for a good format.
		if(strpos($vrm_key, ' ') !== false)
			$vrm_key = str_replace(" ","+",$vrm_key);
		//if authentication is successful	
		if($token == $authentication_result)	
		{
			$table_name1 = 'wp_freereport';
			//get data from the database.
			$row = $wpdb->get_results("SELECT * FROM $table_name1 WHERE vrm_key= '".$vrm_key."'");
			if(!empty($row))
			{	
				//extract the vehicle data items from the database.
				foreach($row as $key)
				{	
					$manufacturer  = $key->manufacturer;
					$colour  = $key->colour;
					$model = $key->model;
					$vehicle_type  = $key->vehicle_type;
					$fuel_type  = $key->fuel_type;
					$body_type  = $key->body_type;
					$count_colarchanges  = $key->count_colarchanges;
					$last_colorchange_date  = $key->last_colorchange_date;
					$gear  = $key->gear;
					$engine_capacity  = $key->engine_capacity; 
					$engine_num  = $key->engine_num;
					$bhp  = $key->bhp;
					$manufactured_year  = $key->manufactured_year;
					$image_url  = $key->image_url;
					$registered_date = $key->registered_date;
					//get VedFull Data from API
					$full_data = get_vdicheckfull($vrm_key);
					//extract each item vehicle data from VedFULL API data.
					$DateFirstRegisteredUk = cleanNull($full_data['Response']['DataItems']['DateFirstRegisteredUk']);
					$WriteOffDate = cleanNull($full_data['Response']['DataItems']['WriteOffDate']);
					$WriteOffCategory = cleanNull($full_data['Response']['DataItems']['WriteOffCategory']);
					//$FinanceRecordList = $full_data['Response']['DataItems']['FinanceRecordList'][0];
					$FinanceRecordCount = checkNumbers($full_data['Response']['DataItems']['FinanceRecordCount']);		
					$VicTestDate = cleanNull($full_data['Response']['DataItems']['VicTestDate']);
					$VicTestResult = cleanNull($full_data['Response']['DataItems']['VicTestResult']);
					$CertificateOfDestructionIssued = cleanNull($full_data['Response']['DataItems']['CertificateOfDestructionIssued']);
					$TransmissionType = cleanNull($full_data['Response']['DataItems']['TransmissionType']);
					$PreviousKeepers = cleanNull($full_data['Response']['DataItems']['PreviousKeepers']);
					$PreviousKeeperCount = checkNumbers($full_data['Response']['DataItems']['PreviousKeeperCount']);
					$LatestKeeperChangeDate = cleanNull($full_data['Response']['DataItems']['LatestKeeperChangeDate']);
					$PreviousColour = cleanNull($full_data['Response']['DataItems']['PreviousColour']);
					$imported = cleanNull($full_data['Response']['DataItems']['imported']);
					$ImportDate = cleanNull($full_data['Response']['DataItems']['ImportDate']);
					$GearCount = cleanNull($full_data['Response']['DataItems']['GearCount']);
					$LatestV5cIssuedDate = cleanNull($full_data['Response']['DataItems']['LatestV5cIssuedDate']);
					//MileageRecordList is the array data type.
					$MileageRecordList = cleanNull(json_encode($full_data['Response']['DataItems']['MileageRecordList']));
					$MileageRecordCount = checkNumbers($full_data['Response']['DataItems']['MileageRecordCount']);
					$ImportUsedBeforeUkRegistration = cleanNull($full_data['Response']['DataItems']['ImportUsedBeforeUkRegistration']);
					$MileageAnomalyDetected = cleanNull($full_data['Response']['DataItems']['MileageAnomalyDetected']);
					$HighRiskRecordCount = checkNumbers($full_data['Response']['DataItems']['HighRiskRecordCount']);		
					$Exported = cleanNull($full_data['Response']['DataItems']['Exported']);
					$ExportDate = cleanNull($full_data['Response']['DataItems']['ExportDate']);		
					$VinLast5 = cleanNull($full_data['Response']['DataItems']['VinLast5']);		
					$PlateChangeCount = checkNumbers($full_data['Response']['DataItems']['PlateChangeCount']);	
					//Create fullReport table to store free report vehicle data with its image url.
					$table_name = $wpdb->prefix."fullReport";
					$sql = "CREATE TABLE `{$table_name}` (
					  `id` int(20) NOT NULL AUTO_INCREMENT,
					  `vrm_key` varchar(100) DEFAULT NULL,
					  `DateFirstRegisteredUk` varchar(100) DEFAULT NULL,		  
					  `WriteOffDate` varchar(100) DEFAULT NULL,
					  `WriteOffCategory` varchar(100) DEFAULT NULL,
					  `FinanceRecordCount` varchar(100) DEFAULT NULL,
					  `VicTestDate` varchar(100) DEFAULT NULL,
					  `VicTestResult` varchar(100) DEFAULT NULL,
					  `CertificateOfDestructionIssued` varchar(100) DEFAULT NULL,
					  `TransmissionType` varchar(100) DEFAULT NULL,
					  `PreviousKeepers` varchar(200) DEFAULT NULL,
					  `PreviousKeeperCount` varchar(100) DEFAULT NULL,
					  `LatestKeeperChangeDate` varchar(100) DEFAULT NULL,
					  `PreviousColour` varchar(100) DEFAULT NULL,
					  `imported` varchar(100) DEFAULT NULL,
					  `ImportDate` varchar(100) DEFAULT NULL,
					  `GearCount` varchar(100) DEFAULT NULL,
					  `LatestV5cIssuedDate` varchar(100) DEFAULT NULL,
					  `MileageRecordList` varchar(900) DEFAULT NULL,
					  `MileageRecordCount` varchar(100) DEFAULT NULL,
					  `ImportUsedBeforeUkRegistration` varchar(100) DEFAULT NULL,
					  `MileageAnomalyDetected` varchar(100) DEFAULT NULL,
					  `HighRiskRecordCount` varchar(100) DEFAULT NULL,
					  `Exported` varchar(100) DEFAULT NULL,
					  `ExportDate` varchar(100) DEFAULT NULL,
					  `VinLast5` varchar(100) DEFAULT NULL,
					  `PlateChangeCount` varchar(100) DEFAULT NULL,
					  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8";
					require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
					dbDelta($sql);
					//check if the same vrm key exists in the fullReport table or not.		
					$row = $wpdb->get_results("SELECT * FROM $table_name WHERE vrm_key= '".$vrm_key."'");
					//insert the data only when that vrm key fullReport data is not in the table.
					if(empty($row)){
					 	$wpdb->insert($table_name, array(
					                            'vrm_key' => $vrm_key,
					                            'DateFirstRegisteredUk' => $DateFirstRegisteredUk,
					                            'WriteOffDate' => $WriteOffDate,
					                            'WriteOffCategory' => $WriteOffCategory,
					                            'FinanceRecordCount' => $FinanceRecordCount,
					                            'VicTestDate' => $VicTestDate,
					                            'VicTestResult' => $VicTestResult,
					                            'CertificateOfDestructionIssued' => $CertificateOfDestructionIssued,
					                            'TransmissionType' => $TransmissionType,
					                            'PreviousKeepers' => $PreviousKeepers,
					                            'PreviousKeeperCount' => $PreviousKeeperCount,
					                            'LatestKeeperChangeDate' => $LatestKeeperChangeDate,
					                            'PreviousColour' => $PreviousColour,
					                            'imported' => $imported,
					                            'ImportDate' => $ImportDate,
					                            'GearCount' => $GearCount,
					                            'LatestV5cIssuedDate' => $LatestV5cIssuedDate,
					                            'MileageRecordList' => $MileageRecordList,
					                            'MileageRecordCount' => $MileageRecordCount,
					                            'ImportUsedBeforeUkRegistration' => $ImportUsedBeforeUkRegistration,
					                            'MileageAnomalyDetected' => $MileageAnomalyDetected,
					                            'HighRiskRecordCount' => $HighRiskRecordCount,
					                            'Exported' => $Exported,		                            
					                            'ExportDate' => $ExportDate,
					                            'VinLast5' => $VinLast5,
					                            'PlateChangeCount' => $PlateChangeCount
					                            ),array(
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s',
					                            '%s')
					    ); 
					}
					?>
						<div class="wrapper" id="full-width-page-wrapper">
							<div class="<?php echo esc_attr( $container ); ?>" id="content">
								<div class="row">
									<div class="col-md-12 content-area" id="primary">
										<main class="site-main" id="main" role="main">	
											<div class="heading_text txt-center col-md-12">
												<h4>Full Report for <b><?php echo $vrm_key;?></b></h4>
											</div>
											<div class="row mgt-50">
												<div class="col-md-12 row txt-left lh0">
													<table class="table table-report">
														<tr>
															<td width="50%">Manufacturer</td>
															<td width="50%"><?php echo $manufacturer;?>
																<img class="car_brand" src="<?php if(!empty($manufacturer)) echo get_stylesheet_directory_uri().'/img/Logos_Cars/'.ucfirst(strtolower($manufacturer)).'-logo.png';?>">
															</td>
														</tr>
														<tr>
															<td width="50%">Model</td>
															<td width="50%"><?php echo $model;?></td>
														</tr>
														<tr>
															<td width="50%">Vehicle Type</td>
															<td width="50%"><?php echo $vehicle_type;?></td>
														</tr>
														<tr>
															<td width="50%">Engine Size</td>
															<td width="50%"><?php echo $engine_capacity;?></td>
														</tr>
														<tr>
															<td width="50%">BHP</td>
															<td width="50%"><?php echo $bhp;?></td>
														</tr>
														<tr>
															<td width="50%">Engine Number</td>
															<td width="50%"><?php echo $engine_num;?></td>
														</tr>
														<tr>
															<td width="50%">Fuel Type</td>
															<td width="50%"><?php echo $fuel_type;?></td>
														</tr>
														<tr>
															<td width="50%">Body Type</td>
															<td width="50%"><?php echo $body_type;?></td>
														</tr>
														<tr>
															<td width="50%">Colour</td>
															<td width="50%"><?php echo $colour;?></td>
														</tr>
														<tr>
															<td width="50%">Number of colour changes</td>
															<td width="50%"><?php echo $count_colarchanges;?></td>
														</tr>
														<tr>
															<td width="50%">Last colour change date</td>
															<td width="50%"><?php echo $last_colorchange_date;?></td>
														</tr>
														<tr>
															<td width="50%">Gear</td>
															<td width="50%"><?php echo $gear;?></td>
														</tr>
														<tr>
															<td width="50%">Manufactured Year</td>
															<td width="50%"><?php echo $manufactured_year;?></td>
														</tr>
														<tr>
															<td width="50%">Registered Date</td>
															<td width="50%"><?php echo $registered_date;?></td>
														</tr>

														<tr>
															<td width="50%">DateFirstRegisteredUk</td>
															<td width="50%"><?php echo $DateFirstRegisteredUk;?></td>
														</tr>
														<tr>
															<td width="50%">WriteOffDate</td>
															<td width="50%"><?php echo $WriteOffDate;?></td>
														</tr>
														<tr>
															<td width="50%">WriteOffCategory</td>
															<td width="50%"><?php echo $WriteOffCategory;?></td>
														</tr>
														<tr>
															<td width="50%">FinanceRecordCount</td>
															<td width="50%"><?php echo $FinanceRecordCount;?></td>
														</tr>
														<tr>
															<td width="50%">VicTestDate</td>
															<td width="50%"><?php echo $VicTestDate;?></td>
														</tr>
														<tr>
															<td width="50%">VicTestResult</td>
															<td width="50%"><?php echo $VicTestResult;?></td>
														</tr>
														<tr>
															<td width="50%">CertificateOfDestructionIssued</td>
															<td width="50%"><?php echo $CertificateOfDestructionIssued;?></td>
														</tr>
														<tr>
															<td width="50%">TransmissionType</td>
															<td width="50%"><?php echo $TransmissionType;?></td>
														</tr>
														<tr>
															<td width="50%">PreviousKeepers</td>
															<td width="50%"><?php echo $PreviousKeepers;?></td>
														</tr>
														<tr>
															<td width="50%">PreviousKeeperCount</td>
															<td width="50%"><?php echo $PreviousKeeperCount;?></td>
														</tr>
														<tr>
															<td width="50%">LatestKeeperChangeDate</td>
															<td width="50%"><?php echo $LatestKeeperChangeDate;?></td>
														</tr>
														<tr>
															<td width="50%">PreviousColour</td>
															<td width="50%"><?php echo $PreviousColour;?></td>
														</tr>
														<tr>
															<td width="50%">imported</td>
															<td width="50%"><?php echo $imported;?></td>
														</tr>
														<tr>
															<td width="50%">ImportDate</td>
															<td width="50%"><?php echo $ImportDate;?></td>
														</tr>
														<tr>
															<td width="50%">GearCount</td>
															<td width="50%"><?php echo $GearCount;?></td>
														</tr>
														<tr>
															<td width="50%">LatestV5cIssuedDate</td>
															<td width="50%"><?php echo $LatestV5cIssuedDate;?></td>
														</tr>
														<tr>
															<td width="50%" style="vertical-align: middle;">MileageRecordList</td>
																<table class="wd100">
																	<tbody>
																		<?php 
																		for($i = 0; $i < count(json_decode($MileageRecordList)); $i++)
																			{ ?>
																				<tr>
																					<td width="35%">Date => <!-- Of Information --> <?php echo json_decode($MileageRecordList)[$i]->DateOfInformation;?></td>
																					<td width="30%">Source =><!-- Of Information --> 
																					<?php echo json_decode($MileageRecordList)[$i]->SourceOfInformation;?></td>
																					<td width="35%">Mileage =><?php echo json_decode($MileageRecordList)[$i]->Mileage;?>
																					</td>
																				</tr>				
																			<?php }		
																		?>	
																	</tbody>											
																</table>
															</td>
														</tr>
														<tr>
															<td width="50%">MileageRecordCount</td>
															<td width="50%"><?php echo $MileageRecordCount;?></td>
														</tr>
														<tr>
															<td width="50%">ImportUsedBeforeUkRegistration/td>
															<td width="50%"><?php echo $ImportUsedBeforeUkRegistration;?></td>
														</tr>
														<tr>
															<td width="50%">MileageAnomalyDetected</td>
															<td width="50%"><?php echo $MileageAnomalyDetected;?></td>
														</tr>
														<tr>
															<td width="50%">HighRiskRecordCount</td>
															<td width="50%"><?php echo $HighRiskRecordCount;?></td>
														</tr>
														<tr>
															<td width="50%">Exported</td>
															<td width="50%"><?php echo $Exported;?></td>
														</tr>
														<tr>
															<td width="50%">ExportDate</td>
															<td width="50%"><?php echo $ExportDate;?></td>
														</tr>
														<tr>
															<td width="50%">VinLast5</td>
															<td width="50%"><?php echo $VinLast5;?></td>
														</tr>
														<tr>
															<td width="50%">PlateChangeCount</td>
															<td width="50%"><?php echo $PlateChangeCount;?></td>
														</tr>
													</table>
												</div>
											</div>
										</main><!-- #main -->
									</div><!-- #primary -->
								</div><!-- .row end -->
							</div><!-- #content -->
						</div>
					<?php
				}
			}
			else
			{	
				//redirect users to the new search again.
				echo "Your report is not available anymore.";
			}
		}		
		else
			echo "You are not allowed to access full report.";		
	}
	exit();
?>	
<div class="wrapper" id="full-width-page-wrapper">
	<div class="<?php echo esc_attr( $container ); ?>" id="content">
		<div class="row">
			<div class="col-md-12 content-area" id="primary">
				<main class="site-main" id="main" role="main">	
					<div class="heading_text txt-center col-md-12">
						<h4>Your report for <b><?php echo $_GET['reg'];?></b></h4>
					</div>
					<?php
						//check the API request results.
						if($api_status_code == "Success")
						{?>
							<div class="row mgt-50">
								<div class="col-md-6 row">
									<div class="col-md-4">
										<img src="<?php echo get_bloginfo('wpurl').'/'.$image_url;?>" class="car_img">
									</div>
									<div class="col-md-8">
										<label><input type="checkbox" name="check1" checked="">Ved Data Check1 - Passed</label>
										<label><input type="checkbox" name="check2" checked="">Ved Data Check2 - Passed</label>
										<label><input type="checkbox" name="check3" checked="">Ved Data Check3 - Passed</label>
									</div>							
								</div>
								<div class="col-md-6  row">
									<div class="col-md-12 extra_spec">
										<h5>Extra description here</h5>
									</div>							
								</div>
							</div>
							<div class="row mgt-50">
								<div class="col-md-6 row left_upgrade">
									<div class="col-md-12">
										<h5>We found the following potential problems</h5>
									</div>
									<div class="col-md-12 ">
										<p>Fraud Check - Upgrade to view</p>
										<p>Stolen Check - Upgrade to view</p>
									</div>
									<div class="col-md-12">
										<a class="upgrade_btn btn-primary" href="<?php echo get_bloginfo('wpurl').'/full-report/reg='.$vrm_key;?>">UPGRADE NOW</a>
									</div>
									<div class="col-md-12">
										<a href="<?php echo get_bloginfo('wpurl');?>">New Search</a>
									</div>
								</div>
								<div class="col-md-6 row txt-left lh0">
									<div class="row col-md-12">
										<div class="wd100">
											<h5>Manufacturer</h5><br>
										</div>
										<div class="row col-md-12">
											<div class="row mgl30">
												<h5><?php echo $manufacturer;?></h5>
												<img class="car_brand" src="<?php if(!empty($manufacturer)) echo get_stylesheet_directory_uri().'/img/Logos_Cars/'.ucfirst(strtolower($manufacturer)).'-logo.png';?>">
											</div>									
										</div>
									</div>
									<div class="row col-md-12">
										<div class="wd100">
											<h5>Model</h5><br>
										</div>
										<div class="row col-md-12">									
											<div class="row mgl30">
												<h5><?php echo $model;?></h5>				
											</div>									
										</div>
									</div>
									<div class="row col-md-12">
										<div class="wd100">
											<h5>Colour</h5><br>
										</div>
										<div class="row col-md-12">									
											<div class="row mgl30">
												<h5><?php echo $colour;?></h5>				
											</div>									
										</div>
									</div>
									<div class="row col-md-12">
										<div class="wd100">
											<h5>Vehicle Type</h5><br>
										</div>
										<div class="row col-md-12">									
											<div class="row mgl30">
												<h5><?php echo $vehicle_type;?></h5>				
											</div>									
										</div>
									</div>
									<div class="row col-md-12">
										<div class="wd100">
											<h5>Fuel Type</h5><br>
										</div>
										<div class="row col-md-12">									
											<div class="row mgl30">
												<h5><?php echo $fuel_type;?></h5>				
											</div>									
										</div>
									</div>
									<div class="row col-md-12">
										<div class="wd100">
											<h5>Engine Size</h5><br>
										</div>
										<div class="row col-md-12">									
											<div class="row mgl30">
												<h5><?php echo $engine_capacity;?></h5>				
											</div>									
										</div>
									</div>
									<div class="row col-md-12">
										<div class="wd100">
											<h5>BHP</h5><br>
										</div>
										<div class="row col-md-12">									
											<div class="row mgl30">
												<h5><?php echo $bhp;?></h5>				
											</div>									
										</div>
									</div>
									<div class="row col-md-12">
										<div class="wd100">
											<h5>Manufactured Date</h5><br>
										</div>
										<div class="row col-md-12">									
											<div class="row mgl30">
												<h5><?php echo $manufactured_year;?></h5>				
											</div>									
										</div>
									</div>
									<div class="row col-md-12">
										<div class="wd100">
											<h5>Registered Date</h5><br>
										</div>
										<div class="row col-md-12">									
											<div class="row mgl30">
												<h5><?php echo $registered_date;?></h5>				
											</div>									
										</div>
									</div>
								</div>
							</div>
						<?php }
						else
						{?>
							<div class=" mgt-50 txt-center">
								<h6>There are no Vechicles registered regarding your search VRM key.<a href="<?php echo get_bloginfo('wpurl');?>">Try search again.</a></h6>								
							</div>
						<?php }
					?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .row end -->
	</div><!-- #content -->
</div>
<?php get_footer(); ?>
