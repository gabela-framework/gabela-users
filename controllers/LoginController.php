<?php

namespace Gabela\Users\Controller;

<<<<<<< HEAD
getRequired(USER_MODULE_MODEL);
=======
//getRequired(USER_MODULE_MODEL);
>>>>>>> d63a2764be67c9a28dd6c0d98b681cc0903d1571

use Gabela\Core\AbstractController;
use Monolog\Logger;
<<<<<<< HEAD
use Gabela\Users\Model\User;
=======
use Gabela\Model\User as UserCollection;
>>>>>>> d63a2764be67c9a28dd6c0d98b681cc0903d1571
use Gabela\Core\ClassManager;
use Monolog\Handler\StreamHandler;

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
<<<<<<< HEAD
     * @var User
     */
    private User $userCollection;

    public function __construct(User $userCollection)
=======
     * @var UserCollection
     */
    private UserCollection $userCollection;

    public function __construct(UserCollection $userCollection)
>>>>>>> d63a2764be67c9a28dd6c0d98b681cc0903d1571
    {
        $this->logger = new Logger('registration-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
        $this->userCollection = $userCollection;
    }

    /**
     * Get Login page
     *
     * @return void
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
                $this->logger->info("The use {$this->userCollection->getName()} logged in successfully");

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
