<!-- References :  -->
<!-- Bootstrap theme : https://bootswatch.com/5/lux/bootstrap.css -->
<!-- Bootstrap Elements : https://bootswatch.com/lux/ -->
<?php
    require('./config/db.php');     //Establishing database connection
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    require('./config/insert_comments.php');        //Invoking logic for commenting
    //get id
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    //Create query
    $query = 'select * from article where article_id='.$id;
    // for all posts titles(for side panel)
    $query_titles = "SELECT * FROM article WHERE article_status = 1 ORDER BY article_id DESC LIMIT 10";
    //for all categories
    $query_allCategories = "select distinct article_category from article order by article_category";
    //for fetching comments
    $query_comments = "select * from comment where (article_id = '$id' and comment_status = '1') order by comment_id desc";

    //get results
    $results = mysqli_query($conn,$query);
    $results_titles = mysqli_query($conn,$query_titles);
    $results_allCategories = mysqli_query($conn,$query_allCategories);
    $results_comments = mysqli_query($conn,$query_comments);

    //fetch data
    $post = mysqli_fetch_assoc($results);
    $titles = mysqli_fetch_all($results_titles,MYSQLI_ASSOC);
    $allCategories = mysqli_fetch_all($results_allCategories,MYSQLI_ASSOC);
    $comments = mysqli_fetch_all($results_comments,MYSQLI_ASSOC);
    
    //finding next and previous post id
    $next = mysqli_fetch_assoc(mysqli_query($conn,"select min(article_id) from article where article_id>$id"));
    $previous = mysqli_fetch_assoc(mysqli_query($conn,"select max(article_id) from article where article_id<$id"));

    //increasing the post view count
    mysqli_query($conn,"update article set article_visit = article_visit + 1 where article_id=$id");

    //free results
    mysqli_free_result($results);
    mysqli_free_result($results_allCategories);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://kit.fontawesome.com/dce8876dde.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://bootswatch.com/5/lux/bootstrap.css">
    <link rel="stylesheet" href="./css/post.css">

    <title>XYZ News Portal</title>
</head>
<body>
    <div class="header">
        <div class="container-fluid">
            <!-- Logo of website -->
            <a href="index.php" id="banner">
                <div id="banner" class="text-center py-3">
                    <h1>XYZ News Portal</h1>
                    <h4 class="text-muted">For those who read</h4>
                </div>
            </a>
            <!-- Dynamic Navbar : Having all categories of news articles -->
            <div class="container-md">
                <ul class="nav nav-pills justify-content-center">
                    <li class="nav-item">
                    <a class="nav-link" href="index.php">Latest</a>
                    </li>
                    <?php foreach($allCategories as $cat): ?>
                        <li class="nav-item">
                        <a class="nav-link" href="categorizedPosts.php?category=<?php echo $cat['article_category']; ?>"><?php echo $cat['article_category']; ?></a>
                        </li>
                    <?php endforeach; ?> 
                </ul>
            </div>
        </div>
    </div>
    <!-- Main container -->
    <div class="main-content container-lg-12 mx-3">
        <h1 class="mx-2 pt-3"> <?php echo $post['article_title']; ?></h1>
        <div class="container-lg-12 main-container mx-0">
            <div class="row my-3">
                <div class="col-sm-6">
                    <span class="mx-2 badge bg-success">Story added on : <?php echo $post['article_date'];?></span>
                </div>
                <div class="col-sm-6">
                    <span style="float:right;" class="mx -2 badge bg-warning">Views : <?php echo $post['article_visit'];?></span>
                </div>
            </div>
            <div class="row mx-0 my-3">
                <!-- Div for News articles display -->
                <div class="col-lg-8 my-2">
                    <!-- News Article Image -->
                    <div class="text-center mb-5"><img src="admin/assets/upload/article/<?php echo $post['article_title_img']?>" alt="" class="img-fluid"></div>
                    <!-- News article full text -->
                    <p><?php echo $post['article_desc'] ; ?></p>
                </div>
                <!-- Side panel -->
                <div class="col-lg-4 mx-auto">
                    <div class=" border border-secondary search-box p-2">
                        <h4 class="text-center">Fresh News headlines</h4>
                        <!-- Recent news links -->
                        <div class="list-group mx-2">
                        <?php foreach($titles as $title) : ?>
                        <a href="post.php?id=<?php echo $title['article_id']; ?>" class="list-group-item list-group-item-action news-headline-links"><?php echo $title['article_title'];?></a>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Next and previous articles Button -->
            <div class="row mx-0 my-3">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-sm-6">
                            <?php if($previous['max(article_id)'] == NULL):?>
                                <a href="post.php?id=<?php echo $previous['max(article_id)'];?>"><button type="button" class="btn btn-primary disabled" id="previous-btn"><i class="fas fa-2x fa-arrow-alt-circle-left"></i></button></a>
                            <?php endif;?>
                            <?php if($previous['max(article_id)'] != NULL):?>
                                <a href="post.php?id=<?php echo $previous['max(article_id)'];?>"><button type="button" class="btn btn-primary" id="previous-btn"><i class="fas fa-2x fa-arrow-alt-circle-left"></i></button></a>
                            <?php endif;?>
                        </div>
                        <div class="col-sm-6">
                            <?php if($next['min(article_id)'] == NULL):?>
                                <a href="post.php?id=<?php echo $next['min(article_id)'];?>"><button style="float:right;" type="button" class="btn btn-primary disabled" id="previous-btn"><i class="fas fa-2x fa-arrow-alt-circle-right"></i></button></a>
                            <?php endif;?>
                            <?php if($next['min(article_id)'] != NULL):?>
                                <a href="post.php?id=<?php echo $next['min(article_id)'];?>"><button style="float:right;" type="button" class="btn btn-primary" id="previous-btn"><i class="fas fa-2x fa-arrow-alt-circle-right"></i></button></a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="row mx-0 my-3">
                <div class="col-md-8">
                    <hr>
                    <!--adding new comments  -->
                    <h3>Have your say</h3>
                     <div class="card">
                        <div class="card-body text-white bg-dark">
                            <form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
                                <div class="mb-3">
                                    <label for="inputName" class="form-label">Enter your name</label>
                                    <input type="text" class="form-control" name="commenter_name" id="inputName" aria-describedby="emailHelp">
                                    <div id="emailHelp" class="form-text">Your name will be shown along with your comment in the comments section.</div>
                                </div>
                                <div class=" mb-3 form-group">
                                <label for="exampleTextarea" class="form-label mt-4">Enter your comment</label>
                                <textarea class="form-control" name="comment" id="exampleTextarea" rows="3"></textarea>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                     </div>
                     <hr>
                    <h3>Comments : <?php echo count($comments);?></h3>
                    <!-- Comment cards -->
                    <?php $N = 0;?>
                    <?php foreach($comments as $comment):?>
                    <?php $N++; ?> 
                    <?php if($N % 2 == 0) : ?>  
                    <div class="card my-2 border-danger">
                        <div class="card-body card-1" style="text-align:right;">
                            <h5 class="card-title"><span class="text-info commenter-name1"><?php echo $comment['comment_auther'];?></span> said:</h5>
                            <p class="card-text"><?php echo $comment['comment_desc'];?></p>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php if($N % 2) : ?> 
                    <div class="card my-2 border-info">
                        <div class="card-body card-2">
                            <h5 class="card-title"><span class="text-danger commenter-name2"><?php echo $comment['comment_auther'];?></span> said:</h5>
                            <p class="card-text"><?php echo $comment['comment_desc'];?></p>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php endforeach; ?>   
                </div>
            </div>

        </div> 
    </div>
    <script src="./scripts/post.js"></script>
    <script src="https://www.markuptag.com/bootstrap/5/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
