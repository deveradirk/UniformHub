CREATE TABLE stock(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fk_uniform_id INT UNSIGNED,
    sold_to INT UNSIGNED,
    FOREIGN KEY(fk_uniform_id)
	REFERENCES uniforms(id),
    FOREIGN KEY(sold_to)
	REFERNCES users(id)
);
