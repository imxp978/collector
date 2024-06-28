<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

$country = $_GET['country'] ;
$year = $_GET['year'] ;
$price = $_GET['price'] ;
// $country = $_GET['country'] ?? '';
// $year = $_GET['year'] ?? '';
// $price = $_GET['price'] ?? '';

// $SQLcommand = "SELECT * FROM stamp WHERE country_id = :country AND price = :price";

// $stmt = $link->prepare($SQLcommand);
// $stmt->bindParam(':country', $country);
// $stmt->bindParam(':year', $year);
// $stmt->bindParam(':price', $price);

// $stmt->execute();

// $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = sprintf("SELECT * FROM stamp WHERE country_id = %d AND year = %s AND price = %d", $country, $year, $price);
// $sql = "SELECT * FROM stamp";
$query = $link->query($sql);
$stamps = $query->fetchAll(); 

if ($stamps) {
    $data = [
        'success' => true,
        'message' => 'stamp found',
        'stampData' => $stamps
    ];
    
} else {
    $data = [
        'success' => false,
        'message' => 'stamp not found'       
    ];
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>