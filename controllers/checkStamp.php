<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

$country = (isset($_GET['country']) && $_GET['country'] !== '') ? $_GET['country'] : null;
$year = (isset($_GET['year']) && $_GET['year'] !== '') ? $_GET['year'] : null ;
$price = (isset($_GET['price']) && $_GET['price'] !== '') ? $_GET['price'] : null;
<<<<<<< HEAD
$limit = (isset($_GET['limit']) && $_GET['limit'] !== '') ? $_GET['limit'] : null; 
$id = (isset($_GET['id']) && $_GET['id'] !== '') ? $_GET['id'] : null; 

=======
>>>>>>> 6ddb6eb435e7428341837a619a2964e2bbf26e5b

$condition = [];
$params = [];

<<<<<<< HEAD
if ($limit == 1) {
    
    $sql = 'SELECT * FROM stamp ORDER BY id DESC LIMIT 12';
} else if ($limit == 0) {
    
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

    if ($id !== null) {
        $condition[] = "id = ?";
        $params[] = $id;
    }
    
    $where = count($condition) > 0 ? ' WHERE ' :'';
    
    $sql = "SELECT * FROM stamp" . $where . implode(' AND ', $condition) . " ORDER BY id DESC";
}

function removeNull($value) {
    return ($value == null || strtolower($value) == 'null') ? '' : $value;
  }
  
try {
    $stmt = $link->prepare($sql);
    $stamps = [];
    if ($stmt) {
        $query = $stmt->execute($params);
        $stamps = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        if ($stamps) {
            foreach($stamps as &$stamp) {
                $stamp['country_id'] = removeNull($stamp['country_id']);
                $stamp['price'] = removeNull($stamp['price']);
                $stamp['unit'] = removeNull($stamp['unit']);
                $stamp['year'] = removeNull($stamp['year']);
                $stamp['img'] = removeNull($stamp['img']);
                $stamp['remark'] = removeNull($stamp['remark']);
            }
            unset($stamp);

            http_response_code(200);
            $data = [
                'success' => true,
                'message' => 'Stamps found',
                'stampData' => $stamps,
            ];
        } else {
            http_response_code(404);
            $data = [
                'success' => false,
                'message' => 'No stamp found'
            ];
        }
    }else {
        http_response_code(500);
        $data = [
            'success' => false,
            'message' => 'DB error'
        ];
    }
    
    
} catch(Exception $e) {
    http_response_code(500);
    $data = [
        'success' => false,
        'message' =>  $e->getMessage(),
=======
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
>>>>>>> 6ddb6eb435e7428341837a619a2964e2bbf26e5b
    ];
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>