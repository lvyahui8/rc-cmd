<?php

if( ! function_exists('base_path')){
    function base_path(){
        return realpath(__DIR__.'/../../../');
    }
}

if( ! function_exists('storage_path')){
    function storage_path(){
        return base_path().'/storage';
    }
}

if( ! function_exists('config_path')){
    function config_path(){
        return base_path().'/config';
    }
}