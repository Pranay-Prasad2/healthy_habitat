CREATE TABLE area (
    area_id INT AUTO_INCREMENT PRIMARY KEY,
    area_name VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    age INT,
    gender VARCHAR(10),
    interests TEXT,
    area_id INT,FOREIGN KEY (area_id) REFERENCES area(area_id) ON DELETE SET NULL
);

CREATE TABLE business(
    business_id INT AUTO_INCREMENT PRIMARY KEY,
    business_name VARCHAR(30) NOT NULL UNIQUE,
    business_email VARCHAR(100) NOT NULL UNIQUE,
    business_password VARCHAR(100) NOT NULL,
    business_description VARCHAR(500) NOT NULL,
    area_id INT,FOREIGN KEY (area_id) REFERENCES area(area_id) ON DELETE SET NULL

);

CREATE TABLE product (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL UNIQUE,
    product_desc TEXT,
    quantity INT NOT NULL,
    health_benefits TEXT,
    product_price DECIMAL(10,2) NOT NULL,
    business_id INT NOT NULL,
    product_likes INT DEFAULT 0,
    product_type ENUM('product', 'service') NOT NULL,
    FOREIGN KEY (business_id) REFERENCES business(business_id)
);
 
 CREATE TABLE vote (
    vote_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES product(product_id),
    UNIQUE (user_id, product_id)
);

CREATE TABLE admin(
    admin_name VARCHAR(30) NOT NULL PRIMARY KEY,
    admin_password VARCHAR(30) NOT NULL
);

INSERT INTO admin VALUES ("team7@herts.ac.uk","123456");

INSERT INTO area (area_name) VALUES
('London'),
('Manchester'),
('Birmingham'),
('Leeds'),
('Glasgow'),
('Liverpool'),
('Bristol'),
('Sheffield'),
('Edinburgh'),
('Cardiff');


-- Inserting some businesses into the business table
INSERT INTO business (business_name, business_email, business_password, business_description, area_id) 
VALUES 
('GreenLife Wellness Center', 'contact@greenlifewellness.com', 'password123', 'A wellness center focused on holistic health, yoga, and fitness.', 1),
('Purely Organic Foods', 'contact@purelyorganic.com', 'password123', 'An organic food business offering fresh, farm-to-table produce.', 2),
('Fit4Life Gym', 'info@fit4lifegym.com', 'password123', 'A modern gym with state-of-the-art equipment and personalized fitness plans.', 3),
('Healthy Habits Juice Bar', 'info@healthyhabitsjuice.com', 'password123', 'A juice bar specializing in fresh and nutritious smoothies and juices.', 4),
('Eco-Friendly Lifestyle', 'support@ecolifestyle.com', 'password123', 'An eco-friendly lifestyle store offering organic products and services.', 5);

-- Inserting products for "GreenLife Wellness Center"
INSERT INTO product (product_name, product_desc, quantity, health_benefits, product_price, business_id, product_type)
VALUES 
('Yoga Mat', 'A high-quality mat for yoga practitioners.', 50, 'Improves flexibility, reduces stress', 20.00, 1, 'product'),
('Meditation Cushion', 'Cushion for comfortable meditation sessions.', 30, 'Helps with posture during meditation, reduces discomfort', 35.00, 1, 'product'),
('Personalized Yoga Sessions', 'Private yoga lessons tailored to individual needs.', 10, 'Improves mental and physical health, reduces anxiety', 50.00, 1, 'service');

-- Inserting products for "Purely Organic Foods"
INSERT INTO product (product_name, product_desc, quantity, health_benefits, product_price, business_id, product_type)
VALUES 
('Organic Apples', 'Fresh, locally grown organic apples.', 100, 'Rich in fiber, supports heart health', 3.50, 2, 'product'),
('Organic Almonds', 'High-quality organic almonds, packed with nutrients.', 200, 'Rich in healthy fats, supports brain health', 7.00, 2, 'product'),
('Nutrition Consultation', 'Personalized nutrition plans to optimize health.', 15, 'Improves overall health and wellness', 60.00, 2, 'service');

-- Inserting products for "Fit4Life Gym"
INSERT INTO product (product_name, product_desc, quantity, health_benefits, product_price, business_id, product_type)
VALUES 
('Monthly Gym Membership', 'Access to gym facilities and group fitness classes.', 100, 'Improves cardiovascular health, builds strength', 40.00, 3, 'service'),
('Personal Trainer Session', 'One-on-one fitness training with a certified personal trainer.', 50, 'Helps with weight loss, muscle building', 60.00, 3, 'service'),
('Protein Powder', 'High-quality protein powder for muscle recovery.', 150, 'Supports muscle growth and recovery', 25.00, 3, 'product');

-- Inserting products for "Healthy Habits Juice Bar"
INSERT INTO product (product_name, product_desc, quantity, health_benefits, product_price, business_id, product_type)
VALUES 
('Green Detox Smoothie', 'A smoothie made with kale, spinach, and lemon.', 100, 'Detoxifies the body, boosts energy', 6.00, 4, 'product'),
('Acai Bowl', 'A bowl made with acai berries, granola, and fresh fruits.', 80, 'Rich in antioxidants, boosts metabolism', 8.00, 4, 'product'),
('Juice Cleanse Program', 'A 3-day juice cleanse program designed to detoxify the body.', 20, 'Helps with detox, boosts immune system', 45.00, 4, 'service');

-- Inserting products for "Eco-Friendly Lifestyle"
INSERT INTO product (product_name, product_desc, quantity, health_benefits, product_price, business_id, product_type)
VALUES 
('Bamboo Toothbrush', 'A biodegradable toothbrush made from bamboo.', 200, 'Eco-friendly, reduces plastic waste', 5.00, 5, 'product'),
('Reusable Shopping Bags', 'Durable, eco-friendly shopping bags made from recycled material.', 300, 'Reduces plastic waste, eco-friendly', 2.50, 5, 'product'),
('Sustainable Living Consultation', 'Consulting services for a more sustainable lifestyle.', 10, 'Promotes environmental awareness and sustainability', 70.00, 5, 'service');
