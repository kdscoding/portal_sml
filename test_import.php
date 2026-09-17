<?php
// Get the page with cookies
$cookieFile = sys_get_temp_dir() . '/csrf_test.txt';
$ch = curl_init('http://localhost:8000/import');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$html = curl_exec($ch);
curl_close($ch);

preg_match('/meta name="csrf-token" content="([^"]+)"/', $html, $m);
$token = $m[1] ?? 'NOTOKEN';
echo "Token: " . substr($token, 0, 20) . "...\n";

// POST with cookies and CSRF token
$ch = curl_init('http://localhost:8000/import');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, []);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-CSRF-TOKEN: ' . $token,
]);
$resp = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP CODE: $httpCode\n";
echo "Response type: " . (str_contains($resp, '<html') ? 'HTML (ERROR)' : 'JSON (OK)') . "\n";
echo "Response: " . substr($resp, 0, 200) . "\n";
