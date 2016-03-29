<?php
class Auth extends Application
{
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->data['pagebody'] = 'login';
        $this->render();
    }
}