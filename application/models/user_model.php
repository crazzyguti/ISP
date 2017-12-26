<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $usr_where;
    function __construct()
    {
        parent::__construct();
        $this->usr_where = 'created_by';
        if($this->session->userdata ( 'roleText' )  != 'System Administrator')
        {
            $this->usr_where = $this->session->userdata ( 'userId' );
        }
    }
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function getDashboardData()
    {
        $today = date('Y-m-d');
        $today_month = date('m-Y');
        $today_total = $this->db->query("SELECT SUM(amount) as today_total FROM `tbl_transaction` WHERE status='success' AND DATE(created_at) = '$today' AND created_by=$this->usr_where ")->result_array()[0]['today_total'];

        $weekly_total = $this->db->query("SELECT SUM(amount) as weekly_total FROM `tbl_transaction` WHERE status='success' AND YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1)  AND created_by=$this->usr_where ")->result_array()[0]['weekly_total'];

        $month_total = $this->db->query("SELECT SUM(amount) as month_total FROM `tbl_transaction` WHERE status='success' AND DATE_FORMAT(created_at,'%m-%Y') = '$today_month'  AND created_by=$this->usr_where ")->result_array()[0]['month_total'];

        $remaing_balance = $this->db->query("SELECT SUM(balance) as remaing_balance FROM `tbl_account` WHERE balance < 0")->result_array()[0]['remaing_balance'];



        $total_subs_req = $this->db->query("SELECT count(Sr_no) as total_subs_req FROM `tbl_subscr_request`")->result_array()[0]['total_subs_req'];
        //$upcoming_rec = $this->db->query("SELECT count(id) as upcoming_rec FROM `tbl_transaction` WHERE activatetype != 'immediate' AND DATE(transaction_date) > '$today'")->result_array()[0]['upcoming_rec'];
        $upcoming_rec = $this->db->query("SELECT count(id) as upcoming_rec FROM `cronJob` WHERE status='new'")->result_array()[0]['upcoming_rec'];

        $response = array(
            'today_total' => $today_total =="" ? 0 : $today_total,
            'weekly_total' => $weekly_total =="" ? 0 : $weekly_total,
            'month_total' => $month_total =="" ? 0 : $month_total,
            'remaing_balance' => $remaing_balance =="" ? 0.00 : $remaing_balance,
            'total_subs_req' => $total_subs_req =="" ? 0 : $total_subs_req,
            'upcoming_rec' => $upcoming_rec =="" ? 0 : $upcoming_rec,
        );

        return $response;

    }


    function todayCollection()
    {
        $today = date('Y-m-d');
        $today_total = $this->db->query("SELECT SUM(amount) as today_total FROM `tbl_transaction` WHERE status='success' AND amount != '' AND DATE(created_at) = '$today' AND created_by = $this->usr_where ")->result_array()[0]['today_total'];
        return $today_total == "" ? 0 : $today_total;
    }

    function todayCollectionList()
    {
        $today = date('Y-m-d');
        return $this->db->query("SELECT * FROM `tbl_transaction` WHERE status='success' AND DATE(created_at) = '$today' AND created_by = $this->usr_where")->result_array();
    }

    function upcomingData()
    {
        $plan_query = $this->db->query("SELECT a.id as 'cid',t.*,p.*,a.*,a.status as done_status FROM cronJob a JOIN tbl_transaction t on a.transaction_id=t.id JOIN tbl_plans p on t.plan_id=p.Sr_no  AND created_by = $this->usr_where")->result_array();
        return $plan_query;
    }


    function userListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();

        return count($query->result());
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function userListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);
        $this->db->where("isDeleted", 0);
        if($userId != 0){
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_users', $userInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return TRUE;
    }



    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }


    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');

        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }

    function addEmployee($data)
    {
        $this->db->insert('tbl_users', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function listEmployee()
    {
        return $this->db->query("SELECT * FROM `tbl_users` as tu JOIN tbl_roles as tr ON  tu.`roleId` = tr.roleId AND role != 'System Administrator'")->result_array();
    }

    function deleteEmployee($id)
    {
        $this->db->trans_start();
        $this->db->where('userId', $id);
        $this->db->update('tbl_users', array('isDeleted' => 1));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}

  