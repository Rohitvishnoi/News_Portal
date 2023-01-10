<!-- References :  -->
<!-- Bootstrap theme : https://bootswatch.com/5/lux/bootstrap.css -->
<!-- Bootstrap Elements : https://bootswatch.com/lux/ -->
<?php
    require('./config/db.php');     //Establishing database connection
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    require('./config/insert.php');     //invoking logic to submit user email for newsletter subscriptions
    
    // For pagination:
    //get page number
    if (!isset ($_GET['page']) ) {  
        $page = 1;  
    } else {  
        $page = $_GET['page'];  
    }
    //defining page variables
    $posts_per_page = 8;

    $page_first_result = ($page-1) * $posts_per_page;

    //get category
    $category = str_replace("_"," & ",mysqli_real_escape_string($conn, $_GET['category']));
    //Create query
    $query_allPosts = "select count(*) from article where article_category="."'".$category."' and article_status = 1";
    $query_paginatedPosts = "select * from article where article_category="."'".$category."' and article_status = 1"." order by article_id desc limit ".$page_first_result.",".$posts_per_page;
    $query_allCategories = "select distinct article_category from article order by article_category";
    

    //get results
    $results_all = mysqli_query($conn,$query_allPosts);
    $results_paginated = mysqli_query($conn,$query_paginatedPosts);
    $results_allCategories = mysqli_query($conn,$query_allCategories);

    //finding number of pages
    $num_posts = mysqli_fetch_assoc($results_all);
    $num_pages = ceil($num_posts["count(*)"] / $posts_per_page);
  
    //fetch data
    $posts = mysqli_fetch_all($results_paginated,MYSQLI_ASSOC);
    $allCategories = mysqli_fetch_all($results_allCategories,MYSQLI_ASSOC);
    
    //free results
    mysqli_free_result($results_all);
    mysqli_free_result($results_paginated);
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

    <title>XYZ News Portal</title>
</head>
<body>
    <div class="header">
        <div class="container-fluid">
            <!-- Logo of Website -->
            <a href="index.php" id="banner">
                <div class="text-center py-3">
                    <h1>XYZ News Portal</h1>
                    <h4 class="text-muted">For those who read</h4>
                </div>
            </a>
            
            <!-- Dynamic Navbar : Having all the categories of news articles -->
            <div class="container-md">
                <ul class="nav nav-pills justify-content-center">
                    <li class="nav-item">
                    <a class="nav-link" href="index.php">Latest</a>
                    </li>
                    <?php foreach($allCategories as $cat): ?>
                    <?php if($category==$cat['article_category']) :?>
                        <li class="nav-item">
                        <a class="nav-link active" href="categorizedPosts.php?category=<?php echo $cat['article_category']; ?>"><?php echo $cat['article_category']; ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if($category!=$cat['article_category']) :?>
                        <li class="nav-item">
                        <a class="nav-link" href="categorizedPosts.php?category=<?php echo str_replace(" & ","_",$cat['article_category']); ?>"><?php echo $cat['article_category']; ?></a>
                        </li>
                    <?php endif; ?>
                    <?php endforeach; ?>     
                </ul>
            </div>
        </div>
    </div>
    <!-- Main container -->
    <div class="main-content container-lg-12 mx-0">
        <h1 class="mx-2 pt-3">Latest <?php echo $category; ?> News articles</h1>
        <div class="container-lg-12 main-container mx-0">
            <div class="row mx-0 my-3">
                <!-- Div for News articles display -->
                <div class="col-lg-8 my-2">
                    <div class="row my-3 mx-auto">
                        <?php $N = 0;?>
                        <?php foreach($posts as $post) : ?>
                        <?php $N++; ?> 
                        <?php if($N % 2 == 0) : ?>   
                        <div class="col-lg-12 my-2">
                            <div class="card text-white bg-primary">
                                    <div class="card-header">
                                        Story Date : <?php echo $post['article_date'];?>
                                        <span class="badge rounded-pill bg-light" style="float:right;"><?php echo $post['article_category'];?></span>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="card-title"><?php echo $post['article_title']; ?></h3>
                                        <!-- article thumbnail and short description -->
                                        <div class="row">
                                            <?php
                                                $words = explode(" ",$post['article_desc']);
                                            ?>
                                            <div class="col-lg-8 mr-1"><p class="card-text"><?php
                                            for($i=0;$i<50;$i++){
                                                echo $words[$i].' ';
                                            }
                                            echo '...';
                                            ?>
                                            </p></div>
                                            <div class="col-lg-4 ml-1"><div id="card-thumb"><img class="img-thumbnail" src="admin/assets/upload/article/<?php echo $post['article_title_img']?>" alt=""></div></div>
                                        </div>
                                        <div class="btn btn-secondary"><a style="text-decoration:none;" href="post.php?id=<?php echo $post['article_id']; ?>">Read More</a></div>
                                    </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($N % 2) : ?>   
                        <div class="col-lg-12 my-2">
                            <div class="card bg-secondary">
                                    <div class="card-header">
                                    Story Date : <?php echo $post['article_date'];?>
                                        <span class="badge rounded-pill bg-dark" style="float:right;"><?php echo $post['article_category'];?></span>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="card-title"><?php echo $post['article_title']; ?></h3>
                                        <!-- article thumbnail and short description -->
                                        <div class="row">
                                            <?php
                                                $words = explode(" ",$post['article_desc']);
                                            ?>
                                            <div class="col-lg-8 mr-1"><p class="card-text"><?php
                                            for($i=0;$i<50;$i++){
                                                echo $words[$i].' ';
                                            }
                                            echo '...';
                                            ?></p></div>
                                            <div class="col-lg-4 ml-1"><div id="card-thumb"><img class="img-thumbnail bg-primary" src="admin/assets/upload/article/<?php echo $post['article_title_img']?>" alt=""></div></div>
                                        </div>
                                        <div class="btn btn-primary"><a style="text-decoration:none; color:white;" href="post.php?id=<?php echo $post['article_id']; ?>">Read More</a></div>
                                    </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>     
                    </div>
                </div>
                <!-- Side panel -->
                <div class="col-sm-4 mx-auto">
                    <div class=" border border-secondary p-2 my-5">
                        <h4 class="text-center">Subscribe to our updates</h4>
                        <form action="<?php $_SERVER['PHP_SELF'];?>" class="form-group mx-5" method="post">
                            <div class="input-group mb-3">
                            <span class="bg-primary text-white input-group-text">Email</i></span>
                            <input type="text" class="form-control" aria-label="email" name='email' placeholder="abc@example.com">
                            <button type="submit" class="btn btn-success" name="submit">Submit</button>
                            </div>
                        </form>
                        <?php if(isset($_REQUEST['submit'])):?>
                        <?php if($emailErr):?>
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>Heads up!</strong> Entered email is not valid Email.
                            </div>
                        <?php endif;?>
                        <?php if($emailErr==0):?>
                        <?php if($email_counts['count(*)']==0):?>
                            <?php 
                                $query = "insert into emails(email) values('$email')";
                                mysqli_query($conn,$query);
                            ?>
                            <div class="alert alert-dismissible alert-success">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>Well done!</strong> You successfully subscribed to our news updates.
                            </div>
                        <?php endif;?>
                        <?php if($email_counts['count(*)']!=0):?>
                            <div class="alert alert-dismissible alert-warning">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>Oh snap!</strong> The email you entered is already in the database.
                            </div>
                        <?php endif;?>
                        <?php endif;?>
                        <?php endif;?>
                    </div>
                    <div class=" border border-secondary search-box p-2 my-5">
                        <h4 class="text-center">Search for news articles</h4>
                        <form class="form-group mx-5" method="post">
                            <div class="input-group mb-3">
                            <span class="bg-primary text-white input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" aria-label="Search box" name='search' placeholder="Search term...">
                            </div>
                        </form>
                        <!-- Search Results -->
                        <div class="list-group mx-5">
                        <?php 
                            // if nothing is in the search box then show no news articles titles
                            if(!isset($_POST['search']))
                                $search_query = "Select * from article where article_title like 'xxxx'";
                            // if search box is filled then use that value to match the news articles titles
                            else{
                                $search_query = "Select * from article where (article_title like '%".$_POST['search']."%' or article_category like '%".$_POST['search']."%' or article_desc like '%".$_POST['search']."%' and article_status = 1) order by article_id desc";  
                            }
                            $search_result = mysqli_query($conn,$search_query);
                            $matched_news = mysqli_fetch_all($search_result,MYSQLI_ASSOC);
                            foreach($matched_news as $match){
                                echo '<a href="post.php?id='.$match['article_id'].'" class="list-group-item list-group-item-action" style="text-decoration:none;">'.$match['article_title'].'</a>';
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <!-- Pagination -->
                <div>
                    <ul class="pagination">
                        <?php 
                        $prev = $page - 1;
                            if($page == 1)
                                echo '<li class="page-item disabled"><a class="page-link" href="?page='.$prev.'&category='.$category.'">&laquo;</a></li>';
                            else
                                echo '<li class="page-item"><a class="page-link" href="?page='.$prev.'&category='.$category.'">&laquo;</a></li>';

                            for($i=1;$i<=$num_pages;$i++){
                                if($i == $page){
                                    echo '<li class="page-item active"><a class="page-link" href="?page='.$i.'&category='.$category.'">'.$i.'</a></li>';
                                }
                                else{
                                    echo '<li class="page-item"><a class="page-link" href="?page='.$i.'&category='.$category.'">'.$i.'</a></li>';
                                }
                            }
                            $next = $page + 1;
                            if($page == $num_pages)
                                echo '<li class="page-item disabled"><a class="page-link" href="?page='.$next.'&category='.$category.'">&raquo;</a></li>';
                            else
                                echo '<li class="page-item"><a class="page-link" href="?page='.$next.'&category='.$category.'">&raquo;</a></li>';

                        ?>
                    </ul>
                </div>

            </div>
        </div> 
    </div>
    <script src="https://www.markuptag.com/bootstrap/5/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
