<?php

namespace Gabela\Users\Controller;

// getRequired(USER_MODULE_MODEL);

use Gabela\Core\AbstractController;
use Gabela\Model\User;

class UsersController extends AbstractController
{
    /**
     * @var User
     */
    private User $userCollection;

    public function __construct(User $userCollection)
    {
        $this->userCollection = $userCollection;
    }

    /**
     * Users tempalte
     *
     * @return void
     */
    public function users()
    {
        $allusers = $this->userCollection->getallusers();
        $city = $this->userCollection->getWeatherCity();
        $data = [
            'tittle' => 'Users page',
            'allUsers' => $allusers,
            'weatherCity' => $city
        ];
        $this->renderTemplate(USER_HOMEPAGE, $data);
    }

    public function edit()
    {
        $city = $this->userCollection->getWeatherCity();
        $data = [
            'tittle' => 'Edit users - Gabela Framework',
            'weatherCity' => $city
        ];
        $this->renderTemplate(USER_UPDATE_PAGE, $data);
    }

    public function profile()
    {
        // $this->getTemplate(USER_PROFILES);
        $userData = [
            'data' => $this->userCollection->getUserById($_GET['user_id'])
        ] ;

        $this->renderTemplate(USER_PROFILES, $userData);
    }
}
