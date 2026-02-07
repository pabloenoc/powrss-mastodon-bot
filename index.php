<?php

require_once 'config.php';

$curl = curl_init();
$url = "https://powrss.com/random";

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_NOBODY, true);

echo "[" . date('Y-m-d H:i:s T') . "] Fetching URL from powrss.com/random\n";

$response = curl_exec($curl);
$http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

echo 'Response Status Code: ' . $http_status_code . PHP_EOL;

if ($http_status_code >= 400) {
    echo "[" . date('Y-m-d H:i:s T') . "] ERROR: HTTP error " . $http_status_code . " received from powrss.com\n";
    curl_close($curl);
    exit(1);
}

if (!$response) {
    echo "[" . date('Y-m-d H:i:s T') . "] ERROR: Empty response from powrss.com\n";
    curl_close($curl);
    exit(1);
}


$effective_url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
curl_close($curl);

echo "[" . date('Y-m-d H:i:s T') . "] Effective URL: " . $effective_url . "\n";


// Mastodon

$greetings = [
    'Found in #powRSS today:',
    'A blog post from #powRSS:',
    'A post on #powRSS after clicking Random...',
    'Pulled from the #powRSS stream:',
    'Spotted on #powRSS:',
    "Today's #powRSS discovery:",
    'Discovered via #powRSS:',
    'Another find from #powRSS:',
    'Random click, courtesy of #powRSS:'
];

$greeting = $greetings[array_rand($greetings)];


$status_text = <<<TEXT
$greeting

$final_url

#blogs #blogging #indieweb
TEXT;

$post_data = [
    'status' => $status_text,
    'language' => 'en',
    'visibility' => 'public'
];

$http_header = [
    'Authorization: Bearer ' . MASTODON_ACCESS_TOKEN,
    'Content-Type: application/x-www-form-urlencoded'
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, MASTODON_INSTANCE . "/api/v1/statuses");
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $status_headers);

$mastodon_response = curl_exec($curl);
$mastodon_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

echo "Mastodon Response Status Code: " . $mastodon_status_code . "\n";
echo "Mastodon Response: " . $mastodon_response . "\n";

curl_close($curl);













