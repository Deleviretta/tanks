<?php

/**
 * Created by PhpStorm.
 * User: deleviretta
 * Date: 16.01.17
 * Time: 00:43
 */
class Register
{

    protected $data = array()  ;

    function __construct () {
    }

    function _read () {
        $this->data['nick'] = $_POST['nick'] ;
        $this->data['email'] = $_POST['email'] ;
        $this->data['haslo'] = $_POST['haslo1'];
    }

}