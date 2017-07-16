<?php

/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 16.07.17
 * Time: 1:28
 */

namespace Parser;

class User
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $first_name;

    /**
     * @var string
     */
    private $last_name;

    /**
     * @var string
     */
    private $middle_name;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $university;

    /**
     * @var string
     */
    private $job;

    /**
     * @var string
     */
    private $skype;

    /**
     * @var string
     */
    private $web;

    /**
     * User constructor.
     * @param $id integer
     */
    public function __construct($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }



    /**
     * @param $_name string
     */
    public function setName($_name)
    {
        $pos = mb_strpos($_name, ' ');
        $this->first_name = mb_substr($_name, 0, $pos);
        if($pos = mb_strpos($_name, ' ', (mb_strlen($this->first_name) + 2))){
            $this->middle_name = trim(mb_substr($_name, mb_strlen($this->first_name), $pos - mb_strlen($this->first_name)));
            $this->last_name = trim(mb_substr($_name, $pos));
        }else{
            $this->last_name = trim(mb_substr($_name, mb_strlen($this->first_name)));
        }
    }

    /**
     * @param $nodes \DOMNodeList
     */
    public function setInfo($nodes)
    {
        foreach ($nodes as $item){
            switch ($item->childNodes[1]->nodeValue){
                case 'Город:':
                    $this->city = $item->childNodes[3]->nodeValue;
                    break;
                case 'Вуз:':
                    $this->university = $item->childNodes[3]->nodeValue;
                    break;
                case 'Место работы:':
                    $this->job = $item->childNodes[3]->nodeValue;
                    break;
                case 'Моб. телефон:':
                    $this->phone = $item->childNodes[3]->nodeValue;
                    break;
                case 'Веб-сайт:':
                    $this->web = $item->childNodes[3]->nodeValue;
                    break;
                case 'Skype:':
                    $this->skype = $item->childNodes[3]->nodeValue;
                    break;
            }
        }
    }
}