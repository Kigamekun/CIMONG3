<?php

defined('BASEPATH') or exit('No direct script access allowed');


class UserController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
		
    }

    public function index()
    {
        $data['users'] = $this->UserModel->get_user_list();
		
        $this->load->view('users', $data);
    }

    public function create()
    {
        if (isset($_POST)) {
            $result = $this->UserModel->create_user($this->input->post('name'), $this->input->post('email'),$this->input->post('role'),$this->input->post('password'));
			redirect('/UserController');
        } 
    }

    public function update($_id)
    {
		if (isset($_POST)) {
			$result = $this->UserModel->update_user($_id, $this->input->post('name'), $this->input->post('email'),$this->input->post('role'),$this->input->post('password'));
			redirect('/UserController');
		}
    }

    public function delete($_id)
    {
        if ($_id) {
            $this->UserModel->delete_user($_id);
        }
        redirect('/UserController');
    }
}
