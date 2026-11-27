# Focused test cases

| Area | Action | Expected result |
| --- | --- | --- |
| Landing page | Open the public home route with seeded data | Shows featured published stories and category links |
| Story access | Open a draft as an unauthenticated user | Draft is not rendered; user is redirected to the catalog |
| Story ownership | Edit or delete a story as a different writer | Request is denied and the story remains unchanged |
| Story catalog | Search using title, category, and age filters | Only published matching stories appear; page links preserve filters |
| State-changing request | Submit delete/publish without a valid CSRF token | No state change is made |
| Reading progress | Submit progress outside 0-100 or invalid JSON | API returns a client error and stores no reading |
| Reading progress | Scroll through a published story while signed in as a child | Progress is persisted and completion is recorded at 100% |
| Parent linking | A child generates a code; parent submits it before expiry | Relationship is saved and reading history appears |
| Parent linking | Submit an expired or already-used code | Request is rejected; no relationship is added |
| Responsive UI | Visit home, catalog, and forms at mobile width | Layout fits the viewport and controls remain keyboard accessible |