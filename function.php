<?php
     function connectionDB() {
        $con = new mysqli('','root','','news');
        return $con;
    }
    function getLogo(){
        $sqlSelect = "SELECT * FROM `website_logo` WHERE `pined` = 1 AND is_deleted = 1";
        $result = connectionDB()->query($sqlSelect);
        $row = mysqli_fetch_assoc($result);
        return $row['thumbnail'];
    }
    function getNews(){
        $sqlSelect = "SELECT * FROM `news2` WHERE `pined` = 1 AND is_deleted = 1";
        $result = connectionDB()->query($sqlSelect);
        $row = mysqli_fetch_assoc($result);
        echo '
            <div class="row">
                <div class="col-6">
                    <figcaption>
                        <h2>'. $row['title'] .'</h2>
                        <div>'. $row['description'] .'</div>
                        <a href="article.php?id='. $row['id'] .'">FIND OUT MORE</a>
                    </figcaption>
                </div>
                <div class="col-6">
                    <div class="thumbnail">
                        <img src="admin/assets/image/'.$row['thumbnail'].'" class="pined-news">
                    </div>
                </div>
            </div>
        ';
    }
    function getLatestNews(){
        $sqlSelect = "SELECT * FROM `news2` WHERE  `is_deleted` = 1 ORDER BY id DESC LIMIT 3";
        $result = connectionDB()->query($sqlSelect);
        while($row = mysqli_fetch_assoc($result)){
            $date = date_create($row['created_at']);
            $dateFormat = date_format($date , 'd M, Y');
            echo '
                <div class="col-4">
                    <figure>
                        <div class="thumbnail">
                            <a href="article.php?id='. $row['id'] .'">
                                <img src="admin/assets/image/'.$row['thumbnail'].'" alt="">
                            </a>
                        </div>
                    </figure>
                    <figcaption>
                        <h3>
                            <a href="article.php?id='.$row['id'].'">'. $row['title'] .'</a>
                        </h3>
                        <div>
                            <img src="assets/icons/date.svg" alt="">
                            <span>'.$dateFormat.'</span>
                        </div>
                    </figcaption>
                </div>
            ';
        }
    }
    function getSocial(){
        $sql_select ="SELECT * FROM `social` WHERE is_deleted =1 ORDER BY id DESC LIMIT 4";
        $rs = connectionDB()->query($sql_select);
        while($row = mysqli_fetch_assoc($rs)){
            echo '
                <li>
                        <a href="'.$row['url'].'">
                            <img src="admin/assets/image/'.$row['thumbnail'].'">
                        </a>
                </li>
            '; 
        }
    }
    // function getThumbnailDetail(){
    //     $postID = $_GET['id'];
    //     // echo $postID;
    //     $sql_select = "SELECT * FROM `news2` WHERE id = $postID";
    //     $rs= connectionDB()->query($sql_select);
    //     $row = mysqli_fetch_assoc($rs);
    //     echo '
    //         <img src="admin/assets/image/'.$row['thumbnail'].'" alt="">
    //     ';
    // }

    function getAllDetail($id){
        $sql_select = "SELECT * FROM `news2` WHERE id = $id";
        $rs= connectionDB()->query($sql_select);
        while($row = mysqli_fetch_assoc($rs)){
            return $row;
        };
    }
    function updateViewer($oldViewer,$id){
        $newViewer = $oldViewer + 1;
        $update_viewer = "UPDATE `news2` SET `viewer`= $newViewer WHERE id = $id";
        $rs= connectionDB()->query($update_viewer);
    }
    function getRelatedNews($category_id,$id): mysqli_result {
        $seclect_related_news = "SELECT * FROM `news2` WHERE category_id = $category_id AND id <> $id ORDER BY id DESC LIMIT 3";
        return connectionDB()->query($seclect_related_news);
    }
    function getPopulorNews(){
        $select_populor_news = "SELECT * FROM `news2` ORDER BY viewer DESC LIMIT 3";
        return connectionDB()->query($select_populor_news);

    }
?>
















