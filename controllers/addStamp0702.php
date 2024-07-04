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

    // if ($queryFindLastFile) {
    if (isset($_FILES['image']) && ($_FILES['image']['error'] == UPLOAD_ERR_OK)) {
        $imageName = NULL;
        $sqlFindLastFile = "SELECT MAX(img) FROM stamp";
        $queryFindLastFile = $link->query($sqlFindLastFile);
        $lastFile = $queryFindLastFile->fetch();
        if ($lastFile) {
            $newFileNumber = intval(pathinfo($lastFile[0], PATHINFO_FILENAME)) + 1;
            $newFileName = str_pad($newFileNumber,6, '0', STR_PAD_LEFT);
            $fileExtension = strrchr($_FILES['image']['name'],'.');
            $imageName = $newFileName. $fileExtension;
            // echo $imageName;
            $fileMoved = move_uploaded_file($_FILES['image']['tmp_name'], '../images/stamps/'.$imageName);
            if ($fileMoved) {
                $sql = sprintf("INSERT INTO stamp (country_id, price, unit, year, img, remark) VALUES (%d, %d, '%s', '%s', '%s', '%s')", $country, $price, $unit, $year, $imageName, $remark);
                $query = $link->query($sql);
                if ($query) {
                    $sqlUpdate = sprintf("SELECT * FROM stamp WHERE country_id = %d AND year = %s AND price = %d ORDER BY id DESC" , $country, $year, $price);
                    $queryUpdate = $link->query($sqlUpdate);
                    $stamps = $queryUpdate->fetchAll();
                    if ($stamps) {
                        $data = array(
                            'success' => true,
                            'message' => 'Stamp added',
                            'stampData' => $stamps
                        );
                    } else {
                        $data = array(
                            'success' => false,
                            'message' => 'Stamp added but stamp list not found'
                        );
                    }
                } else {
                    $data = array(
                        'success' => false,
                        'message' => 'Stamp added but cannot get stamp list'
                    );
                }
            } else {
                $data = array(
                    'success' => false,
                    'message' => 'Image uploaded but failed to move image'
                );
            }
        } else {
            $data = array(
                'success' => false,
                'message' => 'Image uploaded but canoot get last filename'
            );
        }     
    } else {
        $data = array(
            'success' => false,
            'message' => 'Image upload failed'
        );
    }
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>