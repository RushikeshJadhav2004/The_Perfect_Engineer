
-- Create the database
CREATE DATABASE IF NOT EXISTS final;
USE final;

-- Users table (Founders & Developers)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    bio TEXT NULL,
    skills VARCHAR(255) NULL,
    portfolio VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('founder', 'developer') NOT NULL,
    company_name VARCHAR(255) NULL,
    company_website VARCHAR(255) NULL,
    company_size VARCHAR(50) NULL,
    funding_stage VARCHAR(50) NULL,
    equity_range VARCHAR(50) NULL,
    salary_range VARCHAR(50) NULL,
    role_description TEXT NULL,
    required_tech_stack VARCHAR(255) NULL,
    experience_required VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE startup_ideas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    founder_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    company_name VARCHAR(255),
    company_website VARCHAR(255),
    company_size VARCHAR(50),
    equity_range VARCHAR(50),
    salary_range VARCHAR(50),
    role VARCHAR(100),
    detailed_idea TEXT,
    key_responsibilities TEXT,
    ideal_candidate_profile TEXT,
    tech_requirements TEXT,
    experience VARCHAR(50),
    industry VARCHAR(100),
    funding_stage VARCHAR(50),
    role_description TEXT,
     email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (founder_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE developers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    education VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) NULL,
    skills TEXT,
    role VARCHAR(50),
    experience VARCHAR(100),
    portfolio VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE developer_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    developer_id INT NOT NULL,
    idea_id INT NOT NULL,
    cover_letter TEXT,
    resume_link VARCHAR(255),
    contact_number VARCHAR(20),
    instagram VARCHAR(255),
    twitter VARCHAR(255),
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (developer_id) REFERENCES developers(id) ON DELETE CASCADE,
    FOREIGN KEY (idea_id) REFERENCES startup_ideas(id) ON DELETE CASCADE
);



-- Matches table to track connections between founders and developers
CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    founder_id INT NOT NULL,
    developer_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (founder_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (developer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Login activity tracking (optional)
CREATE TABLE login_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);



CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE, 
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);


-- CREATE TABLE saved_ideas (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     developer_id INT NOT NULL,
--     idea_id INT NOT NULL,
--     saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (developer_id) REFERENCES developers(id) ON DELETE CASCADE,
--     FOREIGN KEY (idea_id) REFERENCES startup_ideas(id) ON DELETE CASCADE
-- );

