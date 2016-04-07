<?php

namespace SharingThinks\Components\UseThink;

class UseThinkFactory extends \Nette\Application\UI\Control {
    /** @var \SharingThinks\Model\Think\ThinksRepository $thinkRepository */
    private $thinksRepository;
    
    /** @var \SharingThinks\Components\DefaultFormRenderer $defaultFormRenderer */
    private $defaultFormRenderer;
    
    /** @var \SharingThinks\Model\Uses\UsesRepository */
    private $usesRepository;
    
    /** @var \SharingThinks\Model\User\UsersRepository $usersRepository */
    private $usersRepository;

    public function __construct(\SharingThinks\Model\Think\ThinksRepository $thinksRepository,
				\SharingThinks\Components\DefaultFormRenderer $defaultFormRenderer,
				\SharingThinks\Model\Uses\UsesRepository $usesRepository,
				\SharingThinks\Model\User\UsersRepository $usersRepository) {
	$this->thinksRepository = $thinksRepository;
	$this->defaultFormRenderer = $defaultFormRenderer;
	$this->usesRepository = $usesRepository;
	$this->usersRepository = $usersRepository;
    }

    public function create($thinkId, $userId) {
	$form = new \Nette\Application\UI\Form();

	//$form->addText('start', 'Čas vypůjčení:')
	//	->setRequired('Musíte vyplnit čas vypůjčení.');
	
	$form['start'] = new \SharingThinks\Components\DateInput('Čas vypůjčení:');
	
	$form['end'] = new \SharingThinks\Components\DateInput('Čas vrácení:');

	//$form->addText('end', 'Čas vrácení:')
	//	->setRequired('Musíte vyplnit čas vrácení.');
	
	$form->addHidden('thinkId', $thinkId);
	$form->addHidden('userId', $userId);

	$form->addSubmit('ok', 'Přidat');
	
	$this->defaultFormRenderer->setFormRenderer($form);

	$form->onSuccess[] = array($this, 'saveUse');

	return $form;
    }

    public function saveUse($button) {
	$values = $button->getForm()->getValues();
	$user = $this->usersRepository->getUser($values->userId);
	$think = $this->thinksRepository->getThink($values->thinkId);
	$isSave = $this->usesRepository->saveUse($user, $think, $values);
    }
}

