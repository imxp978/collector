<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

try {
  $sql = "SELECT * FROM country";
  $query = $link->query($sql);
  $countries = $query->fetchAll(PDO::FETCH_ASSOC);
  if ($countries) {
    $data = array(
      'success' => true,
      'message' => 'Countries got',
      'countryData' => $countries,
    );
  } else {
    $data = array(
      'success' => false,
      'message' => 'Countries not found',
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