<?php

namespace Gabela\Users\Controller;

use Gabela\Core\AbstractController;

class UsersController extends AbstractController
{
    public function users()
    {
        $this->getTemplate(USER_HOMEPAGE);   
    }

    public function edit()
    {
        $this->getTemplate(USER_UPDATE_PAGE);
    }

    public function profile()
    {
        $this->getTemplate(USER_PROFILES);
    }
}
