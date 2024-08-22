<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: multipart/form-data; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = ($_POST['id']);
    $country = ($_POST['country']) ? $_POST['country'] : NULL;
    $price = ($_POST['price']) ? $_POST['price'] : NULL;
    $year = ($_POST['year']) ? $_POST['year'] : NULL;
    $unit = ($_POST['unit']) ? $_POST['unit'] : NULL;
    $remark = ($_POST['remark']) ? $_POST['remark'] : NULL;


    if (isset($_FILES['image']) && ($_FILES['image']['error'] == UPLOAD_ERR_OK)) {
        $sqlImageName = sprintf("SELECT img FROM stamp WHERE id = %d", $id);
        $queryImageName = $link->query($sqlImageName);
        $imageName = $queryImageName->fetch();
        if ($imageName) {
            // echo 'file fetched';
            // echo $_FILES['image'];
            $fileName = pathinfo($imageName[0])['filename']. '.'. pathinfo($imageName[0])['extension'];
            $fileMoved = move_uploaded_file($_FILES['image']['tmp_name'], '../images/stamps/'.$fileName);
        }
    }
    

    if ($country !== NULL){
        echo $country;
        $sql = sprintf("UPDATE stamp SET country_id=%d, price=%d, year=%d, unit='%s', remark='%s' WHERE id=%d", $country, $price, $year, $unit, $remark, $id);
    } else {
        $sql = sprintf("UPDATE stamp SET price=%d, year=%d, unit='%s', remark='%s' WHERE id=%d", $price, $year, $unit, $remark, $id);
    }
    $query = $link->query($sql);
    if ($query) {
        $sqlUpdate = sprintf("SELECT * FROM stamp WHERE id =%d", $id);
        $queryUpdate = $link->query($sqlUpdate);
        $stamps = $queryUpdate->fetchAll(PDO::FETCH_ASSOC);
        if ($stamps) {
            $data = array(
                'success' => true,
                'message' => 'Stamp updated',
                'stampData' => $stamps
            );
        } else {
            $data = array(
                'success' => false,
                'message' => 'Stamp updated but stamp list not found'
            );
        }
    } else {
        $data = array(
            'success' => false,
            'message' => 'Stamp updated but cannot get stamp list'
        );
    }
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);
