<?php

namespace App;
use Facebook\Facebook;

class FacebookManager {
    private $fb_api;

    public function __construct(Facebook $facebook)
    {
        $this->fb_api = $facebook;
    }

    public function getFacebookPages($user) {
        $request = $this->fb_api->get('/me?fields=accounts', $user);
        $response = json_decode($request->getBody(), true);
        $pages = $response['accounts']['data'];

        return $pages;
    }

    public function postFacebookPage($request) {
        /*Array com o que será enviado ao facebook conforme o que é recebido na requisição.*/
        $payload = array();
        
        /*Ação a ser realizada conforme o que é recebido na requisição.*/
        $action = "";

        /*Verificar se existe o atributo agendamento na requisição e alterar o payload caso exista.*/
        if ($request['scheduling']) {
            $scheduling = strtotime($request['scheduling']);
            $payload['scheduled_publish_time'] = strtotime("+3 hour", $scheduling);
            $payload['published'] = 'false';
        }

        /*Verificar se existe o atributo imagem na requisição, alterar o payload caso exista,
        mover a imagem para diretório padrão, alterar o action para postar fotos.
        */
        if ($request->file('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalName();
            $folder = storage_path('images');
            $image->move($folder, $name);
            $absolute_path = $folder . '/' . $name;
            $payload['source'] = $this->fb_api->fileToUpload($absolute_path);
            $payload['message'] = $request['message'];
            $action = '/photos';
        }

        /*Verificar se existe o atributo mensage e não exista imagem na requisição, alterar o payload caso exista,
        alterar o action para postar mensagem sem fotos.
        */
        if ($request['message'] && !($request['image'])) {
            $payload['message'] = $request['message'];
            $action = '/feed';
        }

        /*Verificar se existe o payload. caso exista submeter a requisição para o facebook.*/
        if ($payload) {
            $page_id = $request['page_id'];
            $access_token = $request['page_token'];;

            $response = $this->fb_api->post(
                '/' . $page_id . $action,
                $payload,
                $access_token
            );

            if ($response->getHttpStatusCode() == 200) {
                return response()->json([
                    'response' => 'Postagem realizada'
                ], 200);
            }
        }

        /*Caso não exista payload, não realizar a requisição.*/
        return response()->json([
            'response' => 'Postagem não realizada'
        ], 406);
    }

    public function throwingFacebookRequestForm($request) {
        
    }
}