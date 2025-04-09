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
    name VARCHAR(50) NOT NULL
);

-- Years table
CREATE TABLE year (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year YEAR NOT NULL
);

-- Environments table
CREATE TABLE environment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Pollutants table
CREATE TABLE pollutant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Institutes names table
CREATE TABLE institute_name (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Postal codes table
CREATE TABLE postal_code (
    id INT AUTO_INCREMENT PRIMARY KEY,
    postal_code VARCHAR(10) NOT NULL
);

-- Communities table
CREATE TABLE community (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Departments table
CREATE TABLE department (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Regions table
CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Country table
CREATE TABLE country (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- EPSG codes table
CREATE TABLE code_epsg (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL
);

-- APE codes table
CREATE TABLE code_ape (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL
);

-- APE labels table
CREATE TABLE label_ape (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL
);

-- EPTR codes table
CREATE TABLE code_eptr (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL
);

-- EPTR labels table
CREATE TABLE label_eptr (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL
);

-- Institutes table
CREATE TABLE institute (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institute_name_id INT,
    siret VARCHAR(14),
    address VARCHAR(255),
    postal_code_id INT,
    community_id INT,
    department_id INT,
    region_id INT,
    coord_x DECIMAL(10, 6),
    coord_y DECIMAL(10, 6),
    code_epsg_id INT,
    code_ape_id INT,
    label_ape_id INT,
    code_eptr_id INT,
    label_eptr_id INT,
    FOREIGN KEY (institute_name_id) REFERENCES institute_name(id),
    FOREIGN KEY (postal_code_id) REFERENCES postal_code(id),
    FOREIGN KEY (community_id) REFERENCES community(id),
    FOREIGN KEY (department_id) REFERENCES department(id),
    FOREIGN KEY (region_id) REFERENCES region(id),
    FOREIGN KEY (code_epsg_id) REFERENCES code_epsg(id),
    FOREIGN KEY (code_ape_id) REFERENCES code_ape(id),
    FOREIGN KEY (label_ape_id) REFERENCES label_ape(id),
    FOREIGN KEY (code_eptr_id) REFERENCES code_eptr(id),
    FOREIGN KEY (label_eptr_id) REFERENCES label_eptr(id)
);

-- Emissions table
CREATE TABLE emission (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institute_id INT,
    year_id INT,
    environment_id INT,
    pollutant_id INT,
    unit_id INT,
    quantity DECIMAL(15, 3),
    FOREIGN KEY (institute_id) REFERENCES institute(id),
    FOREIGN KEY (year_id) REFERENCES year(id),
    FOREIGN KEY (environment_id) REFERENCES environment(id),
    FOREIGN KEY (pollutant_id) REFERENCES pollutant(id),
    FOREIGN KEY (unit_id) REFERENCES unit(id)
);

-- Prelevement table
CREATE TABLE prelevement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institute_name_id INT,
    year_id INT,
    water_cave INT,
    water_surface INT,
    water_distribution INT,
    water_sea INT,
    FOREIGN KEY (year_id) REFERENCES year(id),
    FOREIGN KEY (institute_name_id) REFERENCES institute_name(id)
);

-- Waste table
CREATE TABLE waste (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL
);

-- Waste codes table
CREATE TABLE waste_code (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code INT NOT NULL
);

-- Waste labels table
CREATE TABLE waste_label (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label TEXT NOT NULL
);

-- Waste disposal/valorization operations table
CREATE TABLE disposal_valorization_operation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_code VARCHAR(4) NOT NULL,
    name_label TEXT NOT NULL
);

-- Waste production table
CREATE TABLE waste_production (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institute_name_id INT,
    waste_id INT,
    year_id INT, 
    disposal_valorization_operation_id INT,
    department_id INT,
    country_id INT,
    waste_code_id INT,
    waste_label_id INT,
    prod_quantity DECIMAL(15, 3),
    quantity_in DECIMAL(15, 3),
    quantity_treated DECIMAL(15, 3),
    unit_id INT,
    FOREIGN KEY (institute_name_id) REFERENCES institute_name(id),
    FOREIGN KEY (waste_id) REFERENCES waste(id),
    FOREIGN KEY (year_id) REFERENCES year(id),
    FOREIGN KEY (disposal_valorization_operation_id) REFERENCES disposal_valorization_operation(id),
    FOREIGN KEY (department_id) REFERENCES department(id),
    FOREIGN KEY (country_id) REFERENCES country(id),
    FOREIGN KEY (waste_code_id) REFERENCES waste_code(id),
    FOREIGN KEY (waste_label_id) REFERENCES waste_label(id),
    FOREIGN KEY (unit_id) REFERENCES unit(id)
);
