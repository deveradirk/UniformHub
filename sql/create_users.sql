CREATE TABLE users(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(14) UNIQUE,
    fullname TEXT,
    username VARCHAR(127),
    password TEXT,
    role ENUM(
	'student',
	'teacher
    )
    INDEX i_user_id(user_id),
);
