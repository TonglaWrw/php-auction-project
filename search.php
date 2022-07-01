<?php 
require_once "config/db.php";

if(isset($_POST['search'])){
    $input = $_POST['search'];

    $stmt = $conn->query("SELECT * FROM items WHERE name LIKE '%{$input}%'  order by price desc");
    $stmt->execute();
    $items = $stmt->fetchAll();
    if (!$items) {
        echo "<div class='d-flex justify-content-center'><h1>No data available</h1></div>";
    } else {
    foreach($items as $item)  { ?>  
        <div class="col-sm-3">
                <div class="card" style="width: 18rem;">
                <img class="rounded" class="card-img-top" width="100%" height="200px" src="uploads/<?php echo $item['img']; ?>" alt="">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $item['name']; ?></h5>
                    <h3 class="card-text" id="price_increment"><?php echo $item['price']; ?>$</h3>
                    <a onclick="bids(<?php echo $item['id']; ?>)" class="btn btn-primary">bid</a>
                </div>
            </div>
        </div>

        <?php }  }
    
}
?>