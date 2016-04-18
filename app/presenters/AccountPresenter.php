<?php

namespace App\Presenters;

use Nette;

class AccountPresenter extends Nette\Application\UI\Presenter {

    /** @var \SharingThinks\Model\Think\ThinksRepository $thinkRepository */
    private $thinksRepository;

    /** @var \SharingThinks\Model\User\UsersRepository $usersRepository */
    private $usersRepository;

    /** @var \SharingThinks\Model\Uses\UsesRepository $usesRepository */
    private $usesRepository;
    private $userId;

    public function injectServices(\SharingThinks\Model\Think\ThinksRepository $thinksRepository, \SharingThinks\Model\User\UsersRepository $usersRepository, \SharingThinks\Model\Uses\UsesRepository $usesRepository) {
	$this->thinksRepository = $thinksRepository;
	$this->usersRepository = $usersRepository;
	$this->usesRepository = $usesRepository;
    }

    public function beforeRender() {
	parent::beforeRender();
	$this->userId = $this->getUser()->getId();
    }

    public function renderDefault() {
	$this->template->thinks = $this->thinksRepository->findThinksForUser($this->userId);
	$this->template->thinksToHire = $this->thinksRepository->findThinksToHire($this->userId);
	$this->template->deletedThinks = $this->thinksRepository->findDeletedThinksForUser($this->userId);
	$this->template->actualUser = $this->usersRepository->getUser($this->userId);
    }

    public function handleDelete($thinkId) {
	$this->thinksRepository->deleteThink($thinkId);
	$this->flashMessage('Věc byla přesunuta do smazaných', 'alert alert-success');
	$this->redirect('this');
    }

    public function handleRefresh($thinkId) {
	$this->thinksRepository->refreshThink($thinkId);
	$this->flashMessage('Věc byla přesunuta obnovena', 'alert alert-success');
	$this->redirect('this');
    }

    public function handleCompleteDelete($thinkId) {
	$this->thinksRepository->completeDeleteThink($thinkId);
	$this->flashMessage('Věc byla kompletně smazana ze systému.', 'alert alert-success');
	$this->redirect('this');
    }

    public function handleDeleteUse($useId) {
	$this->usesRepository->deleteUse($useId);
	$this->flashMessage('Rezervace byla zrušena.', 'alert alert-success');
	$this->redirect('this');
    }

}
