<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$db_username = "Doh"; // MySQL 사용자 이름 변경
$db_password = "1234"; // MySQL 비밀번호 변경
$dbname = "LectureNotes";

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$college = $_POST['college'];

$sql = "SELECT department FROM Departments WHERE faculty='$college'";
$result = $conn->query($sql);

echo "<option value=''>선택하세요</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='" . $row['department'] . "'>" . $row['department'] . "</option>";
}

// 데이터베이스 연결 닫기
$conn->close();
?>
