<?php
session_start();

// 사용자가 로그인하지 않은 경우 로그인 페이지로 리다이렉트
if (isset($_GET['userid'])) {
    $_SESSION['userid'] = $_GET['userid'];
}

// 데이터베이스 연결 설정
$servername = "localhost";
$userid = $_SESSION['userid']; // MySQL 사용자 이름
$password = "1234"; // MySQL 비밀번호 변경
$dbname = "LectureNotes";

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $userid, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자의 수강 중인 과목 가져오기
$courses_sql = "SELECT course_code FROM Enrollments WHERE student_id='$userid'";
$courses_result = $conn->query($courses_sql);

// 수강 중인 과목 메뉴 옵션 생성
$courses_menu = "";
if ($courses_result->num_rows > 0) {
    while ($row = $courses_result->fetch_assoc()) {
        $course_code = $row['course_code'];
        $course_name_sql = "SELECT course_name FROM Courses WHERE course_code='$course_code'";
        $course_name_result = $conn->query($course_name_sql);
        if ($course_name_result->num_rows > 0) {
            $course_row = $course_name_result->fetch_assoc();
            $course_name = $course_row['course_name'];
            $courses_menu .= "<option value='" . $course_code . "'>" . $course_name . "</option>";
        }
    }
} else {
    $courses_menu = "<option value=''>수강 중인 강의가 없습니다.</option>";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notes</title>
    <style>
        .content {
            background-color: #f0f0f0; 
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>My Notes</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="course">수강 중인 강의:</label><br>
        <select id="course" name="selected_course">
            <?php echo $courses_menu; ?>
        </select><br><br>
        <input type="submit" value="선택">
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selected_course'])) {
        $selected_course_code = $_POST['selected_course'];
        $course_name_sql = "SELECT course_name FROM Courses WHERE course_code='$selected_course_code'";
        $course_name_result = $conn->query($course_name_sql);
        if ($course_name_result->num_rows > 0) {
            $course_row = $course_name_result->fetch_assoc();
            $selected_course_name = $course_row['course_name'];

            $posts_sql = "SELECT * FROM Posts WHERE course_code='$selected_course_code' AND author_id = '$userid'";
            $posts_result = $conn->query($posts_sql);

            echo "<h2>" . $selected_course_name . "에 대한 포스트</h2>";
            if ($posts_result->num_rows > 0) {
                while ($post_row = $posts_result->fetch_assoc()) {
                    echo "<div class='content'>";
                    echo "<h3>" . $post_row['title'] . "</h3>";
                    echo "<p>작성자: " . $post_row['author_id'] . "</p>";
                    echo "<p>작성일: " . $post_row['created_at'] . "</p>";
                    echo "<p>공개 여부: " . ($post_row['is_public'] ? "공개" : "비공개") . "</p>";
                    echo "<p>" . $post_row['content'] . "</p>"; // content를 맨 마지막에 출력
                    echo "<form style='display:inline-block;margin-right:5px;' action='update_post.php' method='post'>";
		    echo "<input type='hidden' name='userid'  value='$userid'>";
                    echo "<input type='hidden' name='update_post_id' value='" . $post_row['post_id'] . "'>";
                    echo "<input type='submit' value='수정'>";
                    echo "</form>";
                    echo "<form style='display:inline-block;' method='post'>";
                    echo "<input type='hidden' name='delete_post_id' value='" . $post_row['post_id'] . "'>";
                    echo "<input type='submit' value='삭제'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "포스트가 없습니다.";
            }
        } else {
            echo "선택한 강의의 이름을 가져오는 중 오류가 발생했습니다.";
        }
    }
}

// 포스트 삭제 기능
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_post_id'])) {
    $postId = $_POST['delete_post_id'];
    $delete_sql = "DELETE FROM Posts WHERE post_id='$postId'";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('포스트가 삭제되었습니다.');</script>";
        // 삭제 후 페이지를 다시 로드하여 최신 데이터를 표시할 수 있습니다.
        header("mynote.php");
    } else {
        echo "포스트 삭제 중 오류가 발생했습니다.";
    }
}

?>

<br><br><a href='main.php?userid=$userid'>메인 페이지로 돌아가기</a>

</body>
</html>

<?php
// 데이터베이스 연결 닫기
$conn->close();
?>
