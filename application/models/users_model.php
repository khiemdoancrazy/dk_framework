<?php

class Users_Model extends Framework\Model {
    
    public function add_user() {
        $data = array(
            'username' => ucwords(strtolower($this->input->post('username'))),
            'email' => $this->input->post('email'),
            'password' => sha1($this->input->post('password'))
        );
        return $this->database->insert('users', $data);
    }
    
    public function check_user_exist() {
        $username = ucwords(strtolower($this->input->post('username')));
        $this->database->select(array('username'))->from('users');
        $this->database->where('username', '=', $username);
        return $this->database->count_all_results();
    }
    
    public function get_user() {
        $username = $this->input->post('username');
        $password = sha1($this->input->post('password'));
        $this->database->select(array('user_id', 'username', 'email'));
        $this->database->from('users')->limit(1);
        $this->database->where('username', '=', $username);
        $this->database->where('password', '=', $password);
        $result = $this->database->get();
        if ($result == null) {
            return null;
        } else {
            return $result[0];
        }
    }
    
    public function change_password() {
        $new_password = sha1($this->input->post('new_password'));
        $old_password = sha1($this->input->post('old_password'));
        
        $this->database->select(array('user_id'));
        $this->database->from('users')->limit(1);
        $this->database->where('user_id', '=', $this->session->get('user_id'));
        $this->database->where('password', '=', $old_password);
        if ($this->database->count_all_results() == 1) {
            $this->database->where('user_id', '=', $this->session->get('user_id'));
            $this->database->limit(1);
            $this->database->update('users', array('password' => $new_password));
            return true;
        } else {
            return false;
        }
    }
    
    public function delete_account() {
        $password = sha1($this->input->post('password'));
        $this->database->select(array('user_id'));
        $this->database->from('users')->limit(1);
        $this->database->where('user_id', '=', $this->session->get('user_id'));
        $this->database->where('password', '=', $password);
        if ($this->database->count_all_results() == 1) {
            $this->database->where('user_id', '=', $this->session->get('user_id'));
            $this->database->limit(1);
            $this->database->delete('users');
            return true;
        } else {
            return false;
        }
    }

//    public function get() {
//        $sql = "select * from users";
//        $sql = "SELECT `username`, `email` FROM `users` WHERE `user_id` = 1";
//        return $querys = $this->database->get($sql);
//    }
//
//    public function select() {
//        $this->database->select(array('username'));
//        $this->database->from('users')->where('username', '=', 'Khiem');
//        return $this->database->count_all_results();
//    }
//
//    public function delete() {
//        $this->database->where('user_id', '=', 18);
//        return $this->database->delete('users');
//    }
//
//    public function insert() {
//        $data = array(
//            'username' => 'Thuong',
//            'email' => 'a@v.com',
//            'password' => 'lalala',
//            'user_level' => '0'
//        );
//        return $this->database->insert('users', $data);
//    }
//
//    public function update() {
//        $this->database->where('email', '=', 'a@v.com')->limit(1);
//        $this->database->update('users', array('username' => 'Viet'));
//    }

}
