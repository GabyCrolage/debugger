<?php
use Debugger\DD;

echo 'OK ';

if( !function_exists('dd')){
    function dd( $data ){
        DD::dd( $data );
    }
}