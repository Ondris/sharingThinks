<?php

namespace SharingThinks\Model\Uses;

use Nette;


class UsesRepository
{
    private $thisRepository;
    
    private $entityManager;

    public function __construct(\Kdyby\Doctrine\EntityManager $entityManager) {
	$this->entityManager = $entityManager;
	$this->thisRepository = $this->entityManager->getRepository(\SharingThinks\Model\Uses\Uses::getClassName());
    }
 
    public function getUses($usesId) {
	return $this->thisRepository->find($usesId);
    }
}

