<?php
// session_start();
// echo($_SESSION['user']);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type"); // 加了以後才能過cros檢驗
header("Content-Type: application/json; charset=UTF-8");


function response($status, $success, $message) {
  http_response_code($status);
  echo json_encode(
    [
      'success' => $success,
      'message' => $message
    ], JSON_UNESCAPED_UNICODE
  );
  exit;
}
     
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  response(200, true, '');
}

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
  response(405, false, 'Method not allowed');
}

// if ($_SESSION['user'] != 'fongming') {
//   response(403, false, 'Unauthorized, please login');
// }

require_once ("../controllers/connections/conn_db.php");

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'];

if (!$id) {
  response(400, false, "No envelope id");
}

try {
  $sql = "SELECT img FROM cover_img WHERE cover_id = ?";
  $params = [$id];
  $stmt = $link->prepare($sql);
  if ($stmt->execute($params)) {
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($images) > 0) {
      foreach( $images as $image) {
        $filePath = '../images/envelopes/'.$image['img'];
        if (file_exists($filePath)) {
          if (!unlink($filePath)) {
            throw new Exception('Unable to delete ' .$filePath);
          }
        }
      }
      $sqlDelImg = "DELETE FROM cover_img WHERE cover_id = ?";
      $stmtDelImg = $link->prepare($sqlDelImg);
      if (!$stmtDelImg->execute($params)) {
        throw new Exception('Unable to delete related image records');
      }
    }
  }
  $sqlDelEnvelope = "DELETE FROM cover WHERE id = ?";
  $stmtDelEnvelope = $link->prepare($sqlDelEnvelope);
  if (!$stmtDelEnvelope->execute($params)) {
    throw new Exception('Unable to delete envelope record');
  }
  else {
    response(200, true, 'Envelope deleted');
  }
  
} catch(Exception $e) {
  response(500, false, 'Error: '.$e->getMessage());  
}
