<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Member extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('html');
        $this->load->helper('url');
    }
    
    public function hasUsername(){
        if($this->session->userdata('username')){
            if($username=$this->input->post('username')){
                if($this->Users_model->hasUsernameNotInclude(urldecode($username))){
                    echo "1";
                }
                else{
                    echo "0";
                }
            }
            else{
               redirect('main','refresh');
            }
        }
        else{
            redirect('authen/login','refresh');
        }
    }
    
    public function hasEmail(){
        if($this->session->userdata('username')){
            if($email=$this->input->post('email')){
                if($this->Users_model->hasEmailNotInclude(urldecode($email))){
                    echo "1";
                }
                else{
                    echo "0";
                }
            }
            else{
               redirect('main','refresh');
            }
        }
        else{
            redirect('authen/login','refresh');
        }
    }
    
    public function hasKey(){
        if($this->session->userdata('username')){
            if($uniqKey=$this->input->post('uniqKey')){
                if($this->Users_model->hasUniqueKeyNotInclude(urldecode($uniqKey))){
                    echo "1";
                }
                else{
                    echo "0";
                }
            }
            else{
               redirect('main','refresh');
            }
        }
        else{
            redirect('authen/login','refresh');
        }
    }
    
    public function update(){
        if($this->session->userdata('username')){
            if($this->input->post('username') && $this->input->post('email1') && $this->input->post('email2') 
               && $this->input->post('email3') && $this->input->post('uniqKey')){
               $username=$this->input->post('username');
               $email1=$this->input->post('email1');
               $email2=$this->input->post('email2');
               $email3=$this->input->post('email3');
               $email4=null;
               if($this->input->post('email4')){
                   $email4=$this->input->post('email4');
               }
               $email5=null;
               if($this->input->post('email5')){
                   $email5=$this->input->post('email5');
               }
               $uniqKey=$this->input->post('uniqKey');
                
               if($this->Users_model->canUpdate($username,$email1,$email2,$email3,$email4,$email5,$uniqKey)){
                   
                   $successMessage='Your information has been already updated.';
                   
                   $this->session->set_userdata('username',$username);
                   
                   $this->Users_model->hasUserName($username);
            
                   $emails=$this->Users_model->getUserEmail();
            
                   $uniqKey=$this->Users_model->getOtpKey();
            
                   $main_page_arg=array('emails'=>$emails,
                                        'uniq_key'=>$uniqKey,
                                        'successMessage'=>$successMessage);
                   
                   $pageUsername=array('page'=>'member','username'=>$username);
                   
                   $page=array('page'=>'member','numberOfEmails'=>count($emails));
            
                   $this->loadView('main/main_page',$main_page_arg,$pageUsername,$page);
                   
               }
               else{
                   
                   $errorMessage='Could not update the information, please try again.';
                   
                   $this->Users_model->hasUserName($username);
            
                   $emails=$this->Users_model->getUserEmail();
            
                   $uniqKey=$this->Users_model->getOtpKey();
            
                   $main_page_arg=array('emails'=>$emails,
                                        'uniq_key'=>$uniqKey,
                                        'errorMessage'=>$errorMessage);
                   
                   $pageUsername=array('page'=>'member','username'=>$username);
                   
                   $page=array('page'=>'member','numberOfEmails'=>count($emails));
            
                   $this->loadView('main/main_page',$main_page_arg,$pageUsername,$page);
                                
               }
            }
            else{
               redirect('main','refresh'); 
            }
        }
        else{
           redirect('authen/login','refresh'); 
        }
    }
    
    private function loadView($viewPath,$arg=null,$headerArg=null,$footerArg=null){
        $this->load->view('template/header',$headerArg);
        $this->load->view($viewPath,$arg);
        $this->load->view('template/footer',$footerArg);
    }
    
}