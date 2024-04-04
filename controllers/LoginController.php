<?php

namespace Gabela\Users\Controller;

getRequired(USER_MODULE_MODEL);

use Gabela\Core\AbstractController;
use Gabela\Core\ClassManager;
use Gabela\Users\Model\User;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoginController extends AbstractController
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ClassManager
     */
    private $classManager;

    /**
     * @var User
     */
    private User $userCollection;

    public function __construct(User $userCollection)
    {
        $this->logger = new Logger('registration-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
        $this->userCollection = $userCollection;
    }

    /**
     * Get Login page
     *
     * @return void the path will always be string
     */
    public function index()
    {
        $this->getTemplate(USER_LOGIN_PAGE);
    }

    /**
     * User Login
     *
     * @return string
     */
    public function login()
    {
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
            if ($this->userCollection->login($email, $password)) {
                $this->logger->info("The user {$this->userCollection->getName()} logged in successfully");

                return redirect('/tasks');
            } else {
                // Authentication failed, show an error message
                printValue('User login failed.');
                $this->logger->critical('User login failed...');

                return redirect('/login');

            }
        }

        return redirect('/');
    }

}
