CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL
);

INSERT INTO users (username, password) VALUES ('test', 'test');

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

CREATE TABLE IF NOT EXISTS watchlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    movie_id VARCHAR(255) NOT NULL,
    movie_name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS movies (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    overview VARCHAR(2048) NOT NULL,
    poster_img_url VARCHAR(255) NOT NULL,
    createdAt BIGINT NOT NULL
);

CREATE TABLE IF NOT EXISTS popular_movies (
    id VARCHAR(255) PRIMARY KEY,
    createdAt BIGINT NOT NULL
);
