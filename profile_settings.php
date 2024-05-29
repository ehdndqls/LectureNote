<?php
session_start();

// 사용자가 로그인하지 않은 경우 로그인 페이지로 리다이렉트
if (!isset($_GET['userid'])) {
    header("Location: login.html");
    exit();
}

// 데이터베이스 연결 설정
$servername = "localhost";
$userid = $_GET['userid']; // MySQL 사용자 이름 변경
$password = "1234"; // MySQL 비밀번호 변경
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
    <title>User Profile</title>
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
            max-width: 500px;
            width: 100%;
        }
        .container h1, .container h3 {
            text-align: center;
            color: #333;
        }
        .user-info, .password-form {
            margin-bottom: 20px;
        }
        .user-info p, .password-form label {
            margin: 10px 0;
            color: #555;
        }
        .password-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .password-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .password-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            // 사용자 정보 출력
            $row = $result->fetch_assoc();
            echo "<div class='user-info'>";
            echo "<h1>사용자 정보</h1>";
            echo "<p><strong>학번:</strong> " . $row['student_id'] . "</p>";
            echo "<p><strong>이름:</strong> " . $row['name'] . "</p>";
            echo "<p><strong>학과:</strong> " . $row['department'] . "</p>";
            echo "<p><strong>구분:</strong> " . $row['role'] . "</p>";
            
            // 사용자가 작성한 Post 개수 가져오기
            $post_count_sql = "SELECT COUNT(*) AS post_count FROM Posts WHERE author_id='$userid'";
            $post_count_result = $conn->query($post_count_sql);
            $post_count_row = $post_count_result->fetch_assoc();
            echo "<p><strong>작성한 Post의 개수:</strong> " . $post_count_row['post_count'] . "</p>";

            // 수강중인 과목 개수 가져오기
            $enrolled_count_sql = "SELECT COUNT(*) AS enrolled_count FROM Enrollments WHERE student_id='$userid'";
            $enrolled_count_result = $conn->query($enrolled_count_sql);
            $enrolled_count_row = $enrolled_count_result->fetch_assoc();
            echo "<p><strong>수강중인 과목의 개수:</strong> " . $enrolled_count_row['enrolled_count'] . "</p>";
            echo "</div>";

            // 비밀번호 변경 폼 출력
            echo "<div class='password-form'>";
            echo "<h3>비밀번호 변경</h3>";
            echo "<form action='change_password.php' method='post'>";
            echo "<label for='current_password'>현재 비밀번호:</label>";
            echo "<input type='password' id='current_password' name='current_password' required>";
            echo "<label for='new_password'>새로운 비밀번호:</label>";
            echo "<input type='password' id='new_password' name='new_password' required>";
            echo "<label for='confirm_password'>새로운 비밀번호 확인:</label>";
            echo "<input type='password' id='confirm_password' name='confirm_password' required>";
    	    // 사용자 ID 데이터를 hidden input으로 추가
            echo "<input type='hidden' name='userid' value='$userid'>";
    	    echo "<input type='submit' value='비밀번호 변경'>";
            echo "</form>";
            echo "</div>";
        } else {
            echo "<p>사용자 정보를 가져올 수 없습니다.</p>";
        }

        // 데이터베이스 연결 닫기
        $conn->close();
        ?>
    </div>
    <br>
    <a href="main.php?userid=<?php echo $userid; ?>">이전 페이지로 돌아가기</a>

</body>
</html>
