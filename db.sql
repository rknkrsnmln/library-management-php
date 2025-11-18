CREATE DATABASE library_management;
CREATE DATABASE library_management_test;

-- Use the main database
USE php_login_management;

-- Users table with status and role
CREATE TABLE users
(
    id       VARCHAR(255) PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status   ENUM ('active', 'nonactive') DEFAULT 'active',
    role     ENUM ('member', 'admin')     DEFAULT 'member'
) ENGINE = InnoDB;

-- Sessions table
CREATE TABLE sessions
(
    id      VARCHAR(255) PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE = InnoDB;

-- Books table
CREATE TABLE books
(
    id               VARCHAR(255) PRIMARY KEY,
    title            VARCHAR(255) NOT NULL,
    author           VARCHAR(255),
    publication_year INT,
    available        BOOLEAN DEFAULT TRUE
) ENGINE = InnoDB;

-- Make sure that available is int not string or else
ALTER TABLE books
    MODIFY COLUMN available TINYINT(1) DEFAULT 1;

-- Borrowings table (relationship between users and books)
CREATE TABLE borrowings
(
    id          VARCHAR(255) PRIMARY KEY,
    user_id     VARCHAR(255) NOT NULL,
    book_id     VARCHAR(255) NOT NULL,
    borrow_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    return_date DATETIME     NULL,
    returned    BOOLEAN  DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (book_id) REFERENCES books (id)
) ENGINE = InnoDB;

INSERT INTO books (id, title, author, publication_year, available)
VALUES ('B001', 'The Great Gatsby', 'F. Scott Fitzgerald', 1925, TRUE),
       ('B002', '1984', 'George Orwell', 1949, TRUE),
       ('B003', 'To Kill a Mockingbird', 'Harper Lee', 1960, TRUE),
       ('B004', 'Pride and Prejudice', 'Jane Austen', 1813, TRUE),
       ('B005', 'The Catcher in the Rye', 'J.D. Salinger', 1951, TRUE);
