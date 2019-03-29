<?php

namespace App;

use Facebook\Facebook;

class FacebookManager
{
    private $fb_api;

    public function __construct(Facebook $facebook)
    {
        $this->fb_api = $facebook;
    }

    /**
     * Método responsável por obter as páginas do usuário logado com o Facebook.
     * @param Auth user
     */
    public function getFacebookPages($user)
    {
        try {
            $request = $this->fb_api->get('/me?fields=accounts', $user);
            $response = json_decode($request->getBody(), true);
            $pages = $response['accounts']['data'];

            return $pages;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Enviar postagens para o Facebook.
     * @param Request $request
     * @param array $payload
     * @param string $action
     */
    public function postFacebookPage($request, $payload, $action)
    {
        try {
            /*Verificar se existe o payload. caso exista submeter a requisição para o facebook.*/
            if ($payload) {
                $page_id = $request['page_id'];
                $access_token = $request['page_token'];;

                $response = $this->fb_api->post(
                    '/' . $page_id . $action,
                    $payload,
                    $access_token
                );

                /*Se a postagem retornar status code = 200 retornar mensagem de sucesso.*/
                if ($response->getHttpStatusCode() == 200) {
                    return response()->json([
                        'response' => 'Postagem realizada'
                    ], 200);
                }
            }

            /*Caso não exista payload, não realizar a requisição e retornar mensagem de não realizado.*/
            return response()->json([
                'response' => 'Postagem não realizada'
            ], 406);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Verificar o tipo de postagem realizada para o Facebook.
     * @param Request $request
     */
    public function checkFacebookTypePost($request)
    {
        try {
            $type = array();

            if ($request['scheduling'] && ($request['image'] || $request['message'])) {
                $type['scheduling'] = 'scheduling';
            }

            if ($request['message'] && !($request['image'])) {
                $type['message'] = 'message';
            }

            if ($request['image']) {
                $type['image'] = 'image';
            }

            return $type;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Verificar o tipo de ação a ser feita conforme a requisição. Será feita postagem
     * do tipo feed (somente imagem) ou photos (imagem e fotos).
     * @param Request $request
     */
    public function checkFacebookAction($request)
    {
        try {
            /*Ação a ser realizada conforme o que é recebido na requisição.*/
            $action = "";

            if ($request['image']) {
                $action = '/photos';
                return $action;
            }

            if ($request['message'] && !($request['image'])) {
                $action = '/feed';
                return $action;
            }

            return $action;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Construir o Payload com os dados da postagem que será enviado ao Facebook.
     * @param Request $request
     * @param string $type
     */
    public function buildFacebookPayloadFromRequestType($request, $type)
    {
        try {
            /*Array com o que será enviado ao facebook conforme o que é recebido na requisição.*/
            $payload = array();

            /*Verificar se existe o atributo agendamento na requisição e alterar o payload caso exista.*/
            if (!empty($type['scheduling'])) {
                $scheduling = strtotime($request['scheduling']);
                $payload['scheduled_publish_time'] = strtotime("+3 hour", $scheduling);
                $payload['published'] = 'false';
            }

            /*Verificar se existe o atributo imagem na requisição, alterar o payload caso exista,
            mover a imagem para diretório padrão, alterar o action para postar fotos.
            */
            if (!empty($type['image'])) {
                $file = $request->file('image');
                $file_upload_object = new FileUpload($file);
                $file_data = $file_upload_object->fileUpload();
                
                $payload['source'] = $this->fb_api->fileToUpload($file_data['absolute_path']);
                $payload['message'] = $request['message'];
            }

            /*Verificar se existe o atributo mensage e não exista imagem na requisição, alterar o payload caso exista,
            alterar o action para postar mensagem sem fotos.
            */
            if (!empty($type['message'])) {
                $payload['message'] = $request['message'];
            }

            return $payload;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
