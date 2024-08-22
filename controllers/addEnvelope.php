<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

$data = [
  'success' => false,
  'message' => 'Operation failed'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
  $sendCountry = ($_POST['sendCountry'] !== '')? $_POST['sendCountry']: NULL;
  $sendCity = ($_POST['sendCity'] !== '')? $_POST['sendCity']: NULL;
  $sendZip = ($_POST['sendZip'] !== '')? $_POST['sendZip']: NULL;
  $toCountry = ($_POST['toCountry'] !== '')? $_POST['toCountry']: NULL;
  $toCity = ($_POST['toCity'] !== '')? $_POST['toCity']: NULL;
  $toZip = ($_POST['toZip'] !== '')? $_POST['toZip']: NULL;
  $remark = ($_POST['remark'] !== '')? $_POST['remark']: NULL;
  $theme = ($_POST['theme'] !== '')? $_POST['theme']: NULL;
  $type = ($_POST['type'] !== '')? $_POST['type']: NULL;
  $sendTime = ($_POST['sendTime'] !== '')? $_POST['sendTime']: NULL;

  // cover
  try {
      $sql = "INSERT INTO cover (type, theme, country_from, city_from, zip_from, country_to, city_to, zip_to, time, remark) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $params = [$type, $theme, $sendCountry, $sendCity, $sendZip, $toCountry, $toCity, $toZip, $sendTime, $remark];
      $stmt = $link->prepare($sql);
      $query = $stmt->execute($params);

      if ($query) {
        $data = [
          'success' => true,
          'message' => 'Envelope added'
        ];

        // images (cover_img)
        try {
          $sqlFindCurrentId = "SELECT MAX(id) FROM cover";
          $queryFindCurrentId = $link->query($sqlFindCurrentId);
          $currentId = $queryFindCurrentId->fetch()[0];

          $imageUploaded = false;
      
          for ($i = 0; $i < 3; $i++) {
            if (isset($_FILES['image'.($i+1)]) && ($_FILES['image'.($i+1)]['error'] == UPLOAD_ERR_OK)) {
              $fileName = str_pad($currentId, 6, '0', STR_PAD_LEFT). '_'. ($i+1);
              $fileExtension = strrchr($_FILES['image'.($i+1)]['name'], '.');
              $imageName = $fileName . $fileExtension;

              $path = '../images/envelopes/'.$imageName;
              $fileMoved = move_uploaded_file($_FILES['image'. ($i+1)]['tmp_name'], $path);

              if ($fileMoved) {
                $sql = "INSERT INTO cover_img (cover_id, img, sort) VALUES(?, ?, ?)";
                $stmt = $link->prepare($sql);
                $query = $stmt->execute([$currentId, $imageName, $i+1]);

                if ($query) {
                  $imageUploaded = true;
                  $data['message'] .= ', image'.($i+1).' uploaded';
                }
              }
            }
          }

          if (!$imageUploaded) {
            $data['message'] .= ', no images uploaded';
          }
        } catch (Exception $e) {
          http_response_code(500);
          $data = [
            'success' => false,
            'message' => 'Error: '.$e->getMessage()
          ];
        }
      }
  } catch (Exception $e) {
    http_response_code(500);
    $data = [
      'success' => false,
      'message' => 'Error: '.$e->getMessage()
    ];
  }
} echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
