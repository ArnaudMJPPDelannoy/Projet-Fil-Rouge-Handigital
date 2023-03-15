CREATE TABLE Users(
   Id_Users INT AUTO_INCREMENT,
   lastname VARCHAR(50) NOT NULL,
   firstname VARCHAR(50) NOT NULL,
   age INT NOT NULL,
   gender VARCHAR(10) NOT NULL,
   email VARCHAR(150) NOT NULL,
   username VARCHAR(50) NOT NULL,
   password VARCHAR(50) NOT NULL,
   profile_image_url VARCHAR(150),
   role VARCHAR(50) NOT NULL DEFAULT "author",
   connected TINYINT(1) NOT NULL DEFAULT 0,
   disconnect_date VARCHAR(150) NOT NULL DEFAULT "unset",
   PRIMARY KEY(Id_Users),
   UNIQUE(username)
);

CREATE TABLE Messages(
   Id_Messages INT AUTO_INCREMENT,
   content TEXT NOT NULL,
   send_time DATETIME NOT NULL,
   Id_Receiver INT NOT NULL,
   Id_Sender INT NOT NULL,
   PRIMARY KEY(Id_Messages),
   FOREIGN KEY(Id_Receiver) REFERENCES Users(Id_Users),
   FOREIGN KEY(Id_Sender) REFERENCES Users(Id_Users)
);

CREATE TABLE Games(
   Id_Games INT AUTO_INCREMENT,
   name VARCHAR(100) NOT NULL,
   description TEXT NOT NULL,
   banner_image_url VARCHAR(150) NOT NULL,
   icon_image_url VARCHAR(150) NOT NULL,
   PRIMARY KEY(Id_Games)
);

CREATE TABLE Articles(
   Id_Articles INT AUTO_INCREMENT,
   title VARCHAR(100) NOT NULL,
   content TEXT NOT NULL,
   banner_image_url VARCHAR(150),
   PRIMARY KEY(Id_Articles)
);

CREATE TABLE Comments(
   Id_Comments INT AUTO_INCREMENT,
   content TEXT NOT NULL,
   send_time DATETIME NOT NULL,
   Id_Poster INT NOT NULL,
   Id_Articles INT NOT NULL,
   PRIMARY KEY(Id_Comments),
   FOREIGN KEY(Id_Poster) REFERENCES Users(Id_Users),
   FOREIGN KEY(Id_Articles) REFERENCES Articles(Id_Articles)
);

CREATE TABLE GameGenres(
   Id_GameGenres INT AUTO_INCREMENT,
   name VARCHAR(50),
   PRIMARY KEY(Id_GameGenres)
);

CREATE TABLE ForumMsg(
   Id_ForumMsg INT AUTO_INCREMENT,
   content TEXT NOT NULL,
   send_time DATETIME NOT NULL,
   Id_Games INT NOT NULL,
   Id_ForumPoster INT NOT NULL,
   PRIMARY KEY(Id_ForumMsg),
   FOREIGN KEY(Id_Games) REFERENCES Games(Id_Games),
   FOREIGN KEY(Id_ForumPoster) REFERENCES Users(Id_Users)
);

CREATE TABLE Play(
   Id_Users INT,
   Id_Games INT,
   PRIMARY KEY(Id_Users, Id_Games),
   FOREIGN KEY(Id_Users) REFERENCES Users(Id_Users),
   FOREIGN KEY(Id_Games) REFERENCES Games(Id_Games)
);

CREATE TABLE WriteArticle(
   Id_Users INT,
   Id_Articles INT,
   publish_time DATETIME NOT NULL,
   PRIMARY KEY(Id_Users, Id_Articles),
   FOREIGN KEY(Id_Users) REFERENCES Users(Id_Users),
   FOREIGN KEY(Id_Articles) REFERENCES Articles(Id_Articles)
);

CREATE TABLE Friends(
   Id_Users INT,
   Id_Friend INT,
   PRIMARY KEY(Id_Users, Id_Friend),
   FOREIGN KEY(Id_Users) REFERENCES Users(Id_Users),
   FOREIGN KEY(Id_Friend) REFERENCES Users(Id_Users)
);

CREATE TABLE WhichGenres(
   Id_Games INT,
   Id_GameGenres INT,
   PRIMARY KEY(Id_Games, Id_GameGenres),
   FOREIGN KEY(Id_Games) REFERENCES Games(Id_Games),
   FOREIGN KEY(Id_GameGenres) REFERENCES GameGenres(Id_GameGenres)
);
