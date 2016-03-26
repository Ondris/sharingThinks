<?php

namespace SharingThinks\Components\CreateAccount;

use Nette\Application\UI\Form;

class CreateAccountFactory {

    const EDIT_USER = 0;
    const NEW_USER = 1;

    /**
     * @return Form
     */
    public function create() {
	$form = new Form();

	$form->addText('name', 'Jméno:')
		->setRequired('Vyplňte prosím jméno.');

	$form->addPassword('password', 'Heslo');

	$form->addPassword('passwordVerify', 'Kontrola hesla:');

	$form->addText('email', 'E-mail:')
		->addCondition(Form::FILLED)
		->addRule(Form::EMAIL, 'E-mail nemá správný formát');

	$form->addSubmit('submit', 'Odeslat');

	return $form;
    }
}
