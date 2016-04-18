<?php

namespace SharingThinks\Components\ExtensionForm;

use Nette,
    Nette\Forms\Form,
    Nette\Utils\Html;

class IntervalInput extends \Nette\Forms\Controls\BaseControl {

    private $interval;

    public function __construct($label = NULL) {
	parent::__construct($label);
	$this->addRule(__CLASS__ . '::validateDateInterval', '	Časový interval má nesprávný formát.');
    }

    public function setValue($value) {
	if ($value) {
	    $this->interval = $value;
	} else {
	    $this->interval = NULL;
	}
    }

    public function getValue() {
	return self::validateDateInterval($this) ? $this->interval : NULL;
    }

    public function loadHttpData() {
	$day = (int) $this->getHttpData(Form::DATA_LINE, '[day]');
	$hour = (int) $this->getHttpData(Form::DATA_LINE, '[hour]');
	$minute = (int) $this->getHttpData(Form::DATA_LINE, '[minute]');
	$this->interval = new \DateInterval('P'.$day.'DT'.$hour.'H'.$minute.'M');
    }

    /**
     * Generates control's HTML element.
     */
    public function getControl() {
	$name = $this->getHtmlName();
	$elDay = Html::el('input size="2" maxlength="2"')
		->name($name . '[day]')->id($this->getHtmlId());
	$elHour = Html::el('input size="2" maxlength="2"')
		->name($name. '[hour]');
	$elMinute = Html::el('input size="2" maxlength="2"')
		->name($name. '[minute]');
	if ($this->interval) {
	    $elDay->value($this->interval->format('%d'));
	    $elHour->value($this->interval->format('%h'));
	    $elMinute->value($this->interval->format('%i'));
	}
	return Html::el()
		->add($elDay)->add(Html::el('span')->setText(' dny '))
		->add($elHour)->add(Html::el('span')->setText(' hodiny '))
		->add($elMinute)->add(Html::el('span')->setText(' minuty '));
    }

    /**
     * @return bool
     */
    public static function validateDateInterval(Nette\Forms\IControl $control) {
	return $control->interval instanceof \DateInterval ? TRUE : FALSE;
    }

}
