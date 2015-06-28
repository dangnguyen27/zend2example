<?php

namespace Blog\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class PostForm extends Form implements InputFilterAwareInterface
{
	private $inputFilter;

	public function __construct()
	{
		parent::__construct('post-form');

		$this->setAttribute('method','post');

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
		));

		$this->add(array(
			'name' => 'title',
			'type' => 'text',
			'options' =>array(
				'label' => 'Tieu de',
			),
			'attributes' => array(
				'class' => 'form-control'
			)
		));

		$this->add(array(
			'name' => 'content',
			'attributes' => array(
				'class' => 'form-control',
				'type' => 'texarea'
			)
		));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Save',
				'class' => 'btn btn-primary'
			)
		));
	}

	//filter
	public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used"); 
    }

    public function getInputFilter()
    {
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    		$factory = new InputFactory();

    		$inputFilter->add($factory->createInput(array(
    			'name' => 'id',
    			'required' => true,
    			'filters' => array(
    				array('name' => 'Int')
    			)
    		)));

    		$inputFilter->add($factory->createInput(array(
    			'name' => 'title',
    			'required' => true,
    			'filters' => array(
    				array('name'=>'StripTags'),
    				array('name'=>'StringTrim')
    			), 
    			'validators' => array(
    				array(
    					'name' => 'StringLength',
    					'options' => array(
    						'encoding' => 'UTF-8',
    						'min' => 1,
    						'max' => 255
    					)
    				)
    			)
    		)));

    		$inputFilter->add($factory->createInput(array(
                'name'     => 'content',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
    	}
    	return $this->inputFilter;
    }
}