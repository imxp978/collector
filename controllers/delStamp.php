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
  response(400, false, "No stamp id");
}

try {
  $sql = "SELECT img FROM stamp WHERE id = ?";
  $params = [$id];
  $stmt = $link->prepare($sql);
  if ($stmt->execute($params)) {
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    if (count($image) > 0) {
      $filePath = '../images/stamps/'.$image['img'];
      if (file_exists($filePath)) {
        if (!unlink($filePath)) {
          throw new Exception('Unable to delete ' .$filePath);
        }
      }
    }
  }
  $sqlDelStamp = "DELETE FROM stamp WHERE id = ?";
  $stmtDelStamp = $link->prepare($sqlDelStamp);
  if (!$stmtDelStamp->execute($params)) {
    throw new Exception('Unable to delete stamp record');
  }
  else {
    response(200, true, 'Stamp deleted');
  }
  
} catch(Exception $e) {
  response(500, false, 'Error: '.$e->getMessage());  
}
