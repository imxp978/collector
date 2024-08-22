<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once("../controllers/connections/conn_db.php");
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {

<<<<<<< HEAD
            $country = ($_POST['country'] !=='') ? $_POST['country'] : NULL;
            $price = ($_POST['price'] !=='') ? $_POST['price'] : NULL;
            $year = ($_POST['year'] !=='') ? $_POST['year'] : NULL;
            $unit = ($_POST['unit'] !=='') ? $_POST['unit'] : NULL;
=======
            $country = ($_POST['country']) ? $_POST['country'] : NULL;
            $price = ($_POST['price']) ? $_POST['price'] : NULL;
            $year = ($_POST['year']) ? $_POST['year'] : NULL;
            $unit = ($_POST['unit']) ? $_POST['unit'] : NULL;
>>>>>>> 6ddb6eb435e7428341837a619a2964e2bbf26e5b
            $remark = ($_POST['remark']) ? $_POST['remark'] : NULL;

            // if ($queryFindLastFile) {
            if (isset($_FILES['image']) && ($_FILES['image']['error'] == UPLOAD_ERR_OK)) {
                $imageName = NULL;
                $sqlFindLastFile = "SELECT MAX(id) FROM stamp";
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
                        // $sql = sprintf("INSERT INTO stamp (country_id, price, unit, year, img, remark) VALUES (%d, %d, '%s', '%s', '%s', '%s')", $country, $price, $unit, $year, $imageName, $remark);
                        $sql = "INSERT INTO stamp (country_id, price, unit, year, img, remark) VALUES (?, ?, ?, ?, ?, ?)";
                        $params = [ $country, $price, $unit, $year, $imageName, $remark ];
                        $stmt = $link->prepare($sql);
                        $query = $stmt->execute($params);
                        // $query = $link->query($sql);
                        if ($query) {
                            // $sqlUpdate = sprintf("SELECT * FROM stamp WHERE country_id = %d AND year = %s AND price = %d", $country, $year, $price);
                            $sqlUpdate = "SELECT * FROM stamp WHERE country_id = ? AND year = ? AND price =?";
                            $paramsUpdate = [ $country, $year, $price];
                            $stmtUpdate = $link->prepare($sqlUpdate);
                            $queryUpdate = $stmtUpdate->execute($paramsUpdate);
                            // $queryUpdate = $link->query($sqlUpdate);
<<<<<<< HEAD
                            $stamps = $stmtUpdate->fetchAll(PDO::FETCH_ASSOC);
                            if ($stamps) {
                                http_response_code(201);
                                $data = [
                                    'success' => true,
                                    'message' => 'Stamp added',
                                    'stampData' => $stamps
                                ];
                            } else {
                                http_response_code(500);
                                $data = [
                                    'success' => false,
                                    'message' => 'Stamp added but stamp list not found'
                                ];
                            }
                        } else {
                            http_response_code(500);
                            $data = [
                                'success' => false,
                                'message' => 'Stamp added but cannot get stamp list'
                            ];
                        }
                    } else {
                        http_response_code(500);
                        $data = [
                            'success' => false,
                            'message' => 'Stamp added but failed to save image'
                        ];
                    }
                } else {
                    http_response_code(500);
                    $data = [
                        'success' => false,
                        'message' => 'Image uploaded but canoot get last filename'
                    ];
                }     
            } else {
                http_response_code(500);
                $data = [
                    'success' => false,
                    'message' => 'Image upload failed'
                ];
            }
        } catch(Exception $error) {
            http_response_code(500);
            $data = [
                'success' => false,
                'message' => $error->getMessage()
            ];
=======
                            $stamps = $queryUpdate->fetchAll(PDO::FETCH_ASSOC);
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
        } catch(Exception $error) {
            $data = array(
                'success' => false,
                'message' => $error->getMessage()
            );
>>>>>>> 6ddb6eb435e7428341837a619a2964e2bbf26e5b
        } 
    }
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>