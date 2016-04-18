<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class ProfilPresenter extends Nette\Application\UI\Presenter {

    /** @var \SharingThinks\Components\CreateAccount\CreateAccountFactory $createAccountFactory */
    private $createAccountFactory;

    /** @var \SharingThinks\Model\User\UsersRepository $usersRepository */
    private $usersRepository;

    /** @var \SharingThinks\Model\Uses\UsesRepository $usesRepository */
    private $usesRepository;

    /** @var \SharingThinks\Model\Think\ThinksRepository $thinksRepository */
    private $thinksRepository;

    /** @persistent */
    public $userId;

    public function injectServices(\SharingThinks\Components\CreateAccount\CreateAccountFactory $createAccountFactory, \SharingThinks\Model\User\UsersRepository $usersRepository, \SharingThinks\Model\Uses\UsesRepository $usesRepository, \SharingThinks\Model\Think\ThinksRepository $thinksRepository) {
	$this->createAccountFactory = $createAccountFactory;
	$this->usersRepository = $usersRepository;
	$this->usesRepository = $usesRepository;
	$this->thinksRepository = $thinksRepository;
    }

    public function startup() {
	parent::startup();
	$this->userId = $this->getUser()->getId();
    }

    public function renderDefault() {
	
    }

    protected function createComponentEditAccountForm() {
	$user = $this->usersRepository->getUser($this->userId);
	$form = $this->createAccountFactory->create();
	$form['passwordVerify']->addConditionOn($form['password'], Form::FILLED)
		->setRequired('Vyplňte prosím heslo ještě jednou pro kontrolu')
		->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);
	$form['submit']->onClick[] = $this->editAccount;

	$form['name']->setDefaultValue($user->name);
	$form['email']->setDefaultValue($user->email);
	return $form;
    }

    public function editAccount($button) {
	$values = $button->getForm()->getValues();
	$this->usersRepository->editUser($values, $this->userId);
	$this->flashMessage('Účet byl úspěšně upraven.', 'alert alert-success');
	$this->redirect('Account:default');
    }

    public function handleDeleteUser() {
	$uses = $this->usesRepository->findUsesForUsers($this->getUser()->getId());
	$thinks = $this->thinksRepository->findThinksForUser($this->getUser()->getId());
	if ($uses || $thinks) {
	    $this->flashMessage('Musíte smazat všechny svoje předměty a zrušit vypůjčky.', 'alert-warning');
	    $this->redirect('this');
	}
	$this->getUser()->logout();
	$this->usersRepository->deleteUser($this->getUser()->getId());
	$this->flashMessage('Byl jste ze systému vymazán.', 'alert alert-success');
	$this->redirect('Homepage:default');
    }

}
