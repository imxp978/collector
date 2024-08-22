<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

try {
  $sql = "SELECT * FROM type";
  $query = $link->query($sql);
  $types = $query->fetchAll(PDO::FETCH_ASSOC);
  if ($types) {
    $data = array(
      'success' => true,
      'message' => 'Types found',
      'typeData' => $types,
    );
  } else {
    $data = array(
      'success' => false,
      'message' => 'Types not found',
    );
  }
} catch(Exception $e) {
  $data = array(
    'success' => false,
    'message' => $e->getMessage(),
  );
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>