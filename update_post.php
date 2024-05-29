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

// 수정할 포스트의 ID 가져오기
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_post_id'])) {
    $postId = $_POST['update_post_id'];

    // 수정할 포스트 정보 가져오기
    $post_sql = "SELECT * FROM Posts WHERE post_id='$postId'";
    $post_result = $conn->query($post_sql);

    if ($post_result->num_rows > 0) {
        $post_row = $post_result->fetch_assoc();
    } else {
        echo "포스트를 찾을 수 없습니다.";
        exit();
    }
} else {
    echo "잘못된 요청입니다.";
    exit();
}

// 수정된 내용으로 포스트 업데이트
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_post'])) {
    $title = $_POST['title'];
    $isPublic = $_POST['is_public'];
    $content = $_POST['content'];

    $update_sql = "UPDATE Posts SET title='$title', is_public='$isPublic', content='$content' WHERE post_id='$postId'";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('포스트가 업데이트되었습니다.');</script>";
        // 업데이트 후 mynotes.php로 리다이렉트
        header("Location: mynotes.php");
        exit();
    } else {
        echo "포스트를 업데이트하는 중 오류가 발생했습니다: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>포스트 수정</title>
</head>
<body>
    <h1>포스트 수정</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="title">제목:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $post_row['title']; ?>"><br><br>
        <label for="is_public">공개 여부:</label><br>
        <select id="is_public" name="is_public">
            <option value="1" <?php if ($post_row['is_public'] == 1) echo "selected"; ?>>공개</option>
            <option value="0" <?php if ($post_row['is_public'] == 0) echo "selected"; ?>>비공개</option>
        </select><br><br>
        <label for="content">내용:</label><br>
        <textarea id="content" name="content" rows="5" cols="50"><?php echo $post_row['content']; ?></textarea><br><br>
        <input type="hidden" name="update_post_id" value="<?php echo $postId; ?>">
        <input type="submit" name="update_post" value="수정">
    </form>
</body>
</html>

<?php
// 데이터베이스 연결 닫기
$conn->close();
?>
