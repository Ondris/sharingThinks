<?php

namespace SharingThinks\Model\Uses;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Uses extends \Kdyby\Doctrine\Entities\BaseEntity {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $thinkId;

    /**
     * @ORM\manyToOne(targetEntity="\SharingThinks\Model\Think\Thinks", inversedBy="uses")
     * @ORM\joinColumn(name="think_id", referencedColumnName="id") 
     */
    protected $think;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $start;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $end;

    /**
     * @ORM\Column(type="integer")
     */
    protected $userId;

    /**
     * @ORM\manyToOne(targetEntity="\SharingThinks\Model\User\Users", inversedBy="uses")
     * @ORM\joinColumn(name="user_id", referencedColumnName="id") 
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime", length=255, nullable=true)
     */
    protected $modification;

    public function getStartTimeReadyToUse() {
	return $this->start->add($this->think->pause);
    }

    public function getEndTimeReadyToUse() {
	return $this->end->add($this->think->pause);
    }

}
