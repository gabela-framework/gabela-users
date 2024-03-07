<?php

namespace Gabela\Users\Controller;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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
    
        $this->flush();
    
        session_destroy();
    
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    
        
        $this->logger->info("User logged out at " . date('Y-m-d') );
        
        redirect('/');
        exit(); // Ensure that script execution stops after the redirect
    }
    
    public function flush()
    {
        $_SESSION = [];
    }
    
}
