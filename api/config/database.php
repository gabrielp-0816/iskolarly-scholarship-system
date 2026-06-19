<?php
// Supabase PostgreSQL connection
// All values must come from environment variables. No hardcoded fallbacks:
// if a var is missing, the app should fail loudly instead of silently
// connecting to leftover/dev credentials.
$required = ['SUPABASE_HOST', 'SUPABASE_DB', 'SUPABASE_USER', 'SUPABASE_PASSWORD', 'SUPABASE_PORT'];
$missing = [];
foreach ($required as $var) {
    if (getenv($var) === false) {
        $missing[] = $var;
    }
}

if (!empty($missing)) {
    error_log('Missing required database environment variables: ' . implode(', ', $missing));
    http_response_code(500);
    echo json_encode(['error' => 'Server is misconfigured. Missing database environment variables.']);
    exit;
}

$host = getenv('SUPABASE_HOST');
$dbname = getenv('SUPABASE_DB');
$username = getenv('SUPABASE_USER');
$password = getenv('SUPABASE_PASSWORD');
$port = getenv('SUPABASE_PORT');

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_TIMEOUT => 30,
        ]
    );
} catch (PDOException $e) {
    error_log("Supabase connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed. Please try again later.']);
    exit;
}
?>