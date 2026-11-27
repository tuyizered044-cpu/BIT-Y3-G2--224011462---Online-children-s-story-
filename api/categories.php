<?php
// api/categories.php
require_once __DIR__ . '/../app/models/Category.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $categories = array_map(static function ($category) {
        return [
            'category_id' => (int) $category['category_id'],
            'name' => $category['name'],
            'description' => $category['description'],
        ];
    }, (new Category())->getAll());

    echo json_encode(['categories' => $categories], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (PDOException $exception) {
    error_log('Category API failed: ' . $exception->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Categories could not be loaded.']);
}