<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];

    // Şifre validasyonu
    if (strlen($password) < 8) {
        die("Şifre en az 8 karakter olmalıdır.");
    }

    if ($password !== $confirmPassword) {
        die("Şifreler eşleşmiyor.");
    }

    // Şifreyi hashleyin
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // E-posta kontrolü
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        die("Bu e-posta adresi zaten kayıtlı.");
    }

    // Veritabanına ekleme
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, birthdate, gender) 
                            VALUES (:first_name, :last_name, :email, :password, :birthdate, :gender)");
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':birthdate', $birthdate);
    $stmt->bindParam(':gender', $gender);
    $stmt->execute();

    echo "Kayıt başarıyla tamamlandı.";
} else {
    http_response_code(405);
    echo "Bu sayfa yalnızca POST isteklerini kabul eder.";
}
?>
