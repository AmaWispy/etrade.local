<?php
namespace App\Helpers;


trait Check{
    public function notEmpty(array $values):bool{
        foreach($values as $value){
            if(empty($value)){
                return false;
            }
            return true;
        }
    }
}