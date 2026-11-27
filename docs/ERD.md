# Entity-Relationship Diagram (ERD)

## Entities & Relationships

1. **users**
   - `user_id` (PK)
   - `username`, `email` (Unique), `password_hash`, `role`, `created_at`
   - `link_code_hash`, `link_code_expires_at` (short-lived child-to-parent link code)
   
2. **categories**
   - `category_id` (PK)
   - `name` (Unique), `description`, `created_at`

3. **stories**
   - `story_id` (PK)
   - `author_id` (FK -> users.user_id)
   - `category_id` (FK -> categories.category_id)
   - `title`, `body`, `age_group`, `status`, `published_at`, `created_at`, `updated_at`
   - **Relationships:** One Writer (User) -> Many Stories. One Category -> Many Stories.

4. **readings**
   - `reading_id` (PK)
   - `story_id` (FK -> stories.story_id)
   - `user_id` (FK -> users.user_id)
   - `read_at`, `progress_pct`, `completed_at`
   - **Relationships:** One Child (User) -> Many Readings. One Story -> Many Readings.

5. **parent_child**
   - `relationship_id` (PK)
   - `parent_id` (FK -> users.user_id)
   - `child_id` (FK -> users.user_id)
   - `created_at`
   - **Relationships:** One Parent -> Many Children. One Child may be linked to multiple parents; each child creates a single-use code that expires after 15 minutes.