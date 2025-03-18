CREATE TABLE uniforms(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255),
    name VARCHAR(255),
    image_url VARCHAR(2083),
    size ENUM(
	'xs',
	's',
	'm',
	'l',
	'xl',
	'2xl'
    ),
    department TEXT
);
