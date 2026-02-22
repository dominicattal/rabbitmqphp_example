CREATE TABLE users (
    username varchar(255) PRIMARY KEY,
    password varchar(255) NOT NULL
);

INSERT INTO users VALUES ('test', 'test');

CREATE TABLE validations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username INT NOT NULL,
  sessionKey VARCHAR(128) NOT NULL UNIQUE,
  createdAt BIGINT NOT NULL,
  expiresAt BIGINT NOT NULL,
  FOREIGN KEY (username) REFERENCES users(username)
);

/*For session key
IN JS when user is logging in use some hash function to get a key
  Take said key + EPOCH time stamp -> to DB
   DB Saves time created + key + expiration (5 mins)
    Each request to server should check for valid key
      if y -let request go + update expiration
        if n -Log out user
	-Matt
*/
