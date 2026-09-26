CREATE DATABASE IF NOT EXISTS recipe_book;
USE recipe_book;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Recipes Table
CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    cooking_time INT NOT NULL,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?auto=format&fit=crop&w=600&q=80',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Data (Default Admin/User Recipe)
INSERT INTO users (id, username, email, password) VALUES 
(1, 'chef_john', 'john@flavorforge.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe112.5O.m.Xq1z9G5g6M65e4Y8B3n6L.'); -- password: password123

INSERT INTO recipes (user_id, title, category, cooking_time, ingredients, instructions, image_url) VALUES
(1, 'Creamy Garlic Tuscan Chicken', 'Dinner', 30, 'Chicken breasts, Garlic, Heavy cream, Sun-dried tomatoes, Spinach, Parmesan cheese', '1. Sear chicken until golden.\n2. Fry garlic and sun-dried tomatoes.\n3. Add cream and parmesan, bring to simmer.\n4. Stir in spinach and return chicken.', 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=600&q=80'),
(1, 'Avocado Toast with Poached Egg', 'Breakfast', 15, 'Sourdough bread, Ripe Avocado, Eggs, Chili flakes, Salt and Pepper', '1. Toast sourdough slice.\n2. Mash avocado with salt and pepper.\n3. Poach eggs in simmering water.\n4. Assemble and sprinkle chili flakes.', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=600&q=80'),
(1, 'Berry Blast Smoothie Bowl', 'Dessert', 10, 'Frozen berries, Banana, Almond milk, Chia seeds, Granola', '1. Blend frozen berries, banana, and milk until thick.\n2. Pour into a bowl.\n3. Top with granola and chia seeds.', 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=600&q=80');