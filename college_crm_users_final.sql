USE college_crm;

-- Clear existing HODs and staff
DELETE FROM users WHERE role IN ('hod','staff');

-- ═══════════════════════════════════════════════════════════
-- 18 HODs — Password = admin123
-- ═══════════════════════════════════════════════════════════
INSERT INTO users (name, email, phone, password, role, department) VALUES
('Dr. Ramesh Kumar',  'hod.cse@college.edu',    '9888800001','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E Computer Science and Engineering'),
('Dr. Suresh M',      'hod.ece@college.edu',    '9888800002','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E Electronics and Communication Engineering'),
('Dr. Kavitha R',     'hod.mech@college.edu',   '9888800003','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E Mechanical Engineering'),
('Dr. Anand S',       'hod.civil@college.edu',  '9888800004','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E Civil Engineering'),
('Dr. Meena T',       'hod.eee@college.edu',    '9888800005','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E Electrical and Electronics Engineering'),
('Dr. Ravi P',        'hod.aiml@college.edu',   '9888800006','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E CSE (AI and Machine Learning)'),
('Dr. Lavanya K',     'hod.cyber@college.edu',  '9888800007','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E CSE (Cyber Security)'),
('Dr. Preethi N',     'hod.biomed@college.edu', '9888800008','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E Bio Medical Engineering'),
('Dr. Senthil A',     'hod.aero@college.edu',   '9888800009','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.E Aeronautical Engineering'),
('Dr. Anbu R',        'hod.biotech@college.edu','9888800010','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.Tech Bio Technology'),
('Dr. Sindhu M',      'hod.aids@college.edu',   '9888800011','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.Tech AI and Data Science'),
('Dr. Karthik V',     'hod.chem@college.edu',   '9888800012','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.Tech Chemical Engineering'),
('Dr. Divya S',       'hod.csbs@college.edu',   '9888800013','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.Tech Computer Science and Business Systems'),
('Dr. Murugan K',     'hod.it@college.edu',     '9888800014','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.Tech Information Technology'),
('Dr. Rekha J',       'hod.arch@college.edu',   '9888800015','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','B.Arch Architecture'),
('Dr. Priya Nair',    'hod.mca@college.edu',    '9888800016','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','MCA'),
('Dr. Vignesh R',     'hod.mba@college.edu',    '9888800017','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','MBA'),
('Dr. Jayanthi S',    'hod.me@college.edu',     '9888800018','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','hod','M.E');

-- ═══════════════════════════════════════════════════════════
-- 2 STAFF per department | Email: staff1.dept@college.edu
-- Password = admin123
-- ═══════════════════════════════════════════════════════════
INSERT INTO users (name, email, phone, password, role, department, staff_level) VALUES

-- B.E CSE
('Arjun S',      'staff1.cse@college.edu',    '9777000001','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Computer Science and Engineering',1),
('Meena R',      'staff2.cse@college.edu',    '9777000002','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Computer Science and Engineering',2),

-- B.E ECE
('Preethi M',    'staff1.ece@college.edu',    '9777000003','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Electronics and Communication Engineering',1),
('Sanjay R',     'staff2.ece@college.edu',    '9777000004','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Electronics and Communication Engineering',2),

-- B.E Mechanical
('Kiran S',      'staff1.mech@college.edu',   '9777000005','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Mechanical Engineering',1),
('Deepa M',      'staff2.mech@college.edu',   '9777000006','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Mechanical Engineering',2),

-- B.E Civil
('Ranjith K',    'staff1.civil@college.edu',  '9777000007','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Civil Engineering',1),
('Sowmya T',     'staff2.civil@college.edu',  '9777000008','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Civil Engineering',2),

-- B.E EEE
('Manju S',      'staff1.eee@college.edu',    '9777000009','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Electrical and Electronics Engineering',1),
('Vijay P',      'staff2.eee@college.edu',    '9777000010','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Electrical and Electronics Engineering',2),

-- B.E AI & ML
('Harish M',     'staff1.aiml@college.edu',   '9777000011','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E CSE (AI and Machine Learning)',1),
('Sindhu R',     'staff2.aiml@college.edu',   '9777000012','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E CSE (AI and Machine Learning)',2),

-- B.E Cyber Security
('Ananya K',     'staff1.cyber@college.edu',  '9777000013','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E CSE (Cyber Security)',1),
('Rohith S',     'staff2.cyber@college.edu',  '9777000014','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E CSE (Cyber Security)',2),

-- B.E Bio Medical
('Lakshmi P',    'staff1.biomed@college.edu', '9777000015','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Bio Medical Engineering',1),
('Surya V',      'staff2.biomed@college.edu', '9777000016','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Bio Medical Engineering',2),

-- B.E Aeronautical
('Manoj K',      'staff1.aero@college.edu',   '9777000017','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Aeronautical Engineering',1),
('Rathi S',      'staff2.aero@college.edu',   '9777000018','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.E Aeronautical Engineering',2),

-- B.Tech Bio Technology
('Anbu S',       'staff1.biotech@college.edu','9777000019','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech Bio Technology',1),
('Rekha M',      'staff2.biotech@college.edu','9777000020','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech Bio Technology',2),

-- B.Tech AI & Data Science
('Naveena S',    'staff1.aids@college.edu',   '9777000021','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech AI and Data Science',1),
('Prasad M',     'staff2.aids@college.edu',   '9777000022','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech AI and Data Science',2),

-- B.Tech Chemical
('Sathya K',     'staff1.chem@college.edu',   '9777000023','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech Chemical Engineering',1),
('Bharath M',    'staff2.chem@college.edu',   '9777000024','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech Chemical Engineering',2),

-- B.Tech CSBS
('Dinesh V',     'staff1.csbs@college.edu',   '9777000025','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech Computer Science and Business Systems',1),
('Kavya S',      'staff2.csbs@college.edu',   '9777000026','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech Computer Science and Business Systems',2),

-- B.Tech IT
('Sruthi N',     'staff1.it@college.edu',     '9777000027','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech Information Technology',1),
('Abishek K',    'staff2.it@college.edu',     '9777000028','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Tech Information Technology',2),

-- B.Arch Architecture
('Priya J',      'staff1.arch@college.edu',   '9777000029','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Arch Architecture',1),
('Rahul S',      'staff2.arch@college.edu',   '9777000030','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','B.Arch Architecture',2),

-- MCA
('Anitha K',     'staff1.mca@college.edu',    '9777000031','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','MCA',1),
('Ravi M',       'staff2.mca@college.edu',    '9777000032','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','MCA',2),

-- MBA
('Tamilarasi M', 'staff1.mba@college.edu',    '9777000033','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','MBA',1),
('Ganesh P',     'staff2.mba@college.edu',    '9777000034','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','MBA',2),

-- M.E
('Murugan V',    'staff1.me@college.edu',     '9777000035','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','M.E',1),
('Padma S',      'staff2.me@college.edu',     '9777000036','$2y$10$TKh8H1.PfznFBPNuq8pHfu/oJduJmzXTPXJ4tCUCaIh5fJLhpZSgW','staff','M.E',2);

