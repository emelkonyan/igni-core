<?php

    $week = $field->getValue() ? floor($field->getValue() / 7) : 0;
    $day = $field->getValue() ? $field->getValue() - ($week * 7) : 0;
    $value = $field->getValue() ? $field->getValue() : 0;

?>
<div class="form-group {{ $errors->has($elementName) ? 'has-error' : '' }}">
   <b> {!! Form::label($elementName, $field->getLabel()) !!}</b>
    <br>
    <span class="daystoweek-{{ $elementName }}">
        {!! Form::label('', 'Week') !!}
        {!! Form::text("weeks-". $elementName, $week, $field->getAttributes()) !!}
        {!! Form::label('', 'Day') !!}
        {!! Form::text("days-". $elementName, $day, $field->getAttributes()) !!}
        {!! Form::hidden($elementName, $value, $field->getAttributes()) !!}
    </span>
    @if($field->getHelp())
        <p class="help-text">
            {{$field->getHelp()}}
        </p>
    @endif
    <div class="text-red">
        {{ join($errors->get($elementName), '<br />') }}
    </div>
</div>

@push('additionalScripts')
<script type="text/javascript">
    $(".daystoweek-{{ $elementName }} input").change(function() {
        var week = parseInt($("input[name=weeks-{{ $elementName }}]").val());
        var days = parseInt($("input[name=days-{{ $elementName }}]").val());

        $("input[name={{ $elementName }}]").val((week*7) + days);
    });
</script>
@endpush
