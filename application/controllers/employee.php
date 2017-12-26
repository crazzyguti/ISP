<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Employee extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("complaint", $this->global, NULL, NULL);
    }

    public function add()
    {
        $this->global['pageTitle'] = 'CodeInsect : Pending';

        $this->loadViews("addemployee", $this->global, NULL, NULL);
    }

    public function view()
    {
        $this->global['pageTitle'] = 'CodeInsect : Activated';

        $this->loadViews("empview", $this->global, NULL, NULL);
    }


}
