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

    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $offset;

    public function __construct(Database $db, Curl $curl, $count = 10, $offset = 0)
    {
        $this->db = $db->getConnection();
        $this->curl = $curl;
        $this->count = $count;
        $this->offset = $offset;
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

    public function run()
    {
        $time_start = microtime(true);
        echo "Progress :          ";
        $increment = 1;
        $offset = $this->offset + 1;
        while ($increment <= $this->count){
            if($user = $this->curl->getUser($offset)){
                $this->insert($user);
                echo "\033[11D";
                echo str_pad($increment, 5, ' ', STR_PAD_LEFT) . " users";
                $increment++;
            }
            $offset++;
        }
        $time_end = microtime(true);
        $time = round(($time_end - $time_start), 2);

        echo  " in $time seconds\n";
    }
}