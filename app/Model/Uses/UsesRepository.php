<?php

namespace SharingThinks\Model\Uses;

use Nette;

class UsesRepository {

    /** @var \Kdyby\Doctrine\EntityManager $entityManager */
    private $entityManager;
    private $entityName;
    private $thisRepository;

    public function __construct(\Kdyby\Doctrine\EntityManager $entityManager) {
	$this->entityManager = $entityManager;
	$this->entityName = \SharingThinks\Model\Uses\Uses::getClassName();
	$this->thisRepository = $this->entityManager->getRepository($this->entityName);
    }

    //select methods
    public function getUses($usesId) {
	return $this->thisRepository->find($usesId);
    }

    public function findUsesForUsers($usersId) {
	$actualDate = new \Nette\Utils\DateTime('now');
	$query = $this->entityManager->createQueryBuilder()
		->select('u')
		->from($this->entityName, 'u')
		->where('u.userId = (:userId)')
		->andWhere('u.end > (:actualDate)')
		->getQuery();
	$query->setParameters(array('userId' => $usersId, 'actualDate' => $actualDate));
	return $query->getResult();
    }

    //insert methods
    public function saveUse(\SharingThinks\Model\User\Users $user, \SharingThinks\Model\Think\Thinks $think, $values, \Nette\Http\SessionSection $correctSaveSession) {
	$uses = new \SharingThinks\Model\Uses\Uses();
	$start = $this->stringToDateTime($values->start);
	$end = $this->stringToDateTime($values->end);

	//ověření zda je záznam validní
	$this->isStartBeforeEnd($start, $end, $correctSaveSession);
	$this->isBorrowInAcceptableRange($start, $end, $think, $correctSaveSession);
	$this->isThinkFree($this->prepareStartDateTime($values->start, $think), $this->prepareEndDateTime($values->end, $think), $correctSaveSession);

	$uses->setThink($think);
	$uses->setStart($this->stringToDateTime($values->start));
	$uses->setEnd($this->stringToDateTime($values->end));
	$uses->setUser($user);
	$uses->setModification(new \Nette\Utils\DateTime('now'));
	$this->entityManager->persist($uses);
	$this->entityManager->flush();
    }

    private function prepareStartDateTime($startdatetime, \SharingThinks\Model\Think\Thinks $think) {
	$datetime = $this->stringToDateTime($startdatetime);
	return $datetime->sub($think->pause);
    }

    private function prepareEndDateTime($enddatetime, \SharingThinks\Model\Think\Thinks $think) {
	$datetime = $this->stringToDateTime($enddatetime);
	return $datetime->add($think->pause);
    }

    private function stringToDateTime($datetime) {
	if ($datetime) {
	    return \Nette\Utils\DateTime::createFromFormat('d.m.Y G:i', $datetime);
	} else {
	    return new \Nette\Utils\DateTime('now');
	}
    }

    private function isStartBeforeEnd(\DateTime $start, \DateTime $end, \Nette\Http\SessionSection $correctSaveSession) {
	if ($start > $end) {
	    $correctSaveSession->errorMessage = 'Začátek výpůjčky je před koncem.';
	    throw new \Exception('Začátek výpůjčky je před koncem.');
	}
    }

    private function isBorrowInAcceptableRange(\DateTime $start, \DateTime $end, \SharingThinks\Model\Think\Thinks $think, \Nette\Http\SessionSection $correctSaveSession) {
	$lengthBorrow = $end->diff($start);
	$maximum = $this->dateIntervalToSeconds($think->maximum);
	$minimum = $this->dateIntervalToSeconds($think->minimum);
	if (($this->dateIntervalToSeconds($lengthBorrow) > $maximum && $maximum > 0) || $this->dateIntervalToSeconds($lengthBorrow) < $minimum) {
	    $correctSaveSession->errorMessage = 'Doba výpůjčky je mimo povolený rozsah.';
	    throw new \Exception('Doba výpůjčky je mimo povolený rozsah.');
	}
    }

    private function dateIntervalToSeconds(\DateInterval $dateInterval) {
	return $dateInterval->format('%d') * 86400 + $dateInterval->format('%h') * 3600 + $dateInterval->format('%i') * 60;
    }

    private function isThinkFree(\DateTime $start, \DateTime $end, \Nette\Http\SessionSection $correctSaveSession) {
	$q1 = $this->getIsThinkFreeQuery('u.start < ?1', 'u.end > ?2');
	$q1->setParameters(array(1 => $start, 2 => $start));
	if ($q1->getResult()) {
	    $correctSaveSession->errorMessage = 'Čas je již obsazený.';
	    throw new \Exception('Čas je již obsazený.');
	}

	$q2 = $this->getIsThinkFreeQuery('u.start < ?1', 'u.end > ?2');
	$q2->setParameters(array(1 => $end, 2 => $end));
	if ($q2->getResult()) {
	    $correctSaveSession->errorMessage = 'Čas je již obsazený.';
	    throw new \Exception('Čas je již obsazený.');
	}

	$q3 = $this->getIsThinkFreeQuery('u.start > ?1', 'u.end < ?2');
	$q3->setParameters(array(1 => $start, 2 => $end));
	if ($q3->getResult()) {
	    $correctSaveSession->errorMessage = 'Čas je již obsazený.';
	    throw new \Exception('Čas je již obsazený.');
	}
    }

    private function getIsThinkFreeQuery($firstCondition, $secondCondition) {
	return $this->entityManager->createQueryBuilder()
			->select('u')
			->from($this->entityName, 'u')
			->where($firstCondition)->andWhere($secondCondition)
			->getQuery();
    }

    //update methods
    //delete methos
    public function deleteUse($useId) {
	$use = $this->thisRepository->find($useId);
	$this->entityManager->remove($use);
	$this->entityManager->flush();
    }

}
