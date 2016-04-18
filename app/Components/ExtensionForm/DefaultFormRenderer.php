<?php

namespace SharingThinks\Components;

use Nette\Application\UI\Form;

class DefaultFormRenderer extends \Nette\Object{

    public function setFormRenderer(\Nette\Application\UI\Form &$form) {
	$renderer = $form->getRenderer();
	$renderer->wrappers['controls']['container'] = 'div class=form-horizontal';
	$renderer->wrappers['pair']['container'] = 'div class=form-group';
	$renderer->wrappers['pair']['.error'] = 'error';
	$renderer->wrappers['label']['container'] = 'div class="control-label col-sm-2"';
	$renderer->wrappers['control']['container'] = 'div class=col-sm-10';
	$renderer->wrappers['control']['description'] = 'span class=help-inline';
	$renderer->wrappers['control']['errorcontainer'] = 'span class=help-inline';
	
	//$form->getElementPrototype()->class('form-horizontal');
	foreach ($form->getControls() as $control) {
		$type = $control->getControlPrototype()->type;
		if (in_array($type, ['checkbox', 'radio'], TRUE)) {
			$control->getLabelPrototype()->addClass($control->getControlPrototype()->type);
			$control->getSeparatorPrototype()->setName(NULL);
		} elseif ($type === 'submit') {
		    $control->getControlPrototype()->addClass('btn btn-primary');
		} elseif (in_array($type, ['text', 'password'], TRUE)) {
		    $control->getControlPrototype()->addClass('form-control');
		} elseif ($type === 'multiselect') {
		    $control->getControlPrototype()->addClass('form-control');
		}
	}
    }
}
