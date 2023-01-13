<!-- TinyMCE editor -->            
<script src="{{ asset('assets/admin/assets/plugins/tinymce/js/tinymce/tinymce.min.js') }}"></script>

<script>
    tinymce.init({
        selector: ".tiny-mce-editor",
        theme: "modern",
        verify_html : false,
        relative_urls: false,
        remove_script_host: false,
		convert_urls: false,
        paste_text_sticky: true,
        paste_text_sticky_default: true,   
        entity_encoding : "raw",
        image_caption: true,
        importcss_append: true,
        content_css : '/assets/admin/assets/css/tinymcestyles.css',
        importcss_groups: [
          {title: 'Table styles', filter: /^(td|tr)\./}, // td.class and tr.class
          {title: 'Block styles', filter: /^(div|p)\./}, // div.class and p.class
          {title: 'Image Styles'} // The rest
        ],
        plugins: [
            "advlist autolink lists link importcss image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor"
        ],
	      toolbar: "link | image", file_browser_callback: RoxyFileBrowser,
        toolbar1: "insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image imgdescription",
        toolbar2: "print preview media | forecolor backcolor custombuttons",
        image_advtab: true,

    });
    
    tinymce.init({
        selector: ".tiny-mce-editor-simple",
        verify_html : false,
        relative_urls: false,
        remove_script_host: false,
        paste_text_sticky: true,
        paste_text_sticky_default: true,   
        entity_encoding : "raw",		
        theme: "modern",
        content_css : '/assets/admin/assets/css/tinymcestyles.css',
        toolbar1: "bold italic underline"
    });  
  	
    function RoxyFileBrowser(field_name, url, type, win) {
      var roxyFileman = '/assets/fileman/index.html';
      if (roxyFileman.indexOf("?") < 0) {     
        roxyFileman += "?type=" + type;   
      }
      else {
        roxyFileman += "&type=" + type;
      }
      roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
      if(tinyMCE.activeEditor.settings.language){
        roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
      }
      tinyMCE.activeEditor.windowManager.open({
         file: roxyFileman,
         title: 'File manager',
         width: 850, 
         height: 650,
         resizable: "yes",
         plugins: "media",
         inline: "yes",
         close_previous: "no"  
      }, {     window: win,     input: field_name    });
      return false; 
    }  	
</script>               
