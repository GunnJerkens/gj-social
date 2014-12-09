<?php
/*
 * Plugin Name: GJ Social
 * Plugin URI: https://github.com/GunnJerkens/gj-social
 * Description: Pull down social media streams to json store for usage throughout a WP theme.
 * Version: 0.1.0
 * Author: GunnJerkens
 * Author URI: http://gunnjerkens.com
 * License: MIT
 */
class gjSocial {

  /**
   * Class vars
   *
   * @var array
   */
  protected $content, $settings;

  /**
   * Default class constructor
   *
   * @return void
   */
  function __construct() {
    add_action('admin_menu', array(&$this,'gj_social_admin_actions'));
    update_option("gj_social_version", "0.1.0");
    $this->getSettings();
  }

  /**
   * Load the options page to the admin
   *
   * @return void
   */
  function gj_social_admin_actions() {
    add_options_page( 'GJ Social', 'GJ Social', 'administrator', 'gj_social', array(&$this,'gj_social_admin_options'));
  }

  /**
   * Instantiate the options panel
   * 
   * @return void
   */
  function gj_social_admin_options() {
    include('admin/gj-social-options.php');
  }

  /**
   * Settings getter
   *
   * @return void
   */
  private function getSettings() {
    $this->settings = json_decode(get_option('gj_social_settings'));
  }

  /**
   * Public method to call twitter feed into the theme
   *
   * @param string, int, int
   *
   * @return object
   */
  public function display($network, $count = 10, $minutes = 60) {
    $this->cacheSocialData($network, $count, $minutes);

    $data = [];
    $data['time'] = date('D g:i a', $this->content['time']);
    $data['response'] = (object) json_decode($this->content['response']);

    return $data;
  }

  /**
   * Sets the database cache, puts the data into the class array
   *
   * @param string, int, int
   *
   * @return void
   */
  private function cacheSocialData($network, $count, $minutes = 60) {
    $currentTime = time(); 
    $expireTime  = $minutes * 60;
    $sourceTime  = get_option('gj_social_'.$network.'_timestamp');
    $data        = array();

    if($sourceTime && ($currentTime - $expireTime < $sourceTime)) {
      $content = unserialize(get_option('gj_social_'.$network));
    } else {
      switch($network) {
        case('twitter'):
          $content = $this->retrieveTwitter($count);
          break;
        case('instagram'):
          $content = $this->retrieveInstagram($count);
          break;
        case('facebook'):
          $content = $this->retrieveFacebook();
          break;
        case('tumblr'):
          $content = $this->retrieveTumblr($count);
          break;
      }
      update_option('gj_social_'.$network.'_timestamp', time());
      update_option('gj_social_'.$network, serialize($content));
    }

    $this->content['response'] = $content;
    $this->content['time']     = $currentTime;
  }

  /**
   * Data fetch function
   *
   * @param string
   *
   * @return string (json)
   */
  private function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
  }

  /**
   * Do some maths to return a string of how long ago something was created
   *
   * @param
   *
   * @return string
   */
  private function timeCreated($timeCreated) {
    $timeDiff = time() - $timeCreated;
    $timeTotal = round(abs(time() - $timeCreated) / 60);
    $timeHours = floor($timeTotal / 60);
    $timeDays = floor($timeHours / 24);
    $timeMinutes = $timeTotal % 60;

    $timeDays = $timeDays >= 1 ? $timeDays . ($timeDays == 1 ? ' day ' : ' days ') : '';
    $timeHours = $timeHours >= 1 ? $timeHours % 24 . ($timeHours == 1 ? ' hour ' : ' hours ') : '';
    $timeMinutes = $timeMinutes >= 1 ? $timeMinutes . ($timeMinutes == 1 ? ' minute ' : ' minutes ') : '';
    return $timeDays . $timeHours . $timeMinutes . 'ago';
  }

  /**
   * Retrieves the user timeline from Twitter
   *
   * @param int
   *
   * @return json
   */
  private function retrieveTwitter($count) {
    require_once(plugin_dir_path(__FILE__).'/libs/twitter-api-php/TwitterAPIExchange.php');

    $settings = [
      'oauth_access_token'        => $this->settings->twitter->token,
      'oauth_access_token_secret' => $this->settings->twitter->token_secret,
      'consumer_key'              => $this->settings->twitter->consumer_key,
      'consumer_secret'           => $this->settings->twitter->consumer_secret,
    ];
    $baseurl       = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield      = '?screen_name='.$this->settings->twitter->username.'&count='.$count;
    $requestMethod = 'GET';

    $twitter = new TwitterAPIExchange($settings);
    $tweets  = $twitter->setGetfield($getfield)->buildOauth($baseurl, $requestMethod)->performRequest();

    return $tweets;
  }

  /**
   * Retrieves the user timeline from Tumblr
   *
   * @param int
   *
   * @return json
   */
  private function retrieveTumblr($count) {
    $api_key  = $this->settings->tumblr->api_key;
    $hostname = $this->settings->tumblr->hostname;
    $posts    = $this->fetchData('http://api.tumblr.com/v2/blog/'.$hostname.'/posts?api_key='.$api_key.'&limit='.$count);

    return $posts;
  }

  /**
   * Retrieves the page timeline from Facebook
   *
   * @return json
   */
  private function retrieveFacebook() {
    $token = $this->settings->facebook->token;
    $page  = $this->settings->facebook->page_id;
    $posts = $this->fetchData('https://graph.facebook.com/'.$page.'/feed?access_token='.$token);

    return $posts;
  }

  /**
   * Retrieves the self timeline from Instagram
   *
   * @param int
   *
   * @return json
   */
  private function retrieveInstagram($count) {
    $token = $this->settings->instagram->token;
    $posts = $this->fetchData('https://api.instagram.com/v1/users/self/feed?access_token='.$token.'&count='.$count);

    return $posts;
  }

}
new gjSocial();
