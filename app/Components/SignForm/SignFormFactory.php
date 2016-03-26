<?php
namespace SharingThinks\Components\SignForm;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;

class SignFormFactory extends Nette\Object
{
	/** @var User */
	private $user;
	
	public function __construct(User $user) {
		$this->user = $user;
	}
	/**
	 * @return Form
	 */
	public function create() {
		$form = new \Nette\Application\UI\Form();
		$form->addText('username', 'Username:')
			->setRequired('Please enter your username.');
		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');
		//$form->addCheckbox('remember', 'Keep me signed in');
		$form->addSubmit('send', 'Sign in');
		$form->onSuccess[] = [$this, 'formSucceeded'];
		return $form;
	}
	public function formSucceeded(Form $form, $values)
	{
		try {
			$this->user->login($values->username, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('The username or password you entered is incorrect.');
		}
	}
}