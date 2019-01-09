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
        
        register_deactivation_hook( __FILE__, array( &$this,'deactivating') );
		
        add_action( 'admin_menu', array( &$this, 'init' ) );
        add_filter( 'cron_schedules', array( &$this, 'add_3honce') ); 
        add_action( 'upmatch_cron', array( &$this,'crawl_match') );
		add_shortcode( 'up-match', array( &$this, 'get_shortcode' ));
    }
    public function prepare(){
        $this->create_db();
        if (! wp_next_scheduled ( 'upmatch_cron' )) {
            wp_schedule_event(time(), 'o3h', 'upmatch_cron');
        }
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
            round varchar(25),
            league varchar(25) NOT NULL,
            country varchar(25) NOT NULL,
            teamh varchar(25) NOT NULL,
            teamh_img text NOT NULL,
            teama varchar(25) NOT NULL,
            teama_img text NOT NULL,
            match_datetime text NOT NULL,
            status varchar(25) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $table );
    }
    public function crawl_match($t=0){
        date_default_timezone_set("Asia/Jakarta");
        global $wpdb;
        $date = ($t==1)?date('Y-m-d', strtotime('+1 day')):date('Y-m-d');
        $url = 'http://appwww.fctables.com/app/livescore/2/'.$date.'/';
        $t++;;
        $table_name = $wpdb->prefix . 'upmatches';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch));
        curl_close($ch);
        $i=1;
        foreach($res->leagues as $league){
           foreach($league->games as $game){
                if($i)
                    $check = $wpdb->get_row( "SELECT * FROM $table_name WHERE match_id = $game->id", ARRAY_A );
                else
                    $check = false;
                    
                if(!$check){
                    $wpdb->insert( 
                        $table_name, 
                        array( 
                            'match_id' => $game->id, 
                            'round' => $game->round,
                            'league' => $game->league_name,
                            'country' => $league->c,
                            'teamh' => $game->teams[0]->name,
                            'teamh_img' => $game->teams[0]->img,
                            'teama'=> $game->teams[1]->name,
                            'teama_img' => $game->teams[1]->img,
                            'match_datetime' => $game->date,
                            'status' => $game->status_type
                        ), 
                        array( 
                            '%d', 
                            '%s', 
                            '%s', 
                            '%s', 
                            '%s', 
                            '%s', 
                            '%s', 
                            '%s', 
                            '%s', 
                            '%s'
                        )
                        );
                        $i--;
                }else{
                    $wpdb->update( 
                        $table_name,
                        array( 
                            'status' => $game->status_type
                        ), 
                        array( 'match_id' => $game->id ), 
                        array( 
                            '%s',	// value1
                        ), 
                        array( '%d' ) 
                    );
                }
           }
        }
        if($t<2) $this->crawl_match(1);
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
        
        wp_enqueue_style( 'upmatch-css',  plugin_dir_url( __FILE__ ).'css/style.css', 1, '1.0', null );
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
    }
    /**
     * Setup Cron interval for 2 hours once
     */
    function add_3honce( $schedules ) {
        // add a 'weekly' schedule to the existing set
        $schedules['o3h'] = array(
            'interval' => 10800,
            'display' => __('Once 3 Hours')
        );
        return $schedules;
    }
  
    function deactivating() {
        wp_clear_scheduled_hook( 'upmatch_cron' );
    }
}

$GLOBALS['Upcoming_match'] = new Upcoming_Match();