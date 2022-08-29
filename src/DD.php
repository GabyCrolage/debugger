<?php

namespace Debugger;

class DD {
    
    private static $data = [];

    public static function set( $args, $name ){
        if( is_bool($args) ){
            $args = $args ? 'true' : 'false';
        }  
        $dd = array( 
            'name' => $name ?? null,
            'type' => gettype( $args ),
            'data' => $args,
        );
        self::$data[] = $dd;
    } 
    
    public static function dump( $args, $name ){
        self::set($args, $name);
        echo "<pre>";
        print_r( self::$data );
        echo "</pre>";
    }
    
    public static function die_dump( $args, $name ){
        self::set($args, $name);
        self::print( true );
    }
    
    private static function display( $data ){
        $name = $data['name'];
        $type = $data['type'];
        $data = $data['data'];

        if( in_array( $type , [ 'array', 'object'] )){
            $value = self::iterate($data);
            return "<div class='item'><name>$name</name> : $value</div>";
        } else {
            return "<div class='variable'><name>$name</name> <$type>($type)</$type> : <$type class='data'>$data<$type/></div>";
        }
    }

    private static function item( $data ){   
        switch( gettype( $data ) ){
            case 'NULL' :
                $type = 'Null';
                $value = "<null class='data'>null</null>";
            break;

            case 'string' : 
                $type = 'String';
                $value = "<string class='data'>\"$data\"</string>";
            break;

            case 'integer':
            case 'float' :
            case 'double' : 
                $type = 'Number';
                $value = "<number class='data'>$data</number>";
            break;

            case 'boolean' : 
                $type = 'Boolean';
                $value = $data ? 'true' : 'false';
            break;
        }
        return "<value class='data'>$value</value>";
    }

    private static function iterate( $data ){
        $id = 'cnt_' . rand(0,999999);
        $iterate  = (array) $data;
        $type= gettype( $data );
        $data_len = count( (array) $data );
        $response = "<iterable-item>$type ($data_len)</iterable-item>";
        if( $data_len > 0) $response .= "<unfold onclick=\"unfold(this,'$id')\"></unfold> ";
        $response .= "<div id='$id' class='container'>";
        foreach($iterate as $k => $v){
            $response .= "<div class='iterable'>";
            $response .= is_int( $k ) ? "<number>$k</number>" : "#<key>$k</key>";
            $response .= " => ";
            if( is_array($v) || is_object( $v ) ) $response .= self::iterate( $v );
            else $response .= self::item( $v, false );
            $response .= "</div>";
        }
        $response .= "</div>";
        return $response;
    }

    public static function print( $fullscreen = false ){

        $html_content = "<div id='dd'>";
            $html_content .= "<style>" . file_get_contents( __DIR__ . '/assets/css/dd.css') . "</style>";
                // HEADER
                $html_content .= "<div id='dd_header'>";
                $html_content .= "GabyCrolage DieDump v1.0";
                $html_content .= "</div>"; // dd_header
            
                // CONTENT
                $html_content .= "<div id='dd_content'>";    
                if( !$fullscreen ) $html_content .= "";
                $html_content .= "";
                $html_content .= "<div id='dd_container'>";
                foreach( self::$data as $d ){
                    $html_content .= self::display( $d );
                }
                $html_content .= "</div>"; // dd_container
                $html_content .= "</div>"; // dd_content

            $html_content .= '<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>';
            $html_content .= '<script>' . file_get_contents(__DIR__ . '/assets/js/dd.js') . '</script>';
        $html_content .= "</div>"; // dd_main_container

        echo $html_content;
        exit();
    }    
}