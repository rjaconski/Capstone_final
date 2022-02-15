<!--movie page, takes the movie title the user selected on the homepage and uses it 
to grab data from the database and the page is updated with the selected movie information.
the movie name is used to select the right column-->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
    <?php
    $x = $_POST['movies'];
    require_once('dbConnect.php');
            
    $conn = db_connect();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $sql = "SELECT * FROM MovieDB
    WHERE title= '$x'";
  

     $result = mysqli_query($conn,$sql);
     $contact = mysqli_fetch_array($result);
     $title = $contact[0];
     $release = $contact[1];
     $length = $contact[2];
     $id = $contact[3];
     $pop = $contact[4];
     $syn = $contact[5];
     mysqli_free_result($result);
db_close($conn);
    echo '
<h1> '. $title . ' </h1>
<p>Release Date: '.$release.'</p>
<p>Length: '. $length .'</p>
<p>Popularity: '. $pop .'</p>
<p>Synopsis: '. $syn . '</p>
<p>TMDB ID:<a href="http://www.themoviedb.org/movie/'.$id.'-the-movie-3">'.$id.'</a></p>';
?>
<button onclick="document.location='Home.html'">Return Home</button>
</body>
</html>