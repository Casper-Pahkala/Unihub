<?php

namespace App\Middleware;

use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Core\Configure;


class ApiTokenMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, $next)
    {
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');
        $whitelist = [
            'getBacchusMenu',
            'commendRestaurant'
        ];
        if (in_array($action, $whitelist) || in_array($controller, $whitelist)) {
            return $next($request, $response);
        }
        $token = $request->getHeaderLine('X-Api-Token');
        // Validate the token however you see fit. This is just a simple example.
        if ($token !== Configure::read('Api.token')) {
            if ($request->getHeaderLine('X-CSRF-Token')) {

            } else {
                // throw new UnauthorizedException('Invalid API token');
            }
        }
        // If all is well, forward the request/response to the next middleware in line or to the controller.
        return $next($request, $response);
    }
}


?>
