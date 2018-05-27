<?php
require_once 'libs/Request.php';

class FrontController
{
    private $folder = '';
    private $controller = '';

    public function run()
    {
        $this->friendUrl();
        $request = $this->request();
        $this->LoadController();
        $controller = new $this->controller();
        $controller->main($request);
    }

    private function LoadController()
    {
        require_once __DIR__ . '../../Controller/' . $this->folder . '/' . $this->controller . '.php';
    }

    private function request()
    {
        $post = array();
        $get = array();
        $put = array();
        $delete = array();
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $post = $_REQUEST;
        } else {
            if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'GET') {
                $get = $_REQUEST;
            } else {
                if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'PUT') {
                    $put = $_REQUEST;
                } else {
                    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'DELETE') {
                        $delete = $_REQUEST;
                    }
                }
            }
        }
        return new Request($post, $get, $put, $delete);
    }

    private function friendUrl()
    {
        $load = explode("/", $_SERVER['PATH_INFO']);
        $this->folder = $load[1];
        $this->controller = $load[1] . 'Controller';
    }
    protected function config(){
        $gestor=fopen('config/.env', 'r');
        $config=explode('
',fread($gestor,filesize("config/.env")));
        $driver = [];
        foreach($config as $key=>$value){
            array_push($driver,explode("=",$value));
        }
        $drive=[];
        for ($i = 0; $i < count($driver); $i++) {
            array_push($drive, $driver[$i][1]);
        }
        $tmp = count($drive) - 1;
        unset($drive[$tmp]);
        return $drive;
    }
}