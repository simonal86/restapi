<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Usuario extends ResourceController
{
    protected $format    = 'json';

    public function index()
    {
        $parametro ='';
        
        if (isset($_GET['nombre'])) {
            $parametro='?name='.$_GET['nombre'];
        }
        if (isset($_GET['email'])) {
            $parametro='?email='.$_GET['email'];
        }
        if (isset($_GET['activo'])) {
            $status = '';
            if (strrpos('true', $_GET['activo']) === false) {
                $status = 'inactive';
            }
            if (strrpos('false', $_GET['activo']) === false) {
                $status = 'active';
            }
            $parametro='?status='.$status;
        }
        $url = $this->apiUrl($parametro);
        $datos = file_get_contents($url);
        $datos = json_decode($datos,true); 
        $datos = $this->genericContempora($datos);
        return $this->genericResponse($datos,'',200);
    }
    public function show($id = null)
    {
        $parametro='/' . $id;
        $url = $this->apiUrl($parametro);
        $datos = file_get_contents($url);
        $datos = json_decode($datos,true); 
        $datos = $this->genericContempora($datos);
        return $this->genericResponse($datos,'',200);
    }
    public function apiUrl($parametro){
        $url = 'https://gorest.co.in/public/v2/users';
        $res = $url . $parametro;
        return $res;
    }
    public function genericContempora($datos)
    {
        if (count($datos)>5) {
            foreach ($datos as $d)
            {
                $status = ($d['status']=='active') ? 'true' : 'false';
                $nuevo[]=array('id'=>$d['id'],'nombre'=>$d['name'],'email'=>$d['email'],'genero'=>$d['gender'],'activo'=>$status );
            }
            
        }else{
            $status = ($datos['status']=='active') ? 'true' : 'false';
            $nuevo[]=array('id'=>$datos['id'],'nombre'=>$datos['name'],'email'=>$datos['email'],'genero'=>$datos['gender'],'activo'=>$status );
        }
        return $nuevo;
    }
    public function genericResponse($data, $msj, $code)
    {

        if ($code == 200) {
            return $this->respond(array(
                
                "msj" => 'Codigo Respuesta: '.$code,
                "data" => $data
            ));
        } else{
            return $this->respond(array(
                "msj" => $msj,
                "code" => $code
            ));
            
        }

    }


}