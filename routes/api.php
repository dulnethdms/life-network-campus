<?php

header('Content-Type: application/json; charset=utf-8');

$json = [
    'success' => true,
    'message' => 'Life Network Campus API is ready for phase 3 development.',
    'data' => [
        'name' => 'Life Network Campus',
        'status' => 'foundation-ready',
    ],
];

echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
