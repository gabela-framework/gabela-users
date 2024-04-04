<?php

namespace Gabela\Users\Controller;

getRequired(USER_MODULE_MODEL);

use Gabela\Controller\EmailSenderController;
use Gabela\Core\EventDispatcher;
use Gabela\Core\Events\EmailSenderListener;
use Gabela\Core\Events\NewUserRegisteredEvent;
use Gabela\Users\Model\User;
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

                try {
                    if ($this->userCollection->save()) {
                        $_SESSION['registration_success'] = 'Heey!!! ' . $this->userCollection->getName() . ' you registered successfully. Please Login..';

                        $userId = $this->userCollection->getUserId();

                        $listener = new EmailSenderListener(new EmailSenderController());
                        $this->dispatcher->addListener('user_welcome_email', [$listener, '__invoke']);
                        $event = new NewUserRegisteredEvent((int) $userId);
                        $this->dispatcher->dispatch('user_welcome_email', $event); //uncomment this to dispatch emails to the new user when registering

                        redirect('/login');
                    }
                } catch (\Throwable $th) {
                    printValue('An error occurred. Please try again later.' . $th);
                    $_SESSION['registration_error'] = 'An error occurred while registering. This email address is already in use.';
                    $this->logger->critical($th);
                }
            }
        }

        return redirect('/');
    }
}
