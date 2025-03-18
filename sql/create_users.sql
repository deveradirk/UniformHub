CREATE TABLE users(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    password TEXT,
    role ENUM(
	'student',
	'admin'
    ),
    user_id CHAR(14) UNIQUE,
    fullname TEXT,
    INDEX ix_email(email),
    INDEX ix_user_id(user_id)
);
