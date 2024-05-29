<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "Doh"; // MySQL 사용자 이름 변경
$password = "1234"; // MySQL 비밀번호 변경
$dbname = "LectureNotes";

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST로 전달된 학과 값을 가져옴
$department = $_POST['department'];

// 해당 단과대에 속하는 학과 목록을 데이터베이스에서 가져옴

$sql = "SELECT Courses.course_name FROM Courses LEFT OUTER JOIN Departments ON Courses.department_id = Departments.department_id WHERE Departments.department_name = '$department'";
$result = $conn->query($sql);

echo "<option value=''>선택하세요</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='" . $row['course_name'] . "'>" . $row['course_name'] . "</option>";
}

// 데이터베이스 연결 닫기
$conn->close();
?>
