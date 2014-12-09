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
   * Class variables
   *
   * @var array
   */

  protected $content;

  /**
   * Default class constructor
   *
   * @return void
   */
  function __construct() {
    update_option("gj_social_version", "0.1.0");
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
    $data['response'] = (object) json_decode($this->content['response'], true);

    return $data;
  }

  /**
   * Sets the database cache, puts the data into the class array
   *
   * @param string, int, int
   *
   * @return void
   */

  private function cacheSocialData($sourceTime, $count, $minutes = 60) {
    $currentTime = time(); 
    $expireTime  = $minutes * 60;
    $sourceTime  = get_option('gj_social_'.$source.'_timestamp');
    $data        = array();

    if($sourceTime && ($currentTime - $expireTime < $sourceTime)) {

      $content = unserialize(get_option('gj_social'.$sourceTime));

    } else {
      switch($sourceTime) {
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
    }

    update_option('gj_social_'.$sourceTime.'_timestamp', time());
    update_option('gj_social_'.$sourceTime, serialize($content));

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
      'oauth_access_token'        => get_option('gj_social_twitter_token'),
      'oauth_access_token_secret' => get_option('gj_social_twitter_token_secret'),
      'consumer_key'              => get_option('gj_social_twitter_consumer_key'),
      'consumer_secret'           => get_option('gj_social_twitter_consumer_secret'),
    ];
    $username = get_option('gj_social_twitter_username');
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$username.'&count='.$count;
    $requestMethod = 'GET';

    $twitter = new TwitterAPIExchange($settings);
    $tweets= $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();

    return $tweets;
  }

}
new gjSocial();
