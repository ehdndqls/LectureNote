<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department List</title>
</head>
<body>
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

    // 과목 목록 가져오기
    $departments_sql = "SELECT * FROM Departments";
    $departments_result = $conn->query($departments_sql);

    // 출력
    if ($departments_result->num_rows > 0) {
        echo "<h2>Department List</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Department ID</th><th>Faculty</th><th>Department Name</th></tr>";
        while ($row = $departments_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['department_id'] . "</td>";
            echo "<td>" . $row['faculty'] . "</td>";
            echo "<td>" . $row['department_name'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "과목이 없습니다.";
    }

    // 데이터베이스 연결 닫기
    $conn->close();
    ?>

    <!-- Main 페이지로 돌아가는 버튼 -->
    <br><br>
    <a href="main.php">이전 페이지로 돌아가기</a>
</body>
</html>
