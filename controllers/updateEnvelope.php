<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

$data = array (
  'success' => false,
  'message' => 'Operation failed'
);

function checkNull($value) {
    return ($value == '' || strtolower($value) == 'null') ? NULL : $value;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = intval($_POST['id']);
  $newSendCountry = checkNull($_POST['newSendCountry']);
  $newSendCity = checkNull($_POST['newSendCity']);
  $newSendZip = checkNull($_POST['newSendZip']);
  $newToCountry = checkNull($_POST['newToCountry']);
  $newToCity = checkNull($_POST['newToCity']);
  $newToZip = checkNull($_POST['newToZip']);
  $newSendTime = checkNull($_POST['newSendTime']);
  $newType = checkNull($_POST['newType']);
  $newTheme = checkNull($_POST['newTheme']) ;
  $newRemark = checkNull($_POST['newRemark']) ;

  try {
      $sql = "UPDATE cover SET type=?, theme=?, country_from=?, city_from=?, zip_from=?, country_to=?, city_to=?, zip_to=?, time=?, remark=? WHERE id=?";
      $params = [$newType, $newTheme, $newSendCountry, $newSendCity, $newSendZip, $newToCountry, $newToCity, $newToZip, $newSendTime, $newRemark, $id];
      
      error_log(print_r($params, true));
      
      $stmt = $link->prepare($sql);
      $query = $stmt->execute($params);

      if ($query) {
        //   http_response_code(200);
          $data = [
            'success' => true,
            'message' => 'Envelope updataed'
          ];
          updateImage($link, $id, 'newImage1', 1, $data);
          updateImage($link, $id, 'newImage2', 2, $data);
          updateImage($link, $id, 'newImage3', 3, $data);
      } else {
        http_response_code(500);
        $data =[
            'success' => false,
            'message' => 'DB error'
        ];
      }
  } catch (Exception $e) {
        http_response_code(500);
        $data = [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ];
  }
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

function updateImage($link, $id, $fileKey, $sort, &$data) {
    if (isset($_FILES[$fileKey]) && ($_FILES[$fileKey]['error'] == UPLOAD_ERR_OK)) {
        $fileName = str_pad($id, 6, '0', STR_PAD_LEFT) . "_$sort";
        $fileExtension = strrchr($_FILES[$fileKey]['name'], '.');
        $imageName = $fileName . $fileExtension;
        $fileMoved = move_uploaded_file($_FILES[$fileKey]['tmp_name'], '../images/envelopes/' . $imageName);
        
        if ($fileMoved) {
            try {
                $sqlCheck = "SELECT * FROM cover_img WHERE cover_id = ? AND sort = ?";
                $paramsCheck = [$id, $sort];
                $stmtCheck = $link->prepare($sqlCheck);
                $queryCheck = $stmtCheck->execute($paramsCheck);
                if($queryCheck) {
                    $imageInTable = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);

                    if (count($imageInTable) > 0) {
                        $sql = "UPDATE cover_img SET img = ? WHERE cover_id = ? AND sort = ?";
                    } else {
                        $sql = "INSERT INTO cover_img (img, cover_id, sort) VALUES (?, ?, ?)";
                    }

                    $params = [$imageName, $id, $sort];
                    $stmt = $link->prepare($sql);
                    $query = $stmt->execute($params);

                    if ($query) {
                        $data['success'] = true;
                        $data['message'] .= ", Image$sort updated";
                    } else {
                        $data['message'] .= "Update image$sort file name failed";
                    }
                }
            } catch (Exception $e) {
                $data['message'] = "Update image$sort failed: " . $e->getMessage();
            }
        } else {
            $data['message'] = "Moving image$sort file failed";
        }
    }
}
?>