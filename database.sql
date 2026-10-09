CREATE DATABASE IF NOT EXISTS college_crm;
USE college_crm;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS lead_activity;
DROP TABLE IF EXISTS call_history;
DROP TABLE IF EXISTS leads;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(15),
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','hod','staff') NOT NULL,
  department VARCHAR(150),
  staff_level TINYINT DEFAULT 1,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(15) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  qualification VARCHAR(150),
  course_level ENUM('UG','PG') NOT NULL,
  department_applied VARCHAR(200),
  source ENUM('Webinar','Staff Call','Internet','Social Media','Friend Referral','Other') NOT NULL,
  status ENUM('In Progress','Admitted','Rejected','Wrong Number','Not Reachable') DEFAULT 'In Progress',
  assigned_hod INT,
  assigned_staff INT,
  reminder_date DATE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (assigned_hod) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (assigned_staff) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE call_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lead_id INT NOT NULL,
  staff_id INT NOT NULL,
  call_status ENUM('Answered','Not Reachable','Switched Off','Wrong Number','Busy','Callback Requested') NOT NULL,
  remarks TEXT,
  called_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
  FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE lead_activity (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lead_id INT NOT NULL,
  user_id INT,
  action TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- All passwords = admin123
INSERT INTO users (name,email,phone,password,role) VALUES
('Admin User','admin@college.edu','9999999999','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','admin');

INSERT INTO users (name,email,phone,password,role,department) VALUES
('Dr. Ramesh Kumar','hod.cse@college.edu','9888888881','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','Computer Science'),
('Dr. Priya Nair','hod.pg@college.edu','9888888882','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','PG Department');

INSERT INTO users (name,email,phone,password,role,department,staff_level) VALUES
('Arjun S','arjun@college.edu','9777000001','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','Computer Science',1),
('Meena R','meena@college.edu','9777000002','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','Computer Science',2),
('Vikram T','vikram@college.edu','9777000003','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','Computer Science',3),
('Lakshmi P','lakshmi@college.edu','9777000004','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','Computer Science',4),
('Ravi M','ravi@college.edu','9777000005','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','Computer Science',5),
('Anitha K','anitha@college.edu','9777000006','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','PG Department',1),
('Dinesh V','dinesh@college.edu','9777000007','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','PG Department',2),
('Kavya S','kavya@college.edu','9777000008','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','PG Department',3),
('Mani R','mani@college.edu','9777000009','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','PG Department',4),
('Sruthi N','sruthi@college.edu','9777000010','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','PG Department',5);

INSERT INTO leads (name,phone,email,qualification,course_level,department_applied,source,status,assigned_hod,assigned_staff) VALUES
('Karthik M','9600001111','karthik@gmail.com','B.Sc Computer Science','PG','MCA','Webinar','In Progress',2,4),
('Divya S','9600002222','divya@gmail.com','12th Science','UG','B.E Computer Science and Engineering','Staff Call','Admitted',2,5),
('Murugan K','9600003333','murugan@gmail.com','B.Com','PG','MBA','Internet','Rejected',3,9),
('Ananya P','9600004444','ananya@gmail.com','12th Bio','UG','B.E Bio Medical Engineering','Social Media','In Progress',2,4),
('Surya V','9600005555','surya@gmail.com','B.E Mechanical','PG','M.E','Friend Referral','In Progress',2,7);

INSERT INTO call_history (lead_id,staff_id,call_status,remarks,called_at) VALUES
(1,4,'Answered','Student interested in MCA. Will visit campus next week.','2025-03-10 10:30:00'),
(1,4,'Not Reachable','Phone went to voicemail. Will try again tomorrow.','2025-03-11 11:00:00'),
(1,7,'Callback Requested','Student said call back after 4 PM.','2025-03-12 09:15:00'),
(2,5,'Answered','Confirmed admission. Fee paid.','2025-03-08 14:00:00'),
(3,9,'Answered','Not interested. Joined another college.','2025-03-09 16:30:00');