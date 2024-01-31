<?php

/*
 * This file is part of the Osynapsy package.
 *
 * (c) Pietro Celeste <p.celeste@osynapsy.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osynapsy\Bcl4\DatePicker;

use Osynapsy\Html\Component\AbstractComponent;
use Osynapsy\Bcl4\TextBox;

class DatePicker extends AbstractComponent
{
    private $datePickerId;
    private $dateComponent;
    protected $defaultValue;

    public function __construct($id, $format = 'DD/MM/YYYY')
    {
        parent::__construct('div', $id.'_datepicker');
        $this->datePickerId = $id;
        $this->requireCss('lib/bootstrap-datetimejs-4.17.37/bootstrap-datetimejs.css');
        $this->requireJs('lib/bootstrap-datetimejs-4.17.37/bootstrap-datetimejs.js');
        $this->requireJs('lib/momentjs-2.17.1/moment.js');
        $this->requireJs('bcl4/datepicker/script.js');
        $this->addClass('input-group');
        $this->dateComponent = $this->add($this->textBoxFactory($id));
        $this->add($this->iconFactory());
        $this->setFormat($format);
    }

    protected function textBoxFactory($id)
    {
        $TextBox = new TextBox($id);
        $TextBox->addClass('date date-picker');
        $TextBox->formatValueFunction = function($value)
        {
            if (empty($value)) {
                return $value;
            }
            $dateTimeParts = explode(' ', $value);
            $dateParts = explode('-', $dateTimeParts[0]);
            if (count($dateParts) >= 3 && strlen($dateParts[0]) == 4) {
                return $dateParts[2].'/'.$dateParts[1].'/'.$dateParts[0].(empty($dateTimeParts[1]) ? '' : " {$dateTimeParts[1]}");
            }
        };
        return $TextBox;
    }

    protected function iconFactory()
    {
        return '<div class="input-group-append"><span class="input-group-text"><i class="glyphicon glyphicon-calendar"></i></span></div>';
    }

    public function preBuild()
    {
        if (!empty($this->defaultValue) && empty($this->getTextBox()->getValue())) {
            $this->getTextBox()->setValue($this->defaultValue);
        }
    }

    public function setAction($action, $parameters = null, $confirmMessage = null, $class = 'change-execute datepicker-change')
    {
        $this->dateComponent->setAction($action, $parameters, $class, $confirmMessage);
    }

    /**
     *
     * @param type $min accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     * @param type $max accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateLimit($min, $max)
    {
        $this->setDateMin($min);
        $this->setDateMax($max);
    }

    /**
     *
     * @param type $date accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateMax($date)
    {
        $this->dateComponent->attribute('data-max', $date);
    }

    /**
     *
     * @param type $date accepted mixed input (ISO DATE : YYYY-MM-DD or name of other component date #name)
     */
    public function setDateMin($date)
    {
        $this->dateComponent->attribute('data-min', $date);
    }

    public function setFormat($format)
    {
        $this->dateComponent->attribute('data-format', $format);
    }

    public function setDefaultDate($date = null)
    {
        $this->defaultValue = empty($date) ? date('d/m/Y') : $date;
    }

    public function setDisabled($condition)
    {
        $this->dateComponent->setDisabled($condition);
    }

    public function onChange($code)
    {
        $this->dateComponent->addClass('datepicker-change')->attribute('onchange', $code);
    }

    public function getTextBox()
    {
        return $this->dateComponent;
    }

    public function setPlaceholder($placeholder)
    {
        $this->getTextBox()->setPlaceholder($placeholder);
        return $this;
    }

    public function setSmallSize()
    {
        $this->getTextBox()->setSmallSize();
        return $this;
    }
}
