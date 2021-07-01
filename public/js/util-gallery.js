'use strict';

jQuery(function($){
    $('.util-gallery, [data-toggle="gallery"]').each(function() { attachJustifiedGallery($(this)) });
});

function attachJustifiedGallery(element) {
	element.justifiedGallery({
		rowHeight: 200,
		captions: false,
		margins: 5,
	});
}
