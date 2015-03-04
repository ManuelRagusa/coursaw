CREATE DATABASE coursaw;

CREATE USER 'coursaw_user'@'localhost' IDENTIFIED BY 'eKcGZr59zAa2BEWU';
GRANT SELECT, INSERT, UPDATE, DELETE ON coursaw.* TO 'coursaw_user'@'localhost';

CREATE TABLE coursaw.users (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(32) NOT NULL,
    password VARCHAR(128) NOT NULL,
    first_name VARCHAR(32) NOT NULL,
    last_name VARCHAR(32) NOT NULL,
    email VARCHAR(1024) NOT NULL,
    active INT NOT NULL DEFAULT 1,
    teacher INT NOT NULL DEFAULT 0,
    profile VARCHAR(55) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE coursaw.courses (
	course_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(100) NOT NULL,
	description TEXT NOT NULL,
	category INT NOT NULL,
	start_date DATE NOT NULL,
	duration INT NOT NULL,
	creator INT NOT NULL,
	visible INT NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE coursaw.categories (
	category_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE coursaw.activities (
	course_id INT NOT NULL,
	week INT NOT NULL,
	link VARCHAR(50) NOT NULL,
	PRIMARY KEY (course_id, week),
	FOREIGN KEY (course_id) REFERENCES coursaw.courses(course_id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE coursaw.participants (
	course_id INT NOT NULL,
	user_id INT NOT NULL,
	teacher INT NOT NULL DEFAULT 0,
	PRIMARY KEY (course_id, user_id),
	FOREIGN KEY (course_id) REFERENCES coursaw.courses(course_id) ON DELETE CASCADE
) ENGINE = InnoDB;