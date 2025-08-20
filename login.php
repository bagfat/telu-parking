<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_user";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($password === $user['password']) {
        // Simpan nama lengkap ke dalam session
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        
        // Redirect ke home-page
        header("Location: home-page.php");
        exit();
    } else {
        echo "Password salah.";
    }
} else {
    echo "User tidak ditemukan.";
}
?>
