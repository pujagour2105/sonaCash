<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Template;

use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Asia/Kolkata');

error_reporting(E_ALL);
/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    public $template, $model, $form_validation, $parser, $session;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['general'];

    public function __construct()
    {
        //main template to make frame of this app
        $this->template = new Template();

        //load helpers
        helper(array('url', 'file', 'form', 'language', 'general', 'date_time', 'db_helper', 'notification_helper')); // , 'app_files', 'widget', 'activity_logs', 'currency'

        //models
        $models_array = $this->get_models_array();
        foreach ($models_array as $model) {
            $this->model = model("App\Models\\" . $model);
        }

        //$login_user_id = $this->Users_model->login_user_id();

        //assign settings from database
        //$settings = $this->Settings_model->get_all_required_settings($login_user_id)->getResult();
        //foreach ($settings as $setting) {
        //    config('Rise')->app_settings_array[$setting->setting_name] = $setting->setting_value;
        //}

        //assign language
        //$language = get_setting('user_' . $login_user_id . '_personal_language') ? get_setting('user_' . $login_user_id . '_personal_language') : get_setting("language");
        //service('request')->setLocale($language);


        // $this->session = \Config\Services::session();
        $this->form_validation = \Config\Services::validation();
        $this->parser = \Config\Services::parser();
    }
    
    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = \Config\Services::session();
        $this->checkSession(); 
    }

    private function get_models_array()
    {
        return array(
            'Crud_model',
            // 'Users_model'
        );
    }

    protected function checkSession()
    {
        // Example: check if any of the required session keys are missing or empty
        $requiredKeys = ['id', 'name']; // Add more keys as needed

        foreach ($requiredKeys as $key) {
           
            if (!$this->session->has($key) || empty($this->session->get($key))) {
                // echo "<script>console.log('Session key missing or empty: $key');</script>";
                // $this->session->setFlashdata('error', 'Session expired. Please log in again.');
                header('Location: ' . base_url('/')); // or just '/'
            }
        }
    }
    
    
}
