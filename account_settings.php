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

// Ajax 요청 처리
if (isset($_POST['ajax_action'])) {
    $ajax_action = $_POST['ajax_action'];

    // 단과대에 따라 해당 학과 로드
    if ($ajax_action == "load_departments") {
        $college = $_POST['college'];
        $departments_query = "SELECT * FROM Departments WHERE faculty='$college'";
        $departments_result = $conn->query($departments_query);
        if ($departments_result->num_rows > 0) {
            while ($row = $departments_result->fetch_assoc()) {
                echo "<option value='" . $row['department_id'] . "'>" . $row['department_name'] . "</option>";
            }
        } else {
            echo "<option value=''>학과 없음</option>";
        }
    }

    // 학과에 따라 해당 과목 로드
    elseif ($ajax_action == "load_courses") {
        $department_id = $_POST['department'];
        $courses_query = "SELECT * FROM Courses WHERE department_id='$department_id'";
        $courses_result = $conn->query($courses_query);
        if ($courses_result->num_rows > 0) {
            while ($row = $courses_result->fetch_assoc()) {
                echo "<option value='" . $row['course_code'] . "'>" . $row['course_name'] . "</option>";
            }
        } else {
            echo "<option value=''>과목 없음</option>";
        }
    }
    exit();
}

// 사용자가 로그인하지 않은 경우 로그인 페이지로 리다이렉트
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// 사용자 정보 가져오기
$username = $_SESSION['username'];
$sql = "SELECT * FROM Users WHERE student_id='$username'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        .container h1, .container h3 {
            text-align: center;
            color: #333;
        }
        .user-info, .course-form {
            margin-bottom: 20px;
        }
        .user-info p, .course-form label {
            margin: 10px 0;
            color: #555;
        }
        .course-form select, .course-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .course-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .course-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .enrolled-courses ul {
            list-style-type: none;
            padding: 0;
        }
        .enrolled-courses li {
            margin: 10px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .enrolled-courses form {
            display: inline;
        }
        .enrolled-courses input[type="submit"] {
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .enrolled-courses input[type="submit"]:hover {
            background-color: #cc0000;
        }
    </style>
    <script>
        function loadDepartments() {
            var college = document.getElementById("college").value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "account_settings.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    document.getElementById("department").innerHTML = this.responseText;
                    loadCourses();  // 학과가 변경될 때 과목도 초기화
                }
            }
            xhr.send("ajax_action=load_departments&college=" + college);
        }

        function loadCourses() {
            var department = document.getElementById("department").value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "account_settings.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    document.getElementById("course").innerHTML = this.responseText;
                }
            }
            xhr.send("ajax_action=load_courses&department=" + department);
        }
    </script>
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
                                     WHERE Enrollments.student_id='$username'";
            $enrolled_courses_result = $conn->query($enrolled_courses_sql);
            echo "<div class='enrolled-courses'>";
            echo "<h3>수강중인 과목:</h3>";
            if ($enrolled_courses_result->num_rows > 0) {
                echo "<ul>";
                while ($course_row = $enrolled_courses_result->fetch_assoc()) {
                    echo "<li>" . $course_row['course_name'] . "
                        <form action='delete_enrollment.php' method='post'>
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
            echo "<label for='college'>단과대:</label><br>";
            echo "<select id='college' name='college' onchange='loadDepartments()'>";
            echo "<option value=''>선택하세요</option>";

            // 단과대 목록 하드코딩
            $colleges = ["대학전체", "의과대학", "자연과학대학", "인문사회대학", "공과대학", "SW융합대학"];
            foreach ($colleges as $college) {
                echo "<option value='$college'>$college</option>";
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
echo "<input type='hidden' name='action' value='add'>";
echo "<input type='submit' value='추가'>";
echo "</form>";
echo "</div>";
} else {
echo "사용자 정보를 가져올 수 없습니다.";
}
?>
</div>

</body>
</html>
