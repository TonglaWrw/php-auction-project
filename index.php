<?php 

    session_start();

    require_once "config/db.php";

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $deletestmt = $conn->query("DELETE FROM users WHERE id = $delete_id");
        $deletestmt->execute();

        if ($deletestmt) {
            echo "<script>alert('Data has been deleted successfully');</script>";
            $_SESSION['success'] = "Data has been deleted succesfully";
            header("refresh:1; url=index.php");
        }
        
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Auction</title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>


    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add items</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="insert.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="Name" class="col-form-label">Name:</label>
                    <input type="text" required class="form-control" name="name">
                </div>
                <div class="mb-3">
                    <label for="Price" class="col-form-label">Price:</label>
                    <input type="number" required class="form-control" name="price">
                </div>
                <div class="mb-3">
                    <label for="img" class="col-form-label">Image:</label>
                    <input type="file" required class="form-control" id="imgInput" name="img">
                    <img loading="lazy" width="100%" id="previewImg" alt="">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
        
        </div>
    </div>
    </div>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <h1>Auction PHP</h1>
            </div>
            <div class="col-md-3">
                <input class="form-control" id="search_name" type="search" placeholder="Search" aria-label="Search">
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" data-bs-whatever="@mdo">Add item</button>
            </div>
        </div>
        <hr>
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']); 
                ?>
            </div>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']); 
                ?>
            </div>
        <?php } ?>
        <div class="row" id="result">
        <?php 
                    $stmt = $conn->query("SELECT * FROM items order by price desc");
                    $stmt->execute();
                    $items = $stmt->fetchAll();

                    if (!$items) {
                        echo "<div class='d-flex justify-content-center'><h1>No data available</h1></div>";
                    } else {
                    foreach($items as $item)  {  
                    
        ?>
            <div class="col-sm-3" >
                <div class="card" style="width: 18rem;">
                    <img class="rounded" class="card-img-top" width="100%" height="200px" src="uploads/<?php echo $item['img']; ?>" alt="">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $item['name']; ?></h5>
                        <h3 class="card-text" id="price_increment"><?php echo $item['price']; ?>$</h3>
                        <a onclick="bids(<?php echo $item['id']; ?>)" class="btn btn-primary">bid</a>
                    </div>
                </div>
            </div>
        <?php }  } ?>
        </div>

    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            
            $('#search_name').keyup(function(){
                var name = $(this).val();
                // if(name != ''){
                    $.ajax({
                    type: "POST",
                    url: "search.php",
                    data: { search: name},
                    dataType:"text",
                    success:function(data) {
                        $('#result').html(data)
                    }
                });
                // }
            })
        });

        function bids(item_id){
                $.ajax({
                    type: "POST",
                    url: "bids.php",
                    data: { id: item_id},
                    success:function() {
                        location.reload();
                    }
                });
            }
        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
             const [file] = imgInput.files;
                if (file) {
                    previewImg.src = URL.createObjectURL(file)
            }
        }

        // window.setInterval('bidsall()', 10000); 	
            
        function bidsall() {
            $.ajax({
                type: "POST",
                url: "bids.php",
                data: { msg: 'bids_all_item'},
                success:function() {
                    location.reload();
                }
            });
        }



    </script>
</body>
</html>
