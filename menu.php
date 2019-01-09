<div class="wrap">
<h1>Upcoming Match Settings <small style="font-size:9px;">v.1.0 By Odhier</small></h1>

<form method="post" action="options.php">
    <?php settings_fields( '_upmatch_setting' ); ?>
    <?php do_settings_sections( '_upmatch_setting' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Match</th>
        <td>
            <!-- <input type="color" id="id_match" name="id_match" class="id_match" value="<?php echo esc_attr( get_option('id_match') ); ?>"> -->
           <select name="_id_match" id="_id_match">
           <?php 
           global $wpdb;
           $matches = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}upmatches WHERE status='not_started'", ARRAY_A ); 
           foreach($matches as $match){
               $date = date('d/m/Y H:i:s',strtotime($match['match_datetime'] ." +7 hours")); 
            ?>
               <option <?php echo (esc_attr( get_option('_id_match')) == $match['match_id'])?'selected':'';?> value="<?php echo $match['match_id'];?>"><?php echo "<b>".$match['teamh']."</b> VS <b>".$match['teama']."</b> (".$date.")";?></option> 
            <?php } ?>
            
               </select>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>