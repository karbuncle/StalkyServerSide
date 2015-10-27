<?php
namespace App\Http;

abstract class HttpResponse {
	const __default = 500;
	const Unauthorized = 401;
	const Forbidden = 403;
	const NotFound = 404;
	const Gone = 410;
	const InternalError = 500;
	const ServiceUnavailable = 503;
}
