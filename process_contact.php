<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log function to help with debugging
function logError($message) {
    // Create logs directory if it doesn't exist
    if (!file_exists('logs')) {
        mkdir('logs', 0777, true);
    }
    
    // Write to log file
    file_put_contents('logs/error_log.txt', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

try {
    // Database connection parameters
    $servername = "localhost";
    $username = "root"; // Default XAMPP username
    $password = ""; // Default XAMPP password (empty)
    $dbname = "dashboard";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    logError("Database connection successful");

    // Process form data if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Log received POST data
        logError("POST data received: " . print_r($_POST, true));
        
        // Sanitize and get form data
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
        $message = htmlspecialchars(trim($_POST['message'] ?? ''));
        $created_at = date('Y-m-d H:i:s'); // Current date and time
        
        logError("Sanitized data: name=$name, email=$email, subject=$subject, message=$message");
        
        // Basic validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            throw new Exception("All fields are required!");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format!");
        }
        
        // Verify table structure
        $tableCheck = $conn->query("DESCRIBE contact_messages");
        if (!$tableCheck) {
            throw new Exception("Error checking table structure: " . $conn->error);
        }
        
        logError("Table structure verified");
        
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        logError("Prepare statement created");
        
        $stmt->bind_param("sssss", $name, $email, $subject, $message, $created_at);
        
        logError("Parameters bound");
        
        if ($stmt->execute()) {
            logError("Insert successful");
            $_SESSION['contact_message'] = "Thank you! Your message has been sent successfully.";
            $_SESSION['contact_status'] = "success";
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
    } else {
        // If someone tries to access this file directly without submitting the form
        header("Location: contact.php");
        exit();
    }
} catch (Exception $e) {
    // Log the exception
    logError("Exception: " . $e->getMessage());
    
    // Set error message in session
    $_SESSION['contact_message'] = "Error: " . $e->getMessage();
    $_SESSION['contact_status'] = "error";
} finally {
    // Close the connection if it exists
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
        logError("Database connection closed");
    }
    
    // Redirect back to the contact page
    header("Location: contact.php");
    exit();
}
?>