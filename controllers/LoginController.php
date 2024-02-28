<?php

namespace Gabela\Users\Controller;

getRequired(USER_MODEL);

use Gabela\Model\User;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoginController
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger('registration-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
    }

    public function login()
    {
        $user = new User();

        // Check if the user is already logged in, then redirect to viewAllTasks.php
        if (isset($_SESSION['user_id'])) {
            redirect('/tasks');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // User clicked the "Log In" button
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Create an instance of the User class

            // Authenticate the user
            if ($user->login($email, $password)) {
                $this->logger->info("The use {$user->getName()} logged in successfully");

                return redirect('/tasks');
            } else {
                // Authentication failed, show an error message
                getRequired('User login failed.');
                $this->logger->critical('User login failed...');

                return redirect('/');

            }
        }

        return redirect('/');
    }

}
