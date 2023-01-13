@if (isset($file_upload_config))

@if($file_upload_config['load_resource'] === true)

    @section('css')            
    <!-- Jquery file upload -->            
    {{ HTML::style('assets/plugins/file-upload/css/jquery.fileupload.css') }}                            
    @endsection

    @section('script')

    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->            
    {{ HTML::script('assets/plugins/file-upload/js/vendor/jquery.ui.widget.js') }}

    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->            
    {{ HTML::script('assets/plugins/file-upload/js/jquery.iframe-transport.js') }}

    <!-- The basic File Upload plugin -->            
    {{ HTML::script('assets/plugins/file-upload/js/jquery.fileupload.js') }}
    
    @stop

@endif
 @section('script')
<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
    'use strict';
    var url = '/admin/upload/ajax-upload';
    $('#{{ $file_upload_config["modal_name"] }} #fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<span class="photo"/>').html(file.name).appendTo('#{{ $file_upload_config["modal_name"] }} #files');
            });

            $.each(data.result.errors, function (index, error) {
                $('<p/>').text(error.error_message).appendTo('#{{ $file_upload_config["modal_name"] }} #errors');
                $('{{ $file_upload_config["modal_name"] }} .photo-alert').show();
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#{{ $file_upload_config["modal_name"] }} #progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');               
        
});
</script>

@append

 <!-- Photo preview Modal -->
 <div class="modal photo-preview-modal" id="{{ $file_upload_config["modal_name"] }}">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         <h4 class="modal-title">Upload</h4>
       </div>
         <div class="modal-body">                   
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Odaberi datoteku</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload" type="file" name="files[]" multiple />
            </span> 
                @if(isset($file_upload_config['info']))
                <div class="photo-notice">{{ $file_upload_config['info'] }}</div>                                 
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
            </div>

         </div>
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
 </div><!-- /.modal -->  
@endif