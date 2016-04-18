<?php

namespace SharingThinks\Model\Think;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Thinks extends \Kdyby\Doctrine\Entities\BaseEntity {

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
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $minimum;

    /**
     * @ORM\Column(type="string")
     */
    protected $maximum;

    /**
     * @ORM\Column(type="string")
     */
    protected $pause;

    /**
     * @ORM\Column(type="integer")
     */
    protected $close;

    /**
     * @ORM\Column(name="owner_id", type="integer")
     */
    protected $ownerId;

    /**
     * @ORM\manyToOne(targetEntity="\SharingThinks\Model\User\Users", inversedBy="myThinks")
     * @ORM\joinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $creationDate;

    /**
     * @ORM\Column(type="integer")
     */
    protected $visible;

    /**
     * @ORM\oneToMany(targetEntity="\SharingThinks\Model\Uses\Uses", mappedBy="think")
     */
    protected $uses;

    /**
     * @ORM\manyToMany(targetEntity="\SharingThinks\Model\User\Users")
     * @ORM\joinTable(
     *     name="users_thinks",
     *     joinColumns={
     *         @ORM\joinColumn(name="think_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\joinColumn(name="user_id", referencedColumnName="id")
     *     }
     * )
     */
    private $users;

    public function getMinimum() {
	return new \DateInterval($this->minimum);
    }

    public function getMinimumSeconds() {
	$seconds = $this->getMinimum()->format('%d') * 86400;
	$seconds += $this->getMinimum()->format('%h') * 3600;
	$seconds += $this->getMinimum()->format('%i') * 60;
	return $seconds;
    }

    public function setMinimum(\DateInterval $minimum) {
	$this->minimum = 'P' . $minimum->format('%d') . 'DT' . $minimum->format('%h') . 'H' . $minimum->format('%i') . 'M';
    }

    public function getMaximum() {
	return new \DateInterval($this->maximum);
    }

    public function setMaximum(\DateInterval $maximum) {
	$this->maximum = 'P' . $maximum->format('%d') . 'DT' . $maximum->format('%h') . 'H' . $maximum->format('%i') . 'M';
    }

    public function getPause() {
	return new \DateInterval($this->pause);
    }

    public function setPause(\DateInterval $pause) {
	$this->pause = 'P' . $pause->format('%d') . 'DT' . $pause->format('%h') . 'H' . $pause->format('%i') . 'M';
    }

    public function getUsers() {
	return $this->users;
    }

    public function addUsers($users) {
	foreach ($users as $user) {
	    $this->users->add($user);
	}
    }

    public function removeAllUsers() {
	if ($this->users) {
	    foreach ($this->users as $user) {
		$this->users->removeElement($user);
	    }
	}
    }

    public function getUserIds() {
	$userIds = array();
	foreach ($this->users as $user) {
	    $userIds[] = $user->id;
	}
	return $userIds;
    }

    public function getOwner() {
	return $this->owner;
    }

    public function setOwner(\SharingThinks\Model\User\Users $owner = NULL) {
	$this->owner = $owner;
    }

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

}
