<?php

namespace App\Router;
require "../../vendor/autoload.php";

// use App\Controller\LoginController;
use App\Controller\UserController;
use App\Model\Usuario;

$usuario = new Usuario();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: * ' );
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, no-store, must-revalidate');

$body = json_decode(file_get_contents('php://input'), true);

switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
      
        if (isset($body['email'])) {
            $email = $body ['email'];
            
            $senha=$body['senha'];
            $lembrar=false;
            $usuariosController = new UserController();
            $resultado = $usuariosController->login($senha,$lembrar, $email);

            if(!$resultado['status']){
                echo json_encode(['status' => $resultado['status'], 'message' => $resultado['message']]);
               exit;
            }
            
            echo json_encode(['status' => $resultado['status'], 'message' => $resultado['message'], 'token'=>$resultado['token']]);
        }
        break;
        case "GET":
            $headers = getallheaders();
            $token = $headers['Authorization'] ?? null;
            $usuariosController = new UserController($usuario);
            $validationResponse = $usuariosController->validarToken($token);
            if ($token === null || !$validationResponse['status']) {
                echo json_encode(['status' => false, 'message' => $validationResponse['message']]);
                exit;
            }
            echo json_encode(['status' => true, 'message' => 'Token válido']);
            exit;
            break; 
}