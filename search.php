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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Posts</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Public Posts</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Course</th>
            <th>Created At</th>
            <th>Content</th>
        </tr>
        <?php
        // 공개 게시물 가져오기
        $public_posts_sql = "SELECT Posts.title, Posts.author_id, Courses.course_name, Posts.created_at, Posts.content
                             FROM Posts
                             INNER JOIN Courses ON Posts.course_code = Courses.course_code
                             WHERE Posts.is_public = TRUE";
        $public_posts_result = $conn->query($public_posts_sql);

        if ($public_posts_result->num_rows > 0) {
            while ($row = $public_posts_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['author_id'] . "</td>";
                echo "<td>" . $row['course_name'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td>" . $row['content'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No public posts found.</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="main.php">Go back to Main Page</a>
</body>
</html>

<?php
// 데이터베이스 연결 닫기
$conn->close();
?>
