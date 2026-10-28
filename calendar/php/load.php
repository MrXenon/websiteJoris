<?php
if($_SERVER['HTTP_HOST'] == 'localhost:8888'){
    // Define local test values if IOS
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', 'root');
        define('DB_DATABASE', 'hho');
    }else if($_SERVER['HTTP_HOST'] == 'localhost'){
    // Define local test values
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_DATABASE', 'hho');
    }else{
    // Define live values
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'kevin_hho');
        define('DB_PASSWORD', '4fs4bEvx34A5sf;e464ff5324gdfs423F4');
        define('DB_DATABASE', 'kevin_hho');
    }

$connect = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_DATABASE, ''.DB_USERNAME.'', ''.DB_PASSWORD.'');

$data = array();
$date = date('Y-m-d H:i:s');
$teacher = 0;
if(isset($_COOKIE["SHAKDH#KKKDSAWDM"])){
    $teacher = $_COOKIE["SHAKDH#KKKDSAWDM"];
}
if($teacher > 0){
    $query = "SELECT * FROM events where teacher = $teacher ORDER BY id";
}
else {
    $query = "SELECT * FROM events  ORDER BY id";
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
  'title'   => $row["race"] .' - ' . $row['handler']. ' - '.$row['dog'],
  'start'   => $row["start_date"],
  'end'   => $row["end_date"]
 );
}

echo json_encode($data);

?>
