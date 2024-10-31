<?php

    /* 
    Plugin Name: seKeyword
    Description: tailor content to specific keywords
    Author: Stephan Gerlach
    Version: 1.1
    Author URI: http://www.computersniffer.com
    */  

    add_action('admin_menu', 'sekeyword_menu');
    function sekeyword_menu() {
        add_menu_page('seKeyword', 'seKeyword', 'administrator', 'sekeyword_guide', 'sekeyword_guide');
    }  
    function sekeyword_guide() {
        echo '<div class="wrap">';
        echo '<h2>seKeyword Guide</h2>';
        echo '<p>With this plugin you can display custom text depending on the referring Search Engine and the keyword the visitor serched for.<br />So far we <strong>ONLY</strong> support google, yahoo and bing.</p>';
        echo '<h4>Default Display</h4>';
        echo '<p>This example will display the text if there are no keywords or search engine is defined
                <br /><code>[sekeyword content="This is a sample text"]</code></p>';
        echo '<p>In order to lock the special content to a specific Search Engine use the engine attribute<br />
                <code>[sekeyword content="This is a sample text" engine="google"]</code><br />
                Note: At the moment only google, yahoo and bing are supported</p>';
       echo '<p>In order to lock the special content to a specific Search Engine AND to a specific keyword use the engine attribute and the keyword attribute<br />
                <code>[sekeyword content="This is a sample text" engine="google" keyword="my keyword"]</code><br />
                Note: At the moment only google, yahoo and bing are supported</p>';
       echo '<p>You can also lock the content to a specific keyword use only the keyword attribute<br />
                <code>[sekeyword content="This is a sample text" keyword="my keyword"]</code></p>';
       echo '<h4>Content Vars</h4>';
       echo '<p>Since version 1.1 it is also possible to display variables in the content field.</p>';
       echo '<p><strong>NOTE: The variables are not checked against the shortcode attribute list. They are always replaced.</strong></p>';
        echo '<p>Display the keyword a visitor searched for<br />
                <code>[sekeyword content="You searched for {KEYWORD}"]</code></p>';
        echo '<p>Display the search engine a visitor used<br />
                <code>[sekeyword content="You searched using {ENGINE}"]</code></p>';
        echo '<p>You can also use both<br />
                <code>[sekeyword content="You searched for {KEYWORD} using {ENGINE}"]</code></p>';
        
                
       
        
        echo '</div>';
    }
    
    add_shortcode( 'sekeyword', 'sekeyword_code' );
    function sekeyword_code ($atts) {
        
        extract( shortcode_atts( array(
		  'engine' => '',
          'keyword' => '',
          'content' => ''
        ), $atts ) );
        
        $engine = trim(strtolower($engine));
        $keyword = trim(strtolower($keyword));
        $kw = '';
        $eng = '';
        
        $engines = array('1'=>'google','2'=>'bing','3'=>'yahoo','4'=>'ask');
        $param   = array('1'=>'q','2'=>'q','3'=>'p','4'=>'q');
        
        
        $parsed = parse_url($_SERVER['HTTP_REFERER'] );
        $parsed_q = parse_url($_SERVER['HTTP_REFERER'],PHP_URL_QUERY);
        parse_str( $parsed_q, $query );
        
        foreach ($engines as $ke=>$en) {
            
            if (strstr($parsed['host'],'.'.$en.'.')) {
                $eng = $ke;
                $kw = $query[$param[$eng]];
            }
            
        }
        
        $content = str_replace('{KEYWORD}',$kw,$content);
        $content = str_replace('{ENGINE}',$engines[$eng],$content);
        
        if ($engine=='' && $keyword=='' && $content!='') {
            return $content;
        }
        
        $show = false;
       
        if ($engine!='' && $engines[$eng]==$engine) {
                    $show = true;
        }
        if ($keyword !='' && $keyword == $kw){
            $show = true;
        }
        
        if ($show) {
            return $content;
        }
        
     }
?>