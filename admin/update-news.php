<?php 
    include('sidebar.php');
    $postID = $_GET['id'];
    $currentPost = getCurrentPost($postID, 'news2');

    $isCheck = '';
    if($currentPost['pined'] == 1) {
        $isCheck = 'checked';
    }
?>
                <div class="col-10">
                    <div class="content-right">
                        <div class="top">
                            <h3>Update News</h3>
                        </div>
                        <div class="bottom">
                            <figure>
                                <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="postID" value="<?php echo $postID ?>">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" value="<?php echo $currentPost['title']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Checkbox</label>
                                        <input type="checkbox" name="pined" class="form-check-input" <?php echo $isCheck ?>>
                                    </div>
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="category" class="form-select">
                                            <?php
                                            $cateID = $currentPost['category_id'];
                                            getCategory($cateID); 
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" ><?php echo $currentPost['description'] ?></textarea>
                                    </div>
                                    <input type="hidden" name="oldThumbnail" value="<?php echo $currentPost['thumbnail']; ?>">
                                    <!-- <div class="form-group">
                                        <label>Pined</label>
                                        <input type="checkbox" name="pined" value="1"  class="form-check-input">
                                    </div> -->

                                    <div class="form-group">
                                        <label>Thumbnail</label> <br>
                                        <img src="assets/image/<?php echo $currentPost['thumbnail']; ?> "width="170px" class="img-update">
                                        <input type="file" name="thumbnail" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" name="btn-update-news">Save</button>
                                    </div>
                                </form>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<!-- echo $postID 
    echo $currentPost['thumbnail']
    echo $isCheck;
    echo $currentPost['thumbnail'];
-->