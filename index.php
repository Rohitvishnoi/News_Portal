<!-- Referance : https://www.php.net/manual/en/function.exec.php -->
<!-- Referance : https://stackoverflow.com/questions/166944/calling-python-in-php -->
<?php include_once 'header.php';?>
<?php include_once 'db.php';?>

<?php
// function to format the entered data properly for keyword used while using Google web scrapper
	function parse_keyform($str) {
	    // echo "Hello world!";
	    $chars = str_split($str);
	    $big = "";
	    foreach ($chars as $char) {
		// echo $char;
		if ($char == '"'){
		    $big = $big.'\"';
		}
		else{
			$big = $big.$char;
		}
		// echo "<br>";
	    }
	    return $big;
	}
	// Initializing all variable(url,categories) for each web scrapper i.e news18, economictimes, theHindu, India.com and (keyword, category and number of articles) for Google Parser
    $errEconomictimes = $errNews18 = $errHindu = $errIndia = $errGoogle = "";
    $urlNews18 = $categoryNews18 = $urlEconomictimes = $categoryEconomictimes = "";
    $urlHindu = $categoryHindu = $urlIndia = $categoryIndia = "";
    $keywordGoogle = $categoryGoogle = $numArticle = "";
    
    // Starting session to fetch author id
    session_start();
    $auther_id = $_SESSION['id'];

    if (isset($_POST['submitNews18'])) {    // if author is using News18 web scrapper to fetch articles
        $urlNews18 = trim($_POST['urlNews18']);   // fetching URL of news article
        $categoryNews18 = trim($_POST['categoryNews18']);   // fetching category of news article
       
      //checking for ill-formatted entered data
        if($urlNews18 == "" || !filter_var($urlNews18, FILTER_VALIDATE_URL)) {
            $errNews18 = "Enter valid News18 url";
        } else if($categoryNews18 == "0") {
            $errNews18 = "Please select article category";
        } else {
          // If data entered is correct
            // scrap cpde calling & popup to indicate status
            $command_exec = "python3 ./web_scraping/news18.py $urlNews18 $categoryNews18 $auther_id";   //defining the command to run python file in background
            $output = null;
            $retValue = null;
            exec($command_exec,$output,$retValue);    //executing the command to run python file in background
            if($retValue==0) 
            	echo '<script>alert("News article scrapped successfully")</script>';
            else
            	echo '<script>alert("Something went wrong")</script>';
        }
        
    } else if (isset($_POST['submitEconomictimes'])) {    // if author is using economictimes web scrapper to fetch articles
        $urlEconomictimes = trim($_POST['urlEconomictimes']);   // fetching URL of news article
        $categoryEconomictimes = trim($_POST['categoryEconomictimes']);   // fetching category of news article

        //checking for ill-formatted entered data
        if($urlEconomictimes == "" || !filter_var($urlEconomictimes, FILTER_VALIDATE_URL)) {
            $errEconomictimes = "Enter valid Economic Times url";
        } else if($categoryEconomictimes == "0") {
            $errEconomictimes = "Please select article category";
        } else {  
          // If data entered is correct
            // scrap code calling & show popup to indicate status
            $command_exec = "python3 ./web_scraping/economictimes.py $urlEconomictimes $categoryEconomictimes $auther_id";    //defining the command to run python file in background
            $output = null;
            $retValue = null;
            exec($command_exec,$output,$retValue);    //executing the command to run python file in background
            if($retValue==0) 
            	echo '<script>alert("News article scrapped successfully")</script>';
            else
            	echo '<script>alert("Something went wrong")</script>';
        }
    } else if (isset($_POST['submitHindu'])) {    // if author is using theHindu web scrapper to fetch articles
        $urlHindu = trim($_POST['urlHindu']);   // fetching URL of news article
        $categoryHindu = trim($_POST['categoryHindu']);   // fetching category of news article

        //checking for ill-formatted entered data
        if($urlHindu == "" || !filter_var($urlHindu, FILTER_VALIDATE_URL)) {
            $errHindu = "Enter valid The Hindu url";
        } else if($categoryHindu == "0") {
            $errHindu = "Please select article category";
        } else {
          // If data entered is correct
            // scrap code calling & show popup to indicate status
            $command_exec = "python3 ./web_scraping/thehindu.py $urlHindu $categoryHindu $auther_id";   //defining the command to run python file in background
            $output = null;
            $retValue = null;
            exec($command_exec,$output,$retValue);    //executing the command to run python file in background
            if($retValue==0) 
            	echo '<script>alert("News article scrapped successfully")</script>';
            else
            	echo '<script>alert("Something went wrong")</script>';

        }
    } else if (isset($_POST['submitIndia'])) {  // if author is using India.com web scrapper to fetch articles
        $urlIndia = trim($_POST['urlIndia']);   // fetching URL of news article
        $categoryIndia = trim($_POST['categoryIndia']);   // fetching category of news article

        //checking for ill-formatted entered data
        if($urlIndia == "" || !filter_var($urlIndia, FILTER_VALIDATE_URL)) {
            $errIndia = "Enter valid India.com url";
        } else if($categoryIndia == "0") {
            $errIndia = "Please select article category";
        } else {
          // If data entered is correct
            // scrap code calling & show popup to indicate status
            $command_exec = "python3 ./web_scraping/india_com.py $urlIndia $categoryIndia $auther_id";    //defining the command to run python file in background
            $output = null;
            $retValue = null;
            exec($command_exec,$output,$retValue);    //executing the command to run python file in background
            if($retValue==0) 
            	echo '<script>alert("News article scrapped successfully")</script>';
            else
            	echo '<script>alert("Something went wrong")</script>';
        }
    } else if (isset($_POST['submitGoogle'])) {   // if author is using Google web scrapper to fetch articles
      $keywordGoogle = parse_keyform(trim($_POST['keywordGoogle']));      //fetching and formatting the keyword
      $categoryGoogle = trim($_POST['categoryGoogle']);   //fetching the category of news article to scrape
      $numArticle = trim($_POST['numArticle']);   //fetching the number of articles to be fetched using Google web scrapper
      $tempNum = (int)$numArticle;    //typecasting entered num value to integer

      //checking for ill-formatted entered data
      if($keywordGoogle == "") {
          $errGoogle = "Enter valid keyword";
      } else if($categoryGoogle == "0") {
          $errGoogle = "Please select article category";
      } else if($numArticle == "" || $tempNum <= 0) {
          $errGoogle = "Please enter valid number of Articles";
      } else {
        // If data entered is correct
          // scrap code calling & show popup to indicate status

          //defining the command to run python file in background
          $command_exec ="python3 ./web_scraping/manual_under_dev.py -k \"".$keywordGoogle."\""." -c \"".$categoryGoogle."\" -n \"".$tempNum."\" -a \"".$auther_id."\""." -f \"./web_scraping/map.txt\"";
          
          $output = null;
          $retValue = null;
          exec($command_exec,$output,$retValue);    //executing the command to run python file in background
          if($retValue==0) 
            echo '<script>alert("News article scrapped successfully")</script>';
          else
            echo '<script>alert("Something went wrong")</script>';
      }
  }
?>

<div class="container-fluid">
    <div class="row">
        <!-- News18 -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                  <!-- Form -->
                    <form class="form-horizontal" method="post" action="./index.php" enctype="multipart/form-data">
                    <div class="card-body">
                    <h3 class="card-title">Add News18 Article</h3>

                    <div style="height:15px"> </div>

                    <!-- Article URL -->
                    <div class="form-group row">
                      <label
                        for="urlNews18"
                        class="col-sm-4 control-label col-form-label"
                        >Article Url</label
                      >
                      <div class="col-sm-8">
                        <input
                          name="urlNews18"
                          type="text"
                          class="required form-control"
                          id="title"
                          <?php if($urlNews18 != "") { ?>
                            value = "<?php echo ($urlNews18); ?>"
                          <?php } ?>
                          placeholder="Enter News18 Article URL"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>

                    <!-- Article Category -->
                    <div class="form-group row">
                      <label
                        for="categoryNews18"
                        class="col-sm-4 control-label col-form-label"
                        >Article Category</label
                      >
                      <div class="col-sm-8">
                        <select class="form-select" name="categoryNews18" aria-label="Default select example">
                            <option selected value="0">Please Select Article Category</option>
                            <option value="India" <?php if($categoryNews18 == "India") echo "selected"; ?> >India</option>
                            <option value="World" <?php if($categoryNews18 == "World") echo "selected"; ?> >World</option>
                            <option value="Tech" <?php if($categoryNews18 == "Tech") echo "selected"; ?> >Tech</option>
                            <option value="Sports" <?php if($categoryNews18 == "Sports") echo "selected"; ?> >Sports</option>
                            <option value="Entertainment" <?php if($categoryNews18 == "Entertainment") echo "selected"; ?> >Entertainment</option>
                            <option value="Business" <?php if($categoryNews18 == "Business") echo "selected"; ?> >Business</option>
                            <option value="Health" <?php if($categoryNews18 == "Health") echo "selected"; ?> >Health</option>
                            <option value="Life & Style" <?php if($categoryNews18 == "Life & Style") echo "selected"; ?> >Life & Style</option>
                        </select>
                      </div>
                    </div>

                    <div style="height:15px"> </div>
                    
                    <!-- Error message -->
                    <?php
                        if($errNews18 != "") { 
                    ?>
                        <center>
                            <p style="color: red;"><?php echo $errNews18; ?></p>
                        </center>
                    <?php
                        }
                    ?>
                    <!-- Submit button -->
                    <div class="border-top" style="margin-bottom: -23px;">
                        <div class="card-body">
                            <center><input type="submit" name="submitNews18" value="Submit" class="btn btn-primary"></center>
                        </div>
                    </div>
                  </div>
                    </form>
                </div>    
            </div>
        </div>

        <!-- Economic Times -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                  <!-- Form -->
                    <form class="form-horizontal" method="post" action="./index.php" enctype="multipart/form-data">
                    <div class="card-body">
                    <h3 class="card-title">Add The Economic Times Article</h3>

                    <div style="height:15px"> </div>

                    <!-- Article URL -->
                    <div class="form-group row">
                      <label
                        for="urlEconomictimes"
                        class="col-sm-4 control-label col-form-label"
                        >Article Url</label
                      >
                      <div class="col-sm-8">
                        <input
                          name="urlEconomictimes"
                          type="text"
                          class="required form-control"
                          id="title"
                          <?php if($urlEconomictimes != "") { ?>
                            value = "<?php echo ($urlEconomictimes); ?>"
                          <?php } ?>
                          placeholder="Enter The Economic Times Article URL"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>

                    <!-- Article Category -->
                    <div class="form-group row">
                      <label
                        for="categoryEconomictimes"
                        class="col-sm-4 control-label col-form-label"
                        >Article Category</label
                      >
                      <div class="col-sm-8">
                        <select class="form-select" name="categoryEconomictimes" aria-label="Default select example">
                            <option selected value="0">Please Select Article Category</option>
                            <option value="India" <?php if($categoryEconomictimes == "India") echo "selected"; ?> >India</option>
                            <option value="World" <?php if($categoryEconomictimes == "World") echo "selected"; ?> >World</option>
                            <option value="Tech" <?php if($categoryEconomictimes == "Tech") echo "selected"; ?> >Tech</option>
                            <option value="Sports" <?php if($categoryEconomictimes == "Sports") echo "selected"; ?> >Sports</option>
                            <option value="Entertainment" <?php if($categoryEconomictimes == "Entertainment") echo "selected"; ?> >Entertainment</option>
                            <option value="Business" <?php if($categoryEconomictimes == "Business") echo "selected"; ?> >Business</option>
                            <option value="Health" <?php if($categoryEconomictimes == "Health") echo "selected"; ?> >Health</option>
                            <option value="Life & Style" <?php if($categoryEconomictimes == "Life & Style") echo "selected"; ?> >Life & Style</option>
                        </select>
                      </div>
                    </div>

                    <div style="height:15px"> </div>
                    
                    <!-- Error message -->
                    <?php
                        if($errEconomictimes != "") { 
                    ?>
                        <center>
                            <p style="color: red;"><?php echo $errEconomictimes; ?></p>
                        </center>
                    <?php
                        }
                    ?>
                    <!-- Submit button -->
                    <div class="border-top" style="margin-bottom: -23px;">
                        <div class="card-body">
                            <center><input type="submit" name="submitEconomictimes" value="Submit" class="btn btn-primary"></center>
                        </div>
                    </div>
                  </div>
                    </form>
                </div>    
            </div>
        </div>

        <div style="height:35px"> </div>

        <!-- The Hindu -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                  <!-- Form -->
                    <form class="form-horizontal" method="post" action="./index.php" enctype="multipart/form-data">
                    <div class="card-body">
                    <h3 class="card-title">Add The Hindu Article</h3>

                    <div style="height:15px"> </div>

                    <!-- Article URL -->
                    <div class="form-group row">
                      <label
                        for="urlHindu"
                        class="col-sm-4 control-label col-form-label"
                        >Article Url</label
                      >
                      <div class="col-sm-8">
                        <input
                          name="urlHindu"
                          type="text"
                          class="required form-control"
                          id="title"
                          <?php if($urlHindu != "") { ?>
                            value = "<?php echo ($urlHindu); ?>"
                          <?php } ?>
                          placeholder="Enter The Hindu Article URL"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>

                    <!-- Article Category -->
                    <div class="form-group row">
                      <label
                        for="categoryHindu"
                        class="col-sm-4 control-label col-form-label"
                        >Article Category</label
                      >
                      <div class="col-sm-8">
                        <select class="form-select" name="categoryHindu" aria-label="Default select example">
                            <option selected value="0">Please Select Article Category</option>
                            <option value="India" <?php if($categoryHindu == "India") echo "selected"; ?> >India</option>
                            <option value="World" <?php if($categoryHindu == "World") echo "selected"; ?> >World</option>
                            <option value="Tech" <?php if($categoryHindu == "Tech") echo "selected"; ?> >Tech</option>
                            <option value="Sports" <?php if($categoryHindu == "Sports") echo "selected"; ?> >Sports</option>
                            <option value="Entertainment" <?php if($categoryHindu == "Entertainment") echo "selected"; ?> >Entertainment</option>
                            <option value="Business" <?php if($categoryHindu == "Business") echo "selected"; ?> >Business</option>
                            <option value="Health" <?php if($categoryHindu == "Health") echo "selected"; ?> >Health</option>
                            <option value="Life & Style" <?php if($categoryHindu == "Life & Style") echo "selected"; ?> >Life & Style</option>
                        </select>
                      </div>
                    </div>

                    <div style="height:15px"> </div>
                    
                    <!-- Error message -->
                    <?php
                        if($errHindu != "") { 
                    ?>
                        <center>
                            <p style="color: red;"><?php echo $errHindu; ?></p>
                        </center>
                    <?php
                        }
                    ?>
                    <!-- Submit button -->
                    <div class="border-top" style="margin-bottom: -23px;">
                        <div class="card-body">
                            <center><input type="submit" name="submitHindu" value="Submit" class="btn btn-primary"></center>
                        </div>
                    </div>
                  </div>
                    </form>
                </div>    
            </div>
        </div>

        <!-- India.com -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                  <!-- Form -->
                    <form class="form-horizontal" method="post" action="./index.php" enctype="multipart/form-data">
                    <div class="card-body">
                    <h3 class="card-title">Add India.com Article</h3>

                    <div style="height:15px"> </div>

                    <!-- Article URL -->
                    <div class="form-group row">
                      <label
                        for="urlIndia"
                        class="col-sm-4 control-label col-form-label"
                        >Article Url</label
                      >
                      <div class="col-sm-8">
                        <input
                          name="urlIndia"
                          type="text"
                          class="required form-control"
                          id="title"
                          <?php if($urlIndia != "") { ?>
                            value = "<?php echo ($urlIndia); ?>"
                          <?php } ?>
                          placeholder="Enter The Economic Times Article URL"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>

                    <!-- Article Category -->
                    <div class="form-group row">
                      <label
                        for="categoryIndia"
                        class="col-sm-4 control-label col-form-label"
                        >Article Category</label
                      >
                      <div class="col-sm-8">
                        <select class="form-select" name="categoryIndia" aria-label="Default select example">
                            <option selected value="0">Please Select Article Category</option>
                            <option value="India" <?php if($categoryIndia == "India") echo "selected"; ?> >India</option>
                            <option value="World" <?php if($categoryIndia == "World") echo "selected"; ?> >World</option>
                            <option value="Tech" <?php if($categoryIndia == "Tech") echo "selected"; ?> >Tech</option>
                            <option value="Sports" <?php if($categoryIndia == "Sports") echo "selected"; ?> >Sports</option>
                            <option value="Entertainment" <?php if($categoryIndia == "Entertainment") echo "selected"; ?> >Entertainment</option>
                            <option value="Business" <?php if($categoryIndia == "Business") echo "selected"; ?> >Business</option>
                            <option value="Health" <?php if($categoryIndia == "Health") echo "selected"; ?> >Health</option>
                            <option value="Life & Style" <?php if($categoryIndia == "Life & Style") echo "selected"; ?> >Life & Style</option>
                        </select>
                      </div>
                    </div>

                    <div style="height:15px"> </div>
                    
                    <!-- Error message -->
                    <?php
                        if($errIndia != "") { 
                    ?>
                        <center>
                            <p style="color: red;"><?php echo $errIndia; ?></p>
                        </center>
                    <?php
                        }
                    ?>
                    <!-- Submit button -->
                    <div class="border-top" style="margin-bottom: -23px;">
                        <div class="card-body">
                            <center><input type="submit" name="submitIndia" value="Submit" class="btn btn-primary"></center>
                        </div>
                    </div>
                  </div>
                    </form>
                </div>    
            </div>
        </div>

        <div style="height:35px"> </div>

        <!-- Google News -->
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                  <!-- Form -->
                    <form class="form-horizontal" method="post" action="./index.php" enctype="multipart/form-data">
                    <div class="card-body">
                    <h3 class="card-title">Google News Automatic Fetch</h3>

                    <div style="height:15px"> </div>

                    <!-- Article Keyword -->
                    <div class="form-group row">
                      <label
                        for="keywordGoogle"
                        class="col-sm-4 control-label col-form-label"
                        >Keyword</label
                      >
                      <div class="col-sm-8">
                        <input
                          name="keywordGoogle"
                          type="text"
                          class="required form-control"
                          id="title"
                          <?php if($keywordGoogle != "") { ?>
                            value = "<?php echo ($keywordGoogle); ?>"
                          <?php } ?>
                          placeholder="Enter The Keyword For News Articles"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>

                    <!-- Article Category -->
                    <div class="form-group row">
                      <label
                        for="categoryGoogle"
                        class="col-sm-4 control-label col-form-label"
                        >Article Category</label
                      >
                      <div class="col-sm-8">
                        <select class="form-select" name="categoryGoogle" aria-label="Default select example">
                            <option selected value="0">Please Select Article Category</option>
                            <option value="India" <?php if($categoryGoogle == "India") echo "selected"; ?> >India</option>
                            <option value="World" <?php if($categoryGoogle == "World") echo "selected"; ?> >World</option>
                            <option value="Tech" <?php if($categoryGoogle == "Tech") echo "selected"; ?> >Tech</option>
                            <option value="Sports" <?php if($categoryGoogle == "Sports") echo "selected"; ?> >Sports</option>
                            <option value="Entertainment" <?php if($categoryGoogle == "Entertainment") echo "selected"; ?> >Entertainment</option>
                            <option value="Business" <?php if($categoryGoogle == "Business") echo "selected"; ?> >Business</option>
                            <option value="Health" <?php if($categoryGoogle == "Health") echo "selected"; ?> >Health</option>
                            <option value="Life & Style" <?php if($categoryGoogle == "Life & Style") echo "selected"; ?> >Life & Style</option>
                        </select>
                      </div>
                    </div>

                    <div style="height:15px"> </div>

                    <!-- Number of articles to fetch -->
                    <div class="form-group row">
                      <label
                        for="numArticle"
                        class="col-sm-4 control-label col-form-label"
                        >Number Of Articles</label
                      >
                      <div class="col-sm-8">
                        <input
                          name="numArticle"
                          type="text"
                          class="required form-control"
                          id="title"
                          <?php if($numArticle != "") { ?>
                            value = "<?php echo ($numArticle); ?>"
                          <?php } ?>
                          placeholder="Enter The Number Of Articles"
                        />
                      </div>
                    </div>

                    <div style="height:15px"> </div>

                    <!-- Error message -->
                    <?php
                        if($errGoogle != "") { 
                    ?>
                        <center>
                            <p style="color: red;"><?php echo $errGoogle; ?></p>
                        </center>
                    <?php
                        }
                    ?>
                    <!-- Submit button -->
                    <div class="border-top" style="margin-bottom: -23px;">
                        <div class="card-body">
                            <center><input type="submit" name="submitGoogle" value="Submit" class="btn btn-primary"></center>
                        </div>
                    </div>
                  </div>
                    </form>
                </div>    
            </div>
        </div>
    </div>
</div>
<?php include_once 'footer.php'; ?>
