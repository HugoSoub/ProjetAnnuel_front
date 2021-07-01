'use strict';

jQuery(function($){
    $('.util-tinymce, [data-toggle="tinymce"]').each(function() { attachTinymce($(this)) });
});

function attachTinymce(element) {
    element.tinymce({
        height: 500,
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help'
      });
}
