<?php
    include('function.php');
    if(!empty($_SESSION['uid'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- @theme style -->
    <link rel="stylesheet" href="assets/style/theme.css">

    <!-- @Bootstrap -->
    <link rel="stylesheet" href="assets/style/bootstrap.css">

    <!-- @script -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/bootstrap.js"></script>

    <!-- @tinyACE -->
    <script src="https://cdn.tiny.cloud/1/5gqcgv8u6c8ejg1eg27ziagpv8d8uricc4gc9rhkbasi2nc4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

</head>
<body>
    <main class="admin">
        <div class="container-fluid">
            <div class="row">
                <div class="col-2">
                    <div class="content-left">
                        <div class="wrap-top">
                            <h5>Jong Deng News</h5>
                        </div>
                        <div class="wrap-center">
                            <?php
                                $userDatat = getCurrentProfile();
                                echo '
                                    <img src="assets/image/'.$userDatat['pofile'].'" style="width:50px; height:50px; object-fit: cover">
                                    <h6 class="m-0">Welcome '.$userDatat['name'].'</h6>
                                ';
                            ?>
                        </div>
                        <div class="wrap-bottom">
                            <ul>
                                <!-- Nav Menu -->
                                <li class="parent">
                                    <a class="parent" href="javascript:void(0)">
                                        <span>Posts</span>
                                        <img src="assets/icon/arrow-right.svg" style="width: 1.1rem">
                                    </a>
                                    <ul class="child">
                                        <li>
                                            <a href="list-post.php">List Post</a>
                                            <a href="list-logo.php">List Logo</a>
                                            <a href="add-post.php">Add New</a>
                                            <a href="add-logo.php">Add Logo</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="parent">
                                    <a class="parent" href="list-log.php">
                                        <span>List Log</span>
                                    </a>
                                </li>

                                <!-- category -->
                                <li class="parent">
                                    <a class="parent" href="javascript:void(0)">
                                        <span>Category</span>
                                        <img src="assets/icon/arrow-right.svg" style="width: 1.1rem">
                                    </a>
                                    <ul class="child">
                                        <li>
                                            <a href="add-category.php">Add Category</a>
                                            <a href="list-category.php">List Category</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="parent">
                                    <a class="parent" href="list-log.php">
                                        <span>List Log</span>
                                    </a>
                                </li>

                                <!-- Add news -->
                                <li class="parent">
                                    <a class="parent" href="javascript:void(0)">
                                        <span>News</span>
                                        <img src="assets/icon/arrow-right.svg" style="width: 1.1rem">
                                    </a>
                                    <ul class="child">
                                        <li>
                                            <a href="add-news.php">Add News</a>
                                            <a href="list-news.php">List News</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="parent">
                                    <a class="parent" href="javascript:void(0)">
                                        <span>Social</span>
                                        <img src="assets/icon/arrow-right.svg" style="width: 1.1rem">
                                    </a>
                                    <ul class="child">
                                        <li>
                                            <a href="add-social.php">Add Social</a>
                                            <a href="list-social.php">List Category</a>
                                        </li>
                                    </ul>
                                </li>
                                <!-- <li class="parent">
                                    <a class="parent" href="add-news.php">
                                        <span>Add News</span>
                                    </a>
                                </li>
                                <li class="parent">
                                    <a class="parent" href="list-news.php">
                                        <span>List News</span>
                                    </a>
                                </li> -->
                                <!-- logout -->
                                <li class="parent">
                                    <button type="button" remove-id="1" class="parent btn btn-danger btn-remove" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Logout
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure to logout?</h5>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="" method="post">
                                            <input type="hidden" class="value_remove" name="remove_id">
                                            <button type="submit" class="btn btn-danger" name="btn-logout">Yes</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>  
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
    }
    else{
        header('Location: login.php');
    }
?>