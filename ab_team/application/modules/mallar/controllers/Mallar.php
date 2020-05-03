<?php
defined('BASEPATH') OR exit('No direct script access allowed ');
class Mallar extends CI_Controller {
  
    function __construct() {
        parent::__construct(); 
		$this->load->model('Mallar_model');
		$this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    /**
      * This function is redirect to 
      * @return Void
      */
    public function index() {
      is_login();
      $this->load->view('include/header');
      $this->load->view('mallar');
      $this->load->view('include/footer');
    }

    public function dataTable (){
        is_login();
        $id = $this->session->userdata ('user_details')[0]->users_id;
        $type = $this->session->userdata ('user_details')[0]->user_type;
        $table = 'mallar';
        $primaryKey = 'id';
        $columns = array(
           array( 'db' => 'id', 'dt' => 0 ), array( 'db' => 'date', 'dt' => 1 ),
           array( 'db' => 'title', 'dt' => 2 ), array( 'db' => 'users_id', 'dt' => 3 ),
           array( 'db' => 'file_path', 'dt' => 4 )           
        );

        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
        );
        $where = '';
        
        $output_arr = SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, $where);
        foreach ($output_arr['data'] as $key => $value) {
            $users_id = $output_arr['data'][$key][3];
            $result = $this->Mallar_model->get_users($users_id);
            $user_name = $result[0]->name;
            $preview = '<a href="'.base_url().$output_arr['data'][$key][4].'" class ="mClass" style="cursor:pointer;" target="_blank"><i class="fa fa-eye" ></i></a>';
            $output_arr['data'][$key][0] = '<input type="checkbox" name="selData" value="'.$output_arr['data'][$key][0].'">';
            $output_arr['data'][$key][3] = $user_name;
            $output_arr['data'][$key][4] = $preview;
        }
        echo json_encode($output_arr);
    }

    public function uploadFile($fielName) {
      $filename=$_FILES[$fielName]['name'];
      $tmpname=$_FILES[$fielName]['tmp_name']; 
      $exp=explode('.', $filename);
      $ext=end($exp);
      $newname=  $exp[0].'_'.time().".".$ext; 
      $config['upload_path'] = 'mallar_data/';
      $config['upload_url'] =  base_url().'mallar_data/';
      $config['allowed_types'] = "pdf";
      $config['max_size'] = '9000000';
      $config['file_name'] = $newname;
      $this->load->library('upload', $config);
      move_uploaded_file($tmpname,"mallar_data/".$newname);
      return $newname;
    }

    public function upload_mallar() { 
      $data =array();
      if(!empty($_FILES['pdf_upload']['name'])){
        $newname=$this->uploadFile('pdf_upload');
        $data['date']=date("d-m-Y");
        $data['title']=$newname;
        $data['users_id']=$this->user_id;
        $data['file_path']='mallar_data/'.$newname;
        $this->Mallar_model->insertRow('mallar', $data);
        redirect(base_url().'mallar', 'refresh');
      } else {
        redirect(base_url().'mallar', 'refresh');
      }
    }
}
?>