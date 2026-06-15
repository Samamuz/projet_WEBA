<?php

class Response {
    public int $httpCode;
    public string|null $responseString;

    public function __construct(int $httpCode, string|null $responseString = null) { 
        $this->httpCode = $httpCode;
        $this->responseString = $responseString;
    }

    public function generateResponse() {
        http_response_code($this->httpCode);
        header('Content-Type: application/json; charset=utf-8');

        if ($this->responseString != null) {
            echo $this->responseString;
        }
    }
}
?>