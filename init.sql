-- init.sql
CREATE DATABASE IF NOT EXISTS mydb;

USE mysql;

-- Imposta password per root usando la sintassi MariaDB
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('abcxyz');

-- Permetti connessioni TCP/IP per root
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY 'abcxyz' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' IDENTIFIED BY 'abcxyz' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'abcxyz' WITH GRANT OPTION;

FLUSH PRIVILEGES;

-- Crea le tabelle per l'applicazione
USE mydb;

CREATE TABLE IF NOT EXISTS t_user (
    uid INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    creation_date DATETIME NOT NULL,
    online TINYINT(1) DEFAULT 0,
    last_heartbeat DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS t_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    uid INT NOT NULL,
    op_type TINYINT(1) NOT NULL COMMENT '0=logout, 1=login',
    timestamp DATETIME NOT NULL,
    is_kiosk TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (uid) REFERENCES t_user(uid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;