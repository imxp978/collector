<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

$sendCountry = (isset($_GET['country']) && $_GET['country'] !== '') ? $_GET['country'] : NULL;

try {
  if ($sendCountry == NULL) {
    $sql = "SELECT * FROM cover ORDER BY id DESC";
    $stmt = $link->prepare($sql);
    $query = $stmt->execute();
  } else {
    $sql = "SELECT * FROM cover WHERE cover.country_from = ? ORDER BY id DESC";
    $params = [$sendCountry];
    $stmt = $link->prepare($sql);
    $query = $stmt->execute($params);
  }
  
  if ($query) {
    $envelopes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($envelopes) {
      foreach($envelopes as &$envelope) {
        $sqlImage = sprintf("SELECT * FROM cover_img WHERE cover_id = %d ORDER BY sort", $envelope['id']);
        $queryImage = $link->query($sqlImage);
        $images = $queryImage->fetchAll(PDO::FETCH_ASSOC);
        $envelope['img'] = $images;
      }
      unset($envelope);
      
      $data = array(
        'success' => true,
        'message' => 'Envelopes found',
        'envelopeData' => $envelopes, 
      );
    } else {
      $data = array(
        'success' => false,
        'message' => 'No envelope found'
      );
    }
  } 

} catch (Exception $e) {
  $data = array(
    'success' => false,
    'message' => 'error: '.$e->getMessage(),
  );
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);