CREATE TABLE uniforms(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category TEXT,
    name TEXT,
    size ENUM(
	'xs',
	's',
	'm',
	'l',
	'xl',
	'2xl',
    ),
    department TEXT
)
