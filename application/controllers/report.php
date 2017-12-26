<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Report extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();
    }

    public function customer()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("customerreport", $this->global, NULL, NULL);
    }

    public function paid()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("paidreport", $this->global, NULL, NULL);
    }

    public function balance()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("balancereport", $this->global, NULL, NULL);
    }

    public function billing()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("billingreport", $this->global, NULL, NULL);
    }

    public function onlinepayment()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("onlinepaymentreport", $this->global, NULL, NULL);
    }

    public function subscription()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("subscriptionreport", $this->global, NULL, NULL);
    }

    public function generalledger()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("generalledgerreport", $this->global, NULL, NULL);
    }


}

?>