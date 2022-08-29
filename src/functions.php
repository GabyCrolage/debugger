<?php
use Debugger\DD;

if( !function_exists( 'dd') ){
    function dd( $args, $name = null ){
        DD::dump( $args, $name );
    }
}

if( !function_exists( '_dd') ){
    function _dd( $args, $name = null ){
        DD::die_dump( $args, $name );
    }
}