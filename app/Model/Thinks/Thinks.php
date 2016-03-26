<?php
namespace SharingThinks\Model\Think;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Thinks extends \Kdyby\Doctrine\Entities\BaseEntity
{

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
      * @ORM\Column(type="integer")
     */
    protected $minimum;
    
    /**
      * @ORM\Column(type="integer")
     */
    protected $maximum;
    
    /**
      * @ORM\Column(type="integer")
     */
    protected $pause;
    
        /**
      * @ORM\Column(type="integer")
     */
    protected $ownerId;
    
        /**
     * @manyToOne(targetEntity="Users")
     * @joinColumn(name="owner_id", referencedColumnName="id")
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

}
