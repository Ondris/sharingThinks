<?php
namespace SharingThinks\Components\SignForm;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;

class SignFormFactory extends Nette\Object
{
	/** @var User */
	private $user;
	
	/** @var \SharingThinks\Components\DefaultFormRenderer $defaultFormRendered */
	private $defaultFormRenderer;

	public function __construct(User $user,
				    \SharingThinks\Components\DefaultFormRenderer $defaultFormRenderer) {
		$this->user = $user;
		$this->defaultFormRenderer = $defaultFormRenderer;
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
		$form->addSubmit('send', 'Sign in');
		$form->onSuccess[] = [$this, 'formSucceeded'];
		$this->defaultFormRenderer->setFormRenderer($form);
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