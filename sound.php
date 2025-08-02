<?php
header('Content-Type: application/json');

if (!isset($_FILES['audio'])) {
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}
$tmpPath = $_FILES['audio']['tmp_name'];

$cfile = new CURLFile($tmpPath, 'audio/wav', 'recorded.wav');
$postFields = ['filepath' => $cfile];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://ardneebwar-animals-sounds-classifier.hf.space/run/predict");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer '
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['error' => curl_error($ch)]);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

echo json_encode([
    'success' => true,
    'data' => $data
]);
?>
