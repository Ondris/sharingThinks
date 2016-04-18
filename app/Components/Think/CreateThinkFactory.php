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

    public function __construct(\SharingThinks\Model\Think\ThinksRepository $thinkRepository, \SharingThinks\Model\User\UsersRepository $usersRepository, \Nette\Security\User $user, \SharingThinks\Components\DefaultFormRenderer $defaultFormRenderer) {
	parent::__construct();
	$this->thinkRepository = $thinkRepository;
	$this->usersRepository = $usersRepository;
	$this->user = $user;
	$this->defaultFormRenderer = $defaultFormRenderer;
    }

    public function create($thinkId) {
	$form = new \Nette\Application\UI\Form();

	$form->addText('name', 'Název:')
		->setRequired('Vyplňte prosím jméno věci');

	$form->addTextArea('description', 'Popis:', 120, 10)
		->setRequired('Vyplňte prosím stručný popis o co se jedná.');

	$form['minimum'] = new \SharingThinks\Components\ExtensionForm\IntervalInput('Minimální čas výpujčky:');

	$form['maximum'] = new \SharingThinks\Components\ExtensionForm\IntervalInput('Maximální čas výpůjčky:');

	$form['pause'] = new \SharingThinks\Components\ExtensionForm\IntervalInput('Čas potřebný pro předání:');

	$form->addCheckbox('close', 'Vybrat uživatele, kteří si mohou věc půjčit:')
		->addCondition($form::EQUAL, TRUE)
		->toggle('usersIds');

	$users = $this->usersRepository->findUserPairs('id', 'name');
	$form->addMultiSelect('users', '', $users)
			->setHtmlId('usersIds')
			->getControlPrototype()->class[] = 'form-control';

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

    public function setValuesForEditForm(\Nette\Application\UI\Form $form, \SharingThinks\Model\Think\Thinks $think) {
	$form['name']->setDefaultValue($think->name);
	$form['description']->setDefaultValue($think->description);
	$form['minimum']->setDefaultValue($think->minimum);
	$form['maximum']->setDefaultValue($think->maximum);
	$form['pause']->setDefaultValue($think->pause);
	$form['close']->setDefaultValue($think->close);
	$form['users']->setDefaultValue($think->getUserIds());
    }

}
