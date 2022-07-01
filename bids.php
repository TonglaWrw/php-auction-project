<?php 
require_once "config/db.php";
if($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST['id'];
    if($id != null){

        $sql = $conn->prepare("UPDATE items SET price = price + 10 WHERE id = '".$id."'");
        $sql->execute();

    }else{
        
        $sql = $conn->prepare("UPDATE items SET price = price + 10");
        $sql->execute();

    }
    
    http_response_code(200);
}else{
    http_response_code(405);
}


?>