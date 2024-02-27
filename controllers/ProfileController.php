<?php

namespace Gabela\Users\Controller;

use Gabela\Core\AbstractController;

class ProfileController extends AbstractController
{
    public function profile()
    {
        $this->getTemplate(USER_PROFILES);
    }
}