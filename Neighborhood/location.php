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
 	
$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$var_location&sensor=false&key=AIzaSyCgDHrXPSopLPEe4sxIZRtRXJ8t2ZQ0uVs";


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

function random_float($min, $max) {
    return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX );
}
for ($i = 0; $i < count($latitude); $i++) {
  $name = strval($latitude[$i]).strval($longitude[$i]);
  $centerLat = $latitude[$i];
  $centerLng = $longitude[$i];
  $dir = $name;
  	$radius = 0.15;
  for ($j = 0; $j <= 5; $j++) {



	$angle = deg2rad(random_float(0, 359));
	$pointRadius = random_float(0, $radius);


    $lati = $centerLat + ($pointRadius * cos($angle));
    $long = $centerLng + ($pointRadius * sin($angle));
 
   
    echo $lati,$long;
  
    //200 km 
    	$jpg = '.jpg';
    $file_to_write = $name.strval($j).strval($jpg);
  if( is_dir($dir) === false )
{
    mkdir($dir,0777,true);
    chmod($dir,0777);
}
$image_url = "https://maps.googleapis.com/maps/api/streetview?size=2048x2048&location=$lati,$long&fov=90&heading=0&pitch=10&key=AIzaSyCgDHrXPSopLPEe4sxIZRtRXJ8t2ZQ0uVs";

$image = file_get_contents($image_url);
$fp = fopen($dir . '/' . $file_to_write,"w");
fputs($fp, $image); 

fclose($fp);
unset($image);
 
   
  
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
console.log(latitude,longitude);
</script>
</head>
<div class="topnav">
  <a href="index.html">Home</a>
  <a href="view_neighborhood.php">View Neighbourhood</a>
  <a class ="active" href="#">Locations</a>
 
</div>


</html>