-- Create the database if it does not exist
CREATE DATABASE IF NOT EXISTS hospital_management;

-- Use the created database
USE hospital_management;

-- Create the hospital table if it does not exist
CREATE TABLE IF NOT EXISTS hospital (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    contact_number VARCHAR(15),
    email VARCHAR(100),
    doctor_name VARCHAR(255),
    doctor_contact_number VARCHAR(15),
    doctor_email VARCHAR(100),
    image VARCHAR(255),
    password VARCHAR(100)
);

-- Insert sample data into the hospital table
INSERT INTO hospital (hospital_name, address, zip_code, contact_number, email, doctor_name, doctor_contact_number, doctor_email, image, password)
VALUES
('City Hospital', '123 Main St', '10001', '1234567890', 'info@cityhospital.com', 'Dr. John Smith', '9876543210', 'dr.john@cityhospital.com', 'city_hospital.jpg', 'hospital123'),
('Green Valley Hospital', '456 Green Valley Rd', '10002', '1234567891', 'contact@greenvalley.com', 'Dr. Sarah Lee', '9876543211', 'dr.sarah@greenvalley.com', 'green_valley_hospital.jpg', 'hospital123'),
('Oceanview Hospital', '789 Ocean Blvd', '10003', '1234567892', 'info@oceanview.com', 'Dr. Michael Brown', '9876543212', 'dr.michael@oceanview.com', 'oceanview_hospital.jpg', 'hospital123'),
('Mountain Peak Hospital', '321 Mountain Rd', '10004', '1234567893', 'contact@mountainpeak.com', 'Dr. Emily Clark', '9876543213', 'dr.emily@mountainpeak.com', 'mountain_peak_hospital.jpg', 'hospital123'),
('Riverside Hospital', '654 Riverside Dr', '10005', '1234567894', 'info@riverside.com', 'Dr. David Miller', '9876543214', 'dr.david@riverside.com', 'riverside_hospital.jpg', 'hospital123');

-- Create the patient table if it does not exist
CREATE TABLE IF NOT EXISTS patient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(255) NOT NULL,
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    contact_number VARCHAR(15),
    email VARCHAR(100),
    password VARCHAR(100)
);

-- Create the patient_report table if it does not exist
CREATE TABLE IF NOT EXISTS patient_report (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,  -- Foreign key linking to the patient
    report_file VARCHAR(255) NOT NULL,  -- To store the report file path or name
    report_date DATE,  -- The date when the report was created or uploaded
    FOREIGN KEY (patient_id) REFERENCES patient(id) ON DELETE CASCADE
);

-- Insert a new patient
INSERT INTO patient (patient_name, age, gender, contact_number, email, password)
VALUES ('John Doe', 35, 'Male', '1234567890', 'john.doe@example.com', 'john');  -- Changed quotes around 'john' to standard single quotes

-- Insert a report for this patient (assuming patient_id = 1)
INSERT INTO patient_report (patient_id, report_file, report_date)
VALUES (1, 'report1.pdf', '2024-10-16');
