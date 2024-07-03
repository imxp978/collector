<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: multipart/form-data; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $country = ($_POST['country']) ? $_POST['country'] : NULL;
    $price = ($_POST['price']) ? $_POST['price'] : NULL;
    $year = ($_POST['year']) ? $_POST['year'] : NULL;
    $unit = ($_POST['unit']) ? $_POST['unit'] : NULL;
    $remark = ($_POST['remark']) ? $_POST['remark'] : NULL;

    $imageName = NULL;
    $sqlFindLastFile = "SELECT MAX(img) FROM stamp";
    $queryFindLastFile = $link->query($sqlFindLastFile);

    if ($queryFindLastFile) {
        $lastFile = $queryFindLastFile->fetch();
        $newFileNumber = intval(pathinfo($lastFile[0], PATHINFO_FILENAME)) + 1;
        $newFileName = str_pad($newFileNumber,6, '0', STR_PAD_LEFT);
        $fileExtension = strrchr($_FILES['image']['name'],'.');
        $imageName = $newFileName. $fileExtension;
        // echo $fileName;
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/stamps/'.$imageName);
    }

    $sql = sprintf("INSERT INTO stamp (country_id, price, unit, year, img, remark) VALUES (%d, %d, '%s', '%s', '%s', '%s')", $country, $price, $unit, $year, $imageName, $remark);
    $query = $link->query($sql);
    if ($query) {
        $sqlUpdate = sprintf("SELECT * FROM stamp WHERE country_id = %d AND year = %s AND price = %d", $country, $year, $price);
        $queryUpdate = $link->query($sqlUpdate);
        $stamps = $queryUpdate->fetchAll();
        if ($stamps) {
            $data = array(
                'success' => true,
                'message' => 'stamp added',
                'stampData' => $stamps
            );
        } else {
            $data = array(
                'success' => false,
                'message' => 'stamp added failed'
            );
        }
    } else {
        $data = array(
            'success' => false,
            'message' => 'stamp added failed'
        );
    }
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>