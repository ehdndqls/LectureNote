<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "Doh"; // MySQL 사용자 이름
$password = "1234"; // MySQL 비밀번호
$dbname = "LectureNotes"; // 데이터베이스 이름

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자가 제출한 폼 데이터 가져오기
$student_id = $_POST['student_id'];
$password_hash = $_POST['password'];
$name = $_POST['name'];
$faculty = $_POST['faculty'];
$department = $_POST['department'];
$role = $_POST['role'];

// 중복 체크 쿼리
$check_sql = "SELECT * FROM Users WHERE student_id='$student_id'";
$check_result = $conn->query($check_sql);

// 중복 확인
if ($check_result->num_rows > 0) {
    echo "회원가입 실패. 이미 존재하는 학번입니다.";
} else {
    // 새 사용자 추가 쿼리
    $sql = "INSERT INTO Users (student_id, password_hash, name, department, role) 
            VALUES ('$student_id', '$password_hash', '$name', '$department', '$role')";

    // 사용자 추가
    if ($conn->query($sql) === TRUE) {
        // 회원가입 성공 메시지 출력
        echo "회원가입에 성공했습니다!";

        // Post 테이블에 대한 모든 권한 부여
        $grant_post_sql = "GRANT ALL PRIVILEGES ON LectureNotes.Posts TO '$student_id'@'localhost'";
        $conn->query($grant_post_sql);

        // User 테이블에서 비밀번호 변경 권한만 부여
        $grant_user_sql = "GRANT UPDATE (password_hash) ON LectureNotes.Users TO '$student_id'@'localhost'";
        $conn->query($grant_user_sql);

        // 변경사항 적용
        $conn->query("FLUSH PRIVILEGES");

        // 2초 후 로그인 페이지로 리디렉션
        header("refresh:2;url=login.html");
    } else {
	echo "회원가입 실패. 이미 존재하는 학번 입니다.";
   	
	// 2초 후 로그인 페이지로 리디렉션
   	header("refresh:2;url=login.html");
    }
}

// 데이터베이스 연결 닫기
$conn->close();
?>
