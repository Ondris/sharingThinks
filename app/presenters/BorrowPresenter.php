<?php

namespace App\Presenters;

use Nette;

class BorrowPresenter extends Nette\Application\UI\Presenter {

    /** @var \SharingThinks\Model\Think\ThinksRepository $thinkRepository */
    private $thinksRepository;

    /** @var \SharingThinks\Components\UseThink\UseThinkFactory $useThinkFactory */
    private $useThinkFactory;
    private $userId;

    public function injectServices(\SharingThinks\Model\Think\ThinksRepository $thinksRepository, \SharingThinks\Components\UseThink\UseThinkFactory $useThinkFactory) {
	$this->thinksRepository = $thinksRepository;
	$this->useThinkFactory = $useThinkFactory;
    }

    public function beforeRender() {
	parent::beforeRender();
	$this->userId = $this->getUser()->getId();
    }

    public function renderDefault($thinkId) {
	$this->template->think = $this->thinksRepository->getThink($thinkId);
    }

    protected function createComponentUseThinkForm() {
	$thinkId = $this->getParameter('thinkId');
	$form = $this->useThinkFactory->create($thinkId, $this->userId);
	$form->onSuccess[] = function (\Nette\Application\UI\Form $form) {
	    $correctSaveSession = $this->session->getSection('correctSave');
	    if ($correctSaveSession->errorMessage) {
		$this->flashMessage($correctSaveSession->errorMessage, 'alert-warning');
		unset($correctSaveSession->errorMessage);
	    } else {
		$this->flashMessage('Záznam byl úspěšně založen.', 'alert alert-success');
	    }
	    $this->redirect('Account:default');
	};
	return $form;
    }

}
