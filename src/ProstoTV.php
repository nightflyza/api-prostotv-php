<?php

namespace UTG;

class ProstoTV {

    private $login,
            $password,
            $code,
            $error,
            $token,
            $url = 'https://api.prosto.tv/v1';

    public function __construct($login, $password, $url = null) {
        if ( $url )
            $this->url = $url;
        $this->login = $login;
        $this->password = $password;
        $this->token = null;
    }

    public function __get($name) {
        switch ( $name ) {
            case 'status': return $this->status; break;
            case 'error':  return $this->error;  break;
        }
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

    public function delete($resource, $data) {
        return $this->request('DELETE', $resource, $data);
    }

    private function request($method, $resource, $data = []) {
        if ( !$this->token && ($method != 'POST' || $resource != '/tokens') )
            $this->getToken();
        $context = ['http' => [
                'method' => $method,
                'header' => ["Content-Type: application/json; charset=utf-8"],
                'ignore_errors' => true,
                'timeout' => 60,
        ]];
        if ( $this->token )
            $context['http']['header'][] = "Authorization: Bearer " . $this->token;
        if ( $method != 'GET' )
            $context['http']['content'] = json_encode($data);
        $context = stream_context_create($context);
        try {
            echo $this->url . $resource . "\n";
            $content = file_get_contents($this->url . $resource, false, $context);
            $content = json_decode($content, true);
        } catch (Exception $e) {
            $this->error = $e;
            return false;
        }
        $answer = explode(' ', $http_response_header[0]);
        $this->status = intval($answer[1]);
        if ( in_array($this->status, [200, 201]) ) {
            $this->error = null;
            return $content;
        } else {
            $this->error = $content;
            return false;
        }
    }

    private function getToken() {
        $data = $this->request('POST', '/tokens', ['login' => $this->login, 'password' => $this->password]);
        $this->token = $data['token'];
    }

}
