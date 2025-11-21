DROP DATABASE IF EXISTS library_management_test;
DROP DATABASE IF EXISTS library_management;

CREATE DATABASE library_management_test;
CREATE DATABASE library_management;

USE library_management_test;

-- Create the users table
CREATE TABLE users
(
    id       VARCHAR(255) PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status   ENUM ('active', 'nonactive') DEFAULT 'active',
    role     ENUM ('member', 'admin')     DEFAULT 'member'
) ENGINE = InnoDB;

-- Create the sessions table (with a foreign key to users)
CREATE TABLE sessions
(
    id      VARCHAR(255) PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE = InnoDB;

-- Create the books table
CREATE TABLE books
(
    id               VARCHAR(255) PRIMARY KEY,
    title            VARCHAR(255) NOT NULL,
    author           VARCHAR(255),
    publication_year INT,
    available        TINYINT(1) DEFAULT 1 -- Fixed to be a boolean-like value
) ENGINE = InnoDB;

-- Create the borrowings table with foreign keys
CREATE TABLE borrowings
(
    id          VARCHAR(255) PRIMARY KEY,
    user_id     VARCHAR(255) NOT NULL,
    book_id     VARCHAR(255) NOT NULL,
    borrow_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    return_date DATETIME     NULL,
    returned    BOOLEAN  DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books (id) ON DELETE CASCADE
) ENGINE = InnoDB;