# StoriesDream User Guide

## Browse and read

Open **Stories** to browse published stories. Filter by title, category, or age group; the list updates as filters change. Select **Read** to open a story. A signed-in child account saves reading progress as the reader moves through the page.

## Create and manage stories

Register or sign in as a **Writer**, then open the dashboard. Use **Create New Story** to add a title, category, age group, story text, and draft/published status. Published stories appear in the public catalog. Writers can edit, publish, and delete only their own stories.

## Track a child's reading

Sign in as a **Child** to see recent reading activity and recommendations. Generate a one-time link code from the child dashboard and share it with a parent; the code expires after 15 minutes. A parent enters the code on their dashboard, and then can see that child's reading history and completion totals.

## Local setup

For a new install, import `database/schema.sql` and `database/seed.sql` into the MySQL server used by XAMPP. For a database created from an earlier version, select it and run `database/migrations/001_platform_improvements.sql` once. Database host, database name, and credentials are configured in `config/database.php`.