<?php
// app/Controllers/BaseController.php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Check if user is logged in for protected routes
        $session = session();
        $currentRoute = $this->request->getUri()->getPath();
        
        $publicRoutes = ['/login', '/logout', '/'];
        
        if (!in_array($currentRoute, $publicRoutes) && !$session->get('logged_in')) {
            if ($this->request->isAJAX()) {
                $response->setJSON(['error' => 'Not authenticated'])->setStatusCode(401);
                return;
            } else {
                redirect()->to('/login')->send();
                exit;
            }
        }
    }
}
