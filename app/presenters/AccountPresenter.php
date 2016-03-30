<?php

namespace App\Presenters;

use Nette;


class AccountPresenter extends Nette\Application\UI\Presenter
{
    /** @var \SharingThinks\Model\Think\ThinksRepository $thinkRepository */
    private $thinksRepository;
    
    /** @var \SharingThinks\Model\User\UsersRepository $usersRepository */
    private $usersRepository;
    
    /** @var \SharingThinks\Model\Uses\UsesRepository $usesRepository */
    private $usesRepository;

    private $userId;

    public function injectServices(\SharingThinks\Model\Think\ThinksRepository $thinksRepository,
     \SharingThinks\Model\User\UsersRepository $usersRepository,
     \SharingThinks\Model\Uses\UsesRepository $usesRepository) {
	$this->thinksRepository = $thinksRepository;
	$this->usersRepository = $usersRepository;
	$this->usesRepository = $usesRepository;
    }
    
    public function beforeRender() {
	parent::beforeRender();
	$this->userId = $this->getUser()->getId();
    }
    
    public function renderDefault() {
	$uses = $this->usesRepository->getUses(1);
	dump($uses);
	$user = $this->usersRepository->getUser($this->userId);
	dump($user);
	$this->template->thinks = $this->thinksRepository->findThinksForUser($this->userId);	
	$this->template->deletedThinks = $this->thinksRepository->findDeletedThinksForUser($this->userId);
    }
    
    public function handleDelete($thinkId) {
	$this->thinksRepository->deleteThink($thinkId);
	$this->flashMessage('Věc byla přesunuta do smazaných');
	$this->redirect('this');
    }
    
    public function handleRefresh($thinkId) {
	$this->thinksRepository->refreshThink($thinkId);
	$this->flashMessage('Věc byla přesunuta obnovena');
	$this->redirect('this');
    }
}