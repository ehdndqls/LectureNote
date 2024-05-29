<?php
session_start();

if(!isset($_SESSION['userid'])){
    if(!isset($_GET['userid'])){
	// 로그인하지 않은 경우 로그인 페이지로 리다이렉트
	header("Location: login.html");
	exit();
    }
    else{
    	$userid = $_GET['userid']; // MySQL 사용자 이름
    }
}
else{
    $userid = $_SESSION['userid']; // MySQL 사용자 이름
}


   // userid를 제외한 데이터베이스 연결 설정
    $servername = "localhost";
    $password = "1234"; // MySQL 비밀번호
    $dbname = "LectureNotes"; // 데이터베이스 이름

    // MySQL 데이터베이스에 연결
    $conn = new mysqli($servername, $userid, $password, $dbname);

    // 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
// 사용자의 이름 저장

  
    // 데이터베이스에서 사용자의 이름을 가져오는 쿼리
    $sql = "SELECT name FROM Users WHERE student_id='$userid'";
    $result = $conn->query($sql);

    // 쿼리 결과 확인 및 사용자의 이름을 세션에 저장
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
    } else {
        $_name = "unknown"; // 데이터베이스에서 사용자의 이름을 가져오지 못한 경우 예시 이름 저장
    }

    // 데이터베이스 연결 닫기
    $conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        .navbar {
            overflow: hidden;
            background-color: #343a40;
            display: flex;
            align-items: center;
            padding: 0 10px;
        }
        .navbar a, .dropdown {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover, .dropdown:hover .dropbtn {
            background-color: #6c757d;
            color: white;
        }
        .dropdown {
            float: left;
            overflow: hidden;
        }
        .dropdown .dropbtn {
            cursor: pointer;
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content h2 {
            color: #343a40;
        }
        .content p {
            color: #777;
        }
        .content img {
            width: 500px;
            height: auto;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="dropdown">
            <button class="dropbtn">내 노트 
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="mynotes.php?userid=<?php echo $userid; ?>">노트 목록</a>
                <a href="create_note.php?userid=<?php echo $userid; ?>">노트 작성</a>
            </div>
        </div> 
        <div class="dropdown">
            <button class="dropbtn">검색 
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="search.php?userid=<?php echo $userid; ?>">노트 검색</a>
                <a href="search_courses.php?userid=<?php echo $userid; ?>">강의 검색</a>
            </div>
        </div> 
        <div class="dropdown">
            <button class="dropbtn">설정 
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="profile_settings.php?userid=<?php echo $userid; ?>">프로필 설정</a>
                <a href="account_settings.php?userid=<?php echo $userid; ?>">계정 설정</a>
            </div>
        </div>
        <div class="dropdown" style="margin-left:auto;">
            <button class="dropbtn" onclick="logout()">로그아웃 
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
    </div>
    <div class="content">
        <h2>환영합니다, <?php echo $name; ?>!</h2>
        <p>메뉴를 선택하세요.</p>
        <img src="순천향대학교_seal.png" alt="University Seal">
    </div>
    <script>
    function logout() {
        // 세션 제거
        <?php session_unset(); ?>
        <?php session_destroy(); ?>
        // 로그인 페이지로 리다이렉트
        window.location.href = 'login.html';
    }
</script>
</body>
</html>
