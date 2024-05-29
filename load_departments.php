<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "Doh"; // 사용자 이름 입력
$password = "1234"; // 비밀번호 입력
$dbname = "LectureNotes";

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST로 전달된 단과대 값을 가져옴
$faculty = $_POST['faculty'];

// 해당 단과대에 속하는 학과 목록을 데이터베이스에서 가져옴
$sql = "SELECT department_name FROM Departments WHERE faculty='$faculty'";
$result = $conn->query($sql);

// 학과 목록을 옵션 형식으로 반환
$options = "<option value=''>선택하세요</option>";
while ($row = $result->fetch_assoc()) {
    $options .= "<option value='" . $row['department_name'] . "'>" . $row['department_name'] . "</option>";
}

echo $options;

// 데이터베이스 연결 닫기
$conn->close();
?>
