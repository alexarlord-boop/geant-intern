-- Create the database if it does not exist
CREATE DATABASE IF NOT EXISTS simplesamlphp CHARACTER SET utf8;

-- Grant privileges to the 'ptest' user from localhost
GRANT ALL ON simplesamlphp.* TO 'ptest'@'localhost' IDENTIFIED BY '1111';

-- Flush privileges to apply the changes
FLUSH PRIVILEGES;