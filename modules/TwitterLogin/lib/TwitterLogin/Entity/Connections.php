<?php

/**
 * TwitterLogin
 *
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * Favorites entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="twitter_login")
 */
class TwitterLogin_Entity_Connections extends Zikula_EntityAccess {

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The following are annotations which define the facebook user id field.
     *
     * @ORM\Column(type="text")
     */
    private $twitter_id;

    /**
     * The following are annotations which define the user id field.
     *
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * The following are annotations which define the email field.
     *
     * @ORM\Column(type="text")
     */
    private $email;

    /**
     * The following are annotations which define the status field.
     *
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getid() {
        return $this->id;
    }

    public function gettwitter_id() {
        return $this->twitter_id;
    }

    public function getuser_id() {
        return $this->user_id;
    }

    public function getemail() {
        return $this->email;
    }

    public function getstatus() {
        return $this->status;
    }

    public function settwitter_id($twitter_id) {
        $this->twitter_id = $twitter_id;
    }

    public function setuser_id($user_id) {
        $this->user_id = $user_id;
    }

    public function setemail($email) {
        $this->email = $email;
    }

    public function setstatus($status) {
        $this->status = $status;
    }

}