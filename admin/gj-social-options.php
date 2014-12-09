<?php

if(isset($_POST['gj_social_settings'])) {
  if(1 === check_admin_referer('gj-social-settings')) {

    $settings = (object) [
      'twitter'   => (object) [
        'username'        => $_POST['gj_social_twitter_username'],
        'token'           => $_POST['gj_social_twitter_token'],
        'token_secret'    => $_POST['gj_social_twitter_token_secret'],
        'consumer_key'    => $_POST['gj_social_twitter_consumer_key'],
        'consumer_secret' => $_POST['gj_social_twitter_consumer_secret'],
      ],
      'facebook'  => (object) [
        'token'           => $_POST['gj_social_facebook_token'],
        'page_id'         => $_POST['gj_social_facebook_page_id'],
      ],
      'instagram' => (object) [
        'token'           => $_POST['gj_social_instagram_token'],
      ],
      'tumblr'    => (object) [
        'api_key'         => $_POST['gj_social_tumblr_api_key'],
        'hostname'        => $_POST['gj_social_tumblr_hostname'],
      ],
    ];

    $settingsEncoded = json_encode($settings);
    update_option('gj_social_settings', $settingsEncoded);

    echo '<div id="message" class="updated"><p>Settings updated.</p></div>';
  }
} else {
  $settings = json_decode(get_option('gj_social_settings'));
}

var_dump($settings); ?>

<style>
table {
  width: 60%;
}
input.gj-input {
  width: 100%;
}
</style>

<table class="gj-social-settings">
  <form name="gj_social_settings" method="post">
    <input type="hidden" name="gj_social_settings" value="1">
    <?php wp_nonce_field('gj-social-settings'); ?>
    <tr>
      <td><h3>Twitter</h3></td>
    </tr>
    <tr>
      <td><p>Username: </p></td>
      <td><input class="gj-input" name="gj_social_twitter_username" value="<?php echo $settings && $settings->twitter->username != "" ? $settings->twitter->username : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Token: </p></td>
      <td><input class="gj-input" name="gj_social_twitter_token" value="<?php echo $settings && $settings->twitter->token != "" ? $settings->twitter->token : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Token Secret: </p></td>
      <td><input class="gj-input" name="gj_social_twitter_token_secret" value="<?php echo $settings && $settings->twitter->token_secret != "" ? $settings->twitter->token_secret : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Consumer Key: </p></td>
      <td><input class="gj-input" name="gj_social_twitter_consumer_key" value="<?php echo $settings && $settings->twitter->consumer_key != "" ? $settings->twitter->consumer_key : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Consumer Secret: </p></td>
      <td><input class="gj-input" name="gj_social_twitter_consumer_secret" value="<?php echo $settings && $settings->twitter->consumer_secret != "" ? $settings->twitter->consumer_secret : ''; ?>"></td>
    </tr>
    <tr>
      <td><h3>Facebook</h3></td>
    </tr>
    <tr>
      <td><p>Token: </p></td>
      <td><input class="gj-input" name="gj_social_facebook_token" value="<?php echo $settings && $settings->facebook->token != "" ? $settings->facebook->token : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>Page ID: </p></td>
      <td><input class="gj-input" name="gj_social_facebook_page_id" value="<?php echo $settings && $settings->facebook->page_id !="" ? $settings->facebook->page_id : ''; ?>"></td>
    </tr>
    <tr>
      <td><h3>Instagram</h3></td>
    </tr>
    <tr>
      <td><p>Token: </p></td>
      <td><input class="gj-input" name="gj_social_instagram_token" value="<?php echo $settings && $settings->instagram->token !="" ? $settings->instagram->token : ''; ?>"></td>
    </tr>
    <tr>
      <td><h3>Tumblr</h3></td>
    </tr>
    <tr>
      <td><p>Hostname: </p></td>
      <td><input class="gj-input" name="gj_social_tumblr_hostname" value="<?php echo $settings && $settings->tumblr->hostname != "" ? $settings->tumblr->hostname : ''; ?>"></td>
    </tr>
    <tr>
      <td><p>API Key: </p></td>
      <td><input class="gj-input" name="gj_social_tumblr_api_key" value="<?php echo $settings && $settings->tumblr->api_key != "" ? $settings->tumblr->api_key : ''; ?>"></td>
    </tr>
    <tr colspan="1">
      <td><button class="btn button" type="submit">Update Settings</button></td>
    </tr>
  </form>

</table>
