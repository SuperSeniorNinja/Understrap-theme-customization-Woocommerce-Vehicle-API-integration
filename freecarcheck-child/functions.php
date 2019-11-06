<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

    // Get the theme data
    $the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    //wp_enqueue_script( 'jquery');
    wp_enqueue_style( 'child-understrap-styles2', get_stylesheet_directory_uri() . '/css/custom.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    wp_enqueue_script( 'child-understrap-scripts2', get_stylesheet_directory_uri() . '/js/custom.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );

/*define the AJax processing function*/
function vrmsearch()
{   
    //get the VRM key
    $vrm_key = $_POST['vrm'];
    if(strpos($vrm_key, ' ') !== false)
        $vrm_key = str_replace(" ","+",$vrm_key);
    //free report data 
    $ved_url = "https://uk1.ukvehicledata.co.uk/api/datapackage/VedData?v=2&api_nullitems=1&auth_apikey=1cdba942-950b-481c-bda3-e0a16372fdd5&key_VRM=".$vrm_key;
    global $ved_data;
    $ved_data = callAPI($ved_url);  
    $api_result = $ved_data['Response']['StatusCode'];
    if($api_result == "Success")
        {
            //VDICheckBasic Data
            $basic_url = "https://uk1.ukvehicledata.co.uk/api/datapackage/VDICheckBasic?v=2&api_nullitems=1&auth_apikey=1cdba942-950b-481c-bda3-e0a16372fdd5&key_VRM=".$vrm_key;
            $basic_data = callAPI($basic_url);

            //VDICheckFUll Data
            $full_url = "https://uk1.ukvehicledata.co.uk/api/datapackage/VdiCheckFull?v=2&api_nullitems=1&auth_apikey=1cdba942-950b-481c-bda3-e0a16372fdd5&key_VRM=".$vrm_key;
            $full_data = callAPI($full_url);
            
            echo json_encode($ved_data);
            exit();
        }
    else
        {
            echo "no";
        }
}

//defines the API request function
function callAPI($url){
    $result = file_get_contents($url);
    $result = json_decode($result, true);
    return $result;
}

//defines the function which removes all white spaces in the string when storing images.
function cleanstring($string)
{
    return preg_replace('/\s+/', '', $string);
}

//defines the image_url extraction function from Image API request
function ImageApirequest($vrm)
{
    //Get Vehicle image and download them in the child-theme.
    $image_api_url = "https://uk1.ukvehicledata.co.uk/api/datapackage/VehicleImageData?v=2&api_nullitems=1&auth_apikey=1cdba942-950b-481c-bda3-e0a16372fdd5&user_tag=&key_VRM=".$vrm;
    $image_data = callAPI($image_api_url);
    $image_target_url = $image_data['Response']['DataItems']['VehicleImages']['ImageDetailsList'][0]['ImageUrl'];
    return $image_target_url;
}

//download image files from the Image API response
function downloadimage($vrm_key, $model, $registered_date)
{   
    //send ImageAPI request
    $image_target_url = ImageApirequest($vrm_key);
    //check if the same model of car image already exists in the image repository or not to reduce the API requests.
    if(!empty($image_target_url))
    {   
        //intval() : to get the first registered YEAR of vehicle.                       
        $image_url = 'wp-content/themes/freecarcheck-child/img/car_images/'.cleanstring($vrm_key).'-'.cleanstring($model).'-'.intval($registered_date).'.jpg';
        //force download images from the Image API response image url.
        $image = file_get_contents($image_target_url);
        file_put_contents($image_url, $image); 
        //to avoid the duplicated image files with the same name image for the same search VRM key.
        /*if (!file_exists($image_url)) {  
            $image = file_get_contents($image_target_url);
            file_put_contents($image_url, $image);                         
        } */      
    }
    //default image url
    else
        $image_url="wp-content/themes/freecarcheck-child/img/no-brand-logo.png";
    return $image_url;
}

//get VDICheckBasic Data from the API.
function get_vdicheckbasic($vrm_key)
{
    //free report data 
    $basic_url = "https://uk1.ukvehic-ledata.co.uk/api/datapackage/VDICheckBasic?v=2&api_nullitems=1&auth_apikey=1cdba942-950b-481c-bda3-e0a16372fdd5&key_VRM=".$vrm_key;
    $basic_data = callAPI($basic_url);  
    $api_result = $basic_data['Response']['StatusInformation']['Lookup']['StatusCode'];
    if($api_result == "Success")
        {
            //VDICheckBasic Data     
            return $basic_data;
        }
    else
        {
            return "failure";
        }
} 

//get VDICheckFUll Data from the API.
function get_vdicheckfull($vrm_key)
{
    //free report data 
    $full_url = "https://uk1.ukvehicledata.co.uk/api/datapackage/VDICheckFUll?v=2&api_nullitems=1&auth_apikey=1cdba942-950b-481c-bda3-e0a16372fdd5&key_VRM=".$vrm_key;
    $full_data = callAPI($full_url);  
    $api_result = $full_data['Response']['StatusInformation']['Lookup']['StatusCode'];
    if($api_result == "Success")
        {               
            //VDICheckFUll Data  
            return $full_data;
        }
    else
        {
            return "failure";
        }
} 
//clean date format
function cleanDate($date)
{
    $time = strtotime($date);
    $clean_date = date('Y-m-d',$time);
    return $clean_date;
}
//clean the count values when their values are 0
function checkNumbers($num)
{
    if($num == NULL)
        $num = '0';
    return $num;
}
//clean the Null to "N/A"
function cleanNull($val)
{
    if($val == NULL)
        $val="N/A";
    return $val;
}
