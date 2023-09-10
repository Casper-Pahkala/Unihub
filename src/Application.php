<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();
        $this->addPlugin('ADmad/SocialAuth');
        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }
        // Load more plugins here
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {

        $csrf = new CsrfProtectionMiddleware([
            'httponly' => true,
        ]);
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this))
            ->add(new \ADmad\SocialAuth\Middleware\SocialAuthMiddleware([
                // Request method type use to initiate authentication.
                'requestMethod' => 'POST',
                // Login page URL. In case of auth failure user is redirected to login
                // page with "error" query string var.
                'loginUrl' => '/login',
                // URL to redirect to after authentication (string or array).
                'loginRedirect' => '/',
                // Boolean indicating whether user identity should be returned as entity.
                'userEntity' => false,
                // User model.
                'userModel' => 'Users',
                // Social profile model.
                'socialProfileModel' => 'ADmad/SocialAuth.SocialProfiles',
                // Finder type.
                'finder' => 'all',
                // Fields.
                'fields' => [
                    'password' => 'password',
                ],
                // Session key to which to write identity record to.
                'sessionKey' => 'Auth',
                // The method in user model which should be called in case of new user.
                // It should return a User entity.
                'getUserCallback' => 'getUser',
                // SocialConnect Auth service's providers config. https://github.com/SocialConnect/auth/blob/master/README.md
                'serviceConfig' => [
                    'provider' => [
                        'facebook' => [
                            'applicationId' => '<application id>',
                            'applicationSecret' => '<application secret>',
                            'scope' => [
                                'email',
                            ],
                            'options' => [
                                'identity.fields' => [
                                    'email',
                                    // To get a full list of all possible values, refer to
                                    // https://developers.facebook.com/docs/graph-api/reference/user
                                ],
                            ],
                        ],
                        'google' => [
                            'applicationId' => '270577235544-rtermudlcug7desccd5upopeikksc338.apps.googleusercontent.com',
                            'applicationSecret' => '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',
                            'scope' => [
                                'https://www.googleapis.com/auth/userinfo.email',
                                'https://www.googleapis.com/auth/userinfo.profile',
                            ],
                        ],
                    ],
                ],
                // Instance of `\SocialConnect\Auth\CollectionFactory`. If none provided one will be auto created. Default `null`.
                'collectionFactory' => null,
                // Whether social connect errors should be logged. Default `true`.
                'logErrors' => true,
            ]))
            ->add(new AuthenticationMiddleware($this))
            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware());
        
        $middlewareQueue->add(function ($request, $handler) use ($csrf) {
            // If the prefix is "api", skip the CSRF middleware
            if ($request->getParam('prefix') === 'Api') {
                return $handler->handle($request);
            }
            return $csrf->process($request, $handler);
        });

        return $middlewareQueue;
    }

    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface {
        $authenticationService = new AuthenticationService([
            'unauthenticatedRedirect' => Router::url('/login'),
            'queryParam' => 'redirect',
        ]);

        // Load identifiers, ensure we check email and password fields
        $authenticationService->loadIdentifier('Authentication.Password', [
            'fields' => [
                'username' => 'email',
                'password' => 'password',
            ]
        ]);

        // Load the authenticators, you want session first
        $authenticationService->loadAuthenticator('Authentication.Session');
        // Configure form data check to pick email and password
        $authenticationService->loadAuthenticator('Authentication.Form', [
            'fields' => [
                'username' => 'email',
                'password' => 'password',
            ],
            'loginUrl' => Router::url('/login'),
        ]);

        return $authenticationService;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Cake/Repl');
        $this->addOptionalPlugin('Bake');

        $this->addPlugin('Migrations');

        // Load more plugins here
    }
}
