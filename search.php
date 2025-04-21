<?php
// Start the session at the very beginning before any output
session_start();

// Set user_id for testing and make sure it persists
$_SESSION['user_id'] = 1;

$debug = [
    "stage" => "initial",
    "session" => isset($_SESSION['user_id']) ? "Session user_id exists: " . $_SESSION['user_id'] : "No session user_id",
    "session_id" => session_id()
];

// Authentication check
if (!isset($_SESSION['user_id'])) {
    $debug["stage"] = "auth_failed";
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized", "debug" => $debug]);
    exit();
}

// Include database connection
require_once 'config.php';
$debug["stage"] = "after_config";

// Check connection
if ($conn->connect_error) {
    $debug["stage"] = "connection_failed";
    $debug["error"] = $conn->connect_error;
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed", "debug" => $debug]);
    exit();
}

// Function to expand query semantically
function expandQuery($query) {
    $expansions = [
        "AI" => ["artificial intelligence", "machine learning", "neural networks"],
        "ML" => ["machine learning", "deep learning", "AI"],
        "robotics" => ["automation", "robots", "mechanical systems"],
        "data" => ["big data", "data science", "data analysis"]
    ];
    $query = strtolower($query);
    $expandedTerms = [$query];
    foreach ($expansions as $keyword => $synonyms) {
        if (strpos($query, strtolower($keyword)) !== false) {
            $expandedTerms = array_merge($expandedTerms, $synonyms);
        }
    }
    return array_unique($expandedTerms);
}

// Validate and sanitize the search query
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
if (empty($query)) {
    $debug["stage"] = "empty_query";
    http_response_code(400);
    echo json_encode(["error" => "Empty search query", "debug" => $debug]);
    exit();
}

$debug["query"] = $query;
$debug["stage"] = "query_validated";

// Expand query semantically
$expandedTerms = expandQuery($query);
$debug["expanded_terms"] = $expandedTerms;
$debug["stage"] = "query_expanded";

// Build SQL dynamically
$sql = "SELECT id, title, snippet FROM research_data WHERE ";
$conditions = [];
$params = [];
$types = '';

foreach ($expandedTerms as $term) {
    $conditions[] = "(title LIKE ? OR snippet LIKE ?)";
    $likeTerm = "%" . $term . "%";
    $params[] = $likeTerm;
    $params[] = $likeTerm;
    $types .= 'ss';
}

$sql .= implode(" OR ", $conditions) . " LIMIT 10";
$debug["sql"] = $sql;
$debug["params"] = $params;
$debug["types"] = $types;
$debug["stage"] = "sql_built";

// Prepare statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    $debug["stage"] = "prepare_failed";
    $debug["sql_error"] = $conn->error;
    http_response_code(500);
    echo json_encode([
        "error" => "Failed to prepare statement",
        "sql_error" => $conn->error,
        "sql" => $sql,
        "debug" => $debug
    ]);
    exit();
}
$debug["stage"] = "statement_prepared";

// Bind parameters dynamically
if (!empty($params)) {
    try {
        $stmt->bind_param($types, ...$params);
        $debug["stage"] = "params_bound";
    } catch (Exception $e) {
        $debug["stage"] = "bind_failed";
        $debug["bind_error"] = $e->getMessage();
        http_response_code(500);
        echo json_encode([
            "error" => "Failed to bind parameters",
            "bind_error" => $e->getMessage(),
            "debug" => $debug
        ]);
        exit();
    }
}

// Execute and fetch results
try {
    if ($stmt->execute()) {
        $debug["stage"] = "executed";
        $result = $stmt->get_result();
        $results = [];
        while ($row = $result->fetch_assoc()) {
            $results[] = [
                "id" => $row['id'],
                "title" => $row['title'],
                "snippet" => $row['snippet']
            ];
        }
        $debug["result_count"] = count($results);
        echo json_encode($results);
    } else {
        $debug["stage"] = "execute_failed";
        $debug["stmt_error"] = $stmt->error;
        http_response_code(500);
        echo json_encode([
            "error" => "Failed to execute query",
            "stmt_error" => $stmt->error,
            "debug" => $debug
        ]);
    }
} catch (Exception $e) {
    $debug["stage"] = "exception";
    $debug["exception"] = $e->getMessage();
    http_response_code(500);
    echo json_encode([
        "error" => "Exception during execution",
        "exception" => $e->getMessage(),
        "debug" => $debug
    ]);
}

$stmt->close();
$conn->close();
?>