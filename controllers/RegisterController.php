<?php

namespace Gabela\Users\Controller;

getRequired(USER_MODEL);

use Gabela\Model\User;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class RegisterController
{
    private $db;
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger('registration-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
    }

    public function register()
    {
        $user = new User();

        // Handle login form submission logic
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // User clicked the "Create User" button
            $name = $_POST['name'];
            $city = $_POST['city'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check if the password meets certain criteria
            if ($user->validatePassword($password)) {
                // Password is valid, create the user
                $user->setName($name);
                $user->setCity($city);
                $user->setEmail($email);
                $user->setPassword($password);

                $this->logger->error(var_export($this->db, true));

                try {
                    if ($user->save()) {
                        $_SESSION['registration_success'] = 'Heey!!! ' . $user->getName() . ' you registered successfully. Please Login..';
                        redirect('/');
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