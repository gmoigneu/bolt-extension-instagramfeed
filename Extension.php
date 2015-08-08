<?php

namespace Bolt\Extension\Fsec\InstagramFeed;

use Bolt\Application;
use Bolt\BaseExtension;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Instagram\ImplicitAuth;

class Extension extends BaseExtension
{

    const NAME = 'InstagramFeed';

    private $version = "v0.9.0";

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function initialize() {
        if ($this->app['config']->getWhichEnd() == 'frontend') {
            // Twig hook
            $this->addTwigFunction('instagramfeed', 'twigInstagramFeed');
        }

        $this->app['twig.loader.filesystem']->prependPath(__DIR__."/twig");
    }

    public function getName()
    {
        return Extension::NAME;
    }

    /**
     * Render the Twitter feed
     */
    public function twigInstagramFeed($count = 10) {

        $images = $this->getImages($count);

        $html = $this->app['render']->render('_instagram.twig', ['images' => $images]);
        return new \Twig_Markup($html, 'UTF-8');
    }

    protected function getImages($count)
    {
        $cacheId = 'instagramfeed_images_'.$count;

        if ($this->app['cache']->contains($cacheId)) {
            return $this->app['cache']->fetch($cacheId);
        } else {
            $client = new Client();

            $implicitAuth = new ImplicitAuth($this->config);
            $client->getEmitter()->attach($implicitAuth);

            $oauth = $client->post('https://instagram.com/oauth/authorize');

            $access_token = $implicitAuth->getAccessToken();

            $content = $client->get('https://api.instagram.com/v1/users/self/media/recent?count='.$count.'&access_token='.$access_token);
            $body = json_decode($content->getBody());
            $images = $body->data;

            $this->app['cache']->save($cacheId, $images, $this->config['cache_lifetime']);
            return $images;
        }
    }

    protected function getDefaultConfig()
    {
        return array(
            'username' => '',
            'password' => '',
            'client_id' => '',
            'redirect_uri' => '',
            'cache_lifetime' => 7200
        );
    }

}






