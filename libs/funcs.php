<?php
namespace Libs;

class Funcs {

    
    public function Content_type_check($condition){
        $content_type = $_SERVER["CONTENT_TYPE"];
        if($content_type != "application/json"){
            echo '{"result":"data type error"}';
            exit;
        }
    }



    public function output($Instruction){
        if(!empty($Instruction) && is_array($Instruction)){
            foreach($Instruction as $byte){
              //echo dechex($byte)." ";
              echo chr($byte);
            }
        }
    }


    /**
     * getRand_str
     * 亂數字串產生函數
     * 
     * @param  $len  需要產生的字串長度 
     * @return string
     *
     * @version 0.1
     * @author Bear
     */
    public function getRand_str($len = 4){   
        $Rand_len = $len;   
        $Rand = '';   
        $word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';   
        $len = strlen($word);   
        for ($i = 0; $i < $Rand_len; $i++) {   
          $Rand .= $word[rand() % $len];   
        }   
         return $Rand;   
    }

}