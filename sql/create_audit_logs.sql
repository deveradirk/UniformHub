CREATE TABLE audit_logs(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fk_user_id CHAR(14),
    action TEXT,
    logtime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(fk_user_id)
	REFERENCES users(user_id)
);
