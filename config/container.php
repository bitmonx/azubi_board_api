<?php

use App\Database\DatabaseProxy;
use App\Mail\Mailer;
use Selective\BasePath\BasePathMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use App\Auth\JwtAuth;
use App\Auth\LdapLogin;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;


// Settings for the dependeny injection container
return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },
    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details']
        );
    },
    BasePathMiddleware::class => function (ContainerInterface $container)
    {
        return new BasePathMiddleware($container->get(App::class));
    },
    DatabaseProxy::class => function (ContainerInterface $container)
    {
        return new DatabaseProxy(
            $container->get('ems'),
            $container->get('azubi_board')
        );
    },
    'ems' => function (ContainerInterface $container)
    {
        $settings = $container->get('settings')['ems'];
        $host = $settings['host'];
        $dbname = $settings['database'];
        $username = $settings['username'];
        $password = $settings['password'];
        $charset = $settings['charset'];
        $flags = $settings['flags'];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        return new PDO($dsn, $username, $password, $flags);
    },
    'azubi_board' => function (ContainerInterface $container)
    {
        $settings = $container->get('settings')['azubi_board'];
        $host = $settings['host'];
        $dbname = $settings['database'];
        $username = $settings['username'];
        $password = $settings['password'];
        $charset = $settings['charset'];
        $flags = $settings['flags'];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        return new PDO($dsn, $username, $password, $flags);
    },
    ResponseFactoryInterface::class => function (ContainerInterface $container)
    {
        return $container->get(App::class)->getResponseFactory();
    },
    JwtAuth::class => function (ContainerInterface $container)
    {
        $config = $container->get('settings')['jwt'];
        $issuer = (string)$config['issuer'];
        $lifetime = (int)$config['lifetime'];
        $privateKey = (string)$config['private_key'];
        $publicKey = (string)$config['public_key'];

        return new JwtAuth($issuer, $lifetime, $privateKey, $publicKey);
    },
    LdapLogin::class => function (ContainerInterface $container)
    {
        $config = $container->get('settings')['apiServer'];
        $url = $config['url'];

        return new LdapLogin($url);
    },
    Mailer::class => function (ContainerInterface $container)
    {
        $config = $container->get('settings')['email'];
        $sender = $config['sender'];
        $userSelector = $container->get(\App\Domain\User\Service\UserSelector::class);

        return new \App\Mail\Mailer($sender, $userSelector);
    }
];
