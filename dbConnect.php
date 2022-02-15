<!-- mysql connection and closing commands, credentials is passed in-->
<?php
require_once('credentials.php');

function db_connect(){
    $conn = mysqli_connect(host, username, password, database, 3306);
    return $conn;
};

function db_close($conn){
if (isset($conn)){
  return  mysqli_close($conn);
}
}
?>