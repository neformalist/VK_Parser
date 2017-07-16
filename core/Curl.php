<?php

/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 16.07.17
 * Time: 1:27
 */

namespace Parser;

class Curl
{
    /**
     * @var resource
     */
    private $curl;

    /**
     * @var \domDocument
     */
    private $dom;


    public function __construct()
    {
        $this->curl = curl_init();
        $agent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:54.0) Gecko/20100101 Firefox/54.0';
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $agent);
        curl_setopt($this->curl, CURLOPT_ENCODING, '');

        $this->dom = new \domDocument;
        libxml_use_internal_errors(true);
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    /**
     * @param $id integer
     * @return mixed|string
     */
    private function exec($id)
    {
        curl_setopt($this->curl, CURLOPT_URL, "https://vk.com/id$id");
        $res = curl_exec($this->curl);
        $html = iconv('windows-1251', 'UTF-8', $res);
        return mb_convert_encoding($html, "HTML-ENTITIES", "UTF-8");
    }

    /**
     * @param $id integer
     * @return null|User
     */
    public function getUser($id)
    {
        if($html_source = $this->exec($id)){
            $this->dom->loadHTML($html_source);
            $xpath = new \DOMXpath($this->dom);
            $name = $xpath->query('//h2[contains(@class, "page_name")]');
            if(!empty($name->item(0))){
                $user = new User($id);
                $user->setName($name->item(0)->nodeValue);
                $info = $xpath->query('//div[contains(@class, "profile_info_row")]');
                $user->setInfo($info);
                return $user;
            }
        }
        return null;
    }
}