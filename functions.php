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
        
        register_activation_hook( __FILE__, array( &$this, 'prepare') );
		
        add_action( 'admin_menu', array( &$this, 'init' ) );
        
		add_shortcode( 'up-match', array( &$this, 'get_shortcode' ));
    }
    public function prepare(){
        $this->create_db();
    }
    /**
     * Admin Functions
     */
    public function load_menu(){
        include('menu.php');
    }
    public function create_db(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'upmatches';
        $table = "CREATE TABLE IF NOT EXISTS $table_name (
            id int unsigned NOT NULL AUTO_INCREMENT,
            match_id int NOT NULL,
            round int,
            league varchar(25) NOT NULL,
            country varchar(25) NOT NULL,
            teamh varchar(25) NOT NULL,
            teamh_img text NOT NULL,
            teama varchar(25) NOT NULL,
            teama_img text NOT NULL,
            match_datetime text NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $table );
    }
    public function crawling(){
        $url = 'http://appwww.fctables.com/app/livescore/2/2019-01-07/';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch));
        curl_close($ch);
        foreach($res->leagues as $league){
           foreach($league->games as $game){
               $league_name = $game->league_name;
           }
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
    public function upmatch_register_settings(){
        register_setting( '_upmatch_setting', '_id_match' );
    }

    /**
     * Frontend Functions
     */
    public function init(){
        add_menu_page( 'Upcoming Match', 'Upcoming Match Setting', 'manage_options', 'upmatch', array( &$this,'load_menu'));
        
        add_action( 'admin_init', array( &$this, 'upmatch_register_settings' ));
    }
    /**
     * Shortcode Hooks
     */
    public function get_shortcode($atts){
        // $a = shortcode_atts( array(
        //     'league' => '',
        // ), $atts );
        
        wp_enqueue_style( 'upmatch-css',  plugin_dir_url( __FILE__ ).'css/style.css', array(), '1.0', null );
        ?>
            <div class="mainsp wow fadeIn" data-wow-delay="0.6s" style="background: url(<?php echo plugin_dir_url( __FILE__ ).'img/bg.png';?>) bottom center no-repeat">
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
        $this->crawling();
    }
}

$GLOBALS['Upcoming_match'] = new Upcoming_Match();