<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json;charset=utf-8');

require_once("../controllers/connections/conn_db.php");

$input = json_decode(file_get_contents('php://input'), true);
$userName = $input['username'];
$passWord = md5($input['password']);

try {
  if ($userName =='fongming' && $passWord == md5('Aa24619622')) {
    $_SESSION['user'] = $userName;
    $data = array(
      'success' => true,
      'message' => '成功登入',
      'user' => 'admin'
    );
  } else {
    $data = array(
      'success' => false,
      'message' => '帳號密碼錯誤'
    );
  }
} catch(Exception $e) {
  $data = array(
    'success' => false,
    'message' => '伺服器錯誤',
  );
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);
// echo ($_SESSION['user']);

?>