#!/usr/local/bin/php
<?php
require_once __DIR__ . '/../../../vendor/autoload.php'; // Include Composer's autoload for dotenv

use GuzzleHttp\Client;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration
$dbHost = $_ENV['DB_HOST'];
$dbName = $_ENV['DB_NAME'];
$dbUser = $_ENV['DB_USER'];
$dbPass = $_ENV['DB_PASS'];

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// HTTP client
$client = new Client(['base_uri' => 'http://localhost']); // Adjust base URI as needed

// Test data
$testData = [
    'volunteer' => [
        'form_type' => 'volunteer',
        'name' => 'Test Volunteer',
        'email' => 'volunteer@test.com',
        'phone' => '1234567890',
        'availability' => 'Weekends',
        'message' => 'I want to help!'
    ],
    'donate' => [
        'form_type' => 'donate',
        'name' => 'Test Donor',
        'email' => 'donor@test.com',
        'amount' => '100.00',
        'message' => 'Happy to contribute!'
    ],
    'contact' => [
        'form_type' => 'contact',
        'name' => 'Test Contact',
        'email' => 'contact@test.com',
        'subject' => 'Inquiry',
        'message' => 'I have a question.'
    ]
];

// Helper function to clean up test records
function cleanup($pdo, $table, $email) {
    $stmt = $pdo->prepare("DELETE FROM $table WHERE email = :email");
    $stmt->execute([':email' => $email]);
}

// Run tests
foreach ($testData as $type => $data) {
    echo "Testing $type form...\n";

    try {
        // Send POST request
        $response = $client->post('/path/to/your/script.php', [ // Adjust endpoint
            'json' => $data
        ]);
        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['success']) {
            echo "✔ $type form submitted successfully.\n";

            // Verify database record
            $table = $type === 'volunteer' ? 'volunteers' : ($type === 'donate' ? 'donations' : 'contacts');
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = :email");
            $stmt->execute([':email' => $data['email']]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($record) {
                echo "✔ Database record exists for $type.\n";

                // Clean up
                cleanup($pdo, $table, $data['email']);
                echo "✔ Cleanup successful for $type.\n";
            } else {
                echo "✘ Database record not found for $type.\n";
            }
        } else {
            echo "✘ $type form submission failed: " . $responseBody['message'] . "\n";
        }
    } catch (Exception $e) {
        echo "✘ Error testing $type form: " . $e->getMessage() . "\n";
    }

    echo "\n";
}

