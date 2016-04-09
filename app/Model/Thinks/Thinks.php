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
    protected $open;
    
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
    
    public function getUsers() {
	return $users;
    }
    
    public function addUser(\SharingThinks\Model\User\Users $user) {
	$this->users->add($user);
    }
    
    public function getOwner() {
	return $this->owner;
    }
    
    public function setOwner(\SharingThinks\Model\User\Users $owner = NULL) {
	$this->owner = $owner;
    }

}
