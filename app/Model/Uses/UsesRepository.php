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
    
    public function saveUse($user, $think, $values) {
	$uses = new \SharingThinks\Model\Uses\Uses();
	try {
	    $this->isThinkFree($values->start, $values->end);
	} catch (\Exception $ex) {
	    return FALSE;
	}
	$uses->setThink($think);
	$uses->setStart($values->start);
	$uses->setEnd($values->end);
	$uses->setUser($user);
	$uses->setModification(new \Nette\Utils\DateTime('now'));
	$this->entityManager->persist($uses);
	$this->entityManager->flush();
    }
    
    private function isThinkFree($start, $end) {
	$isFree = TRUE;
	
	$q1 = $this->getIsThinkFreeQuery('u.start < ?1', 'u.end > ?2');
	$q1->setParameters(array(1 => $start, 2 => $start));
	if ($q1->getResult()) {
	    throw new \Exception('Time use already used.');
	}
	
	$q2 = $this->getIsThinkFreeQuery('u.start < ?1', 'u.end > ?2');
	$q2->setParameters(array(1 => $end, 2 => $end));
	if ($q2->getResult()) {
	    throw new \Exception('Time use already used.');
	}
	
	$q3 = $this->getIsThinkFreeQuery('u.start > ?1', 'u.end < ?2');
	$q3->setParameters(array(1 => $start, 2 => $end));
	if ($q3->getResult()) {
	    throw new \Exception('Time use already used.');
	}
	
	return $isFree;
    }
    
    private function getIsThinkFreeQuery($firstCondition, $secondCondition) {
	return $this->entityManager->createQueryBuilder()
		->select('u')
		->from(\SharingThinks\Model\Uses\Uses::getClassName(), 'u')
		->where($firstCondition)->andWhere($secondCondition)
		->getQuery();
    }
    
    public function deleteUse($useId) {
	$use = $this->thisRepository->find($useId);
	$this->entityManager->remove($use);
	$this->entityManager->flush();
    }
}

