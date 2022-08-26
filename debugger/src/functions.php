<?php
use Debugger\DD;

echo 'OKidoki';

if( !function_exists('dd')){
    function dd( $data ){
        DD::dd( $data );
    }
}