<?php

namespace Gabela\Users\Controller;

use Gabela\Core\AbstractController;

class LoginPageController extends AbstractController
{
    /**
     * Get Login page
     *
     * @return void
     */
    public  function index()
    {
        $this->getTemplate(USER_LOGIN_PAGE);
    }
}
