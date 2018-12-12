<?php

namespace UTG;

class ProstoTV {

    private $login,
            $password,
            $token,
            $url = 'https://api.prosto.tv/v0';

    public function __construct($login, $password, $url = null) {
        if ( $url )
            $this->url = $url;
        $this->login = $login;
        $this->password = $password;
        $this->token = null;
    }

    public function get($resource) {
        return $this->request('GET', $resource);
    }

    public function post($resource, $data) {
        return $this->request('POST', $resource, $data);
    }

    public function put($resource, $data) {
        return $this->request('PUT', $resource, $data);
    }

    private function request($method, $resource, $data = []) {
        if ( !$this->token && $method != 'POST' && $resource != '/token' )
            $this->getToken();
        $context = ['http' => [
                'method' => $method,
                'header' => ["Content-Type: application/json; charset=utf-8"],
                'timeout' => 60,
        ]];
        if ( $this->token )
            $context['http']['header'][] = "Authorization: Bearer " . $this->token;
        if ( $method != 'GET' )
            $context['http']['content'] = json_encode($data);
        $context = stream_context_create($content);
        try {
            $content = file_get_contents($this->url . $resource, false, $context);
        } catch (Exception $e) {
            return [
                'result' => 'error',
                'message' => $e,
            ];
        }
        return json_decode($content, true);
    }

    private function getToken() {
        $data = $this->request('POST', '/token', ['login' => $this->login, 'password' => $this->password]);
        $this->token = $data['token'];
    }

}
