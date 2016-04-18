<?php

namespace SharingThinks\Model\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Users extends \Kdyby\Doctrine\Entities\BaseEntity {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime", length=255, nullable=true)
     */
    protected $creationDate;

    /**
     * @ORM\Column(type="string")
     */
    protected $role;

    /**
     * @ORM\Column(type="integer")
     */
    protected $visible;

    /**
     * @ORM\oneToMany(targetEntity="\SharingThinks\Model\Think\Thinks", mappedBy="owner")
     */
    protected $myThinks;

    /**
     * @ORM\oneToMany(targetEntity="\SharingThinks\Model\Uses\Uses", mappedBy="user")
     */
    protected $uses;

    /**
     * @ORM\manyToMany(targetEntity="\SharingThinks\Model\Think\Thinks", mappedBy="users")
     */
    private $thinks;

    public function getUses() {
	$usesArray = array();
	$actualDate = new \Nette\Utils\DateTime('now');
	if ($this->uses) {
	    foreach ($this->uses as $uses) {
		if ($uses->end > $actualDate) {
		    $usesArray[] = $uses;
		}
	    }
	}
	return $usesArray;
    }

    public function getThinks() {
	return $this->thinks;
    }

}
