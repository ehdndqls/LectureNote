<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$userid = $_GET['userid']; // MySQL 사용자 이름
$password = "1234"; // MySQL 비밀번호
$dbname = "LectureNotes";

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $userid, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자 정보 가져오기
$sql = "SELECT * FROM Users WHERE student_id='$userid'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    </head>
<body>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<div class='user-info'>";
            echo "<h1>사용자 정보</h1>";
            echo "<p><strong>학번:</strong> " . $row['student_id'] . "</p>";
            echo "<p><strong>이름:</strong> " . $row['name'] . "</p>";
            echo "<p><strong>학과:</strong> " . $row['department'] . "</p>";
            echo "<p><strong>구분:</strong> " . $row['role'] . "</p>";
            echo "</div>";

            // 수강중인 과목 가져오기
            $enrolled_courses_sql = "SELECT Enrollments.course_code, Courses.course_name
                                     FROM Enrollments
                                     JOIN Courses ON Enrollments.course_code = Courses.course_code
                                     WHERE Enrollments.student_id='$userid'";
            $enrolled_courses_result = $conn->query($enrolled_courses_sql);
            echo "<div class='enrolled-courses'>";
            echo "<h3>수강중인 과목:</h3>";
            if ($enrolled_courses_result->num_rows > 0) {
                echo "<ul>";
                while ($course_row = $enrolled_courses_result->fetch_assoc()) {
                    echo "<li>" . $course_row['course_name'] . "
                        <form action='delete_enrollment.php' method='post'>
			    <input type='hidden' name='userid' value='$userid'>
                            <input type='hidden' name='course_code' value='" . $course_row['course_code'] . "'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='submit' value='삭제'>
                        </form>
                    </li>";
                }
                echo "</ul>";
            } else {
                echo "수강 중인 과목이 없습니다.";
            }
            echo "</div>";

            // 단과대 선택 폼 출력
            echo "<div class='course-form'>";
            echo "<h3>과목 추가</h3>";
            echo "<form id='courseForm' action='add_enrollments.php' method='post'>";
            echo "<label for='faculty'>단과대:</label><br>";
            echo "<select id='faculty' name='faculty' onchange='loadDepartments()'>";
            echo "<option value=''>선택하세요</option>";

            // 단과대 목록 하드코딩
            $facultys = ["대학전체", "의과대학", "자연과학대학", "인문사회대학", "공과대학", "SW융합대학"];
            foreach ($facultys as $faculty) {
                echo "<option value='$faculty'>$faculty</option>";
            }

            echo "</select><br><br>";
            echo "<label for='department'>학과:</label><br>";
            echo "<select id='department' name='department' onchange='loadCourses()'>";
            echo "<option value=''>선택하세요</option>";
            echo "</select><br><br>";
            echo "<label for='course'>과목:</label><br>";
            echo "<select id='course' name='course'>";
            echo "<option value=''>선택하세요</option>";
            echo "</select><br><br>";
            echo "<input type='hidden' name='userid' value='$userid'>";
            echo "<input type='hidden' name='action' value='add'>";
            echo "<input type='submit' value='추가'>";
            echo "</form>";
            echo "</div>";

        } else {
            echo "사용자 정보를 가져올 수 없습니다.";
        }
        ?>
    </div>
    <script>
        function loadDepartments() {
            var faculty = document.getElementById("faculty").value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "load_departments.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    document.getElementById("department").innerHTML = this.responseText;
                    loadCourses();  // 학과가 변경될 때 과목도 초기화
                }
            }
            xhr.send("faculty=" + encodeURIComponent(faculty));
        }

        function loadCourses() {
            var department = document.getElementById("department").value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "load_courses.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    document.getElementById("course").innerHTML = this.responseText;
                }
            }
            xhr.send("department=" + encodeURIComponent(department));
        }
    </script>
    <br>
    <a href="main.php?userid=<?php echo $userid; ?>">이전 페이지로 돌아가기</a>

</body>
</html>
