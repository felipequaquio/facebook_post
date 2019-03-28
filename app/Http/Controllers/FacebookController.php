<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\FacebookManager;

class FacebookController extends Controller
{
    private $facebook;

    public function __construct(FacebookManager $facebook_manager)
    {
        $this->facebook = $facebook_manager;
    }

    /**
     * Método responsável por obter as páginas do usuário logado com o Facebook.
     */
    public function getFacebookPages()
    {
        try {
            $pages = $this->facebook->getFacebookPages(Auth::user()->token);

            return view('facebook_pages', compact('pages'));
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Facebook SDK error: ' . $e->getMessage();
        }
    }

    /**
     * Método responsável por enviar postagens para o Facebook.
     * @param Request $request
     */
    public function postFacebookPage(Request $request)
    {
        return $this->facebook->postFacebookPage($request);
    }
}
