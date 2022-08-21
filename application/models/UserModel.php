<?php

defined('BASEPATH') or exit('No direct script access allowed');


class UserModel extends CI_model
{
    private $database = 'WrestMost';
    private $collection = 'users';
    private $conn;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('MongoDB');
        $this->conn = $this->mongodb->getConn();
    }

    public function get_user_list()
    {
        try {
            $filter = [];
            $query = new MongoDB\Driver\Query($filter);
            $result = $this->conn->executeQuery($this->database.'.'.$this->collection, $query);
            return $result;
        } catch(MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while fetching users: ' . $ex->getMessage(), 500);
        }
    }

    public function get_user($_id)
    {
        try {
            $filter = ['_id' => new MongoDB\BSON\ObjectId($_id)];
            $query = new MongoDB\Driver\Query($filter);

            $result = $this->conn->executeQuery($this->database.'.'.$this->collection, $query);

            foreach ($result as $user) {
                return $user;
            }

            return null;
        } catch(MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while fetching user: ' . $ex->getMessage(), 500);
        }
    }

    public function create_user($name, $email,$role,$password)
    {
        try {
            $user = array(
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'password' => $password,
            );

            $query = new MongoDB\Driver\BulkWrite();
            $query->insert($user);

            $result = $this->conn->executeBulkWrite($this->database.'.'.$this->collection, $query);
            return true;

        } catch(MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while saving users: ' . $ex->getMessage(), 500);
        }
    }

    public function update_user($_id, $name, $email)
    {
        try {
            $query = new MongoDB\Driver\BulkWrite();
            $query->update(['_id' => new MongoDB\BSON\ObjectId($_id)], ['$set' => array('name' => $name, 'email' => $email)]);

            $result = $this->conn->executeBulkWrite($this->database.'.'.$this->collection, $query);


            return true;
        } catch(MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while updating users: ' . $ex->getMessage(), 500);
        }
    }

    public function delete_user($_id)
    {
        try {
            $query = new MongoDB\Driver\BulkWrite();
            $query->delete(['_id' => new MongoDB\BSON\ObjectId($_id)]);

            $result = $this->conn->executeBulkWrite($this->database.'.'.$this->collection, $query);

            if ($result == 1) {
                return true;
            }

            return false;
        } catch(MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while deleting users: ' . $ex->getMessage(), 500);
        }
    }
}
