# System Architecture

StoriesDream uses a lightweight MVC-inspired structure:

- `public/index.php` is the front controller and dispatches page and API routes.
- `app/controllers/` coordinates requests, validation, models, and views.
- `app/models/` contains PDO-backed database operations.
- `app/views/` renders HTML with shared header, navigation, and footer includes.
- `api/` contains JSON search/category endpoints and the reading/parent-child actions.
- `public/assets/` contains the responsive stylesheet and vanilla JavaScript.
- `database/` contains the MySQL schema and sample data.

Page requests flow from the front controller to a controller, then a model when data is needed, and finally a view. Mutating forms and the reading-progress endpoint require a session CSRF token. Parent-child links use hashed, one-time codes with a short expiry. Stories are paginated in the catalog; search results use the same filters and page size.