<?php

namespace SharingThinks\Model\Think;

use Nette;


class ThinksRepository
{
    private $thisRepository;
    
    private $entityManager;

    public function __construct(\Kdyby\Doctrine\EntityManager $entityManager) {
	$this->entityManager = $entityManager;
	$this->thisRepository = $this->entityManager->getRepository(\SharingThinks\Model\Think\Thinks::getClassName());
    }
    
    public function saveThink($values, $owner, $users) {
	if ($values->thinkId) {
	    $think = $this->thisRepository
		    ->find($values->thinkId);
	} else {
	    $think = new \SharingThinks\Model\Think\Thinks();
	    $think->setOwner($owner);
	    $think->setCreationDate(new \Nette\Utils\DateTime('now'));
	}
	$think->setName($values->name);
	$think->setDescription($values->description);
	$think->setMinimum($values->minimum);
	$think->setMaximum($values->maximum);
	$think->setPause($values->pause);
	$think->setOpen($values->open);
	if ($values->open == 1) {
	    foreach ($users as $user) {
		$think->addUser($user);
	    }
	}
	$think->setVisible(1);
	$this->entityManager->persist($think);
	$this->entityManager->flush();
    }
    
    public function findThinksForUser($userId) {
	return $this->thisRepository
		->findBy(array('ownerId' => $userId, 'visible' => 1));
    }
    
    public function findDeletedThinksForUser($userId) {
	return $this->thisRepository
		->findBy(array('ownerId' => $userId, 'visible' => 0));
    }
    
    public function findThinksToHire($userId) {
	$query = $this->entityManager->createQuery('SELECT t FROM \SharingThinks\Model\Think\Thinks t '
		. 'WHERE t.ownerId != :userId AND t.open = 1');
	$query->setParameter('userId', $userId);
	return $query->getResult();
    }
    
    public function deleteThink($thinkId) {
	$think = $this->thisRepository->find($thinkId);
	$think->setVisible(0);
	$this->entityManager->persist($think);
	$this->entityManager->flush();
    }
    
    public function refreshThink($thinkId) {
	$think = $this->thisRepository->find($thinkId);
	$think->setVisible(1);
	$this->entityManager->persist($think);
	$this->entityManager->flush();	 
    }
    
    public function completeDeleteThink($thinkId) {
	$think = $this->thisRepository->find($thinkId);
	$this->entityManager->remove($think);
	$this->entityManager->flush();
    }
    
    public function getThink($thinkId) {
	return $this->thisRepository->find($thinkId);
    }
}