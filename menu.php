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
               <option value="123">Liverpool vs Mancity</option>
               <option value="123">Liverpool vs Mancity</option>
               <option value="123">Liverpool vs Mancity</option>
               <option value="123">Liverpool vs Mancity</option>
           </select>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>