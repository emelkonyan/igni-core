<?php

namespace Despark\Cms\Fields;

class Model extends Field
{
	public $parent;

    public function __construct($fieldName, array $options, $value = null)
    {

        $options['attributes']['placeholder'] = $options['label'];
        $options['attributes']['id'] = $fieldName;
        parent::__construct($fieldName, $options, $value);
    }

    protected function beforeToHtml()
    {
    	dd($this);
    }    
}
