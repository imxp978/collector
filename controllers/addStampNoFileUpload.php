<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $country = !empty($input['country']) ? $input['country'] : NULL;
    $price = !empty($input['price']) ? $input['price'] : NULL;
    $year = !empty($input['year']) ? $input['year'] : NULL;
    $unit = !empty($input['unit']) ? $input['unit'] : NULL;
    $image = !empty($input['image']) ? $input['image'] : NULL;
    $remark = !empty($input['remark']) ? $input['remark'] : NULL;

    $sql = sprintf("INSERT INTO stamp (country_id, price, unit, year, img, remark) VALUES (%d, %d, '%s', '%s', '%s', '%s')", $country, $price, $unit, $year, $image, $remark);
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