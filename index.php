<?php

require_once 'config.php';

$curl = curl_init();
$url = "https://powrss.com/random";

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_NOBODY, true);

$response = curl_exec($curl);
$http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

echo 'Response Status Code: ' . $http_status_code . PHP_EOL;

if ($http_status_code === 404) {
    echo "Error: 404 received from powrss.com";
    exit(1);
}

if (!$response) {
    echo 'There was an error with this request\n';
    exit(1);
}


$final_url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

curl_close($curl);
echo "Final URL: " . $final_url . "\n";


// Mastodon

$greetings = [
    'Found in #powRSS today:',
    'A blog post from #powRSS:',
    'Saw this post on #powRSS after clicking Random...',
    'Pulled from the #powRSS stream:',
    'Spotted on #powRSS:',
    "From today's #powRSS wanderings:",
    'Discovered via #powRSS:',
    'Another find from #powRSS:',
    'Unearthed on #powRSS:',
    'Random click, courtesy of #powRSS:',
    'Crossed my path on #powRSS:',
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

$status_headers = [
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
echo "Mastodon Resposne: " . $mastodon_response . "\n";

curl_close($curl);













