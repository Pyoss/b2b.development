<?php
namespace Bitrix1C;

use Bitrix\Highloadblock\HighloadBlockTable;

class Api
{
    private $base_link = 'https://1c.dobriy-jar.ru/testbase/hs/b2b/';
    private $log_string = '';
    private $guid = '';

    /**
     * @param string $guid
     */
    public function __construct()
    {
        $this->setCurrentClient();
    }

    function setCurrentClient(){
        global $USER;
        $user_id = $USER->GetID();
        \Bitrix\Main\Loader::IncludeModule("highloadblock");
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(5)->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();
        $result = $entityDataClass::getList(array(
            "select" => array("*"),
            "order" => array("ID"=>"DESC"),
            "filter" => Array("UF_USER_ID"=>$user_id),
        ));

        while ($arRow = $result->Fetch())
        {
            $this->guid = $arRow['UF_COMPANY_GUID'];
        }
        return false;
    }

    public function log($title, $string){
        $this -> log_string .= '<br>';
        $this -> log_string .= $title;
        $this -> log_string .= '<br>';
        $this -> log_string .= date('h:i:s');
        $this -> log_string .= '<br>';
        $this -> log_string .= print_r($string, true);
        $this -> log_string .= '<br>';
    }

    public function showLog(){
        echo $this -> log_string;
    }

    public function formRequest(){
        $request = new Request();
        $request -> setGuid($this -> guid);
        return $request;
    }

    public function CreateCompany(){
        $link = $this -> base_link . 'company';
        $arCompany['inn'] = '123243352328';
        $arCompany['ogrn'] = '11111111111111';
        $arCompany['name'] = 'ИП тест 0407';
        $arCompany['reg_address'] = 'Тест адрес рег';
        $arCompany['act_address'] = 'Тест адрес акт';
        $arCompany['company_status'] = 'ИП';
        $arCompany['phone'] = '71111111111';
        $arCompany['mail'] = '711test@gmail.com';
        $arCompany['manager'] = '4a9520f3-8260-11ec-aedc-da838655f028';
        $arCompany['bank_req'] = [
            "num" => "40702810438000236919",
            "bik" => "044525225",
            "kor" => "30101810400000000225"
        ];
        $request = $this -> formRequest();
        $request -> SetPayload($arCompany);
        $request -> SetLink($link);
        $request -> SetMethod('post');
        $request -> Exec();
        $this->log('response_code', $request -> getResponseCode());
        $this->log('response_text', $request -> getResponseBody());
    }

    public function GetCompany(){
        $link = $this -> base_link . 'company';
        $request = $this -> formRequest();
        $request -> SetLink($link);
        $request -> SetMethod('get');
        $request -> Exec();
        return $request -> getResponseBody();
    }

    public function CreateOrder($arOrder){
        $link = $this -> base_link . 'order';
        $request = $this -> formRequest();
        $request -> SetPayload($arOrder);
        $request -> SetLink($link);
        $request -> SetMethod('post');
        $request -> Exec();
        $this->log('response_code', $request -> getResponseCode());
        $this->log('response_text', $request -> getResponseBody());
        return $request;
    }

    public function getProductGuid($productId){
        $hlbl = 3; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $this -> $entity_data_class = $entity->getDataClass();
        $rsSelect = $this -> $entity_data_class::getList(array(
            "select" => array("*"),
            "filter" => array("UF_ELEMENT_ID" => $productId)
        ));
        return $rsSelect->Fetch();
    }

    public function UpdateOrder($guid){
        $link = $this -> base_link . 'order?guid=' . $guid;
        $arOrder['package'] = 'Упаковка';
        $arOrder['delivery'] = 'СДЭК';
        $arOrder['company'] = '07dfc163-f910-11ec-aedc-da838655f028';
        $arOrder['shipment_date'] = '10.07.2022';
        $arOrder['comment'] = '711test@gmail.com';
        $arOrder['manager'] = '4a9520f3-8260-11ec-aedc-da838655f028';
        $arOrder['city'] = 'Москва';
        $arOrder['bucket'] = [[
            'guid' => '31cb4705-3ec6-11eb-a463-18c04d3850b8',
            'quantity' => '2',
            'price' => '1000'
        ]
        ];
        $request = $this -> formRequest();
        $request -> SetPayload($arOrder);
        $request -> SetLink($link);
        $request -> SetMethod('put');
        $request -> Exec();
        $this->log('response_code', $request -> getResponseCode());
        $this->log('response_text', $request -> getResponseBody());
        return $request;
    }

    public function GetOrder($guid): Request
    {
        $link = $this -> base_link . 'order?guid=' . $guid;
        $request = $this -> formRequest();
        $request -> SetLink($link);
        $request -> SetMethod('get');
        $request -> Exec();
        $this->log('response_code', $request -> getResponseCode());
        $this->log('response_text', $request -> getResponseBody());
        return $request;
    }

    public function GetPaymentList(){
        $link = $this -> base_link . 'payments';
        $request = $this -> formRequest();
        $request -> SetLink($link);
        $request -> SetMethod('get');
        $request -> Exec();
        $this->log('response_code', $request -> getResponseCode());
        $this->log('response_text', $request -> getResponseBody());
        return $request -> getResponseBody();
    }

    public function GetPayment($guid){
        $link = $this -> base_link . 'payments?guid=' . $guid;
        $request = $this -> formRequest();
        $request -> SetLink($link);
        $request -> SetMethod('get');
        $request -> Exec();
        $body = $request -> getResponseBody();
        $data = json_decode($body, true);
        $bin = $data[0]['data'][0];
        if ($bin){
            return base64_decode($bin);
        }
        return false;

    }

    public function getClientManager(){
        return ['guid' => 'b4dd6138-39f1-11eb-a45a-18c04d3850b8'];
    }

    public function getFeniksGuid(){
        return 'd1193bfc-a55e-11ea-83d3-ebfa0c7c92c4';
    }

    public function GetCompanyByInn(string $inn){
        $link = $this -> base_link . 'company';
        $arCompany['inn'] = $inn;
        $request = $this -> formRequest();
        $request -> SetPayload($arCompany);
        $request -> SetLink($link);
        $request -> SetMethod('post');
        $request -> Exec();
        $this->log('response_code', $request -> getResponseCode());
        $this->log('response_text', $request -> getResponseBody());
        return $request -> getResponseBody();
    }

    public function GetOrderList(){
        $link = $this -> base_link . 'company';
        $arCompany['inn'] = $inn;
        $request = $this -> formRequest();
        $request -> SetPayload($arCompany);
        $request -> SetLink($link);
        $request -> SetMethod('post');
        $request -> Exec();
        $this->log('response_code', $request -> getResponseCode());
        $this->log('response_text', $request -> getResponseBody());
        return $request -> getResponseBody();
    }
}