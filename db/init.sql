CREATE USER IF NOT EXISTS 'admin' @'localhost' IDENTIFIED BY 'auth_string';

CREATE DATABASE IF NOT EXISTS forum;

GRANT ALL ON forum.* TO 'admin' @'localhost';

USE forum;

DROP TABLE IF EXISTS comments;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT,
  name VARCHAR(9),
  password VARCHAR(255),
  PRIMARY KEY (id),
  UNIQUE (name)
);

INSERT INTO
  users (name, password)
VALUES
  (
    "fox",
    "$2y$10$f3TG4D/VjlP9f7rjbcAQ/uFYoGLHAHMTN6NVjn.0gEBd7d9WbXCm6"
  ),
  (
    "<u>w</u>",
    "$2y$10$HIYknnFDPMFJjSf.cmahIu7oXEolF7zdiIs0eSXK9ltzNWqR1.9VW"
  ),
  (
    "881",
    "$2y$10$aUv5k4s.TOSQecpH7QxQjOP3DjaVlv7qDjE5tCe6.jbLR/5t6dw.6"
  );

CREATE TABLE comments (
  id INT UNSIGNED AUTO_INCREMENT,
  user_id INT UNSIGNED,
  content VARCHAR(255),
  -- is_deleted BOOL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

INSERT INTO
  comments (content, user_id)
VALUES
  ("First post of the year!", 1),
  ("Looking forward to great collaborations.", 1),
  ("Excited about new projects!", 2),
  ("Happy to be part of this community!", 3),
  ("Can't wait for the upcoming events!", 2),
  ("Here's to making a difference!", 3),
  (
    "Thrilled about the new features we're launching.",
    1
  ),
  ("Ready to tackle new challenges!", 2),
  (
    "Inspired by the positive feedback we've received.",
    1
  ),
  ("Celebrating our team's achievements.", 3),
  ("Eager to see what the future holds!", 2),
  (
    "Grateful for all the support from our followers.",
    3
  ),
  ("Let's make this year count!", 1);
