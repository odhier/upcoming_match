<?php 
/**
* Plugin Name: Upcoming Match
* Plugin URI: https://agenkaisar.online/
* Description: Free Upcoming Match Plugin
* Version: 1.0
* Author: Odhier
* Author URI: https://agenkaisar.online/
*/

/*
* Creating a function to create our CPT
*/
class Upcoming_Match{
    /**
     * Class Constructor
     */
	public function __construct() {
		
		add_action( 'admin_menu', array( &$this, 'init' ) );
	    add_action( 'admin_init', 'admin_init' );
    }
    /**
     * Admin Functions
     */
    public function admin_init(){
        global $pagenow;
        if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {
			add_action( 'admin_enqueue_scripts', array(&$this,'load_admin_things') );
        }
    }
    /**
     * Load Admin Scripts
     */
    public function load_admin_things(){
        wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
    }

    /**
     * Frontend Functions
     */
    public function init(){
       
        add_menu_page( 'Upcoming Match Settings', 'Upcoming Match Setting', 'manage_options', 'upmatch' );
		add_shortcode( 'upmatch', array( &$this, 'get_shortcode' ));
    }
    /**
     * Shortcode Hooks
     */
    public function get_shortcode(){
        ?>
            <div class="mainsp wow fadeIn" data-wow-delay="0.6s" style="background: url(https://static.yaboclub.com/banner/1542970622241438893.png) bottom center no-repeat">
          <h1>UPCOMING MATCHES</h1>
          <h3 class="league">ENGLISH PREMIER LEAGUE</h3><strong>Matchday 21 of 38</strong><br><br><span>3rd January 2019 3:45 A.M </span>
          <div class="matchdtl">
            <div class="teamh">
              <img src="https://static.yaboclub.com/banner/15463919461151594262.png" alt="CHELSEA">
              <h4>CHELSEA</h4>
            </div>
            <div class="matchtime">
              
            </div>
            <div class="teama">
              <img src="https://static.yaboclub.com/banner/15463919611028031391.png" alt="SOUTHAMPTON">
              <h4>SOUTHAMPTON</h4>
            </div>
          </div>
        </div>
        <?php
    }
}

$GLOBALS['Upcoming_match'] = new Upcoming_Match();