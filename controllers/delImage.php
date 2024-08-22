<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  $data = [
    'success'=> false,
    'message' => 'Operation failed'
  ];
  require_once("../controllers/connections/conn_db.php");
  $uri = $_SERVER['REQUEST_URI'];
  $uriParts = explode('/', trim($uri, '/'));
  $id = $uriParts[count($uriParts) - 1];
  if($id) {
    try {
      $sqlCheck = "SELECT img FROM cover_img WHERE id = ?";
      $paramsCheck = [$id];
      $stmtCheck = $link->prepare($sqlCheck);
      
      if ($stmtCheck->execute($paramsCheck)) {
        $image = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($image) {
          $filePath = '../images/envelopes/'. $image['img'];

          if (file_exists($filePath) && unlink($filePath)) {
            
            $sql = "DELETE FROM cover_img WHERE ID = ?";
            $params = [$id];
            $stmt = $link->prepare($sql);

            if ($stmt->execute($params)) {
              http_response_code(200);
              $data = [
                'success' => true,
                'message' => '圖片已刪除'
              ];
            } else {
              http_response_code(500);
              $data['message'] = 'DB error';
            
            }
          } else {
            http_response_code(500);
            $data['message'] = 'Delete failed';
          }
        }
      }
    } catch (Exception $e) {
      http_response_code(500);
      $data['message'] = 'DB error'. $e->getMessage();
    }
  }
} else {
  http_response_code(405);
  $data['message'] = 'Invalid request';
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);