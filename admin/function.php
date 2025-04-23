<!-- @import jquery & sweet alert  -->
<script src="assets/js/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
    session_start();

    //Connection to DB
    function connection_db() {
        $con = new mysqli('','root','','news');
        return $con;
    }

    //Upload file
    function uploadFile($file){
        $file_name = rand(1,200).'-'.$file['name'];
        // echo $file_name;
        $path = $_SERVER['DOCUMENT_ROOT']."/PHP CMS Template/admin/assets/image/".$file_name;
        $source_file = $file['tmp_name'];
        move_uploaded_file($source_file,$path);
        // echo $path;
        return $file_name;
    }
    

    //For create and update we use currentDate()
    function currentDate(){
        $date = date('Y-m-d');
        return $date;
    }

    //Connect to sql
    function connectSql($sql_insert){
        $sql = connection_db()->query($sql_insert);
        if($sql){
            return true;
        }
        else{
            return false;
        }
    }
    function userRegister(){
        if(isset($_POST['btn_register'])){
            if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_FILES['profile'])){
                $name     = $_POST['name'];
                $email    = $_POST['email'];
                $password = md5($_POST['password']);

                // Create funtion for calling file
                // $image    = rand(1,200).'-'.$_FILES['profile']['name'];
                // $path     = $_SERVER['DOCUMENT_ROOT']."/PHP CMS Template/admin/assets/image".$image;
                // move_uploaded_file($_FILES['profile']['tmp_name'],$path);
                $file = $_FILES['profile'];
                $file_name = uploadFile($file);

                $sql_insert = "INSERT INTO `user`(`name`, `email`, `password`, `pofile`, `is_deleted`, `created_at`, `updated_at`) 
                               VALUES ('".$name."','".$email."','".$password."','".$file_name."',0,'".currentDate()."','".currentDate()."')";
                $sqlInsert = connectSql($sql_insert);
                if($sqlInsert){
                    // return 'User registered';
                    echo 'Success';
                    header('Location: login.php');
                }
            }
            else{
                return 'Information is not full';
            }
            
            
        }
    }
    userRegister();

    function userLogin(){
        if(isset($_POST['btn_login'])){
            $user_name = $_POST['name_email'];
            $password  = md5($_POST['password']);

            $login = "SELECT * FROM `user` WHERE (name = '$user_name' OR email = '$user_name') AND password='$password'";
            // $result = connectSql($login);
            $result = connection_db()->query($login);
            $row = mysqli_fetch_assoc($result);
            if($row > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $_SESSION['uid']=$row['id'];
                    // echo $_SESSION['uid'];
                    header('Location: index.php');
                }   
            }
            else{
                echo 0;
            }
        }
    }
    userLogin();

    function userLogout(){
        if(isset($_POST['btn-logout'])){
            unset($_SESSION['uid']);
            header('Location: login.php');
        }
    }
    userLogout();

    function getCurrentProfile(){
        $uid = $_SESSION['uid'];
        $sql_select = "SELECT * FROM `user` WHERE id = $uid";
        $result_select = connection_db()->query($sql_select);
        $row = mysqli_fetch_assoc($result_select);
        return $row;
    }

    function activityLog($postType,$action) {
        $actions = $action;
        $uID = $_SESSION['uid'];

        if($postType == 'logo') {
            $tableName = 'website_logo';
        }
        else if($postType == 'category') {
            $tableName = 'category';
        }
        else if($postType == 'news2') {
            $tableName = 'news2';
        }
        else if($postType == 'social') {
            $tableName = 'social';
        }

        $sqlSelect = "SELECT id FROM ".$tableName." ORDER BY id DESC LIMIT 1";
        $rs        = connection_db()->query($sqlSelect);
        $row       = mysqli_fetch_assoc($rs);
        $postID    = $row['id']; 

        $sqlStr = "
            INSERT INTO `activity_log`(`post_type`, `post_id`, `author_id`, `action`, `created_at`) 
            VALUES ('". $postType ."', $postID, $uID, '". $actions ."', '". currentDate() ."');
        ";
        connectSql($sqlStr);
    }

    function getCurrentPost($postID, $tableName) {
        $sqlStr = "SELECT * FROM ". $tableName ." WHERE id = $postID";
        $rs     = connection_db()->query($sqlStr);
        $row    = mysqli_fetch_assoc($rs);
        return $row;
    }

    function addLogo(){
        if(isset($_POST['btn-insert-logo'])){
            if(!empty($_FILES['thumbnail']['name'])){
                if(!empty($_POST['pined'])){
                    $pined = 1;
                    $sqlPined = "UPDATE `website_logo` SET `pined`= 0 WHERE pined = $pined";
                    connectSql($sqlPined);
                }
                else{
                    $pined = 0;
                }
            
            $file = $_FILES['thumbnail'];
            $filename = uploadFile($file);

            $uid = $_SESSION['uid'];

            $sqlStr = "
                INSERT INTO `website_logo`(`thumbnail`, `pined`, `author_id`, `is_deleted`, `created_at`, `updated_at`) 
                VALUES ('".$filename."','".$pined."','".$uid."',1,'".currentDate()."','".currentDate()."')
            ";
            $result = connectSql($sqlStr);
            if($result){
                activityLog('logo','insert');
                return 'Post insert successfully';
            }
            else{
                return 'Internal server error!';
            }
            }
            else{
                return 'Please input information';
            }
        }
    }
    function listLogo(){
        $sql_select = "
            SELECT website_logo.*, user.name AS u_name 
            FROM website_logo INNER JOIN user ON website_logo.author_id =  user.id 
            WHERE website_logo.is_deleted = 1 ORDER BY website_logo.id DESC 
        ";
        $result = connection_db()->query($sql_select);
        while($row = mysqli_fetch_assoc($result)){
            $pined = $row['pined'];
            if($pined == 1){
                $strPined = 'pined';
            }
            else{
                $strPined = 'unpined';
            }
            echo '
                <tr>
                    <td>'. $row['id'] .'</td>
                    <td><img src="assets/image/'. $row['thumbnail'] .'"></td>
                    <td>'. $strPined .'</td>
                    <td>'. $row['u_name'] .'</td>
                    <td>'. $row['created_at'] .'</td>
                    <td width="150px">
                        <a href="update-logo.php?id='.$row['id'].'" class="btn btn-primary">Update</a>
                        <button type="button" remove-id="'.$row['id'].'" class="btn btn-danger btn-remove" data-bs-toggle="modal" data-bs-target="#removeLogo">
                            Remove
                        </button>
                    </td>
                </tr>
            ';
        }
    }

    function updateLogo(){
        if(isset($_POST['btn-update-logo'])){
            $postID = $_POST['postID'];

            if(!empty($_POST['pined'])){
                $pined = 1;

                $sqlPined = "UPDATE `website_logo` SET `pined`= 0  WHERE pined = 1";
                connectSql($sqlPined);
            }
            else{
                $pined = 0;
            }

            if($_FILES['thumbnail']['name'] != ''){
                $file = $_FILES['thumbnail'];
                $filename = uploadFile($file);
            }
            else{
                $filename = $_POST['oldThumbnail'];
            }

            $sqlStr = "
                UPDATE `website_logo` SET `thumbnail`='".$filename."',`pined`='".$pined."',`updated_at`='".currentDate()."' 
                WHERE id = $postID
            ";

            $result = connectSql($sqlStr);
            if($result){
                header('Location: list-logo.php');
                activityLog('logo','update');
            }

        }
    }
    updateLogo();

    function removeLogo(){
        if(isset($_POST['btn-remove-logo'])){
            $postID = $_POST['postID'];
            if(!empty($postID)){
                echo 1;
            }
            else{
                echo 0;
            }
            $sql_deleted = "UPDATE `website_logo` SET `is_deleted`= 0 WHERE id = $postID";
            $rs = connection_db()->query($sql_deleted);
            // $rs = connectSql($sql_delete);
            if($rs){
                header('Location: list-logo.php');
                activityLog('logo','deleted');
            }
        }
    }
    removeLogo();

    function listLog(){
        $sql_list_log = "
            SELECT activity_log.*, user.name 
            FROM activity_log INNER JOIN user
            ON activity_log.author_id = user.id
            ORDER BY activity_log.id DESC
        ";
        $rs = connection_db()->query($sql_list_log);
        while($row = mysqli_fetch_assoc($rs)){
            echo '
                <tr>
                    <td>'. $row['id'] .'</td>
                    <td>'. $row['post_type'] .'</td>
                    <td>'. $row['post_id'] .'</td>
                    <td>'. $row['name'] .'</td>
                    <td>'. $row['action'] .'</td>
                    <td>'. $row['created_at'] .'</td>
                </tr>
            ';
        }
    }
    // listLog();

    function addCategory(){
        if(isset($_POST['btn-add-category'])){
            if($_POST['title'] != ''){
                $title = $_POST['title'];
                $author = $_SESSION['uid'];
                
                $sql_category_insert = "
                    INSERT INTO `category`(`name`, `author_id`, `is_deleted`, `created_at`, `updated_at`) 
                    VALUES ('".$title."','".$author."',1,'".currentDate()."','".currentDate()."')
                ";
                $rs = connection_db()->query($sql_category_insert);
                if($rs){
                    echo 'Insert successful';
                    activityLog('category','insert');
                }
                else{
                    echo 'Error System!!';
                }
            }
            else{
                echo 'Please input information !!';
            }
        }
    }
    // addCategory();

    function listCategory(){
        $sql_select = "
            SELECT category.*, user.name AS author_name FROM category 
            INNER JOIN user ON category.author_id = user.id 
            WHERE category.is_deleted = 1
            ORDER BY category.id DESC
        ";
        $result = connection_db()->query($sql_select);
        while($row = mysqli_fetch_assoc($result)){
            echo '
                <tr>
                    <td>'. $row['id'] .'</td>
                    <td>'. $row['name'] .'</td>
                    <td>'. $row['author_name'] .'</td>
                    <td>'. $row['created_at'] .'</td>
                    <td width="150px">
                        <a href="update-category.php?id='.$row['id'].'" class="btn btn-primary">Update</a>
                        <button type="button" remove-id="'.$row['id'].'" class="btn btn-danger btn-remove" data-bs-toggle="modal" data-bs-target="#removeLogo">
                            Remove
                        </button>
                    </td>
                </tr>
            ';
        }
    }

    function updateCategory(){
        if(isset($_POST['btn-update-category'])){
            $postID = $_GET['id'];
            $category_name = $_POST['category_name'];
            if($category_name != ''){
                
            }
            else{
                $sql_select = "SELECT * FROM `category` WHERE id = $postID";
                $rs = connection_db()->query($sql_select);
                $row = mysqli_fetch_assoc($rs);
                return $row['name'];
            }

            $sql_update = "
                UPDATE `category` SET `name`='".$category_name."',`updated_at`='".currentDate()."' WHERE id = $postID
            ";
            $rs = connection_db()->query($sql_update);
            if($rs){
                // return 'Update Successful';
                activityLog('category','update');
                header('Location: list-category.php');
            }
        }
    }
    updateCategory();

    function removeCategory(){
        if(isset($_POST['btn-remove-category'])){
            $postID = $_POST['postID'];
            if(!empty($postID)){
                echo 1;
            }
            else{
                echo 0;
            }
            $sql_deleted = "UPDATE `category` SET `is_deleted`= 0 WHERE id = $postID";
            $rs = connection_db()->query($sql_deleted);
            // $rs = connectSql($sql_delete);
            if($rs){
                header('Location: list-category.php');
                activityLog('category','deleted');
            }
        }
    }
    removeCategory();

    function getCategory($cateID){
        $sql_show_category = "SELECT * FROM `category` WHERE is_deleted = 1 ORDER BY category.id DESC";
        $rs = connection_db()->query($sql_show_category);
        while($row = mysqli_fetch_assoc($rs)){
            if($cateID != ''){ 
                if($cateID == $row['id']){
                    echo '
                        <option selected value="'. $row['id'] .'">'. $row['name'] .'</option>
                    ';
                }
                else{
                    echo '
                        <option value="'. $row['id'] .'">'. $row['name'] .'</option>
                    ';
                }
            }
            else{
                    echo '
                        <option value="'. $row['id'] .'">'. $row['name'] .'</option>
                    '; 
            }
        }
        
        
    }
    
    function addNews(){
        if(isset($_POST['btn-add-news'])){
            if($_POST['title'] != '' || $_POST['description'] != ''){
                $title = $_POST['title'];
                $describe = $_POST['description'];
                $view = 0;
                $author_id = $_SESSION['uid'];
                $category_id = $_POST['category'];
                $file = $_FILES['thumbnail'];
                $thumbnail = uploadFile($file);
                if(!empty($_POST['pined'])){
                    $pined = 1;
                    $sqlPined = "UPDATE `news2` SET `pined`= 0 WHERE pined = $pined";
                    connectSql($sqlPined);
                }
                else{
                    $pined = 0;
                }

                $sql_insert_news = "
                    INSERT INTO `news2`(`title`, `pined`, `thumbnail`, `viewer`, `description`, `author_id`, `category_id`, `is_deleted`, `created_at`, `updated_at`) 
                    VALUES ('".$title."','".$pined."','".$thumbnail."','".$view."','".$describe."','".$author_id."','".$category_id."',1,'".currentDate()."','".currentDate()."')
                ";
                $rs = connection_db()->query($sql_insert_news);
                if($rs){
                    activityLog('news2','insert');
                    return 'Insert successful';
                }
                else{
                    return 'Error !!!';
                }
                
            }
            else{
                return 'Please input information!!!';
            }
        }
    }
    // addNews();

    function listNews(){
        $sql_select = "
            SELECT news2.*, user.name AS author_name , category.name AS category_name FROM news2 
            INNER JOIN user ON news2.author_id = user.id
            INNER JOIN category ON news2.category_id = category.id
            WHERE news2.is_deleted = 1
            ORDER BY news2.id DESC
        ";
        $result = connection_db()->query($sql_select);
        while($row = mysqli_fetch_assoc($result)){
            if($row['pined']==1){
                $pined = 'pined';
            }
            else{
                $pined = 'unpined';
            }
            echo '
                <tr>
                    <td>'.$row['id'].'</td>
                    <td>'.$row['title'].'</td>
                    <td><img src="assets/image/'.$row['thumbnail'].'"></td>
                    <td>'.$row['category_name'].'</td>
                    <td>'.$pined.'</td>
                    <td>'.$row['author_name'].'</td>
                    <td>'.$row['created_at'].'</td>
                    <td width="150px">
                    <a href="update-news.php?id='.$row['id'].'"class="btn btn-primary">Update</a>
                    <button type="button" remove-id='.$row['id'].' class="btn btn-danger btn-remove" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                        Remove
                    </button>
                    </td>
                </tr>
            ';
        }
    }

    function updateNews(){
        if(isset($_POST['btn-update-news'])){
            $postID = $_POST['postID'];

            if(!empty($_POST['pined'])){
                $pined = 1;

                $sqlPined = "UPDATE `news2` SET `pined`= 0  WHERE pined = 1";
                connectSql($sqlPined);
            }
            else{
                $pined = 0;
            }

            if($_FILES['thumbnail']['name'] != ''){
                $file = $_FILES['thumbnail'];
                $filename = uploadFile($file);
            }
            else{
                $filename = $_POST['oldThumbnail'];
            }
            $title = $_POST['title'];
            $describe = $_POST['description'];
            $category_id = $_POST['category'];
            $author_id = $_SESSION['uid'];
            $sqlStr = "
                UPDATE `news2` SET `title`='".$title."',`pined`='".$pined."',`thumbnail`='".$filename."',`description`='".$describe."',`author_id`='".$author_id."',`category_id`='".$category_id."',`updated_at`='".currentDate()."' WHERE id = $postID
            ";

            $result = connectSql($sqlStr);
            if($result){
                header('Location: list-news.php');
                activityLog('news2','update');
            }

        }
    }
    updateNews();

    function removeNews(){
        if(isset($_POST['btn_remove_news'])){
            if (!isset($_SESSION['uid'])) {
                header('Location: login.php');
                exit();
            }
            $postID = $_POST['postID'];
            // echo $postID;
            // if(!empty($postID)){
            //     echo 1;
            // }
            // else{
            //     echo 0;
            // }
            $sql_deleted = "UPDATE `news2` SET `is_deleted`= 0 WHERE id = $postID";
            $rs = connection_db()->query($sql_deleted);
            // $rs = connectSql($sql_delete);
            if($rs){
                // header('Location: list-news.php');
                activityLog('news2','deleted');
            }
        }
    }
    removeNews();

    // Social
    function addSocial(){
        if(isset($_POST['btn-insert-social'])){
            if($_POST['url'] != ''){
                $url = $_POST['url'];
                $file = $_FILES['thumbnail'];
                $thumbnail = uploadFile($file);
                $author_id = $_SESSION['uid'];
                // echo $author_id;
                $sql_insert = "
                INSERT INTO `social`(`thumbnail`, `url`, `author_id`, `is_deleted`, `created_at`, `updated_at`) 
                VALUES ('".$thumbnail."','".$url."','".$author_id."',1,'".currentDate()."','".currentDate()."')";
                $rs = connection_db()->query($sql_insert);
                if($rs){
                    activityLog('social','insert');
                    return 'Insert social successful...';
                }
            }
            else{
                return '!Full info required....';
            }
        }
        
    }

    function listSocial(){
        $sql_select = "
        SELECT social.*, user.name FROM social 
        INNER JOIN user ON social.author_id = user.id 
        WHERE social.is_deleted=1 ORDER BY social.id DESC";
        $rs = connection_db()->query($sql_select);
        while($row = mysqli_fetch_assoc($rs)){
            echo '
                <tr>
                    <td>'.$row['id'].'</td>
                    <td><img src="assets/image/'.$row['thumbnail'].'"></td>
                    <td>'.$row['url'].'</td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['created_at'].'</td>
                    <td width="150px">
                    <a href="update-news.php?id='.$row['id'].'"class="btn btn-primary">Update</a>
                    <button type="button" remove-id='.$row['id'].' class="btn btn-danger btn-remove" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                        Remove
                    </button>
                    </td>
                </tr>
            ';
        }
    }
    // addSocial();

    function ListArticle(){
        $user_id = $_SESSION['uid'];
        $sql_select = "SELECT * FROM `news2` WHERE id = $user_id";
        
    }

