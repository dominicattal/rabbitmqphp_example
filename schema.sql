CREATE TABLE users (
    username varchar(255) PRIMARY KEY,
    password varchar(255) NOT NULL
);

INSERT INTO users VALUES ('test', 'test');

CREATE TABLE validations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  sessionKey VARCHAR(255) NOT NULL UNIQUE,
  createdAt BIGINT NOT NULL,
  expiresAt BIGINT NOT NULL,
  FOREIGN KEY (username) REFERENCES users(username)
);




