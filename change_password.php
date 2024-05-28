<?php
session_start();

// 사용자가 로그인하지 않은 경우 로그인 페이지로 리다이렉트
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

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
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];
$username = $_SESSION['username'];

// 기존 비밀번호 확인
$sql = "SELECT password_hash FROM Users WHERE student_id='$username'";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $db_password = $row['password_hash'];
    if ($current_password == $db_password) {
        // 새로운 비밀번호와 확인 비밀번호가 일치하는지 확인
        if ($new_password == $confirm_password) {
            // 비밀번호 업데이트
            $update_sql = "UPDATE Users SET password_hash='$new_password' WHERE student_id='$username'";
            if ($conn->query($update_sql) === TRUE) {
                echo "비밀번호가 변경되었습니다.";
		header("refresh:2;url=main.php"); // 2초 후 메인 페이지로 리디렉션
                exit();
            } else {
                echo "비밀번호 변경에 실패했습니다.";
            }
        } else {
            echo "새로운 비밀번호와 확인 비밀번호가 일치하지 않습니다.";
        }
    } else {
        echo "현재 비밀번호가 일치하지 않습니다.";
    }
} else {
    echo "사용자 정보를 가져올 수 없습니다.";
}

// 데이터베이스 연결 닫기
$conn->close();
?>