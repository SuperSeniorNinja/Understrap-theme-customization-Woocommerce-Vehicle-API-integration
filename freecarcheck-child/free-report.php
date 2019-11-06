<?php
/**
 * Template Name: Free report
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
	if($_GET['reg'])
	{	
		$vrm_key = $_GET['reg'];
		//$vrm_key = $_POST['vrm'];
		if(strpos($vrm_key, ' ') !== false)
			$vrm_key = str_replace(" ","",$vrm_key);
		//check if the vrm key data exists in the database or not, API request is run only when no data is in db.
		global $api_status_code;
		global $image_url;
		//create custom tables to store 3 levels of vehicle data and image url.
		$table_name1 = $wpdb->prefix."freereport";
		$sql = "CREATE TABLE `{$table_name1}` (
		  `id` int(20) NOT NULL AUTO_INCREMENT,
		  `vrm_key` varchar(100) DEFAULT NULL,
		  `manufacturer` varchar(100) DEFAULT NULL,	
		  `model` varchar(100) DEFAULT NULL,			  
		  `colour` varchar(50) DEFAULT NULL,
		  `vehicle_type` varchar(100) DEFAULT NULL,
		  `fuel_type` varchar(100) DEFAULT NULL,
		  `body_type` varchar(100) DEFAULT NULL,
		  `count_colarchanges` varchar(100) DEFAULT NULL,
		  `last_colorchange_date` varchar(100) DEFAULT NULL,
		  `gear` varchar(100) DEFAULT NULL,
		  `engine_capacity` varchar(200) DEFAULT NULL,
		  `engine_num` varchar(100) DEFAULT NULL,
		  `bhp` varchar(100) DEFAULT NULL,
		  `registered_date` varchar(100) DEFAULT NULL,
		  `manufactured_year` varchar(50) DEFAULT NULL,
		  `image_url` varchar(300) DEFAULT NULL,
		  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		//Check records in the Database before sending API requests to get data.
		$row = $wpdb->get_results("SELECT * FROM $table_name1 WHERE vrm_key= '".$vrm_key."'");
		if(!empty($row))
		{
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
				$api_status_code='Success';
			}
		}
		else
		{
		    //Get VehicleAndMotHistory API request at the same time with VedDate
			$mot_url = "https://uk1.ukvehicledata.co.uk/api/datapackage/VehicleAndMotHistory?v=2&api_nullitems=1&auth_apikey=1cdba942-950b-481c-bda3-e0a16372fdd5&key_VRM=".$vrm_key;
			$mot_data = callAPI($mot_url);			
			$api_status_code = $mot_data['Response']['StatusInformation']['Lookup']['StatusCode'];
			$manufacturer = $mot_data['Response']['DataItems']['VehicleRegistration']['Make'];
			$model = $mot_data['Response']['DataItems']['VehicleRegistration']['Model'];
			$colour = $mot_data['Response']['DataItems']['VehicleRegistration']['Colour'];
			$vehicle_type = $mot_data['Response']['DataItems']['VehicleRegistration']['VehicleClass'];
			$fuel_type = $mot_data['Response']['DataItems']['VehicleRegistration']['FuelType'];
			$body_type = $mot_data['Response']['DataItems']['SmmtDetails']['BodyStyle'];
			$count_colarchanges = $mot_data['Response']['DataItems']['VehicleHistory']['ColourChangeCount'];
			if($count_colarchanges == NULL)
				$count_colarchanges = '0';
			$last_colorchange_date = cleanDate($mot_data['Response']['DataItems']['VehicleHistory']['KeeperChangesList'][0]['DateOfLastKeeperChange']);
			if($last_colorchange_date == '0' || empty($last_colorchange_date) || $last_colorchange_date == NULL)
				$last_colorchange_date = '1970-01-01';
			$gear = $mot_data['Response']['DataItems']['TechnicalDetails']['General']['Engine']['ValveGear'];
			$engine_capacity = $mot_data['Response']['DataItems']['VehicleRegistration']['EngineCapacity'].' cc';
			$engine_num = $mot_data['Response']['DataItems']['VehicleRegistration']['EngineNumber'];
			$bhp = $mot_data['Response']['DataItems']['TechnicalDetails']['Performance']['Power']['Bhp'].' BHP';
			$registered_date = cleanDate($mot_data['Response']['DataItems']['VehicleRegistration']['DateFirstRegistered']);
			$manufactured_year = $mot_data['Response']['DataItems']['VehicleRegistration']['YearOfManufacture'];	
			if($manufactured_year == '0')
				$manufactured_year = '-';
			if($api_status_code == 'Success')
			{	
				//check if the same vrm or model or year car image exists or not.
				$dir = "wp-content/themes/freecarcheck-child/img/car_images/";
				// Open a directory, and read car images and check for the image API requests
				if (is_dir($dir)){
				  if ($dh = opendir($dir)){
				    while (($file = readdir($dh)) !== false){
				    	//check if the same vrm car image exists in the directory or not.
				    	if(strpos($file, $vrm_key) !== false)
				    	{	
				    		$image_url = $dir.$file;
				    		//check if the car image name includes the same first registered year or not.
				    		if(strpos($file, date('Y', strtotime($registered_date))) !== false)
					    	{	
					    		//check the model in case the vrm key and registered year are the same as
					    		if(strpos($file, cleanstring($model)) !== false)
						    	{	
						    		$image_url = $dir.$file; 		
						    	}
						    	//if models are different, send ImageAPI requests
						    	else
						    		$image_url = downloadimage($vrm_key, $model, $registered_date);
					    	}
					    	//if registered years are different, send ImageAPI requests
					    	else
					    	{
					    		$image_url = downloadimage($vrm_key, $model, $registered_date);
					    	}
				    	}
				    	else
					    	{   
					    		$image_url = downloadimage($vrm_key, $model, $registered_date);
					    	}
				    }
				    closedir($dh);
				  }
				}
				//insert the data only when that vrm key free report data is not in the table.
				$wpdb->insert($table_name1, array(
				                            'vrm_key' => $vrm_key,
				                            'manufacturer' => $manufacturer,
				                            'model' => $model,
				                            'colour' => $colour,
				                            'vehicle_type' => $vehicle_type,
				                            'fuel_type' => $fuel_type,
				                            'body_type' => $body_type,
				                            'count_colarchanges' => $count_colarchanges,
				                            'last_colorchange_date' => $last_colorchange_date,
				                            'gear' => $gear,
				                            'engine_capacity' => $engine_capacity,
				                            'engine_num' => $engine_num,
				                            'bhp' => $bhp,
				                            'registered_date' => $registered_date,
				                            'manufactured_year' => $manufactured_year,
				                            'image_url' => $image_url
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
				                            '%s')
				    );  
			}
			
		}
	}
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
										<a class="upgrade_btn btn-primary" href="<?php echo get_bloginfo('wpurl').'/full-report/?reg='.base64_encode(base64_encode(base64_encode($vrm_key))).'&token='.base64_encode(base64_encode(base64_encode($vrm_key."fullpaid+")));?>">UPGRADE NOW</a>
									</div>
									<div class="col-md-12">
										<a href="<?php echo get_bloginfo('wpurl');?>">New Search</a>
									</div>
								</div>
								<div class="col-md-6 row txt-left lh0">
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
											<td width="50%">Manufactured Date</td>
											<td width="50%"><?php echo $manufactured_year;?></td>
										</tr>
										<tr>
											<td width="50%">Registered Date</td>
											<td width="50%"><?php echo $registered_date;?></td>
										</tr>
									</table>
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
