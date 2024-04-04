<?php

namespace Gabela\Users\Controller;

use Gabela\Core\Session;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogoutController
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct($logger)
    {
        $this->logger = new Logger('logout-controller');
    }

    public function logout()
    {
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));

        Session::destroy();

        $this->logger->info("User logged out at " . date('Y-m-d H:i:s'));

        redirect('/login');
        exit(); // Ensure that script execution stops after the redirect
    }
}