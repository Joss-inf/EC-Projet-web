-- Create the database
CREATE DATABASE statpol;
USE statpol;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, 
    user_state INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

CREATE TABLE message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    channel_id INT,
    content TEXT NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)  
);

CREATE TABLE user_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL, 
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (user_id) REFERENCES users(id) 
);

-- Units table
CREATE TABLE unit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Years table
CREATE TABLE year (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year YEAR NOT NULL UNIQUE
);

-- Environments table
CREATE TABLE environment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Pollutants table
CREATE TABLE pollutant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Institutes names table
CREATE TABLE institute_name (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- Postal codes table
CREATE TABLE postal_code (
    id INT AUTO_INCREMENT PRIMARY KEY,
    postal_code VARCHAR(10) NOT NULL UNIQUE
);

-- Communities table
CREATE TABLE community (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Departments table
CREATE TABLE department (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Regions table
CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Country table
CREATE TABLE country (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Institutes table
CREATE TABLE institute (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institute_name_id INT,
    siret VARCHAR(14) UNIQUE,
    address VARCHAR(255),
    postal_code_id INT,
    community_id INT,
    department_id INT,
    region_id INT,
    coord_x DECIMAL(10, 6),
    coord_y DECIMAL(10, 6),
    FOREIGN KEY (institute_name_id) REFERENCES institute_name(id),
    FOREIGN KEY (postal_code_id) REFERENCES postal_code(id),
    FOREIGN KEY (community_id) REFERENCES community(id),
    FOREIGN KEY (department_id) REFERENCES department(id),
    FOREIGN KEY (region_id) REFERENCES region(id)
);


-- Emissions table
CREATE TABLE emission (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institute_name_id  INT,
    year_id INT,
    environment_id INT,
    pollutant_id INT,
    unit_id INT,
    quantity DECIMAL(15, 3),
    FOREIGN KEY (institute_name_id ) REFERENCES institute_name(id),
    FOREIGN KEY (year_id) REFERENCES year(id),
    FOREIGN KEY (environment_id) REFERENCES environment(id),
    FOREIGN KEY (pollutant_id) REFERENCES pollutant(id),
    FOREIGN KEY (unit_id) REFERENCES unit(id)
);

-- Waste table
CREATE TABLE waste (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL UNIQUE
);

-- Waste_label table
CREATE TABLE waste_label (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label TEXT NOT NULL UNIQUE
);
-- Waste production table
CREATE TABLE waste_production (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institute_name_id INT,
    waste_id INT,
    waste_label_id
    year_id INT, 
    department_id INT,
    country_id INT,
    prod_quantity DECIMAL(15, 3),
    quantity_in DECIMAL(15, 3),
    quantity_treated DECIMAL(15, 3),
    unit_id INT,
    FOREIGN KEY (institute_name_id) REFERENCES institute_name(id),
    FOREIGN KEY (waste_id) REFERENCES waste(id),
    FOREIGN KEY (year_id) REFERENCES year(id),
    FOREIGN KEY (department_id) REFERENCES department(id),
    FOREIGN KEY (country_id) REFERENCES country(id),
    FOREIGN KEY (unit_id) REFERENCES unit(id)
);
