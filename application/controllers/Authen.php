<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Authen extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('html');
        $this->load->library('email');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Users_model');
    }
    
    public function login(){
        if(($username=$this->input->post('username'))){
             if($this->Users_model->hasUserName($username)){
                    $this->generateOTPCode();
                    if($this->generateOTPCodeSegments()){
                        $this->session->set_userdata('hasOTPSent',true);
                        echo "OK";
                    }
                    else{
                        echo "NOK";
                    }
             }
             else{
                 $page=array('page'=>'login');
                 $error=array('error'=>'The username is not exist.');
                 $this->loadView('authen/login_page',$error,$page,$page);
             }
        }
        else{
             $page=array('page'=>'login');
             $this->loadView('authen/login_page',null,$page,$page);
             return;
        }
    }
    
    
     private function generateOTPCodeSegments(){
         $emails=$this->Users_model->getUserEmail();
         $numOfEmails=count($emails);
         $otpCode=$this->Users_model->getOtpCode();
         $userName=$this->Users_model->getUserName();
         $segmentPerEmail=(strlen($otpCode)/$numOfEmails);
         $otpCode_segments=array();
         $i=0;
         $sentCount=0;
         for($i;$i<$numOfEmails;$i++){     
             $start=($i*$segmentPerEmail); 
             $otpCode_segments[$i]=substr($otpCode,$start,$segmentPerEmail);
             if($i==2){
                $otpCode_segments[$i].=substr($otpCode,-1); 
             }
             $result=$this->email->from('wan_kik321@hotmail.com','Kukkik Wannida')
                                ->to($emails[$i])
                                ->subject('OTP Code for '.$userName.' access')
                                ->message('The OTP code is '.$otpCode_segments[$i])
                                ->send();
             if($result){
                 $sentCount++;
             }
         }
         if($sentCount==$numOfEmails){
             return true;
         }
         else{
             return false;
         }
     }
    
    
    
     private function generateOTPCode(){
         $otpKey=$this->Users_model->getOtpKey();
         $otpKey_length=strlen($otpKey);
         $secret_key="";
         for($i=1;$i<$otpKey_length;$i++){
             $secret_key.=chr(rand(33,126));
         }
         $otpCode=hash('sha256',(time().$otpKey.$secret_key));
         $this->Users_model->setOtpCode($otpCode);
         $current_timestamp_obj = new DateTime();
         $current_timestamp_obj->modify('+30 minutes');    
         $current_timestamp=$current_timestamp_obj->format('Y-m-d h:i:s');
         $this->Users_model->setOtpExpired($current_timestamp);
         if($this->Users_model->save()){
             return true;
         }
         else{
             return false;
         }
     }
    
    private function loadView($viewPath,$arg=null,$headerArg=null,$footerArg=null){
        $this->load->view('template/header',$headerArg);
        $this->load->view($viewPath,$arg);
        $this->load->view('template/footer',$footerArg);
    }
}