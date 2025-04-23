<?php 
    include('sidebar.php');
    // $postID = $_GET['id'];
    // $currentPost = getCurrentPost($postID, 'website_logo');

    // $isCheck = '';
    // if($currentPost['pined'] == 1) {
    //     $isCheck = 'checked';
    // }
?>
                <div class="col-10">
                    <div class="content-right">
                        <div class="top">
                            <h3>Update Logo</h3>
                        </div>
                        <div class="bottom">
                            <figure>
                                <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="postID" value="<?php echo $postID; ?>">
                                    <!-- echo $postID -->
                                    <label for="">New categary</label>
                                    <input type="text" name="category_name" value="<?php updateCategory(); ?>"><br>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" name="btn-update-category">Save</button>
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