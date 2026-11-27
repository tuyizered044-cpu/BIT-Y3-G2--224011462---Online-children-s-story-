-- Run once against the existing childrens_story_platform database after selecting it with USE.
ALTER TABLE users MODIFY role ENUM('admin', 'writer', 'child', 'parent') NOT NULL;

ALTER TABLE users
    ADD COLUMN link_code_hash CHAR(64) NULL,
    ADD COLUMN link_code_expires_at DATETIME NULL,
    ADD UNIQUE KEY unique_child_link_code (link_code_hash);

ALTER TABLE stories
    ADD INDEX idx_stories_catalog (status, published_at, story_id),
    ADD INDEX idx_stories_category_age (category_id, age_group);

ALTER TABLE readings
    ADD INDEX idx_readings_user_activity (user_id, read_at);

CREATE TABLE IF NOT EXISTS parent_child (
    relationship_id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NOT NULL,
    child_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_relationship (parent_id, child_id),
    FOREIGN KEY (parent_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES users(user_id) ON DELETE CASCADE
);
