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

// 사용자가 삭제를 요청한 경우
if(isset($_POST['course_code'])) {
    $course_code = $_POST['course_code'];
    
    // Enrollments 테이블에서 해당 수강 내역 삭제
    $delete_sql = "DELETE FROM Enrollments WHERE student_id='$userid' AND course_code='$course_code'";
    if ($conn->query($delete_sql) === TRUE) {
        // 이전 페이지로 리다이렉트
	echo "삭제완료";
        echo "<br><a href='account_settings.php?userid=$userid'>이전 페이지로 돌아가기</a>";
    } else {
        echo "오류: " . $conn->error;
    }
}

// 데이터베이스 연결 닫기
$conn->close();
?>
