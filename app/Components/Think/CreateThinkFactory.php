<?php

namespace SharingThinks\Components\Think;

class CreateThinkFactory extends \Nette\Application\UI\Control {
    /** @var \SharingThinks\Model\Think\ThinksRepository $thinkRepository */
    private $thinkRepository;
    
    /** @var \SharingThinks\Model\User\UsersRepository $usersRepository */
    private $usersRepository;
    
    /** @var \Nette\Security\User $user */
    private $user;
    
    /** @var \SharingThinks\Components\DefaultFormRenderer $defaultFormRendered */
    private $defaultFormRenderer;

    public function __construct(\SharingThinks\Model\Think\ThinksRepository $thinkRepository, 
				\SharingThinks\Model\User\UsersRepository $usersRepository,
				\Nette\Security\User $user,
				\SharingThinks\Components\DefaultFormRenderer $defaultFormRenderer) {
	$this->thinkRepository = $thinkRepository;
	$this->usersRepository = $usersRepository;
	$this->user = $user;
	$this->defaultFormRenderer = $defaultFormRenderer;
    }

    public function create($thinkId) {
	$form = new \Nette\Application\UI\Form();

	$form->addText('name', 'Název:')
		->setRequired('Vyplňte prosím jméno věci');

	$form->addTextArea('description', 'Popis:')
		->setRequired('Vyplňte prosím stručný popis o co se jedná.');

	$form->addText('minimum', 'Minimální čas výpujčky:');

	$form->addText('maximum', 'Maximální čas výpůjčky:');

	$form->addText('pause', 'Čas potřebný pro předání:');

	$form->addCheckbox('open', 'Vybrat uživatele, kteří si mohou věc půjčit:')
		->addCondition($form::EQUAL, TRUE)
		->toggle('usersIds');
	
	$users = $this->usersRepository->findUserPairs('id', 'name');
	$form->addMultiSelect('users', '', array($users))
		->setHtmlId('usersIds');
	
	$form->addHidden('thinkId', $thinkId);

	$form->addSubmit('ok', 'Přidat');
	
	$this->defaultFormRenderer->setFormRenderer($form);

	$form->onSuccess[] = array($this, 'saveThink');

	return $form;
    }

    public function saveThink($button) {
	$values = $button->getForm()->getValues();
	$owner = $this->usersRepository->getUser($this->user->getId());
	$users = $this->usersRepository->findUsers($values->users);
	$this->thinkRepository->saveThink($values, $owner, $users);
    }

    public function setValuesForEditForm(\Nette\Application\UI\Form $form, $think) {
	$form['name']->setDefaultValue($think->name);
	$form['description']->setDefaultValue($think->description);
	$form['minimum']->setDefaultValue($think->minimum);
	$form['maximum']->setDefaultValue($think->maximum);
	$form['pause']->setDefaultValue($think->pause);
	$form['open']->setdefaultValue($think->open);
    }
}
