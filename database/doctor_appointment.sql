CREATE DATABASE IF NOT EXISTS doctor_appointment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE doctor_appointment;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(20) NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','doctor','patient') NOT NULL DEFAULT 'patient',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE doctors (
  user_id INT UNSIGNED PRIMARY KEY,
  specialization VARCHAR(120) NOT NULL,
  qualification VARCHAR(160) NULL,
  experience_years TINYINT UNSIGNED NOT NULL DEFAULT 0,
  consultation_fee DECIMAL(10,2) NOT NULL DEFAULT 0,
  bio TEXT NULL,
  CONSTRAINT fk_doctors_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE appointments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  doctor_id INT UNSIGNED NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  reason VARCHAR(1000) NULL,
  status ENUM('pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_appointments_patient FOREIGN KEY(patient_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_appointments_doctor FOREIGN KEY(doctor_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_doctor_slot(doctor_id,appointment_date,appointment_time,status),
  INDEX idx_patient_date(patient_id,appointment_date)
) ENGINE=InnoDB;

-- Demo accounts: all use password "password".
-- Password hashes are generated with PHP password_hash(PASSWORD_DEFAULT).
INSERT INTO users(name,email,phone,password,role) VALUES
('System Admin','admin@example.com','9999999999','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin'),
('Dr. Aditi Sharma','doctor@example.com','9876543210','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','doctor'),
('Rahul Verma','patient@example.com','9123456780','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','patient');

INSERT INTO doctors(user_id,specialization,qualification,experience_years,consultation_fee,bio)
SELECT id,'General Physician','MBBS, MD',8,500,'Focused on preventive care and general health consultations.'
FROM users WHERE email='doctor@example.com';
