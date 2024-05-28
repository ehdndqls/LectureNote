<?php
session_start();

// 사용자가 로그인하지 않은 경우 로그인 페이지로 리다이렉트
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// 사용자의 학번을 세션에 저장
if (!isset($_SESSION['student_id'])) {
    // 여기에 데이터베이스에서 사용자의 학번을 가져오는 코드를 작성하세요.
    // 사용자의 학번을 $_SESSION['student_id']에 저장하세요.
    $_SESSION['student_id'] = "사용자의 학번"; // 예시 코드입니다. 실제로 사용자의 학번을 가져와서 저장하세요.
}
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
                <a href="mynotes.php">노트 목록</a>
                <a href="create_note.php">노트 작성</a>
            </div>
        </div> 
        <div class="dropdown">
            <button class="dropbtn">검색 
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="search.php">노트 검색</a>
                <a href="search_courses.php">강의 검색</a>
            </div>
        </div> 
        <div class="dropdown">
            <button class="dropbtn">설정 
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="profile_settings.php">프로필 설정</a>
                <a href="account_settings.php">계정 설정</a>
            </div>
        </div>
        <div class="dropdown" style="margin-left:auto;">
            <button class="dropbtn" onclick="logout()">로그아웃 
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
    </div>
    <div class="content">
        <h2>환영합니다, <?php echo $_SESSION['username']; ?>!</h2>
        <p>메뉴를 선택하세요.</p>
        <img src="순천향대학교_seal.png" alt="University Seal">
    </div>
    <script>
        function logout() {
            // 로그아웃 요청을 서버로 보냅니다.
            // 이 부분은 PHP로 로그아웃을 처리하는 방법과 같은 방법으로 수정해야 합니다.
            // 여기에서는 간단히 로컬 스토리지의 세션을 삭제하고 로그인 페이지로 이동하는 예시 코드를 작성했습니다.
            localStorage.removeItem('username');
            localStorage.removeItem('student_id');
            window.location.href = 'login.html'; // 로그인 페이지로 리다이렉트합니다.
        }
    </script>
</body>
</html>
