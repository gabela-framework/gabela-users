<?php

namespace Gabela\Users\Controller;

getRequired(USER_MODEL);

use Monolog\Logger;
use Gabela\Users\Model\User;
use Gabela\Core\ClassManager;
use Monolog\Handler\StreamHandler;

class UsersDeleteController
{
    private $logger;

    /**
     * @var ClassManager
     */
    private $classManager;
    public function __construct()
    {
        $this->logger = new Logger('delete-user-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
        $this->classManager = new ClassManager();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $userId = $_GET['id'];

            $user = $this->classManager->createInstance(User::class);

            if ($user->delete($userId)) {
                $this->logger->info('User {' . $user->getName() . '} is deleted succesfully.');

                return redirect('/users?delete_success=1');
            } else {
                printValue("Failed to delete the user.");
            }
        } else {
            printValue("Invalid request.");
        }
    }
}
