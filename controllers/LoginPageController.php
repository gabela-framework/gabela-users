<?php

namespace Gabela\Users\Controller;

use Gabela\Core\AbstractController;

class LoginPageController extends AbstractController
{
    
    public  function index()
    {
        $this->getTemplate(HOME_PAGE);
    }
}
