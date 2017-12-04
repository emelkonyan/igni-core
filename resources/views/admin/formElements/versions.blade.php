<?php 
    
    $children = $field->getOptions();
    $children = $children['children']; 

    $default = $field->parent;

    $ajax_link = \Despark\Helpers\Txt::site('/admin/dailyinfo/newversion');

?>


<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" id="tabs_navigation">
    <li role="presentation" class="active"><a href="#default" aria-controls="home" role="tab" data-toggle="tab">Default</a></li>
    @if($children)
    @foreach ($children as $child)
        <li role="presentation"><a href="#version_{{ $child->id() }}" aria-controls="profile" role="tab" data-toggle="tab">
        {{ $child->decode_spider()  }}
        </a></li>
    @endforeach
    @endif

    @if($default->id())
        <li role="presentation"><a data-toggle="modal" data-target="#myModal" id="add" href="#default" aria-controls="home" role="tab" data-toggle="tab">Add new version</a></li>
    @endif    
  </ul>

<!-- Tab panes -->
  <div class="tab-content" id="tab_content">
    <div role="tabpanel" class="tab-pane active" id="default">
         <div class="form-group {{ $errors->has($elementName) ? 'has-error' : '' }}">
            {!! Form::label("title") !!}
            {!! Form::text("title", $default->title, $field->getAttributes()) !!}
        </div>
    

        <div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
            {!! Form::label("Content") !!}
            {!! Form::textarea("content", $default->content, [
                'id' =>  "content_" . $default->id(),
                'class' => "form-control wysiwyg",
                'placeholder' => "",
            ] ) !!}
            <div class="text-red">
                {{ join($errors->get($fieldName), '<br />') }}
            </div>
        </div>       

    </div>
    @if($children)
    @foreach ($children as $child)
        <div role="tabpanel" class="tab-pane" id="version_{{ $child->id }}">
            {!! Form::hidden("title", $child->id, $field->getChildrenAttributes($loop->iteration, "id")) !!}
            <div class="form-group {{ $errors->has($elementName) ? 'has-error' : '' }}">
                {!! Form::label("title") !!} 
                {!! Form::text("title", $child->title, $field->getChildrenAttributes($loop->iteration, "title")) !!}
            </div>
        

            <div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
                {!! Form::label("Content") !!}
                {!! Form::textarea("content", $child->content, 
                    $field->getChildrenAttributes($loop->iteration, "content", array("wysiwyg"))) !!}
                <div class="text-red">
                    {{ join($errors->get($fieldName), '<br />') }}
                </div>
            </div>
        </div>
    @endforeach
    @endif
  </div>



</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add new version</h4>
        </div>
        <div class="modal-body">
                    <div class="control-group control-group-input">
                        <label class="control-label" for="partnership">I'm</label>                  
                            <div class="controls">
                                <select class="form-control" id="partnership">
                                    <option value="with_the_dad">With the dad</option>
                                    <option value="with_a_partner">With a partner</option>
                                    <option value="on_my_own">On my own</option>
                                </select>                  
                            </div>
                    </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="submit_version">Add</button>
      </div>
    </div>

  </div>
</div>



@push('additionalScripts')
<script src="{{ asset('/admin_assets/plugins/tinymce/tinymce.min.js') }}"></script>

<script type="text/javascript">
    function merge_options(obj1, obj2) {
        var obj3 = {};
        for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
        for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
        return obj3;
    }

    var defaultOptions = {
        selector: ".wysiwyg",
        skin: "despark-cms",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen responsivefilemanager template",
            "insertdatetime media table contextmenu paste imagetools jbimages"
        ],
        content_css: "/css/styles.css",
        menubar: false,
        toolbar: "code undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image jbimages | media | template",
        image_advtab: true,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        height: 500,
        imagetools_cors_hosts: ['{{env('APP_URL')}}'],
        external_filemanager_path: "/plugins/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            "filemanager": "{{ asset('/plugins/filemanager/plugin.min.js') }}"
        },
        media_live_embeds: true,
        style_formats: [
            {title: 'Paragraph (p)', block: 'p', classes: ''},
            {
                title: 'Containers', items: [
                {
                    title: 'First div',
                    block: 'div',
                    classes: 'col-lg-4 col-lg-offset-2',
                    wrapper: true,
                    merge_siblings: false
                },
                {
                    title: 'Second div',
                    block: 'div',
                    classes: 'col-lg-4',
                    wrapper: true,
                    merge_siblings: false
                },
            ]
            }
        ],
        end_container_on_empty_block: true
    };

    var additionalOptions = {!! json_encode($field->getOptions('additional_options')) !!};

    tinymce.init(merge_options(defaultOptions, additionalOptions));

    $("#submit_version").click(function() {
        $.get(
            "{{ $ajax_link }}",
            {
                type: "{{ $default->type }}." + $("#partnership").val(), 
                id: "{{ $default->id()  }}"
            },
            function(d) {
                if(d.success == 'false') {
                    alert(d.message);
                    return;
                }
                $("#tabs_navigation li").removeClass("active");
                $("#tab_content div").removeClass("active");

                $("#tabs_navigation li:last").before('<li role="presentation" class="active"><a href="#new_content" aria-controls="home" role="tab" data-toggle="tab">' + d.type + '</a></li>');                
                $("#tab_content").append(d.add);
                tinymce.init(merge_options(defaultOptions, additionalOptions));
                $("#myModal").modal("toggle");

            },
            "JSON"
        );
    });
</script>
@endpush