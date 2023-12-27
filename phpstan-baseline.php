<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Construct empty\\(\\) is not allowed\\. Use more strict comparison\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Entity/Video.php',
];
$ignoreErrors[] = [
	'message' => '#^YoutubeDl\\\\Tests\\\\StaticProcess\\:\\:__construct\\(\\) does not call parent constructor from Symfony\\\\Component\\\\Process\\\\Process\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/tests/StaticProcess.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
