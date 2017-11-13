<?php

    $options = $field->getOptions();
    $fieldname = $options['field'];
    $image = $field->getModel()->getThumbnailPath($fieldname);
    $filename = $field->getModel()->{$fieldname}; 

?>
{{-- Image --}}
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $options['label']) !!}
    <br>
    @if ($field->getValue())
        <img src="{{ $image['path']  }}{{ $filename }}">
    @endif
    {!! Form::file($elementName,  [
        'id' => $elementName,
        'class' => "form-control",
        'placeholder' => $options['label'],
    ] ) !!}

    <div>{{ $options['help'] or '' }}</div>

</div>
