<?php 

session_start();
require_once "config/db.php";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $img = $_FILES['img'];

    $allow = array('jpg', 'jpeg', 'png');
    $extension = explode('.', $img['name']);
    $fileActExt = strtolower(end($extension));
    $fileNew = rand() . "." . $fileActExt;  // rand function create the rand number 
    $filePath = 'uploads/'.$fileNew;

    if (in_array($fileActExt, $allow)) {
        if ($img['size'] > 0 && $img['error'] == 0) {
            if (move_uploaded_file($img['tmp_name'], $filePath)) {
                $sql = $conn->prepare("INSERT INTO items(name, price, img) VALUES(:name, :price, :img)");
                $sql->bindParam(":name", $name);
                $sql->bindParam(":price", $price);
                $sql->bindParam(":img", $fileNew);
                $sql->execute();

                if ($sql) {
                    $_SESSION['success'] = "Data has been inserted successfully";
                    header("location: index.php");
                } else {
                    $_SESSION['error'] = "Data has not been inserted successfully";
                    header("location: index.php");
                }
            }
        }
    }
}


?>