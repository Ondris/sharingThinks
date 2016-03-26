<?php
namespace SharingThinks\Components\Think;

class Think extends \Nette\Application\UI\Control
{
    
    private $thingRepository;

    public function __construct(\SharingThinks\Model\Think\ThinksRepository $thingRepository){
        $this->thingRepository = $thingRepository;
    }
    
    public function renderNewThink() {
	$template = $this->template;
	$template->setFile(__DIR__.'/newThink.latte');
	$template->render();
    }
    
    protected function createComponentThinkForm() {
	$form = new \Nette\Application\UI\Form();
	
	$form->addText('name', 'Název:');
	
	$form->addTextArea('description', 'Popis:');
	
	$form->addText('minimum', 'Minimální čas výpujčky:');
	
	$form->addText('maximum', 'Maximální čas výpůjčky:');
	
	$form->addText('pause', 'Čas potřebný pro předání:');
	
	$form->addSubmit('ok', 'Přidat');
	
	$form->onSuccess[] = $this->saveThink;
	
	return $form;
    }
    
    public function saveThink($button) {
	$values = $button->getForm()->getValues();
	$this->thingRepository->saveThink($values, $this->presenter->getUser());
	$this->presenter->flashMessage('Záznam byl úspěšně založen.');
	$this->presenter->redirect('Account:default');
    }
}

