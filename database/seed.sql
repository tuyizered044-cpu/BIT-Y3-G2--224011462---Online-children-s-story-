-- database/seed.sql
USE childrens_story_platform;

-- Passwords are all 'password123' hashed with password_hash()
INSERT INTO users (username, email, password_hash, role) VALUES
('StoryWriter', 'writer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'writer'),
('ParentUser', 'parent@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'parent'),
('ChildUser', 'child@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'child');

INSERT INTO categories (name, description) VALUES
('Adventure', 'Exciting journeys and quests'),
('Animals', 'Stories about furry, feathered, and scaly friends'),
('Fairy Tales', 'Magical stories with princes, princesses, and witches'),
('Friendship', 'Stories about making friends and helping others');

INSERT INTO stories (author_id, category_id, title, body, age_group, status, published_at) VALUES
(1, 1, 'The Brave Little Rabbit', 'Once upon a time, there was a little rabbit who was not afraid of anything. He lived in a meadow...', '6-8', 'published', NOW()),
(1, 3, 'A Star for Mia', 'Mia loved to look at the stars. One night, she saw a star fall from the sky...', '3-5', 'published', NOW()),
(1, 2, 'The Forest Friends', 'In the heart of the green forest, a fox, a deer, and a hedgehog were the best of friends...', '6-8', 'published', NOW());

INSERT INTO parent_child (parent_id, child_id) VALUES
(2, 3);

INSERT INTO readings (story_id, user_id, progress_pct, completed_at) VALUES
(1, 3, 100, NOW()),
(2, 3, 50, NULL);