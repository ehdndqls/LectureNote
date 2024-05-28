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

// 사용자가 제출한 폼 데이터 가져오기
$username = $_POST['username']; // 변수명 변경

// 사용자가 제출한 폼 데이터를 이용하여 쿼리 생성
$sql = "SELECT * FROM Users WHERE student_id='$username' AND password_hash='$password'";

// 쿼리 실행
$result = $conn->query($sql);

// 결과 확인
if ($result->num_rows > 0) {
    // 로그인 성공: 세션 설정 및 메인 페이지로 리디렉션
    session_start();
    $_SESSION['username'] = $username;
    header("Location: main.php");
    exit();
} else {
    // 로그인 실패
    echo "Login failed. Invalid username or password.";
    // 2초 후 로그인 페이지로 리디렉션
    header("refresh:2;url=login.html");
}


// 데이터베이스 연결 닫기
$conn->close();
?>
