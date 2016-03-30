<?php
namespace SharingThinks\Model\Uses;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Uses extends \Kdyby\Doctrine\Entities\BaseEntity
{

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
     * @ORM\manyToOne(targetEntity="\SharingThinks\Model\Think\Thinks")
     * @ORM\joinColumn(name="think_id", referencedColumnName="id") 
     */
    protected $think;
    
    /**
      * @ORM\Column(type="integer")
     */    
    protected $start;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $duration;
    
    /**
      * @ORM\Column(type="integer")
     */
    protected $userId;
    
    /**
     * @ORM\manyToOne(targetEntity="\SharingThinks\Model\User\Users")
     * @ORM\joinColumn(name="user_id", referencedColumnName="id") 
     */
    protected $user;
    
    /**
     * @ORM\Column(type="datetime", length=255, nullable=true)
     */
    protected $modification;

}

