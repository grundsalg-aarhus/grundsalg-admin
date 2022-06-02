<?php

use Symfony\Component\HttpFoundation\Request;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../app/autoload.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
// tell Symfony about your reverse proxy
Request::setTrustedProxies(
  // trust *all* requests
  ['127.0.0.1', $request->server->get('REMOTE_ADDR')],

  // trust *all* "X-Forwarded-*" headers
  Request::HEADER_X_FORWARDED_ALL
);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
