<!--movieglu api used to find local theaters using geocode paased in through 
database extraction-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <title>Local Theaters</title>
</head>
<body>
  <div class="container" >
    <h2 id="text-center"> Movie theaters near by: </h2>
  </div>
  
<?php

function localcins(){
require_once('dbConnect.php');
$conn = db_connect();
 

 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
 $sql3 = "SELECT geocode FROM geocode ORDER BY id DESC LIMIT 1";
 
 
$result3 = mysqli_query($conn,$sql3);
$contact = mysqli_fetch_array($result3);

$api = 'cinemasNearby/?n=5';

$api_endpoint = 'https://api-gate2.movieglu.com/';
$username = 'ROWA_7'; // Example: $username = 'ABCD';
$api_key = 'RQUIGsYt5R3sM97H17ols9zxOgDi5Hxp97BDk2nj';  //Example: $api_key = 'AbCdEFG7CuTTc6KX76mI5aAoGtqbrGW2ga6B4jRg';
$basic_authorization = ' Basic UK9XQV83OCUINVbkhYTFhYVA=='; // Example: $basic_authorization = 'Basic UHSYGF4xNTpNOHdJQllxckYyN3y=';
$territory = 'US'; // Territory chosen as part of your evaluation key request  (Options: UK, FR, ES, DE, US, CA, IE, IN)
$api_version = 'v200'; // API Version for evaluation - check documentation for later versions
$device_datetime = (new DateTime())->format('Y-m-d H:i:s'); // Current device date/time 
$geolocation = $contact[0]; // Device Geolocation. Note semicolon (;) used as separator. IMPORTANT: This MUST be a location in the territory you selected above. The sample location is set at: Leicester Square, London, UK


$ch = curl_init();

// Assign cURL Settings
curl_setopt($ch, CURLOPT_URL, $api_endpoint . $api);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: ' . $basic_authorization, 
  'client: ' . $username,
  'x-api-key: ' . $api_key,  
  'territory: ' . $territory,
  'api-version: ' .$api_version,
  'device-datetime: ' . $device_datetime,
  'geolocation: ' .$geolocation 
 ]
);


$ret = curl_exec($ch);


$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

$body = substr($ret, $header_size);

curl_close($ch);


$response = json_decode($body, true);
for($i = 0;$i<3; $i++){

$cinema_name[$i] =  $response['cinemas'][$i]['cinema_name'];
$address[$i] =  $response['cinemas'][$i]['address'];
$city[$i] =  $response['cinemas'][$i]['city'];
$cinema_id[$i] = $response['cinemas'][$i]['cinema_id'];
$fullAddress[$i] = $address[$i] . ", " . $city[$i];
}

$conn = db_connect();
 

 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }




$x = 0;
  if($http_code == 200){
      echo "<ol>";
while($x < $i){
      echo "<li>" . $cinema_id[$x] . ": ". $cinema_name[$x] . ", ". $address[$x] .", " . $city[$x] . "</li>";
      
      $sql3 = "INSERT INTO LocalCins (cin_id, name, address) value ('".$cinema_id[$x]. "', '".$cinema_name[$x]."', '".$fullAddress[$x]. "')";
      
      $result3 = mysqli_query($conn,$sql3);
      mysqli_free_result($result3);
    $x++;
    }
    mysqli_close($conn);
    echo"</ol>";
  }elseif($http_code == 204){
      echo 'No results for request';
      echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "</pre>";
  }else{
    echo "fail";
      exit();
  }
  
  }?>
</body>
</html>
