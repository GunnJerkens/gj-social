# gj-social

Small WordPress plugin that returns an object of your personal Facebook, Twitter, Instagram, or Tumblr to use in your theme. It is stored in the database and allows you to call a custom number of posts and set a cache value for the content (so your user isn't waiting on an API call every page load). Default cache is 60 minutes and the default number of posts is 10.

## usage

For the networks you want to access data from just fill in the required fields and instantiate the class in your code.

```
$gjSocial = new gjSocial();
$gjSocial->display($network, $count, $time);
```

| variable | expected (default) | options                              |
| -------- | ------------------ | -------------------------------------|
| $network | string             | twitter, instagram, facebook, tumblr |
| $count   | int (10)           |                                      |
| $time    | int (60)           |                                      |
| $fields  | array (string)     | requested fields, facebook only      |

### tips

Each social site requires different ways to access their API. Here is my feeble attempt at including a couple guides to help get the keys needed to make this work.

#### instagram
[Access Token Creation](http://jelled.com/instagram/access-token)  
[User ID Lookup](http://jelled.com/instagram/lookup-user-id)  

## dependencies

[Twitter PHP API](https://github.com/J7mbo/twitter-api-php)

## requirements

PHP 5.4

## License

MIT
