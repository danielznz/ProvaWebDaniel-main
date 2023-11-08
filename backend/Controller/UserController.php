<?php

namespace App\Controller;

use App\Model\Model;
use Firebase\JWT\JWT;

class UserController {

    private $db;

    public function __construct() {
        $this->db = new Model();
    }
    public function select(){
        $user = $this->db->select('users');
        
        return  $user;
    }
    public function selectId($id){
        $user = $this->db->select('users',['id'=>$id]);
        
        return  $user;
    }
    public function insert($data){
        if($this->db->insert('users', $data)){
            return true;
        }
        return false;
    }
    public function update($newData,$condition){
        if($this->db->update('users', $newData, ['id'=>$condition])){
            return true;
        }
        return false;
    }
    public function delete( $conditions){
        if($this->db->delete('users', ['id'=>$conditions])){
            return true;
        }
        return false;
        
    }
    
    public function login($senha,$lembrar,$email) {
        
        $resultado = $this->db->select('users', ['email' => $email]);
        $checado=$lembrar? 60*12 : 3;
        if (!$resultado) {
            return ['status' => false, 'message' => 'Usuário não encontrado.'];
        }
        if (password_verify($senha, $resultado[0]['senha'])) {
            return ['status' => false, 'message' => 'Senha incorreta.'];
        }
        $key = "123";
        $algoritimo='HS256';
            $payload = [
                "iss" => "localhost",
                "aud" => "localhost",
                "iat" => time(),
                "exp" => time() + (60 * $checado),  
                "sub" => $email
            ];
            
            $jwt = JWT::encode($payload, $key,$algoritimo);
           
        return ['status' => true, 'message' => 'Login bem-sucedido!','token'=>$jwt];
    }
}
