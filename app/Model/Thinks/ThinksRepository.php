<?php

namespace SharingThinks\Model\Think;

use Nette;

class ThinksRepository {

    /** @var \Kdyby\Doctrine\EntityManager $entityManager */
    private $entityManager;
    private $entityName;
    private $thisRepository;

    public function __construct(\Kdyby\Doctrine\EntityManager $entityManager) {
	$this->entityManager = $entityManager;
	$this->entityName = \SharingThinks\Model\Think\Thinks::getClassName();
	$this->thisRepository = $this->entityManager->getRepository($this->entityName);
    }

    //select methods     
    public function getThink($thinkId) {
	return $this->thisRepository->find($thinkId);
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
		. 'WHERE t.ownerId != :userId AND t.close = 1');
	$query->setParameter('userId', $userId);
	return $query->getResult();
    }

    //insert methods
    public function saveThink($values, \SharingThinks\Model\User\Users $owner, $users) {
	if ($values->thinkId) {
	    $think = $this->thisRepository->find($values->thinkId);
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
	$think->setClose($values->close);
	if ($values->close == 1) {
	    $think->removeAllUsers();
	    $think->addUsers($users);
	} else {
	    $think->removeAllUsers();
	}
	$think->setVisible(1);
	$this->entityManager->persist($think);
	$this->entityManager->flush();
    }

    //update methods
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

    //delete methods
    public function completeDeleteThink($thinkId) {
	$think = $this->thisRepository->find($thinkId);
	$this->entityManager->remove($think);
	$this->entityManager->flush();
    }

}
