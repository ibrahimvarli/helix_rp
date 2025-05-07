<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * Home Controller
 */
class HomeController extends Controller
{
    /**
     * Display homepage
     */
    public function index()
    {
        $this->view('home/index');
    }
    
    /**
     * Display about page
     */
    public function about()
    {
        $this->view('home/about');
    }
    
    /**
     * Display features page
     */
    public function features()
    {
        $this->view('home/features');
    }
    
    /**
     * Display guide page
     */
    public function guide()
    {
        $this->view('home/guide');
    }
    
    /**
     * Display world map
     */
    public function world()
    {
        $this->view('home/world');
    }
} 