<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 16.07.17
 * Time: 1:59
 */

namespace Parser;


use DataSource\Database;


class Parser
{
    /**
     * @var \PDO
     */
    private $db;
    /**
     * @var Curl
     */
    private $curl;

    public function __construct(Database $db, Curl $curl)
    {
        $this->db = $db->getConnection();
        $this->curl = $curl;
    }

    /**
     * @param \Parser\User $user
     */
    private function insert(User $user)
    {
        $sql = <<<SQL
INSERT INTO vk (
first_name,last_name,middle_name,city,phone,university,job,skype,web,vk_id
) VALUES (
:first_name,
:last_name,
:middle_name,
:city,
:phone,
:university,
:job,
:skype,
:web,
:vk_id
)
SQL;
        $statement = $this->db->prepare($sql);
        $statement->bindValue(':first_name', $user->getFirstName());
        $statement->bindValue(':last_name', $user->getLastName());
        $statement->bindValue(':middle_name', $user->getMiddleName());
        $statement->bindValue(':city', $user->getCity());
        $statement->bindValue(':phone', $user->getPhone());
        $statement->bindValue(':university', $user->getUniversity());
        $statement->bindValue(':job', $user->getJob());
        $statement->bindValue(':skype', $user->getSkype());
        $statement->bindValue(':web', $user->getWeb());
        $statement->bindValue(':vk_id', $user->getId());

        $statement->execute();
    }

    public function parseUser($offset)
    {
        if($user = $this->curl->getUser($offset)){
            $this->insert($user);
            return true;
        }
        return false;
    }
}