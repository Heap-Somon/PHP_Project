<?php include('header.php');

    $id = $_GET['id'];
    $row = getAllDetail($id);
    // echo $row;
    if(isset($_GET['id'])){
        updateViewer($row['viewer'],$id);
    }

?>
        <main>
            <section class="article">
                <div class="container">
                    <div class="row">
                        <div class="col-8">
                            <figure>    <!-- <img src="https://placehold.co/800x600" alt=""> -->
                                <div class="thumbnail">
                                    <img src="admin/assets/image/<?php echo $row['thumbnail'] ?>" alt="">
                                </div>
                            </figure>
                            <figcaption>
                                <h3><?php echo $row['title'] ?></h3>
                                <div class="date">
                                    <img src="assets/icons/date.svg" alt="">
                                    <h6><?php echo $row['created_at'] ?></h6>
                                </div>
                                <div class="date">
                                    <img src="assets/icons/eye.svg" alt="">
                                    <h6><?php echo $row['viewer']+1; ?></h6>
                                </div>
                                <div class="line"></div>
                                <div class="description">
                                    <?php echo $row['description'] ?>
                                </div>
                            </figcaption>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Php to database related news -->
            
            <section class="news">
                <div class="container">
                    <div class="top">
                        <h2>Related News</h2>
                    </div>
                    <div class="row">
                    <?php
                        if(isset($_GET['id'])){
                            // $id = $_GET['id'];
                            $rs = getRelatedNews($row['category_id'],$row['id']);
                            while($rowR = mysqli_fetch_assoc($rs)){
                                echo '
                                    <div class="col-4">
                                    <figure>
                                        <div class="thumbnail">
                                            <a href="article.php?id='. $rowR['id'] .'">
                                                <img src="admin/assets/image/'.$rowR['thumbnail'].'" alt="">
                                            </a>
                                        </div>
                                    </figure>
                                    <figcaption>
                                        <h3>
                                            <a href="article.php?id='. $rowR['id'] .'">
                                                '.$rowR['title'].'
                                            </a>
                                        </h3>
                                        <div>
                                            <img src="assets/icons/date.svg" alt="">
                                            <span>'.$rowR['created_at'].'</span>
                                        </div>
                                    </figcaption>
                                </div>
                                ';
                            }
                        }
                    ?>
                        <!-- <div class="col-4">
                            <figure>
                                <div class="thumbnail">
                                    <a href="">
                                        <img src="https://placehold.co/300" alt="">
                                    </a>
                                </div>
                            </figure>
                            <figcaption>
                                <h3>
                                    <a href="">
                                        1990 World Cup Finals 3rd Shirt
                                    </a>
                                </h3>
                                <div>
                                    <img src="assets/icons/date.svg" alt="">
                                    <span>19 Jun, 2023</span>
                                </div>
                            </figcaption>
                        </div> -->
                    </div>
                </div>
            </section>

        </main>
      <?php include('footer.php') ?>
    </body>

    <script src="assets/script/jquery.js"></script>
    <script src="assets/script/theme.js"></script>
    <script src="assets/script/bootstrap.js"></script>
</html>