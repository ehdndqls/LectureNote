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

// Departments 테이블 내용 출력
$sql = "SELECT * FROM Departments";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>department_id</th><th>department_name</th><th>faculty</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["department_id"] . "</td><td>" . $row["department_name"] . "</td><td>" . $row["faculty"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "테이블이 비어 있습니다.";
}

// 데이터베이스 연결 닫기
$conn->close();
?>
