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
    
    public function saveThink($values, $user) {
	$think = new \SharingThinks\Model\Think\Thinks();
	$think->setName($values->name);
	$think->setDescription($values->description);
	$think->setMinimum($values->minimum);
	$think->setMaximum($values->maximum);
	$think->setPause($values->pause);
	$think->setOwnerId($user->getId());
	$think->setCreationDate(new \Nette\Utils\DateTime('now'));
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
}