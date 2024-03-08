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
<<<<<<< HEAD

        Session::destroy();

        $this->logger->info("User logged out at " . date('Y-m-d'));

=======
    
        $this->flush();
    
        session_destroy();
    
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    
        
        $this->logger->info("User logged out at " . date('Y-m-d') );
        
>>>>>>> d63a2764be67c9a28dd6c0d98b681cc0903d1571
        redirect('/');
        exit(); // Ensure that script execution stops after the redirect
    }
}