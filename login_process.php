<?php
session_start();

// 데이터베이스 연결 설정
$servername = "localhost";
$userid = "Doh"; // MySQL 사용자 이름 변경
$password = "1234"; // MySQL 비밀번호 변경
$dbname = "LectureNotes";

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $userid, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자가 제출한 폼 데이터 가져오기
$userid = $_POST['userid']; // 학번 저장
$password = $_POST['password'];// 비밀전호 저장

// 사용자가 제출한 폼 데이터를 이용하여 쿼리 생성
$sql = "SELECT * FROM Users WHERE student_id='$userid' AND password_hash='$password'";

// 쿼리 실행
$result = $conn->query($sql);

// 결과 확인
if ($result->num_rows > 0) {
    // 로그인 성공: 세션 설정 및 메인 페이지로 리디렉션
    $_SESSION['userid'] = $userid;
    //$_SESSION['password'] = $password;
    header("Location: main.php");
    exit();
} else {
    // 로그인 실패
    echo "Login failed. Invalid userid or password.";
    // 2초 후 로그인 페이지로 리디렉션
    header("refresh:2;url=login.html");
}


// 데이터베이스 연결 닫기
$conn->close();
?>
