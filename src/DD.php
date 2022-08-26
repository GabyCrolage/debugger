<?php

namespace Debugger;

class DD {
    
    static function dd( $data = null ){
        echo "<pre>";
        print_r( $data );
        echo "</pre>";
    }
    
}