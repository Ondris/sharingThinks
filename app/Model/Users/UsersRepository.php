<?php

namespace SharingThinks\Model\User;

use \Nette,
    \Nette\Security\Passwords;


class UsersRepository implements \Nette\Security\IAuthenticator
{
    private $thisRepository;
    
    /** @var \Kdyby\Doctrine\EntityManager */
    private $entityManager;

    public function __construct(\Kdyby\Doctrine\EntityManager $entityManager) {
	$this->entityManager = $entityManager;
	$this->thisRepository = $this->entityManager->getRepository(\SharingThinks\Model\User\Users::getClassName());
    }
    
    public function getUser($userId) {
	return $this->thisRepository->find($userId);
    }


    public function createUser($values) {
	$user = new \SharingThinks\Model\User\Users();
	$user->setName($values->name);
	$user->setPassword(Passwords::hash($values->password));
	$user->setEmail($values->email);
	$user->setCreationDate(new \Nette\Utils\DateTime('now'));
	$user->setRole('user');
	$user->setVisible(1);
	$this->entityManager->persist($user);
	$this->entityManager->flush();
    }
    
    public function editUser($values, $userId) {
	$user = $this->thisRepository->find($userId);
	$user->setName($values->name);
	if ($values->password) {
	    $user->setPassword(Passwords::hash($values->password));
	}
	$user->setEmail($values->email);
	$this->entityManager->persist($user);
	$this->entityManager->flush();
    }
    
    public function deleteUser($userId) {
	$user = $this->thisRepository->find($userId);
	$this->entityManager->remove($user);
	$this->entityManager->flush();
    }
    
    public function findUserPairs($key, $value) {
	$query = $this->entityManager->createQueryBuilder()
		->select('u')
		->from(\SharingThinks\Model\User\Users::getClassName(), 'u')
		->getQuery();

	$res = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
	$arr = array();
	foreach ($res as $row) {
		$arr[$row[$key]] = $row[$value];
	}
	return $arr;
    }
    
    public function findUsers($userIds) {
	$query = $this->entityManager->createQueryBuilder()
		->select('u')
		->from(\SharingThinks\Model\User\Users::getClassName(), 'u')
		->andWhere('u.id IN (:ids)')
		->getQuery();
	$query->setParameter('ids', $userIds);
	return $query->getResult();
    }
    
    	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;
		$row = $this->entityManager->getRepository(\SharingThinks\Model\User\Users::getClassName())
			->findOneBy(array('name' => $username));
		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!Passwords::verify($password, $row->password)) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		} elseif (Passwords::needsRehash($row->password)) {
			$row->update([
				$row->password => Passwords::hash($password),
			]);
		}
		$arr['name'] = $row->name;
		$arr['id'] = $row->id;
		$arr['role'] = 'user';
		return new Nette\Security\Identity($arr['id'], $arr['role'], $arr);
	}
}