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

// 사용자 정보 가져오기
$username = $_SESSION['username'];

// 공개 여부 콤보박스 옵션
$public_options = array("TRUE", "FALSE");

// 사용자의 수강 중인 과목 가져오기
$courses_sql = "SELECT course_code FROM Enrollments WHERE student_id='$username'";
$courses_result = $conn->query($courses_sql);

// 과목 코드 옵션 생성
$course_options = "";
if ($courses_result->num_rows > 0) {
    while ($row = $courses_result->fetch_assoc()) {
        $course_code = $row['course_code'];
        $course_name_sql = "SELECT course_name FROM Courses WHERE course_code='$course_code'";
        $course_name_result = $conn->query($course_name_sql);
        if ($course_name_result->num_rows > 0) {
            $course_row = $course_name_result->fetch_assoc();
            $course_name = $course_row['course_name'];
            $course_options .= "<option value='" . $course_code . "'>" . $course_name . "</option>";
        }
    }
} else {
    $course_options = "<option value=''>수강 중인 과목이 없습니다.</option>";
}

// 사용자가 게시물 작성을 요청한 경우
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $course_code = $_POST['course_code'];
    $is_public = isset($_POST['is_public']) ? 1 : 0; // 1 for TRUE, 0 for FALSE

    // 게시물 데이터베이스에 추가
    $insert_sql = "INSERT INTO Posts (title, content, author_id, course_code, is_public) 
                   VALUES ('$title', '$content', '$username', '$course_code', $is_public)";
    if ($conn->query($insert_sql) === TRUE) {
        // 게시물 추가 성공 시 메시지 출력
        echo "새 게시물이 성공적으로 추가되었습니다.";
        // main.php로 돌아가기
        echo "<br><br><a href='main.php'>메인 페이지로 돌아가기</a>";

    } else {
        echo "오류: " . $conn->error;
    }
}

// 데이터베이스 연결 닫기
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Note</title>
</head>
<body>
    <h2>Create New Note</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="4" cols="50" required></textarea><br><br>

        <label for="course_code">Course:</label><br>
        <select id="course_code" name="course_code">
            <?php echo $course_options; ?>
        </select><br><br>

        <label for="is_public">Is Public:</label><br>
        <input type="checkbox" id="is_public" name="is_public" value="1">
        <label for="is_public">Public</label><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
