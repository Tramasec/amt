<?php
namespace AMT;

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\DomCrawler\Crawler;

class AMT
{
    private $url = '';
    private $url2 = '';

    public function __construct(string $url, string $url2)
    {
        $this->url = $url;
        $this->url2 = $url2;
    }

    public function getVehicle(string $reference)
    {
        $client = new Client([
            'cookies' => true,
            'force_ip_resolve' => 'v4',
            'verify' => false
        ]);

        //error_reporting(E_ERROR | E_PARSE);


        $response = $client->get($this->url2);
        //var_dump($response->getBody()->getContents());
        $cookieJar = $client->getConfig('cookies');

        //var_dump($cookieJar);
        $response = $client->get($this->url.'?cboTipoBusqueda=1&txtDato='.$reference);

        $data = $response->getBody()->getContents();
        $data = str_replace('<?xml version="1.0" encoding="ISO-8859-1" ?>', '', $data);
        $crawler = new Crawler($data);
        $crawler = $crawler->filter('.dato > td');
        $cont = 1;

        $info = [];

        foreach ($crawler as $domElement) {
            if ($cont == 3){
                $info[] = $domElement->textContent;
            }
            $cont++;
            if ($cont == 4) {
                $cont = 1;
            }
        }

        return $info;

    }

}