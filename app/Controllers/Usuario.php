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
    public function create()
    {
        if ($this->validate('usuario')) {
            $status = '';
            if (strrpos('true', $_POST['activo']) === false) {
                $status = 'inactive';
            }
            if (strrpos('false', $_POST['activo']) === false) {
                $status = 'active';
            }
            $parametros = [
                'name' => $_POST['nombre'],
                'gender' => $_POST['genero'],
                'email'  => $_POST['email'],
                'status' => $status
            ];
            $datos = $this->curlContempora('POST', $parametros);

            if (isset($datos[0]['message']) == 'has already been taken') {  
                $msj = array('campo' => 'email','error' => 'Ya existe el registro.'); 
                return $this->genericResponse($msj,'',200);  
            }
            $datos = $this->genericContempora($datos);
            return $this->genericResponse($datos,'',200);
        }
        $validation = \Config\Services::validation();

        return $this->genericResponse(null, $validation->getErrors(), 500);
    } 
    public function update($id = null)
    {
        if ($this->validate('usuario')) {
            $data = $this->request->getRawInput();
            $status = '';
            if (strrpos('true', $data['activo']) === false) {
                $status = 'inactive';
            }
            if (strrpos('false', $data['activo']) === false) {
                $status = 'active';
            }
            $parametros = [
                'name' => $data['nombre'],
                'gender' => $data['genero'],
                'email'  => $data['email'],
                'status' => $status
            ];
            $datos = $this->curlContempora('PUT', $parametros, $id);
            if (isset($datos[0]['message']) == 'has already been taken') {  
                $msj = array('campo' => 'email','error' => 'Ya existe el registro.'); 
                return $this->genericResponse($msj,'',200);  
            }
            $datos = $this->genericContempora($datos);
            return $this->genericResponse($datos, null, 200);

        }
        $validation = \Config\Services::validation();
        return $this->genericResponse(null, $validation->getErrors(), 500);
    }
    public function delete($id = null)
    {
        $datos = $this->curlContempora('DELETE',0,$id);
        return $this->genericResponse('Registro '. $id . ' borrado.',null,200);
    }
    public function apiUrl($parametro)
    {
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
    public function curlContempora($metodo, $parametros, $id = null)
    {
        
        if ($metodo=='PATCH' || $metodo == 'PUT') {
            $id='/'.$id;
        }
        if (isset($parametros)!='' && $parametros != 0) {
            $parametros = http_build_query($parametros);
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://gorest.co.in/public/v2/users'.$id); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER , array('Authorization: Bearer a3406c9d3a9cb8395b83cea4ac27a3ebeafde3005bb8857c0e2fa095276b1232')); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        $data = curl_exec($ch); 
        curl_close($ch); 
        $data = json_decode($data,true);
        return $data;

    }


}