# gj-social

Add your tokens/keys/usernames to the admin panel and then instantiate a new `gjSocial()` class in your theme. Returned will be an array containing an object of the social media feed of choice to be parsed and consumed in the feed. This is raw data return only.

## usage

```
$gjSocial = new gjSocial();
$gjSocial->display($network, $count, $time);
```

| variable | expected (default) | options                              |
| -------- | ------------------ | -------------------------------------|
| $network | string (none)      | twitter, instagram, facebook, tumblr |
| $count   | int (10)           |                                      |
| $time    | int (60)           |                                      |

## dependencies

[Twitter PHP API](https://github.com/J7mbo/twitter-api-php)

## requirements

PHP 5.4

## License

MIT