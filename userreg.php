<?php
include("database.php");

if (isset($_POST['submit'])) {
    $vech_no = $_POST['vech_no'];
    $vech_name = $_POST['vech_name'];
    $vech_col = $_POST['vech_col'];
    $vech_desc = $_POST['description'];
    $cat = $_POST['vec_cat'];
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileError = $file['error'];
    $fileType = $file['type'];
    $fileExt = explode('.', $fileName);
    $fileActExt = strtolower(end($fileExt));
    $fileAllow = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'Jpg', 'Jpeg');
    $sqlChk = "SELECT * FROM vechicle WHERE vech_id='$vech_no' ";
                    $result = mysqli_query($dbh, $sqlChk);
                                $resultCheck = mysqli_num_rows($result);
                                if ($resultCheck > 0) {
                                    header("Location: register.php?usser=exist");
                                }else {
                                    if (in_array($fileActExt, $fileAllow)) {
                        if ($fileError == 0) {
                            if ($fileSize < 900000000) {
                                $image = $vech_no.'.'.$fileActExt;
                                $fileDes = "images/".$image;
                                move_uploaded_file($fileTmp, $fileDes);
                                        $status="free";
                                        $sql = "INSERT INTO vechicle(vech_id, vech_name, vech_color, category, vech_img, vech_desc, status) VALUES('$vech_no','$vech_name','$vech_col','$cat', '$image','$vech_desc','$status')";
                                        mysqli_query($dbh, $sql);
                                        header("Location: register.php?usser=success");
                                    }else {
                                        header("Location: register.php?usser=size");
                                        exit();
                                    }
                                }else {
                                    header("Location: register.php?usser=error");
                                    exit();
                                }
                            }else {
                                header("Location: register.php?usser=type");
                                exit();
                            }
                                }
                                
}
