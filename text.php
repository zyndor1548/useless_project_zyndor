<?php
$prompt_set = [
    "The \$animal just made a sound—what’s its sarcastic one-liner about humans? Respond with only a one-liner.",
    "If this \$animal could tweet right now, what’s its funniest roast? Respond with only a one-liner.",
    "The \$animal is feeling sassy—what’s its snarkiest comeback? Respond with only a one-liner.",
    "Imagine the \$animal is a stand-up comic—what’s its opening joke? Respond with only a one-liner.",
    "The \$animal just got called cute—what’s its witty response? Respond with only a one-liner.",
    "The \$animal is annoyed by humans—what’s its dry, sarcastic remark? Respond with only a one-liner.",
    "The \$animal just had a bad day—what’s its grumpy, one-line complaint? Respond with only a one-liner.",
    "If the \$animal could throw shade at another animal, what would it say? Respond with only a one-liner.",
    "The \$animal just saw something ridiculous—what’s its clever observation? Respond with only a one-liner.",
    "The \$animal is feeling dramatic—what’s its over-the-top, funny reaction? Respond with only a one-liner.",
    "The \$animal is unimpressed—what’s its deadpan one-liner? Respond with only a one-liner.",
    "If the \$animal could tweet about its daily life, what’s its most relatable gripe? Respond with only a one-liner.",
    "The \$animal just heard a human joke—what’s its sarcastic review? Respond with only a one-liner.",
    "The \$animal is feeling superior—what’s its smug, witty remark? Respond with only a one-liner.",
    "The \$animal is bored—what’s its dry, funny comment? Respond with only a one-liner."
];

$apiKey = "AIzaSyCQgDNaEcRyheXDhpZwWVor59ybQSJBmkY"; 

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;


$animal = isset($_POST['animal']) ? $_POST['animal'] : "chicken";

$prompt = $prompt_set[random_int(0, count($prompt_set) - 1)];
$prompt = str_replace("\$animal", $animal, $prompt);

$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt]
            ]
        ]
    ]
];

$options = [
    "http" => [
        "method"  => "POST",
        "header"  => "Content-Type: application/json\r\n",
        "content" => json_encode($data),
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);

$responseData = json_decode($response, true);
echo $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "No response";
?>
