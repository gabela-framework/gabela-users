<?php

namespace Gabela\Users\Controller;

use Gabela\Core\AbstractController;

class UsersEditController extends AbstractController
{
    public function edit()
    {
        $this->getTemplate(USER_UPDATE_PAGE);
    }
}
