CREATE DATABASE study_planner;
USE study_planner;
CREATE TABLE tasks (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(50) NOT NULL,
    task TEXT NOT NULL,
    due_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);