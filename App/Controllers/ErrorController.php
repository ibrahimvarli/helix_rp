<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * Error Controller
 */
class ErrorController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        // Load language files
        $this->loadLanguage('general');
    }
    
    /**
     * Display error page
     * 
     * @param int $code HTTP status code
     * @param string $message Error message
     */
    public function index($code = 404, $message = null)
    {
        // If no message provided, use default message for code
        if ($message === null) {
            switch ($code) {
                case 404:
                    $message = $this->lang('error_404');
                    break;
                case 403:
                    $message = $this->lang('error_403');
                    break;
                case 500:
                    $message = $this->lang('error_500');
                    break;
                default:
                    $message = $this->lang('error_message');
            }
        }
        
        $this->view('errors/error', [
            'code' => $code,
            'message' => $message
        ]);
    }
    
    /**
     * Display 404 Not Found error
     */
    public function notFound()
    {
        $this->index(404, $this->lang('error_404'));
    }
    
    /**
     * Display 403 Forbidden error
     */
    public function forbidden()
    {
        $this->index(403, $this->lang('error_403'));
    }
    
    /**
     * Display 500 Internal Server Error
     */
    public function serverError()
    {
        $this->index(500, $this->lang('error_500'));
    }
    
    /**
     * Display maintenance page
     */
    public function maintenance()
    {
        $this->view('errors/maintenance');
    }
} 