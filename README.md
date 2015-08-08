Bolt Extension Instagram Feed
======================

Display your last media feed on any twig template.
The call to the Instagram API is made throught the Implicit Authentication method provided by [Rafael Calleja Instagram Subscribeer](https://github.com/rafaelcalleja/guzzle-instagram-subscriber).

## Dependencies

    {
        "require": {
            "guzzlehttp/guzzle-instagram-subscriber": "0.1.*"
        }
    }

## Setup

### 1. Instagram developers
Go to the [Instagram Developer Platform](https://instagram.com/developer/) and register a new client.
Don't forget to uncheck `Disable implicit OAuth`in the security tab of the client.

### 2. Bolt
Activate the extension and setup the `instagram-feed.fsec.yml` config file :

    username: <your instagram username>
    password: <your password>
    client_id: <your application client id>
    cache_lifetime: <seconds>
    redirect_uri: <your website url>

Juste use : `{{ instagramfeed(x) }}` where x is the number of photos you need to display.

The feed has a cache lifetime of two hours by default.
