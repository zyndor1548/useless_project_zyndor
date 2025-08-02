<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animal Twitter</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f8fa;
        }
        .feed-container {
            max-width: 500px;
            margin: 0 auto;
            padding-top: 30px;
            height: 100vh;
            overflow-y: auto;
        }
        .message {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 16px;
            padding: 16px;
        }
        .username {
            font-weight: bold;
            color: #1da1f2;
            margin-bottom: 8px;
        }
        .tweet-btn {
            position: fixed;
            left: 20px;
            bottom: 20px;
            background: #1da1f2;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 16px 24px;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <?php
    $servername = "sql112.byethost7.com";
    $username = "b7_39615818";
    $password = "aromal2006";
    $dbname = "b7_39615818_zwitter";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("DB Connection failed: " . $conn->connect_error);
    }

    $messages = [];
    $result = $conn->query("SELECT username,messages FROM tweets ORDER BY created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    $conn->close();
    ?>

    <div class="feed-container">
        <?php foreach ($messages as $msg): ?>
            <div class="message">
                <div class="username">@<?= htmlspecialchars($msg['username']) ?></div>
                <div><?= htmlspecialchars($msg['messages']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <p id="status"> </p>
    <button id="record_audio" class="tweet-btn">Tweet</button>
    <input type="file" id="debug_audio" accept=".wav" />
    <button id="upload_debug_audio">Upload Debug Audio</button>
    <script src="script.js" type="module"></script>
</body>
</html>