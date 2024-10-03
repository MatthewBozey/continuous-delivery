<?php

namespace App\Http\Controllers\KronTm;

use App\Http\Controllers\Controller;

class KronTmController extends Controller
{
    protected $user;

    protected $password;
    protected $deployControlUrl;

    public function __construct()
    {
        $this->user = getenv('DEPLOY_CONTROL_USERNAME');
        $this->password = getenv('DEPLOY_CONTROL_PASSWORD');
        $this->deployControlUrl = getenv('DEPLOY_CONTROL_URL');
    }
}
