<?php

namespace SharingThinks\Components\CreateAccount;

use Nette\Application\UI\Form;

class CreateAccountFactory {
    
    private $defaultFormRenderer;
    
    public function __construct(\SharingThinks\Components\DefaultFormRenderer $defaultFormRenderer) {
	$this->defaultFormRenderer = $defaultFormRenderer;
    }

    /**
     * @return Form
     */
    public function create() {
	$form = new \Nette\Application\UI\Form();

	$form->addText('name', 'Jméno:')
		->setRequired('Vyplňte prosím jméno.');

	$form->addPassword('password', 'Heslo');

	$form->addPassword('passwordVerify', 'Kontrola hesla:');

	$form->addText('email', 'E-mail:')
		->addCondition(Form::FILLED)
		->addRule(Form::EMAIL, 'E-mail nemá správný formát');

	$form->addSubmit('submit', 'Odeslat');
	
	$this->defaultFormRenderer->setFormRenderer($form);

	return $form;
    }
}
