<?php

// 데이터베이스 연결 설정
$servername = "localhost";
$userid = $_POST['userid']; // MySQL 사용자 이름 
$password = "1234"; // MySQL 비밀번호 
$dbname = "LectureNotes";

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $userid, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// 과목 추가 액션인지 확인
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $student_id = $_POST['userid'];

// course_code를 얻기 위한 쿼리 수행
$course_name = $_POST['course'];
$query_course_code = "SELECT course_code FROM Courses WHERE course_name = '$course_name'";
$result_course_code = $conn->query($query_course_code);

// 쿼리 결과 확인 후 course_code 얻기
if ($result_course_code->num_rows > 0) {
    $row = $result_course_code->fetch_assoc();
    $course_code = $row['course_code'];

    // INSERT 쿼리 수행
    $insert_sql = "INSERT INTO Enrollments (student_id, course_code) VALUES ('$student_id', '$course_code')";
    
    if ($conn->query($insert_sql) === TRUE) {
        echo "과목이 성공적으로 추가되었습니다.";
        // 이전 페이지로 리다이렉트
        echo "<br><a href='account_settings.php?userid=$userid'>이전 페이지로 돌아가기</a>";
    } else {
        echo "과목 추가에 실패했습니다.";
    }
} else {
    echo "쿼리 결과에서 course_code를 얻을 수 없습니다.";
}

}

// 데이터베이스 연결 닫기
$conn->close();
?>
