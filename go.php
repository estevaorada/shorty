<?php

require_once 'model/database.php';

$shortCode = trim($_GET['code'] ?? '');
if ($shortCode === '') {
	http_response_code(404);
	exit('Link não encontrado.');
}

$connection = Database::conectar();
$statement = $connection->prepare('SELECT id, long_url FROM urls WHERE short_code = :short_code LIMIT 1');
$statement->execute(['short_code' => $shortCode]);
$url = $statement->fetch(PDO::FETCH_ASSOC);

if (!$url) {
	http_response_code(404);
	exit('Link não encontrado.');
}

$utmParameters = [];
foreach (['utm_source', 'utm_medium', 'utm_campaign'] as $parameter) {
	if (!empty($_GET[$parameter])) {
		$utmParameters[$parameter] = trim($_GET[$parameter]);
	}
}

$destination = $url['long_url'];
if ($utmParameters) {
	$separator = strpos($destination, '?') === false ? '?' : '&';
	$destination .= $separator . http_build_query($utmParameters);
}

$log = $connection->prepare(
	'INSERT INTO click_logs (url_id, utm_source, utm_medium, utm_campaign, referrer, user_agent)
	 VALUES (:url_id, :utm_source, :utm_medium, :utm_campaign, :referrer, :user_agent)'
);
$log->execute([
	'url_id' => $url['id'],
	'utm_source' => $utmParameters['utm_source'] ?? null,
	'utm_medium' => $utmParameters['utm_medium'] ?? null,
	'utm_campaign' => $utmParameters['utm_campaign'] ?? null,
	'referrer' => $_SERVER['HTTP_REFERER'] ?? null,
	'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
]);

$connection->prepare('UPDATE urls SET clicks_count = clicks_count + 1 WHERE id = :id')
	->execute(['id' => $url['id']]);

header('Location: ' . $destination, true, 302);
exit;
