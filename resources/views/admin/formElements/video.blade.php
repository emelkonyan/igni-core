<?php
    $options = $field->getOptions();
    $fieldname = $options['field'];

    //dd($field->getModel()->getThumbnailPath($fieldname));
    $image = $field->getModel()->getThumbnailPath($fieldname);
    $filename = $field->getModel()->{$fieldname};    
?>
{{-- Image --}}
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $options['label']) !!}
    <br>

    <video width="320" height="240" controls="">
      <source type="video/mp4" src="{{$image['path']}}{{$filename}}">
    </video>
    {!! Form::file($elementName,  [
        'id' => $elementName,
        'class' => "form-control",
        'placeholder' => $options['label'],
    ] ) !!}

    <div>{{ $options['help'] or '' }}</div>

</div>
