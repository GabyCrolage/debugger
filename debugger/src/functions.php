<?php

use Debugger\DD;

if( !function_exists('dd')){
    function dd( $data ){
        DD::dd( $data );
    }
}