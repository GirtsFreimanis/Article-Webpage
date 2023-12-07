# Article-Webpage
This is my articles project with an editors view.
## Prerequisites:
• MySql:
<br />
CREATE SCHEMA article_webpage;
<br />
CREATE USER 'article_user'@'localhost' IDENTIFIED BY  'MyPassword'
<br />
GRANT ALL PRIVILEGES ON database_name.* TO 'article_user'@'localhost';
####
CREATE TABLE article_webpage.articles (
    id          INT AUTO_INCREMENT,
    title       VARCHAR(225) NOT NULL,
    description TEXT NOT NULL,
    picture     VARCHAR(225) NOT NULL,
    created_at  DATETIME NOT NULL,
    updated_at  DATETIME NULL,
    PRIMARY KEY (id)
);
####
• rename ".env.example" to ".env" and fill in blanks:
<br />
DB_NAME="article_webpage"
<br />
DB_USER="article_user"
<br />
DB_USER_PASSWORD="MyPassword"
<br />
DB_HOST="localhost"

## Articles Index view
![image](https://github.com/GirtsFreimanis/Article-Webpage/blob/master/readmePictures/1.png)

## Articles Create view
![image](https://github.com/GirtsFreimanis/Article-Webpage/blob/master/readmePictures/2.png)

## Articles Index view vith successfull article creation message
![image](https://github.com/GirtsFreimanis/Article-Webpage/blob/master/readmePictures/3.png)

## Article Show view
![image](https://github.com/GirtsFreimanis/Article-Webpage/blob/master/readmePictures/4.png)

## Article Edit view with invalid input
![image](https://github.com/GirtsFreimanis/Article-Webpage/blob/master/readmePictures/5.png)

## Articles Index view, Article update error message
![image](https://github.com/GirtsFreimanis/Article-Webpage/blob/master/readmePictures/6.png)

## Article Index view, deleting article
![image](https://github.com/GirtsFreimanis/Article-Webpage/blob/master/readmePictures/7.png)

## Articles Index view, article successfully deleted
![image](https://github.com/GirtsFreimanis/Article-Webpage/blob/master/readmePictures/8.png)
