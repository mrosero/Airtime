<?php

class Application_Form_AddShowWhen extends Zend_Form_SubForm
{

    public function init()
    {
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/add-show-when.phtml'))
        ));
        
        // Add start date element
        $startDate = new Zend_Form_Element_Text('add_show_start_date');
        $startDate->class = 'input_text';
        $startDate->setRequired(true)
                    ->setLabel('Date/Time Start:')
                    ->setValue(date("Y-m-d"))
                    ->setFilters(array('StringTrim'))
                    ->setValidators(array(
                        'NotEmpty',
                        array('date', false, array('YYYY-MM-DD'))))
                    ->setDecorators(array('ViewHelper'));
        $startDate->setAttrib('alt', 'date');
        $this->addElement($startDate);
        
        // Add start time element
        $startTime = new Zend_Form_Element_Text('add_show_start_time');
        $startTime->class = 'input_text';
        $startTime->setRequired(true)
                    ->setValue('00:00')
                    ->setFilters(array('StringTrim'))
                    ->setValidators(array(
                        'NotEmpty',
                        array('date', false, array('HH:mm')),
                        array('regex', false, array('/^[0-2]?[0-9]:[0-5][0-9]$/', 'messages' => 'Time format should be HH:mm'))
                        ))->setDecorators(array('ViewHelper'));
        $startTime->setAttrib('alt', 'time');
        $this->addElement($startTime);

        // Add end date element
        $endDate = new Zend_Form_Element_Text('add_show_end_date_no_repeat');
        $endDate->class = 'input_text';
        $endDate->setRequired(true)
                    ->setLabel('Date/Time End:')
                    ->setValue(date("Y-m-d"))
                    ->setFilters(array('StringTrim'))
                    ->setValidators(array(
                        'NotEmpty',
                        array('date', false, array('YYYY-MM-DD'))))
                    ->setDecorators(array('ViewHelper'));
        $endDate->setAttrib('alt', 'date');
        $this->addElement($endDate);
        
        // Add end time element
        $endTime = new Zend_Form_Element_Text('add_show_end_time');
        $endTime->class = 'input_text';
        $endTime->setRequired(true)
                    ->setValue('01:00')
                    ->setFilters(array('StringTrim'))
                    ->setValidators(array(
                        'NotEmpty',
                        array('date', false, array('HH:mm')),
                        array('regex', false, array('/^[0-2]?[0-9]:[0-5][0-9]$/', 'messages' => 'Time format should be HH:mm'))))
                    ->setDecorators(array('ViewHelper'));
        $endTime->setAttrib('alt', 'time');
        $this->addElement($endTime);
        
        // Add duration element
        $this->addElement('text', 'add_show_duration', array(
        	'label'		 => 'Duration:',
            'class'      => 'input_text',
        	'value'		 => '01h 00m',
        	'readonly'	 => true,
            'decorators'  => array('ViewHelper')
        ));

		// Add repeats element
		$this->addElement('checkbox', 'add_show_repeats', array(
            'label'      => 'Repeats?',
            'required'   => false,
		    'decorators'  => array('ViewHelper')
		));

    }

    public function checkReliantFields($formData, $validateStartDate) {
        $valid = true;
        
        $start_time = $formData['add_show_start_date']." ".$formData['add_show_start_time'];
        
        //DateTime stores $start_time in the current timezone
        $nowDateTime = new DateTime();
        $showStartDateTime = new DateTime($start_time);

		if ($validateStartDate){
	        if($showStartDateTime->getTimestamp() < $nowDateTime->getTimestamp()) {
	            $this->getElement('add_show_start_time')->setErrors(array('Cannot create show in the past'));
	            $valid = false;
	        }
	    }
	    
        if( $formData["add_show_duration"] == "00h 00m" ) {
            $this->getElement('add_show_duration')->setErrors(array('Cannot have duration 00h 00m'));
            $valid = false;
        }elseif(strpos($formData["add_show_duration"], 'h') !== false && intval(substr($formData["add_show_duration"], 0, strpos($formData["add_show_duration"], 'h'))) > 24) {
            $this->getElement('add_show_duration')->setErrors(array('Cannot have duration greater than 24h'));
            $valid = false;
        }elseif( strstr($formData["add_show_duration"], '-') ){
        	$this->getElement('add_show_duration')->setErrors(array('Cannot have duration < 0m'));
            $valid = false;
        }

        return $valid;
    }
    
    public function disable(){
        $elements = $this->getElements();
        foreach ($elements as $element)
        {
            if ($element->getType() != 'Zend_Form_Element_Hidden')
            {
                $element->setAttrib('disabled','disabled');
            }
        }
    }
    
    public function disableRepeatCheckbox(){
        $element = $this->getElement('add_show_repeats');
        if ($element->getType() != 'Zend_Form_Element_Hidden')
        {
            $element->setAttrib('disabled','disabled');
        }
    }
    
    public function disableStartDateAndTime(){
        $elements = array($this->getElement('add_show_start_date'), $this->getElement('add_show_start_time'));
        foreach ($elements as $element)
        {
            if ($element->getType() != 'Zend_Form_Element_Hidden')
            {
                $element->setAttrib('disabled','disabled');
            }
        }
    }
}

