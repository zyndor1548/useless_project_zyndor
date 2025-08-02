<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animal Twitter</title>
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

    <div class="feed-container">
        <?php foreach ($messages as $msg): ?>
            <div class="message">
                <div class="username">@<?= htmlspecialchars($msg['username']) ?></div>
                <div><?= htmlspecialchars($msg['messages']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <p id="status"> </p>
    <button id="record_audio" class="tweet-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M320 64C267 64 224 107 224 160L224 288C224 341 267 384 320 384C373 384 416 341 416 288L416 160C416 107 373 64 320 64zM176 248C176 234.7 165.3 224 152 224C138.7 224 128 234.7 128 248L128 288C128 385.9 201.3 466.7 296 478.5L296 528L248 528C234.7 528 224 538.7 224 552C224 565.3 234.7 576 248 576L392 576C405.3 576 416 565.3 416 552C416 538.7 405.3 528 392 528L344 528L344 478.5C438.7 466.7 512 385.9 512 288L512 248C512 234.7 501.3 224 488 224C474.7 224 464 234.7 464 248L464 288C464 367.5 399.5 432 320 432C240.5 432 176 367.5 176 288L176 248z"/></svg></button>
    <input type="file" id="debug_audio" accept=".wav" />
    <button id="upload_debug_audio">Upload Debug Audio</button>
    <script src="script.js" type="module"></script>
</body>
</html>