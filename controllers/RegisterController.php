<?php

namespace Gabela\Users\Controller;

getRequired(USER_MODULE_MODEL);

use Gabela\Core\ClassManager;
use Gabela\Core\Events\NewUserRegisteredEvent;
use Gabela\Users\Model\User;
use League\Event\EventDispatcher;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class RegisterController
{
    private $logger;

    /**
     * @var EventDispatcher
     */
    private EventDispatcher $dispatcher;

    /**
     * @var User
     */
    private User $userCollection;

    /**
     * Register constructor
     *
     * @param EventDispatcher $dispatcher
     * @param User $userCollection
     */
    public function __construct(EventDispatcher $dispatcher, User $userCollection)
    {
        $this->logger = new Logger('registration-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
        $this->userCollection = $userCollection;
        $this->dispatcher = $dispatcher;
    }

    public function register()
    {

        // Handle login form submission logic
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // User clicked the "Create User" button
            $name = $_POST['name'];
            $city = $_POST['city'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check if the password meets certain criteria
            if ($this->userCollection->validatePassword($password)) {
                // Password is valid, create the user
                $this->userCollection->setName($name);
                $this->userCollection->setCity($city);
                $this->userCollection->setEmail($email);
                $this->userCollection->setPassword($password);

                $this->logger->error(var_export($this->db, true));

                try {
                    if ($this->userCollection->save()) {
                        $_SESSION['registration_success'] = 'Heey!!! ' . $this->userCollection->getName() . ' you registered successfully. Please Login..';

                        $userId = $this->userCollection->getUserId();

                        $event = new NewUserRegisteredEvent((int) $userId);
                        $this->dispatcher->dispatch($event); //uncomment this to dispatch emails to the new user when registering

                        redirect('/login');
                    }
                } catch (\Throwable $th) {
                    printValue('An error occurred. Please try again later.' . $th);
                    $_SESSION['registration_error'] = 'An error occurred while registering. This email address is already in use.';
                    $this->logger->critical(var_export($th, true));
                }
            }
        }

        return redirect('/');
    }
}
