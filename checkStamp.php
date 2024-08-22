<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

$country = (isset($_GET['country']) && $_GET['country'] !== '') ? $_GET['country'] : null;
$year = (isset($_GET['year']) && $_GET['year'] !== '') ? $_GET['year'] : null ;
$price = (isset($_GET['price']) && $_GET['price'] !== '') ? $_GET['price'] : null;

$condition = [];
$params = [];

if ($country !== null) {
    $condition[] = "country_id = ?";
    $params[] = $country;
}

if ($year !== null) {
    $condition[] = "year = ?";
    $params[] = $year;
}

if ($price !== null) {
    $condition[] = "price = ?";
    $params[] = $price;
}

$where = count($condition) > 0 ? ' WHERE ' :'';

$sql = "SELECT * FROM stamp" . $where . implode(' AND ', $condition) . " ORDER BY id DESC";

// $sql = sprintf("SELECT * FROM stamp WHERE country_id = %d AND year = %s AND price = %d", $country, $year, $price);
// $sql = "SELECT * FROM stamp";
$stmt = $link->prepare($sql);
if ($stmt) {
    $query = $stmt->execute($params);
    $stamps = $stmt->fetchAll(PDO::FETCH_ASSOC); 
}

if ($stamps) {
    $data = [
        'success' => true,
        'message' => 'Stamp found',
        'stampData' => $stamps,
        'sql' => $sql
    ];
    
} else {
    $data = [
        'success' => false,
        'message' => 'No stamp found',
        'sql' => $sql       
    ];
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>