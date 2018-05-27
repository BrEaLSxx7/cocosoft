<?php
require_once "model/AsistenteModel.php";
require_once "libs/Response.php";

class AsistenteController extends AsistenteModel
{
    public function main(Request $request)
    {
        $data = $request->getPost();
        $this->addTable(['nombre' => $data['grupo']], 'grupo');
        $ansuwe = $this->getTable(['id'], 'grupo', ['nombre' => 'ADSI']);
        for ($i = 0; $i < count($data['date']); $i++) {
            $data['date'][$i]['fk_grupo_id'] = (int)$ansuwe[0]->id;
            $answer = $this->addTable($data['date'][$i], 'asistente');
            var_dump($data['date'][$i]);
        }
        $res = new Response();
        $res->response($answer, 200);
    }

}