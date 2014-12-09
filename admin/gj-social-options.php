<?php

if(isset($_POST['gj_social_settings'])) {
  if(1 === check_admin_referer('gj-maps-settings')) {

    $twitter_username = $_POST['gj_social_twitter_username'];
    update_option('gj_social_twitter_username', $twitter_username);
    $twitter_token = $_POST['gj_social_twitter_token'];
    update_option('gj_social_twitter_token', $twitter_token);
    $twitter_token_secret = $_POST['gj_social_twitter_token_secret'];
    update_option('gj_social_twitter_token', $twitter_token_secret);
    $twitter_consumer_key = $_POST['gj_social_twitter_consumer_key'];
    update_option('gj_social_twitter_consumer_key', $twitter_consumer_key);
    $twitter_consumer_secret = $_POST['gj_social_twitter_consumer_secret'];
    update_option('gj_social_twitter_consumer_secret', $twitter_consumer_secret);

    echo '<div id="message" class="updated"><p>'.$response['message'].'</p></div>';
  }
} else {

  $twitter_username = get_option('gj_social_twitter_username');
  $twitter_token = get_option('gj_social_twitter_token');
  $twitter_token_secret = get_option('gj_social_twitter_token_secret');
  $twitter_consumer_key = get_option('gj_social_twitter_consumer_key');
  $twitter_consumer_secret = get_option('gj_social_twitter_consumer_secret');

} ?>

<table class="gj-social-settings">
  <tr>
    <td><h3>Twitter</h3></td>
  </tr>
  <form name="gj_social_settings" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="gj_social_settings" value="1">
    <?php wp_nonce_field('gj-social-settings'); ?>
    <tr>
      <td><p>Username: </p></td>
      <td><input name="gj_social_twitter_username" value="<?php echo $twitter_username != "" ? $twitter_username : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Token: </p></td>
      <td><input name="gj_social_twitter_token" value="<?php echo $twitter_token != "" ? $twitter_token : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Token Secret: </p></td>
      <td><input name="gj_social_twitter_token_secret" value="<?php echo $twitter_token_secret != "" ? $twitter_token_secret : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Consumer Key: </p></td>
      <td><input name="gj_social_twitter_consumer_key" value="<?php echo $twitter_consumer_key != "" ? $twitter_consumer_key : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Consumer Secret: </p></td>
      <td><input name="gj_social_twitter_consumer_secret" value="<?php echo $twitter_consumer_secret != "" ? $twitter_consumer_secret : ''; ?>"></td>
    </tr>
    <tr colspan="1">
      <td><button class="btn button" type="submit">Update Settings</button></td>
    </tr>
  </form>

</table>
