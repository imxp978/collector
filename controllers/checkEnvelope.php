<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

$sendCountry = (isset($_GET['country']) && $_GET['country'] !== '') ? $_GET['country'] : NULL;
<<<<<<< HEAD
$year = ($_GET['year'] !== '')? $_GET['year'] : NULL;
$id = ($_GET['id'] !== '')? $_GET['id'] : NULL;
$limit = ($_GET['limit']);

if ($limit == 1) {
  $sql = "SELECT * FROM cover ORDER BY id DESC LIMIT 12";
} else if($limit == 0) {
  $condition = [];
  if ($sendCountry !== NULL) {
    $condition[] = "cover.country_from = $sendCountry";
  }
  if ($year != NULL) {
    $condition[] = "cover.time = $year";
  }
  if ($id !== NULL) {
    $condition[] = "cover.id = $id";
  }
  $where = count($condition) > 0 ? ' WHERE ' :'';
  
  $sql = "SELECT * FROM cover" . $where . implode(' AND ', $condition) . " ORDER BY id DESC";
}
// echo $sql;

function removeNull($value) {
  return ($value == null || strtolower($value) == 'null') ? '' : $value;
}

try {
  $query = $link->query($sql);
  if ($query) {
    $envelopes = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($envelopes) {
      foreach($envelopes as &$envelope) {
        $sqlImage = sprintf("SELECT * FROM cover_img WHERE cover_id = %d ORDER BY sort", $envelope['id']);
        $queryImage = $link -> query($sqlImage);
        $images = $queryImage -> fetchAll(PDO::FETCH_ASSOC);
        $envelope['img'] = $images;
        $envelope['theme'] = removeNull($envelope['theme']);
        $envelope['country_from'] = removeNull($envelope['country_from']);
        $envelope['city_from'] = removeNull($envelope['city_from']);
        $envelope['zip_from'] = removeNull($envelope['zip_from']);
        $envelope['country_to'] = removeNull($envelope['country_to']);
        $envelope['city_to'] = removeNull($envelope['city_to']);
        $envelope['zip_to'] = removeNull($envelope['zip_to']);
        $envelope['time'] = removeNull($envelope['time']);
        $envelope['type'] = removeNull($envelope['type']);
        $envelope['remark'] = removeNull($envelope['remark']);
      }
      unset($envelope);
      http_response_code(200);
      $data = [
        'success' => true,
        'message' => 'Envelopes found',
        'envelopeData' => $envelopes,
      ]; 
    } else {
      http_response_code(404);
      $data = [
        'success' => false,
        'message' => 'No envelope found',
      ];
    } 
  } else {
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
    'message' => $e->getMessage()
  ];
}

=======

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
>>>>>>> 6ddb6eb435e7428341837a619a2964e2bbf26e5b
echo json_encode($data, JSON_UNESCAPED_UNICODE);