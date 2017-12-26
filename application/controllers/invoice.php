<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/xmlrpc.inc';
require APPPATH . '/libraries/xmlrpcs.inc';
require APPPATH . '/libraries/ccave/Payment.php';
require APPPATH . '/libraries/NetworkAPI.php';
require APPPATH . '/libraries/dompdf/autoload.inc.php';
require APPPATH . 'libraries/PHPMailer/src/SMTP.php';
require APPPATH . 'libraries/PHPMailer/src/Exception.php';
require APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';

class Invoice extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('transaction');
        $this->load->model('cronJob');
        $this->load->model('customersubmodel');
        $this->load->model('tbl_plans');
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("invoice", $this->global, NULL, NULL);
    }

    public function invoiceList()
    {
        $this->global['pageTitle'] = 'CodeInsect : Customer';

        $this->loadViews("invoice", $this->global, NULL, NULL);
    }

    public function invoicedetails()
    {
        if ($_POST['id'] == '') {

        } else {
            $data = array(
                'column' => 'id',
                'value' => $_POST['id'],
            );
            $request_result = $this->transaction->getTranslationData($data);
            $network = new NetworkAPI();
            $customer_data = $network->getAccountDetails($request_result[0]->actid);
            echo $customer_data['email'];
        }
    }
    public function datasend(){
        var_dump($_POST);
        echo "hello";
    }
}

?>