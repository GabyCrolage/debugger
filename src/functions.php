<?php
use Debugger\DD;

echo 'OKi';

if( !function_exists('dd')){
    function dd( $data ){
        DD::dd( $data );
    }
}