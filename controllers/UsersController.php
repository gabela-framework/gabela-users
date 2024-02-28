<?php

namespace Gabela\Users\Controller;

use Gabela\Core\AbstractController;

class UsersController extends AbstractController
{
    public function users()
    {
        $this->getTemplate(USER_HOMEPAGE);   
    }
}
