<?php

$servername = "localhost";
$username = "root";
$password = "VH4YiIfzgBtcFICF";
$dbname = "phpsuperheros";

// Creating connection here:
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking connection here:
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$route = $_GET['route'];

switch ($route) {
  case "getAllHeroes":
    $myData = getAllHeroes($conn);
    break;
  case "getAllFlying":
    $myData = getAllFlying($conn, 2);
    break;
  case "updateBio":
    $myData = updateBio($conn);
    break;
  case "revertBio":
    $myData = revertBio($conn);
    break;
  case "deleteHero":
    $myData = deleteHero($conn);
    break;
     case "insertNewHero":
    $myData = insertNewHero($conn);
    break;
  
  default:
   $myData = json_encode([]);
}

echo $myData;


//passing in the connection
function getAllHeroes($conn){
  //the new data variable is an array
  $data = array();
  //select all from heroes 
  $sql = "SELECT * FROM heroes";
  $result = $conn->query($sql);
  //this is a blank array
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      array_push($data,$row);
    }
  }
  return json_encode($data);
}

// ----------
//need to do a join to the other tabe to get the hero information 
//hero id is foreign key on table
function getAllFlying ($conn, $ability_ID) {
  $data=array();
  //establish tables you select from, then say how you want to join the tables, then have your regular where statements where you pass in parameters.
  $sql = "SELECT * FROM ability_hero 
  INNER JOIN heroes ON ability_hero.hero_id = heroes.id INNER JOIN abilities ON ability_hero.ability_id = abilities.id WHERE ability_hero.ability_id =" . $ability_ID;
  $result = $conn->query($sql);

  // blank array
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
//          unset($row['nombre']);
         array_push($data,$row); // push rows to array $myData
      }
  } 
  
  return json_encode($data);
  
}
// ----------
//can do conditional statements where pulling in an object. Say if not null, append sql statement. Add multiple properties that you'll set with a value. 
//Can allow for more dynamic rendering of string based on what pass to it. 
function updateBio ($conn) {
   $sql = "UPDATE heroes
    SET biography = 'This is a new bio'
    WHERE id = 1";
  
  if ($conn->query($sql) === TRUE) {
    $record = "{'success':'updated bio'}"; // needs the data from the created record
  } else {
    echo "{'error': '" . $sql . " - " . $conn->error . "'}";
  }

  return json_encode([$record]);
}

// ----------
function revertBio ($conn) {
   $sql = "UPDATE heroes
    SET biography = 'In a freak industrial accident, Chill Woman was dunked in toxic waste. After an agonizing transformation, she developed the ability to exhale sub-zero mist that freezes everything it touches.'
    WHERE id = 1";
  
  if ($conn->query($sql) === TRUE) {
    $record = "{'success':'updated to former bio'}"; // needs the data from the created record
  } else {
    echo "{'error': '" . $sql . " - " . $conn->error . "'}";
  }

  return json_encode([$record]);
}

// ----------


//removes LIDAR MAN 
function deleteHero ($conn) {

   $sql = "DELETE FROM heroes
    WHERE name = 'LIDAR MAN'";
  
  if ($conn->query($sql) === TRUE) {
    $record = "{'success':'deleted LIDAR MAN'}"; // needs the data from the created record
  } else {
    echo "{'error': '" . $sql . " - " . $conn->error . "'}";
  }

  return json_encode([$record]);
}  

// ----------

function insertNewHero ($conn) {
  
   $sql = "INSERT INTO heroes (name, about_me, biography) 
   VALUES ('Lidar Man', 'Born without the ability to see, Lidar Man learned to use his ears as a child. One day he was hit with an intense ray of gamma radiation and the only way the doctors could fix him was to add nanotech robots into his brain.', 'Because of the gamma radiation and nanotech combo, he now has the ability to see everyday objects using his mind, and with immense control he can even zoom in 1000X away!')";
  
  if ($conn->query($sql) === TRUE) {
    $record = "{'success':'inserted LIDAR MAN'}"; // needs the data from the created record
  } else {
    echo "{'error': '" . $sql . " - " . $conn->error . "'}";
  }

  return json_encode([$record]);
}


$conn->close();



?>