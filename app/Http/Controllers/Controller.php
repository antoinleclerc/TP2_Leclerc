<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;
define('CREATED', 201);
define('NO_CONTENT', 204);
define('NOT_FOUND', 404);
define('INVALID_DATA', 422);
define('SERVER_ERROR', 500);
define("SONGS_PAGINATION", 5);
define("OK", 200);
define("UNAUTHORIZED", 401);
define("FORBIDDEN", 403);

#[OA\Info(
		version: '1.0.0',
		title: 'API Laravel pour Albums et Chansons',
		description: 'Documentation API Laravel avec Swagger'
	)]
abstract class Controller
{
    //
}
