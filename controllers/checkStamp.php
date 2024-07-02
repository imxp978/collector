<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

$country = (isset($_GET['country']) && $_GET['country'] !== '') ? $_GET['country'] : null;
$year = (isset($_GET['year']) && $_GET['year'] !== '') ? $_GET['year'] : null ;
$price = (isset($_GET['price']) && $_GET['price'] !== '') ? $_GET['price'] : null;
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

$condition = [];

if ($country !== null) {
    $condition[] = "country_id = $country";
}

if ($year !== null) {
    $condition[] = "year = '$year'";
}

if ($price !== null) {
    $condition[] = "price = $price";
}

$where = count($condition) > 0 ? ' WHERE ' :'';

$sql = "SELECT * FROM stamp". $where. implode(' AND ', $condition);

// $sql = sprintf("SELECT * FROM stamp WHERE country_id = %d AND year = %s AND price = %d", $country, $year, $price);
// $sql = "SELECT * FROM stamp";
$query = $link->query($sql);
$stamps = $query->fetchAll(); 

if ($stamps) {
    $data = [
        'success' => true,
        'message' => 'stamp found',
        'stampData' => $stamps,
        'sql' => $sql
    ];
    
} else {
    $data = [
        'success' => false,
        'message' => 'stamp not found',
        'sql' => $sql       
    ];
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>