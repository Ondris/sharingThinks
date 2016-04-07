<?php

namespace App\Presenters;

use Nette;

class ThinkPresenter extends Nette\Application\UI\Presenter {

    /** @var \SharingThinks\Components\Think\CreateThinkFactory $createThinkFactory */
    private $createThinkFactory;

    /** @var \SharingThinks\Components\Think\ThinksRepository $thinksRepository */
    private $thinksRepository;

    public function injectServices(\SharingThinks\Components\Think\CreateThinkFactory $createThinkFactory, \SharingThinks\Model\Think\ThinksRepository $thinksRepository) {
	$this->createThinkFactory = $createThinkFactory;
	$this->thinksRepository = $thinksRepository;
    }

    public function renderNewThink($thinkId = 0) {
	
    }

    protected function createComponentThink() {
	$thinkId = $this->getParameter('thinkId');
	$form = $this->createThinkFactory->create($thinkId);
	
	if ($thinkId) {
	    $think = $this->thinksRepository->getThink($thinkId);
	    $this->createThinkFactory->setValuesForEditForm($form, $think);
	}

	$form->onSuccess[] = function (\Nette\Application\UI\Form $form) {
	    $this->flashMessage('Záznam byl úspěšně založen.');
	    $this->redirect('Account:default');
	};

	return $form;
    }

}
