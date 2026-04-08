CREATE TABLE IF NOT EXISTS bundleList (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL,
    version INT NOT NULL,
    status varchar(255) NOT NULL,
    file_path varchar(255) NOT NULL
);

-- The DB's name on deployment is 'bundles'
