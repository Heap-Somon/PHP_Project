<?php include('header.php') ?>
<section class="trending">
    <div class="container">
        <?php echo getNews(); ?>
    </div>
</section>

<section class="news">
    <div class="container">
        <div class="top">
            <h2>Latest News</h2>
        </div>
        <div class="row">
            <?php echo getLatestNews(); ?>
        </div>
    </div>
</section>

<section class="news">
    <div class="container">
        <div class="top">
            <h2>Popular News</h2>
        </div>
        <div class="row">
            <?php 
                $rs = getPopulorNews();
                while($row = mysqli_fetch_assoc($rs)){
                    echo '
                        <div class="col-4">
                            <figure>
                                <div class="thumbnail">
                                    <a href="article.php?id='.$row['id'].'">
                                        <img src="admin/assets/image/'.$row['thumbnail'].'" alt="">
                                    </a>
                                </div>
                            </figure>
                            <figcaption>
                                <h3>
                                    <a href="article.php?id='.$row['id'].'">
                                        '.$row['title'].'
                                    </a>
                                </h3>
                                <div>
                                    <img src="assets/icons/date.svg" alt="">
                                    <span>'.$row['created_at'].'</span>
                                </div>
                            </figcaption>
                        </div>
                    ';
                }
            ?>
            
        </div>
    </div>
</section>
<?php include('footer.php') ?>