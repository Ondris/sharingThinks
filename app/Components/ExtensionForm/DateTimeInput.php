<?php

namespace SharingThinks\Components;

use Nette,
    Nette\Forms\Form,
    Nette\Utils\Html;

class DateInput extends \Nette\Forms\Controls\BaseControl {

    private $day, $month, $year, $hour, $minute;

    public function __construct($label = NULL) {
	parent::__construct($label);
	$this->addRule(__CLASS__ . '::validateDateTime', 'Date is invalid.');
    }

    public function setValue($value) {
	if ($value) {
	    $date = Nette\Utils\DateTime::from($value);
	    $this->day = $date->format('j');
	    $this->month = $date->format('n');
	    $this->year = $date->format('Y');
	    $this->hour = $date->format('G');
	    $this->minute = $date->format('i');
	} else {
	    $this->day = $this->month = $this->year = $this->hour = $this->minute = NULL;
	}
    }

    /**
     * @return DateTime|NULL
     */
    public function getValue() {
	return self::validateDateTime($this) ? \DateTime::createFromFormat('j.n.Y G:i:s', $this->day.'.'.$this->month.'.'.$this->year.''
		. ' '. $this->hour.':'.$this->minute.':00') : NULL;
    }

    public function loadHttpData() {
	$this->day = $this->getHttpData(Form::DATA_LINE, '[day]');
	$this->month = $this->getHttpData(Form::DATA_LINE, '[month]');
	$this->year = $this->getHttpData(Form::DATA_LINE, '[year]');
	$this->hour = $this->getHttpData(Form::DATA_LINE, '[hour]');
	$this->minute = $this->getHttpData(Form::DATA_LINE, '[minute]');
    }

    /**
     * Generates control's HTML element.
     */
    public function getControl() {
	$name = $this->getHtmlName();
	return Html::el()
			->add(
				Html::el('input size="2" maxlength="2"')->name($name . '[day]')->id($this->getHtmlId())->value($this->day)
				)
			->add(
				Nette\Forms\Helpers::createSelectBox(
					array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12), array('selected?' => $this->month)
				)->name($name . '[month]')
				)
			->add(
				Html::el('input size="4" maxlength="4"')->name($name . '[year]')->value($this->year)
				)
			->add(
				Html::el('input size="2" maxlength="2"')->name($name. '[hour]')->value($this->hour)
				)
			->add(
				Html::el('input size="2" maxlength="2"')->name($name. '[minute]')->value($this->minute)
				);
    }

    /**
     * @return bool
     */
    public static function validateDateTime(Nette\Forms\IControl $control) {
	return (\DateTime::createFromFormat('j.n.Y G:i:s', $control->day.'.'.$control->month.'.'.$control->year.''
		. ' '. $control->hour.':'.$control->minute.':00') !== false);
    }

}
