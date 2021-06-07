<?php
if(isset($_POST['upload']))
	{
 $file = $_FILES["file"]["tmp_name"];
 $file_open = fopen($file,"r");
 $latitude =[];
$longitude=[];
 while(($csv = fgetcsv($file_open, 1000, ",")) !== false)
 {
  $ID = $csv[0];
  $Location  = $csv[1];

  $var_location = str_replace(' ', '+', $Location);
 	
$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$var_location&sensor=false&key=<Your API key>";


$response = file_get_contents($url);
$response = json_decode($response, true);

//print_r($response);
if($response['status']=="OK"){
$lat = $response['results'][0]['geometry']['location']['lat'];
$long = $response['results'][0]['geometry']['location']['lng'];

array_push($latitude,$lat);
array_push($longitude,$long);

}
else{
	echo "No results";
}
 

}
/* Use it for json_encode some corrupt UTF-8 chars
 * useful for = malformed utf-8 characters possibly incorrectly encoded by json_encode
 */
function utf8ize( $mixed ) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
    }
    return $mixed;
  }

function random_float($min, $max) {
  $range = $max-$min;
  $num = $min + $range * (mt_rand() / mt_getrandmax()); 
  return $num;
    //return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX );
}
for ($i = 0; $i < count($latitude); $i++) {
  $name = strval($latitude[$i]).strval($longitude[$i]);
  $centerLat = $latitude[$i];
  $centerLng = $longitude[$i];
  $dir = $name;
  	$radius = 0.1;

    
  for ($j = 0; $j <= 10; $j++) {

    $lati=0;
    $long=0;

	$angle = (random_float(0, 359));
	$pointRadius = random_float(0, $radius);
// $lng_min = $centerLng - $pointRadius / (cos(deg2rad($centerLat)) * 69);
// $lng_max = $centerLng + $pointRadius / (cos(deg2rad($centerLat)) * 69);
// $lat_min = $centerLat - ($pointRadius / 69);
// $lat_max = $centerLat + ($pointRadius / 69);
  //echo $pointRadius;
    $lati = $centerLat + ($pointRadius / (cos($angle)*69));
    $long = $centerLng + ($pointRadius / (sin($angle)*69));
 
   //echo $lati,$long . PHP_EOL;
//     echo 'lng (min/max): ' . $lng_min . '/' . $lng_max . PHP_EOL;
// echo 'lat (min/max): ' . $lat_min . '/' . $lat_max . PHP_EOL;
   $file_name = strval($lati).strval($long);
    //200 km 
    	$jpg = '.jpg';
    $file_to_write = $file_name.strval($j).strval($jpg);
  if( is_dir($dir) === false )
{
    mkdir($dir,0777,true);
    chmod($dir,0777);
}
$image_url = "https://maps.googleapis.com/maps/api/streetview?size=2048x2048&location=$lati,$long&fov=90&heading=0&pitch=10&key=<Your API key>";

$image = file_get_contents($image_url);
if(strlen($image)==8836){

 $j--;
  }
else{
$fp = fopen($dir . '/' . $file_to_write,"w");
fputs($fp, $image); 

fclose($fp);
unset($image);

 }  
  
} 
 
}
}
?>

   <!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<script type="text/javascript">
	var latitude = <?php echo json_encode($latitude); ?>;
var longitude = <?php echo json_encode($longitude); ?>;

</script>
</head>
<div class="topnav">
  <a href="index.html">Home</a>
  <a href="view_neighborhood.php">View Neighbourhood</a>
  <a class ="active" href="#">Locations</a>
 
</div>


</html>
