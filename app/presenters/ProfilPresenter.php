<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;


class ProfilPresenter extends Nette\Application\UI\Presenter
{
    /** @var \SharingThinks\Model\User\UsersRepository */
    private $usersRepository;
    
    private $createAccountFactory;

    /** @persistent */
    public $userId;
    
    public function injectServices(\SharingThinks\Model\User\UsersRepository $usersRepository,
     \SharingThinks\Components\CreateAccount\CreateAccountFactory $createAccountFactory) {
	$this->usersRepository = $usersRepository;
	$this->createAccountFactory = $createAccountFactory;
    }
    
    public function startup() {
	parent::startup();
	$this->userId = $this->getUser()->getId();
    }
    
    public function renderDefault() {
	
    }
    
    protected function createComponentEditAccountForm() {
	$user = $this->usersRepository->getUser($this->userId);
	$form =  $this->createAccountFactory->create();
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
	$this->flashMessage('Účet byl úspěšně upraven.');
	$this->redirect('Account:default');
    }
    
    public function handleDeleteUser() {
	$this->getUser()->logout();
	$this->usersRepository->deleteUser($this->getUser()->getId());
	$this->flashMessage('Byl jste ze systému vymazán.');
	$this->redirect('Homepage:default');	
    }
}