<?php namespace App\Controllers;

use CodeIgniter\Controller;

class BaseController extends Controller
{
    protected $helpers = ['form', 'url', 'auth'];

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Add global CSRF token to all views
        if (session()->has('csrf_token')) {
            $this->setVar('csrf_token', session()->csrf_token);
        }
    }
}