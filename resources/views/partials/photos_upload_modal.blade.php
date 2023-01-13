@if (isset($photo_upload_config))

@if($photo_upload_config['load_resource'] === true)

    <!-- Jquery file upload -->            
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('/assets/theme/js/plugins/file-upload/css/jquery.fileupload.css') }}">    

	<script src="https://code.jquery.com/jquery-migrate-3.3.0.js"></script>


    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->    
    <script src="{{ asset('/assets/theme/js/plugins/file-upload/js/vendor/jquery.ui.widget.js') }}"></script>                    

    <!-- The Iframe Transport is required for browsers without support for XHR file uploads --> 
    <script src="{{ asset('/assets/theme/js/plugins/file-upload/js/jquery.iframe-transport.js') }}"></script>                        

    <!-- The basic File Upload plugin -->  
    <script src="{{ asset('/assets/theme/js/plugins/file-upload/js/jquery.fileupload.js') }}"></script>
	
<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
    'use strict';
    var url = '/item-photo/ajax-upload';
    $('#{{ $photo_upload_config["modal_name"] }} #fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<span class="photo"/>').html('<img src="'+ file.name +'" alt="" />').appendTo('#{{ $photo_upload_config["modal_name"] }} #files');
            });

            $.each(data.result.errors, function (index, error) {
                $('<p/>').text(error.error_message).appendTo('#{{ $photo_upload_config["modal_name"] }} #errors');
                $('{{ $photo_upload_config["modal_name"] }} .photo-alert').show();
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#{{ $photo_upload_config["modal_name"] }} #progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
        
    $('#{{ $photo_upload_config["modal_name"] }} #fileupload').bind('fileuploadadd', function (e, data) {                  
            data.formData = {item_id: '{{ $photo_upload_config["item_id"] }}', folder: '{{ $photo_upload_config["folder"] }}', watermark: $('#watermark:checked').size() > 0 ? 1 : 0 };
    }); 

	$(function()
	{		
		$(".remove_photo").click(function(e) {	   
			e.preventDefault(); 
			
			var photo_id = $(this).attr('data-photo-id');
			var folder = $(this).attr('data-folder');
			
			$.post('/item-photo/remove', {
				id: photo_id,
				folder: folder            
			})
			.done(function( data ) { 
				$('#photo_id_'+photo_id).remove();
			});        
		});
	});	
        
});
</script>

 <!-- Photo preview Modal -->
 <div class="photo-preview-modal" id="{{ $photo_upload_config["modal_name"] }}">
                  
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Odaberi slike</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload" type="file" name="files[]" multiple accept="image/*" />
            </span> 
                @if(isset($photo_upload_config['info']))
                <div class="photo-notice">{{ $photo_upload_config['info'] }}</div>                                 
                @endif            
            <br>
            <!-- The global progress bar -->
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>

            <!-- The container for the errors msg -->
            <div style="display: none" class="photo-alert alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <strong>Upozorenje!</strong> Dogodile su se sljedeće greške:
                <div id="errors" class="errors"></div>                          
            </div>                        
            <!-- The container for the uploaded files -->
            <div id="files" class="files clearfix">
                @if(isset($photo_upload_config['item_photos']))
                @foreach($photo_upload_config['item_photos'] as $id => $photo_item)
                <span class="photo" style="position: relative" id="photo_id_{{ $photo_item->id }}"><img src="/assets/photos/gallery/small/{{ $photo_item->name }}" alt="" /> <span data-folder="{{ $photo_upload_config['folder'] }}" data-photo-id="{{ $photo_item->id }}" style="color:#fff; background: red; padding: 5px; position: absolute; left: 0" class="remove_photo glyphicon glyphicon-remove">X</span></span>
                @endforeach
                @endif
            </div>
 </div><!-- /.modal -->  
@endif
@endif