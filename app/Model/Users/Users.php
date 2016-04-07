<?php
namespace SharingThinks\Model\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Users extends \Kdyby\Doctrine\Entities\BaseEntity
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
     * @ORM\manyToMany(targetEntity="\SharingThinks\Model\Think\Thinks")
     * @ORM\joinTable(
     *     name="users_thinks",
     *     joinColumns={
     *         @ORM\joinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\joinColumn(name="think_id", referencedColumnName="id")
     *     }
     * )
     */
    private $thinks;

}