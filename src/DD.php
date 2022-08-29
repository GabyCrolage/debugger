<?php

namespace Debugger;

class DD {
    
    private static $data = [];
    
    public static function dump( $args, $name ){
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
    
    public static function die_dump( $args, $name ){
        self::dump( $args, $name );
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

    private static function userInfo(){
        $html = "<div id='dd_util'>";
        $util = get_session('util');
        if( $util ){
            $mail = $util['email'] ?? '';
            $id = $util['id'] ?? '';
            $connecte = $util['connecte'] ?? null;
            if( $connecte ) $html .= "Connecté : ";
            else $html .= "En cours de connexion : ";
            $html .= "$mail [$id]";
        } else {
            $html .= "<i>Pas d'util connecté</i>";
        }

        $html .= "</div>";
        return $html;
    }

    public static function print( $fullscreen = false ){
        self::debug_vars();
        $html = "<style>" . file_get_contents( __DIR__ . '/assets/css/dd.css') . "</style>";
        $class = $fullscreen ? "class='full'" : '';
        $html .= "<div id='dd'$class>";
        // Header
        $html .= "<div id='dd_header'><h1>V3 DEBUGGER (1.0)</h1>";
        $html .= self::userInfo();
        $html .= "<button id='dd_hide'>[x]</button></div>";

        // CONTENT
        $html .= "<div id='dd_content'>";    
            if( !$fullscreen ) $html .= "";
            $html .= "";
            $html .= "<div id='dd_container'>";
            foreach( self::$data as $d ){
                $html .= self::display( $d );
            }
            $html .= "</div>";
        $html .= "</div></div>";
        $html .= '<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>';
        $html .= '<script src="/V3/app/classes/DD/assets/js/dd.js"></script>';
        if( $fullscreen ) {
            echo $html;
            exit();
        } else {
            return $html;
        }
    }

    static function debug_vars(){
        self::dump( $_SESSION, 'Session' );
    }

    
}