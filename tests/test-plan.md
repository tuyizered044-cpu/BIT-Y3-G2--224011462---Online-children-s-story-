# Manual smoke test plan

1. Import the schema and sample data, then open the `public/` URL. Confirm the landing page and featured stories render.
2. Browse stories; verify title search, category, age group, and pagination retain their selected filters.
3. Sign in as a writer. Create a draft, publish it, edit it, and delete a test story. Verify a writer cannot manage another writer's story.
4. Sign in as a child. Read a published story and verify progress is saved and shown on the child dashboard.
5. Sign in as a child and generate a link code; as a parent, link using that code and verify the child's reading history is visible. Verify an expired or reused code is rejected.
6. Submit profile and category forms with missing or invalid values; verify validation messages are shown and output remains escaped.
7. Check the main pages at desktop and mobile widths and verify form controls can be reached with a keyboard.