<?php

namespace Bitrix1C;

class Request
{
    private $payload;
    private $headers = [];
    private $method;
    private $link;
    private $response_code;
    private $response_body;
    private $guid;

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->response_code;
    }

    /**
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->response_body;
    }

    /**
     * @return mixed
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * @param mixed $response_code
     */
    public function setResponseCode($response_code): void
    {
        $this->response_code = $response_code;
    }

    /**
     * @param mixed $response_body
     */
    public function setResponseBody($response_body): void
    {
        $this->response_body = $response_body;
    }

    /**
     * @param mixed $guid
     */
    public function setGuid($guid): void
    {
        $this->guid = $guid;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload): void
    {
        $this->payload = json_encode($payload);
    }


    private function HeaderAuth(&$headers){
        $username = 'UsrSite';
        $password = "UsrSite%705";
        $credentials = base64_encode("$username:$password");
        $headers[] = "Authorization: Basic {$credentials}";
        $headers[] = "Company_GUID:{$this->guid}";
    }

    public function Exec(){
        if ($this->method == 'get'){
            $this -> CurlGet();
        } elseif ($this->method == 'post'){
            $this -> CurlJsonPost();
        } elseif ($this->method == 'put'){
            $this -> CurlJsonPut();
        }
    }

    // Сам запрос Curl
    private function CurlGet()
    {
        // Setting headers
        $this -> HeaderAuth($this -> headers);

        // Setting options and executing
        $curlDeal = curl_init();
        curl_setopt_array($curlDeal, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->link));
        curl_setopt($curlDeal, CURLOPT_HTTPHEADER, $this -> headers);
        $resultQuery = curl_exec($curlDeal);

        // Setting object properties
        $this -> setResponseCode(curl_getinfo($curlDeal, CURLINFO_HTTP_CODE));
        $this -> setResponseBody($resultQuery);
        return $curlDeal;
    }

    private function CurlJsonPost()
    {
        // Setting headers
        $this -> HeaderAuth($this -> headers);
        $this -> headers[] = "Content-Type:application/json";
        // Setting options and executing
        $curlDeal = curl_init();
        curl_setopt_array($curlDeal, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->link,));
        curl_setopt($curlDeal, CURLOPT_HTTPHEADER, $this -> headers);
        curl_setopt($curlDeal, CURLOPT_POST, 1);
        curl_setopt($curlDeal, CURLOPT_POSTFIELDS, $this -> payload);
        $resultQuery = curl_exec($curlDeal);

        // Setting object properties
        $this -> setResponseCode(curl_getinfo($curlDeal, CURLINFO_HTTP_CODE));
        $this -> setResponseBody($resultQuery);
        return $curlDeal;
    }

    private function CurlJsonPut()
    {
        // Setting headers
        $this -> HeaderAuth($this -> headers);
        $this -> headers[] = "Content-Type:application/json";
        // Setting options and executing
        $curlDeal = curl_init();
        curl_setopt_array($curlDeal, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->link,));
        curl_setopt($curlDeal, CURLOPT_HTTPHEADER, $this -> headers);
        curl_setopt($curlDeal, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curlDeal, CURLOPT_POSTFIELDS, $this -> payload);
        $resultQuery = curl_exec($curlDeal);

        // Setting object properties
        $this -> setResponseCode(curl_getinfo($curlDeal, CURLINFO_HTTP_CODE));
        $this -> setResponseBody($resultQuery);
        return $curlDeal;
    }

}