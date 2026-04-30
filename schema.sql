CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL
);

INSERT INTO users (username, email, password) VALUES ('test', 'it490madd@gmail.com', 'test');

CREATE TABLE IF NOT EXISTS validations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    sessionKey VARCHAR(255) NOT NULL UNIQUE,
    createdAt BIGINT NOT NULL,
    expiresAt BIGINT NOT NULL
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    movie_id VARCHAR(255) NOT NULL,
    score INT NOT NULL,
    review VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS review_reviews (
    review_id INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    status INT NOT NULL,
    PRIMARY KEY (review_id, username)
);

INSERT INTO users (username, email, password) VALUES ('test_recommend', 'it490madd@gmail.com', 'test');
INSERT INTO reviews (username, movie_id, score, review) VALUES ('test_recommend', '1266798', 8, 'This movie freaking rules');

CREATE TABLE IF NOT EXISTS watchlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    movie_id VARCHAR(255) NOT NULL,
    movie_name VARCHAR(255) NOT NULL,
    release_date VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS movies (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    genre_id INT NOT NULL,
    overview VARCHAR(2048) NOT NULL,
    poster_img_url VARCHAR(255) NOT NULL,
    vote_average FLOAT NOT NULL,
    createdAt BIGINT NOT NULL,
    release_date VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS popular_movies (
    id VARCHAR(255) PRIMARY KEY,
    createdAt BIGINT NOT NULL
);

CREATE TABLE IF NOT EXISTS genres (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    createdAt BIGINT NOT NULL
);

CREATE TABLE IF NOT EXISTS achievements (
    name VARCHAR(255) NOT NULL,
    hr_name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS user_achievements (
    achievement_name VARCHAR(255),
    username VARCHAR(255),
    PRIMARY KEY (achievement_name, username)
);

INSERT INTO achievements (name, hr_name) VALUES ('review_1', 'Review One Movie');
INSERT INTO achievements (name, hr_name) VALUES ('review_2', 'Review Two Movies');
INSERT INTO achievements (name, hr_name) VALUES ('review_3', 'Review Three Movies');
INSERT INTO achievements (name, hr_name) VALUES ('review_review', 'Review a Review');
