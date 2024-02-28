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

    public function __construct(Logger $logger = null)
    {
        $this->logger = $logger;
    }

    public function logout()
    {
        $logger = new Logger('logout-controller');
        $logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
    
        $this->flush();
    
        session_destroy();
    
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    
        
        $logger->info("User logged out at " . date('t') );
        
        redirect('/');
        exit(); // Ensure that script execution stops after the redirect
    }
    
    public function flush()
    {
        $_SESSION = [];
    }
    
}
