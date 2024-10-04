CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    balance DECIMAL(10, 2) DEFAULT 10.00, 
    CONSTRAINT chk_phone_number_country CHECK (
        phone_number REGEXP '^\\+1[0-9]{10}$' 
        OR phone_number REGEXP '^\\+43[0-9]{9,13}$' 
        OR phone_number REGEXP '^\\+44[0-9]{10}$' 
    ),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    video_url VARCHAR(255) NOT NULL,
    watched_at DATE NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_daily_limit CHECK (
        (SELECT COUNT(*) 
         FROM user_videos 
         WHERE user_id = user_videos.user_id 
         AND watched_at = CURDATE()) <= 3
    )
);

CREATE TABLE manual_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_address VARCHAR(255) NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

