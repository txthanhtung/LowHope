<?php 
defined('BASEPATH') OR exit('No direct script access allowed');



class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();        
        //load google login library
        $this->load->library('google');
        $this->load->model('user');
    }

    /**
     * load first when login
     * @return [type] [description]
     */
    public function index()
    {
        //redirect to profile page if user already logged in
        if($this->session->userdata('loggedInGooge') == true){
            redirect('login/user/');
        }
        
        if(isset($_GET['code'])){
            //authenticate user
            $this->google->getAuthenticate();
            
            //get user info from google
            $gpInfo = $this->google->getUserInfo();
            
            //preparing data for database insertion
            $userGGData['USER_CIF']       = $gpInfo['id'];
            $userGGData['USER_NAME']      = $gpInfo['name'];
            $userGGData['USER_EMAIL']     = $gpInfo['email'];
            $userGGData['USER_LINK']      = !empty($gpInfo['link'])?$gpInfo['link']:'';
            $userGGData['USER_AVATAR']    = !empty($gpInfo['picture'])?$gpInfo['picture']:'';
            $userGGData['GOOGLE_USER']    = true;
            
            //check user if exist in DB -> redirect to choose screen
            
            //kiểm tra xem đã có email tồn tại trong hệ thống chưa
            $userGGExist = $this->user->checkUserExist($userGGData['USER_EMAIL']);            

            //store status & user info in session
            $this->session->set_userdata('loggedInGooge', true);
            $this->session->set_userdata('userGGExist', $userGGExist);
            $this->session->set_userdata('userGGData', $userGGData);
            
            //redirect to profile page
            redirect('login/user/');
        }
        
        //google login url
        $data['loginURL'] = $this->google->loginURL();     
        //load google login view
        $this->load->view('login/login_view',$data);
    }     

    /**
     * Kiểm tra user đã đăng nhập vào hệ thống chưa nếu chưa thì update thông tin
     * @return [type] [description]
     */
    public function user(){
        //redirect to login page if user not logged in
        if(!$this->session->userdata('loggedInGooge')){
            redirect('login/');
        }else {
            //get user info from session
            //$data['userGGData'] = $this->session->userdata('userGGData');
            //neu user ton tai roi thi chuyen sang man hinh chon loai choi
            if($this->session->userdata('userGGExist')){
                //load user profile view
                $user = $this->user->getUserByMail($this->session->userdata('userGGData')['USER_EMAIL']);
                $data['USER_NAME'] = $user->USER_NAME;
                $this->load->view('user/home', $data);
            }else{
                $this->load->view('user/updateUser');
            } 
        }        
    }

    /**
     * logout user ra khoi he thong
     * @return [type] [description]
     */
    public function logoutGoogle(){
        //delete login status & user info from session
        $this->session->set_userdata('loggedInGooge', false);
        $this->session->unset_userdata('loggedInGooge');
        $this->session->unset_userdata('userGGData');
        $this->session->sess_destroy();        
        
        redirect(base_url());
    }

    /*---------------------------------------  FACEBOOK  ---------------------------------------------------------*/
    /**
     * [fb_CheckUserExist description]
     * @return [type] [description]
     */
    public function fb_CheckUserExist()
    {

        //set session logged by fb
        $this->session->set_userdata('loggedInFB', true);
        
        $userFBData['USER_CIF'] = $this->input->post('fb_id');
        $userFBData['USER_NAME'] = $this->input->post('fb_name');
        $userFBData['USER_EMAIL'] = $this->input->post('fb_email');
        $userFBData['USER_LINK'] = $this->input->post('fb_link');
        $userFBData['USER_AVATAR'] = $this->input->post('fb_avatar');
        $userFBData['FB_USER']    = true;

        $this->session->set_userdata('userFBData', $userFBData);

        $userFBExist = $this->user->checkUserExist($userFBData['USER_EMAIL']);

        if($userFBExist){
            echo json_encode("1");
        }else{
            echo json_encode("0");
        }
    }

    /**
     * [fb_AddUser description]
     * @return [type] [description]
     */
    public function fb_AddUser()
    {
        $this->load->view('user/updateUser');
    }

    /**
     * [fb_goHome description]
     * @return [type] [description]
     */
    public function fb_goHome()
    {
        $user = $this->user->getUserByMail($this->session->userdata('userFBData')['USER_EMAIL']);
        $data['USER_NAME'] = $user->USER_NAME;
        $this->load->view('user/home', $data);
    }   
    /**
     * [fb_Logout description]
     * @return [type] [description]
     */
    public function fb_Logout()
    {
        $this->session->set_userdata('loggedInFB', false);
        $this->session->unset_userdata('loggedInFB');
        $this->session->unset_userdata('userFBData');
        $this->session->sess_destroy();
    }

    public function test()
    {
        $this->load->view('user/testListen');
    }
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */