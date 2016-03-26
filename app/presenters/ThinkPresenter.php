<?php

namespace App\Presenters;

use Nette;


class ThinkPresenter extends Nette\Application\UI\Presenter
{
    /** @var \SharingThinks\Components\Think\IThinkFactory $thinkFactory */ 
    private $thinkFactory;
    
    public function injectServices(\SharingThinks\Components\Think\IThinkFactory $thinkFactory) {
	$this->thinkFactory = $thinkFactory;
    }
    
    public function renderNewThink() {
    }
    
    protected function createComponentNewThink() {
	return $this->thinkFactory->create();
    }
}