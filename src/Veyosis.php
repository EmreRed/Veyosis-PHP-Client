<?php
class Veyosis {
  protected static $_url = "https://api.veyosis.com/";
  protected static $_token = null;
  protected static $_arr = [];
  protected static $_api = [];
  const ERROR = "api_error";
  const ERROR_MSG = "api_error_msg";
  const REQUEST = "api_request";
  const REQUEST_URL = "api_request_url";
  const REQUEST_HEADER = "api_request_header";
  const RESULT = "api_result";
  const RESULT_CODE = "api_result_code";

  function __construct($token=null){
    if($token!=null) self::auth($token);
  }

  public static function auth($token){
    self::$_token = $token;
    return true;
  }

  public static function brand(){
    return new class{
      function get(){ return Veyosis::call("brand/get"); }
    };
  }

  public static function consent(){
    return new class{
      function single($brand,$type,$recipientType,$recipient,$source,$consentDate,$status){
        return Veyosis::call("consent/single/$brand",[
          'recipient' => $recipient,
          'type' => $type,
          'recipientType' => $recipientType,
          'recipient' => $recipient,
          'source' => $source,
          'consentDate' => $consentDate,
          'status' => $status]); }
      function async($brand,$recipients){ return Veyosis::call("consent/async/$brand",$recipients); }
      function status($transaction){ return Veyosis::call("consent/status/$transaction"); }
    };
  }

  public static function report(){
    return new class{
      function single($brand,$type,$recipientType,$recipient){
        return Veyosis::call("report/single/$brand",[
          'recipient' => $recipient,
          'type' => $type,
          'recipientType' => $recipientType]); }
      function async($brand,$type,$recipientType,$recipients){ return Veyosis::call("report/async/$brand",['type' => $type, 'recipientType' => $recipientType, 'recipients' => $recipients]); }
      function status($transaction){ return Veyosis::call("report/status/$transaction"); }
    };
  }

  public static function call($action, $data=[]){
    self::$_arr[self::ERROR] = false;
    self::$_arr[self::ERROR_MSG] = false;
    $url = self::$_url.$action;
    self::$_arr[self::REQUEST_URL] = $url;
    self::$_arr[self::REQUEST] = null;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    $header = [];
    if(!empty($data) && ((array)$data) > 0){
      $postData = json_encode($data);
      self::$_arr[self::REQUEST] = $postData;
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      $header[] = 'Content-Type: application/json';
    }
    if(self::$_token !== null) $header[] = 'Authorization: Bearer '.self::$_token;
    self::$_arr[self::REQUEST_HEADER] = $header;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    self::$_arr[self::RESULT] = curl_exec($ch);
    $result = json_decode(self::$_arr[self::RESULT]);
    if(json_last_error() != JSON_ERROR_NONE) $result = self::$_arr[self::RESULT];
    self::$_arr[self::RESULT_CODE] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    self::$_arr[self::ERROR] = isset($result->errors) ? $result->errors[0]->code : false;
    self::$_arr[self::ERROR_MSG] = isset($result->errors) ? $result->errors[0]->message : false;
    $result_success = [200,202,204];
    return in_array(self::$_arr[self::RESULT_CODE],$result_success) ? $result->data : false;
  }

  public static function get($v=null){
    if($v === null) return new class {
      public function error()  { return (object)['code' => Veyosis::get(Veyosis::ERROR),      'desc' => Veyosis::get(Veyosis::ERROR_MSG)]; }
      public function request(){ return (object)['url'  => Veyosis::get(Veyosis::REQUEST_URL),'body' => Veyosis::get(Veyosis::REQUEST)]; }
      public function result() { return (object)['code' => Veyosis::get(Veyosis::RESULT_CODE),'body' => Veyosis::get(Veyosis::RESULT)]; }
    };
    return isset(self::$_arr[$v]) ? self::$_arr[$v] : false;
  }
}
