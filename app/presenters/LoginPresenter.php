<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class LoginPresenter extends Nette\Application\UI\Presenter {

    /** @var \SharingThinks\Model\User\UsersRepository $usersRepository */
    private $usersRepository;

    /** @var \SharingThinks\Components\CreateAccount\CreateAccountFactory $createAccountFactory */
    private $createAccountFactory;

    /** @var \SharingThinks\Components\SignForm\SignFormFactory $signFormFactory */
    private $signFormFactory;

    public function injectServices(\SharingThinks\Model\User\UsersRepository $usersRepository, \SharingThinks\Components\CreateAccount\CreateAccountFactory $createAccountFactory, \SharingThinks\Components\SignForm\SignFormFactory $signFormFactory) {
	$this->usersRepository = $usersRepository;
	$this->createAccountFactory = $createAccountFactory;
	$this->signFormFactory = $signFormFactory;
    }
    
    public function isNameAvailable($item) {
	$isNameOccupied = $this->usersRepository->isNameOccupied($item->value);
	return $isNameOccupied ? FALSE : TRUE;
    }

    protected function createComponentCreateAccountForm() {
	$form = $this->createAccountFactory->create();
	$form['name']->addRule(array($this, 'isNameAvailable'), 'Jméno je již obsazené.');
	$form['password']->setRequired('Vyplňte prosím heslo');
	$form['passwordVerify']->setRequired('Vyplňte prosím heslo ještě jednou pro kontrolu')
		->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);
	$form['submit']->onClick[] = $this->createAccount;
	return $form;
    }

    public function createAccount($button) {
	$values = $button->getForm()->getValues();
	$this->usersRepository->createUser($values);
	$this->flashMessage('Účet se úspěšně vytvořil.');
	$this->redirect('Login:default');
    }

    protected function createComponentSignInForm() {
	$form = $this->signFormFactory->create();
	$form->onSuccess[] = function () {
	    $this->flashMessage('Přihlášení proběhlo úspěšně.');
	    $this->redirect('Homepage:default');
	};

	return $form;
    }

    public function handleLogout() {
	$this->getUser()->logout();
	$this->flashMessage('Odhlášení proběhlo úspěšně');
	$this->redirect('Homepage:default');
    }

}
