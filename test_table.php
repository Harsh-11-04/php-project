<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Contact Messages Table Test</h1>";

try {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dashboard";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "<p style='color:green'>Connected to database successfully!</p>";
    
    // Check table structure
    $result = $conn->query("DESCRIBE contact_messages");
    
    if (!$result) {
        throw new Exception("Error describing table: " . $conn->error);
    }
    
    echo "<p style='color:green'>Table exists! Structure:</p>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Check for any existing records
    $result = $conn->query("SELECT COUNT(*) as count FROM contact_messages");
    $row = $result->fetch_assoc();
    echo "<p>Current record count: " . $row['count'] . "</p>";
    
    // Test insert (commented out for safety)
    /*
    $testName = "Test User";
    $testEmail = "test@example.com";
    $testSubject = "Test Subject";
    $testMessage = "This is a test message.";
    $testDate = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $testName, $testEmail, $testSubject, $testMessage, $testDate);
    
    if ($stmt->execute()) {
        echo "<p style='color:green'>Test insert successful!</p>";
    } else {
        echo "<p style='color:red'>Test insert failed: " . $stmt->error . "</p>";
    }
    
    $stmt->close();
    */
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>