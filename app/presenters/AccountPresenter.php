<?php

namespace App\Presenters;

use Nette;


class AccountPresenter extends Nette\Application\UI\Presenter
{
    /** @var \SharingThinks\Model\Think\ThinksRepository $thinkRepository */
    private $thinksRepository;
    
    private $userId;

    public function injectServices(\SharingThinks\Model\Think\ThinksRepository $thinksRepository) {
	$this->thinksRepository = $thinksRepository;
    }
    
    public function beforeRender() {
	parent::beforeRender();
	$this->userId = $this->getUser()->getId();
    }
    
    public function renderDefault() {
	$this->template->thinks = $this->thinksRepository->findThinksForUser($this->userId);
	dump($this->template->thinks);
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