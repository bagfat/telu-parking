<?php
// Improved get_parking_data.php dengan error handling yang lebih baik

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // CORS untuk development

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0); // Jangan tampilkan error di output

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_user';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }
    
    $parking = isset($_GET['name']) ? $_GET['name'] : 'Parkiran TULT';
    $date = date('Y-m-d');
    
    $stmt = $conn->prepare("
        SELECT hour, percentage, count_motor
        FROM parking_statistics
        WHERE parking_name = ? AND date = ?
        ORDER BY hour ASC
    ");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ss", $parking, $date);
    
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $data = [];
    
    while ($row = $result->fetch_assoc()) {
        $h = (int)$row['hour'];
        $time = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
        $data[] = [
            'hour'  => $h,
            'time'  => $time,
            'value' => (int)$row['percentage'],
            'count' => (int)$row['count_motor'],
            'label' => $time
        ];
    }
    
    $stmt->close();
    $conn->close();
    
    // Return data atau array kosong jika tidak ada data
    echo json_encode($data);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'debug' => [
            'parking' => $parking ?? 'undefined',
            'date' => $date ?? 'undefined'
        ]
    ]);
}
?>