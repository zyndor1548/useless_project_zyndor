<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zwitter</title>
    <link rel="stylesheet" href="style.css">
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
    <img src=".asset/images/logo" class="logo" alt="logo">
    <div class="feed-container">
        <?php foreach ($messages as $msg): ?>
            <div class="message">
                <div class="username">@<?= htmlspecialchars($msg['username']) ?></div>
                <div><?= htmlspecialchars($msg['messages']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <p id="status"> </p>
    <button id="record_audio" class="tweet-btn"><img src="./asset/images/mic.png" alt="microphone" ></button>
    <script src="script.js" type="module"></script>
</body>
</html>