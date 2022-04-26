<?php
require_once ('libs/autoloader.php');
require_once ('init.php');



// HTTPS json
// 所有軟體一開啟都要先傳送一次這串給BIP Server
// {"getToken":"1" ,"cardid":"34234341234134", "program":"actoma", "os":"ios", "polling": 0}

// 之後所傳送的資料格式
// {"cardid":"2392932992376589", "program":"slimeduet", "cmd":"modify_pin", "value":"123456", "token": "adf34fsfsfa"}

// 會得到的回傳格式
// {"result":"ok", "value":"a32323"}

// Content-Type: application/json



$funcs -> Content_type_check("application/json");



main();




function main(){
    global $global;

    if(!empty($global -> input)){
        $jsonObj = json_decode($global -> input);
        
        if(!empty($jsonObj)){
            if(!redis_connect($global -> redis, $global -> conf['REDIS_SERVER'], $global -> conf['REDIS_PASSWD'])){
                return false;
            }

            $apitoken = $global -> redis -> get($jsonObj->token);

            //if($jsonObj->token == "KNVrtfNIkRk53TuLJriZrtS2WWMCp3yn5mAI89DHVZbx2vVMS25AY2aH4dndis9u"){
            if(!empty($apitoken)){
                if(!empty($jsonObj->cardid)){
                    if($jsonObj->getToken){
                        $token = $global -> funcs -> getRand_str(64);
                        if(info_handle($jsonObj, $token)){
                            $timestamp = time();
                            $reponse = "";
    
                            $index = $jsonObj->cardid."_".$jsonObj->program."_".$token."_reponse";
    
                            while(time() - $timestamp < 60){
    
                                $queue = $global -> redis -> get($index);
                                if(!empty($queue)){
                                    echo '{"result":"ok", "token": "'.$token.'"}';
                                    exit;
                                }
                            }
    
                            echo '{"result":"timeout"}';
                        } else {
                            echo '{"result":"error : token not set"}';
                        }
                    } else {
                        cmd_handle($jsonObj);
                    }
                } else { echo '{"result":"cardid empty"}'; }
            } else {
                echo '{"result":"invalid token"}';
            }
        } else { echo '{"result":"wrong format"}'; }
    }
}






function info_handle($jsonObj, $token){
    global $global;

    $index = $jsonObj->cardid."_".$jsonObj->program."_".$token;
    $infoData = $jsonObj->os.",".$jsonObj->polling;
    $global -> redis -> set($index, $infoData);
    return true;
}








function cmd_handle($jsonObj){ 
    global $global;
    $rand = rand(10, 65535);
 
    if(!redis_connect($global -> redis, $global -> conf['REDIS_SERVER'], $global -> conf['REDIS_PASSWD'])){
        exit;
    }
    

    switch($jsonObj->cmd){
      case "device_info": // 取得設備資訊
        $index = $jsonObj->cardid."_".$jsonObj->program."_".$rand;
        //echo "index : $index\n";
  
        array_push($Instruction, $rand);
  
        $global -> redis -> set($index, "device_info");
        $global -> redis -> set($index."_waitting", "1");
 
        $index_response = $jsonObj->cardid."_".$jsonObj->program."_".$rand."_response";
        $timestamp = time();
        $response_queue = "";


        while(time() - $timestamp < 60){
            $response_queue = $global -> redis -> get($index_response);

            if(!empty($response_queue)){
                break;
            }
        }

        if(!empty($response_queue)){
            $global -> redis -> del($index."_waitting");
            $global -> redis -> del($index_response);
            echo '{"result":"ok", "value":"'.$response_queue.'"}';
        } else {
            echo '{"result":"timeout", "value":""}';
        }

      break;
  
      case "change_pin": // 變更 pin 碼
  
      break;
  
      case "verify_pin": // 校驗 pin 碼
  
      break;
  
      case "unlock_pin": // 解鎖 pin 碼
  
      break;
  
      case "create_doc": // 建立文件
  
      break;
  
      case "select_doc": // 選擇文件
  
      break;
  
      case "delete_doc": // 刪除文件
  
      break;
  
      case "doc_info": // 取得文件屬性
  
      break;
  
      case "get_doc_read_id": // 取得 讀取文件的 id
  
      break;
  
      case "read_doc":  // 讀取文件內容
  
      break;
  
      case "get_doc_write_id": // 取得 寫文件的 id
  
      break;
  
      case "write_doc": // 將資料寫入文件
  
      break;
  
      case "get_rand": // 取得卡片隨機數
  
      break;
  
      case "sm4_encrypt": // SM4 加密
  
      break;
  
      case "create_key_pair": // 卡片產生密鑰對
  
      break;
  
      case "summary_d1": // 摘要 消息數據
  
      break;
  
      case "summary_d2": // 摘要 摘要數據
  
      break;
  
      case "rsa_d1": //  RSA 待運算
  
      break;
  
      case "rsa_d2": // RSA 運算結果
  
      break;
  
      case "hash_set":
  
      break;
  
      case "":
  
      break;
  
  
      default:
    }
  }




