<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: multipart/form-data; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $country = ($_POST['country'] == '') ? NULL : $_POST['country'];
    $price = ($_POST['price'] == '') ? NULL : $_POST['price'];
    $year = ($_POST['year'] == '') ? NULL : $_POST['year'];
    $unit = ($_POST['unit'] == '') ? NULL : $_POST['unit'] ;
    $remark = ($_POST['remark'] == '') ? NULL : $_POST['remark'] ;


    if (isset($_FILES['image']) && ($_FILES['image']['error'] == UPLOAD_ERR_OK)) {
        $sqlImageName = sprintf("SELECT img FROM stamp WHERE id = %d", $id);
        $queryImageName = $link->query($sqlImageName);
        $imageName = $queryImageName->fetch();
        if ($imageName) {
            // echo 'file fetched';
            // echo $_FILES['image']['name'];
            $fileName = pathinfo($imageName[0])['filename']. '.'. pathinfo($_FILES['image']['name'])['extension'];
            // echo $fileName;
            $fileMoved = move_uploaded_file($_FILES['image']['tmp_name'], '../images/stamps/'.$fileName);
            $sqlImage = sprintf("UPDATE stamp SET img='%s' WHERE id=%d", $fileName, $id);
            $queryImage = $link->query($sqlImage);
        }
    }
 
    if ($country !== NULL){
        // $sql = sprintf("UPDATE stamp SET country_id=%d, price=%d, year=%d, unit='%s', remark='%s' WHERE id=%d", $country, $price, $year, $unit, $remark, $id);
        $sql = "UPDATE stamp SET country_id=?, price=?, year=?, unit=?, remark=? WHERE id=?";
        $params = [ $country, $price, $year, $unit, $remark, $id ];        
    } else {
        // $sql = sprintf("UPDATE stamp SET price=%d, year=%d, unit='%s', remark='%s' WHERE id=%d", $price, $year, $unit, $remark, $id);
        $sql = "UPDATE stamp SET price=?, year=?, unit=?, remark=? WHERE id=?";
        $params = [ $price, $year, $unit, $remark, $id ];
    }
    // $query = $link->query($sql);
    $stmt = $link->prepare($sql);
    if ($stmt) {
        $query = $stmt->execute($params);
    }

    if ($query) {
        $sqlUpdate = sprintf("SELECT * FROM stamp WHERE id =%d", $id);
        $queryUpdate = $link->query($sqlUpdate);
        $stamps = $queryUpdate->fetchAll(PDO::FETCH_ASSOC);
        if ($stamps) {
            http_response_code(200);
            $data = [
                'success' => true,
                'message' => 'Stamp updated',
                'stampData' => $stamps
            ];
        } else {
            http_response_code(404);
            $data = [
                'success' => false,
                'message' => 'Stamp updated but stamp list not found'
            ];
        }
    } else {
        http_response_code(500);
        $data = [
            'success' => false,
            'message' => 'Stamp updated but cannot get stamp list'
        ];
    }
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);
