<?php

/**
 * FConnect
 *
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * Favorites entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="google")
 */
class Google_Entity_Connections extends Zikula_EntityAccess {

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
    private $google_id;

    /**
     * The following are annotations which define the user id field.
     *
     * @ORM\Column(type="integer")
     */
    private $user_id;

    public function getid() {
        return $this->id;
    }

    public function getgoogle_id() {
        return $this->google_id;
    }

    public function getuser_id() {
        return $this->user_id;
    }

    public function setgoogle_id($google_id) {
        $this->google_id = $google_id;
    }

    public function setuser_id($user_id) {
        $this->user_id = $user_id;
    }

}