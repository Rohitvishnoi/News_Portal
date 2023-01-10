# XYZ NEWS PORTAL

## Motivation
* In the world of rapid news and information accumulation, it is often easier to be distracted and lose the valuable news of one's personal interest.
* Many a times we are just bombarded with current breaking news or news which are not really catered towards our interest, and even for finding news from our domain, we need to google it manually. That can be tedious at times. 
* So our project aims to solve this problem by creating a user centric news article portal, where users can find news articles of their personal taste, also they can subscribe to newsletters for updates.

## Why you should consider our news portal?
The answer is simple, our website provides one stop solution for news updates where you can easily read the news without being bombarded with adlinks and promotional messages which you never like.


## User Site
A web application that helps users to read news articles from various reliable resources like : 
1. News 18
2. The Economic Times
3. The Hindu
4. India[dot]com

Users can also subscribe to news updates notification mails.
### User site features:
1. Landing Page - Read latest added news articles by clicking on any news article from the landing page.
2. Categorical News articles - Sort news by their categories by cliicking on any category from the navbar.
3. Subscribe to Updates - Readers can subscribe for newly added articles by submitting their email.
4. Search for news articles - Readers can search for any news article by any keyword.
5. Commenting - Readers can also post comments on any news article.
6. Other features - Readers can also see the *number of views* for each news article. Also the website is having *next* and *previous* buttons and *pagination* for easier navigation through the site.
## Admin Site
1. Admin login
2. Add/Update admin details
3. Admin has option to add/update news articles either manually, or automatically
4. Management of comments

For automatically adding the news articles:
* Go to dashboard
* Paste the url of news article in the respective box, also select the category of news article.
* Press 'Submit', the news article will be added in the portal.

This is achieved by using "Web Scrapping" in Python, which is invoked using PHP.


## Technology Stack
1. PHP, HTML, CSS, Javascript
2. Python, Webscrapping
3. Database - Using Python and PHP
4. Code Documenting - Using Sphinx
5. Latex - For project presentations


## Hardware/Software requirements
1. Hardware - Any Windows/Linux/Mac based system.
2. Software -
    * LAMP stack or XAMPP
    * PHP 7.3 or higher
    * Python 3.6 or higher
    * Python Libraries -
            beautifulsoup4==4.10.0
            | bs4==0.0.1
            | certifi==2021.10.8
            | charset-normalizer==2.0.7
            | idna==3.3
            | lxml==4.6.3
            | requests==2.26.0
            | six==1.16.0
            | soupsieve==2.2.1
            | urllib3==1.26.7
            | webencodings==0.5.1
            | PyMySQL==1.0.2

## How to run the project
* Clone the repository and extract the files to 'htdocs' folder in php localhost folder in your system.
* Give read and write permission to all the files and folders using command "chmod 777 -R projectXYZ".
* Start xampp server on your system.
* Import the "cs699proj.sql" database file by going in phpmyadmin of your system.
* Change credentials in config/db.php file to your phpmyadmin credentials (user,password).
* Also credentials in webscraping files need to be changed.
* Open the project on web browser by typing "localhost/projectXYZ".
* You can also open the admin site uisng "localhost/projectXYZ/admin".
* NOTE : For admin site, you need authorized credentials to log in.

## How to operate the project
### User site
1. Once you've opened "localhost/projectXYZ" in web browser, you'll see the landing page of the News Portal.
2. Landing Page - 
    * Top banner has logo and navbar to sort the news articles based on categories.
    * The main container has links to various news articles in cards format, it has the news headline along with a short description also it has a image thumbnail. Users can read the complete news article by clicking the *Read More* button in any card.
    * Side bar has *Subscribe to updates* and *Search bar*. Users can enter their email to subscribe to a news letter which will update them whenever a news article is added to the portal. Users can also search for any news article by entering the *search term* in the search bar.
3. News article webpage - 
    * Individual news article webpage has the complete news article where reader can read the news also they can see the accompanying image. At the end of the news description, there is a hyperlink for accessing the orignal news article.
    * *Next* & *Previous* buttons - Reader can go to next/previous news article by clicking it.
    * Commenting - Below the news article, users can add commnents by entering their name and the comment in the comment box, also users can read comments left by others on the same article(if any).
    * Side bar - Side bar is having links to top 10 latest added news articles. 
### Admin Site
1. Once you've opened "localhost/projectXYZ/admin" in the web browser, you'll see the admin login portal.
2. After logging in (this requires valid credentials)
    * Landing page(Dashboard) - This page has web scrapping mechanism to automatically add news articles. 
    * Using News18/Economic Times/The Hindu/India.com Web scrapper -
        * Paste the respective news url in the Article Url box, also select the category of the news article to be added from the dropdown. 
        * Click on submit button, the news article will be fetched and added to the news database.
        * If some error happens (this can occur if the url entered is not valid for that web scrapper) - alert will be raised.  
    * Using Google Web scrapper - 
        * This web scrapper automatically fetches multiple news articles based on the keyword entered in the *Keyword* box.
        * Admin can specify the number of news articles to fetch by typing in the number in the *Number of articles* box. Also the category should be selected from the dropdown.
        * **NOTE** : This web scrapper uses Google News for fetching news articles related to passed keyword. This is a strong web scrapper as it fetches multiple news articles at once along with any search keyword.
3. Add admin page - This page can be used to add other news admins to the site.
4. View admin page - This page can be used to update and view all the admins on the site. **NOTE**: Only the ROOT admin can change information of all the admins, NON ROOT admins can only change their own information.
5. Add post page - This page is used to add news articles manually.
6. View post page - This page is used to view and edit already added news artilces in the portal.
7. View comment page - This page is used to view all the comments added by readers in the news articles, using this page, admin can Hide/Unhide or delete the comments.

## Documentaion Path
docs/_build/html/index.html

## Primary Stakeholders
* All the individuals who want to read news
* News editors and writers

## References
1. [Sticky Divs](https://www.w3docs.com/snippets/css/how-to-make-a-div-stick-to-the-top-of-screen-when-scrolling-with-css-and-javascript.html)
2. [Bootstrap Theme](https://bootswatch.com/5/lux/bootstrap.css)
3. [Bootstrap Elements](https://bootswatch.com/lux/)
4. [PHP Pagination](https://www.javatpoint.com/php-pagination)
5. [PHP dynamic data fetching](https://youtu.be/IYlDJ2K3MGk?list=PLillGF-Rfqbap2IB6ZS4BBBcYPagAjpjn)
6. [Bootstrap Sidebar](https://freefrontend.com/bootstrap-sidebars/)
7. [Bootstrap form filed](https://getbootstrap.com/docs/4.0/components/input-group/)
8. [Pagination](https://mdbootstrap.com/docs/b4/jquery/tables/pagination/)
9. [Login card](https://codepen.io/eminqasimov/pen/zXJVzJ)
10. [Bootstrap model](https://codepen.io/fadzrinmadu/pen/NWpvLwb)
11. [PHP session managment](https://www.w3schools.com/php/php_sessions.asp)
12. [Python in php](https://stackoverflow.com/questions/166944/calling-python-in-php)
13. [Function in php](https://www.php.net/manual/en/function.exec.php)
14. [Bootstrap code](https://getbootstrap.com/)
15. [Link Scraping](https://pythonprogramminglanguage.com/get-all-links-from-webpage/)
16. [Image Type](https://docs.python.org/3/library/imghdr.html)
