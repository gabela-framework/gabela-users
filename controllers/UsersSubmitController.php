<?php

namespace Gabela\Users\Controller;

use Exception;

getRequired(USER_MODEL);

use Gabela\Model\User;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class UsersSubmitController
{
    private $logger;

    /**
     * @var User
     */
    private User $userCollection;

    public function __construct(User $userCollection)
    {
        $this->logger = new Logger('users-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
        $this->userCollection = $userCollection;
    }

    public function submit()
    {
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('/index');
        }

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Use setters to update user properties
            $this->userCollection->setUserId($_POST['id']);
            $this->userCollection->setName($_POST['name']);
            $this->userCollection->setCity($_POST['city']);
            $this->userCollection->setRole($_POST['role']);

            try {
                // Update the user in the database
                if ($this->userCollection->update()) {
                    $this->logger->info('User {' . $this->userCollection->getName() . '} is updated succesfully.');

                    // Redirect back to edit page with success message
                    return redirect("/users?id={$_POST['id']}&edit_success=1");
                }
            } catch (Exception $e) {
                $this->logger->error('An exception occurred', ['exception' => $e]);
                throw new  Exception($e);
            }
        }
    }
}
