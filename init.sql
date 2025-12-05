CREATE DATABASE IF NOT EXISTS mydb;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'abcxyz';
FLUSH PRIVILEGES;