<?php
session_start();

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

// 사용자가 로그인하지 않은 경우 로그인 페이지로 리다이렉트
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// 과목 추가 액션인지 확인
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    // 사용자의 학번 가져오기
    $student_id = $_SESSION['username'];
    
    // 선택한 과목과 학번을 Enrollments 테이블에 추가
    $course_code = $_POST['course'];
    $insert_sql = "INSERT INTO Enrollments (student_id, course_code) VALUES ('$student_id', '$course_code')";
    
    if ($conn->query($insert_sql) === TRUE) {
        echo "과목이 성공적으로 추가되었습니다.";
	// 이전 페이지로 리다이렉트
        echo "<script>window.history.go(-1);</script>";
    } else {
        echo "과목 추가에 실패했습니다.";
    }
}

// 데이터베이스 연결 닫기
$conn->close();
?>
