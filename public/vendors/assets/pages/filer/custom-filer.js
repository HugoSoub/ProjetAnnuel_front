$(document).ready(function(){
	$('#js-project-pictures-add').filer({
		limit: null,
		maxSize: null,
		extensions: null,
		changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Drag&Drop files here</h3> <span style="display:inline-block; margin: 15px 0">or</span></div><a class="jFiler-input-choose-btn blue">Browse Files</a></div></div>',
		showThumbs: true,
		dragDrop: {
			dragEnter: null,
			dragLeave: null,
			drop: null,
			dragContainer: null,
		},
		addMore: true,
		uploadFile: {
			url: Routing.generate('project_add_picture', { id: $('.js-project-form').data('id') }),
			data: null,
			type: 'POST',
			enctype: 'multipart/form-data',
			synchron: true,
			success: function(data, itemEl, listEl, boxEl, newInputEl, inputEl, id){
				var parent = itemEl.find(".jFiler-jProgressBar").parent(),
					new_file_name = data.upload,
					filerKit = inputEl.prop("jFiler");

        		filerKit.files_list[id].name = new_file_name;
				itemEl.find(".jFiler-item-thumb-image img").attr('src', data.upload.file);
				itemEl.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
				});
			},
			error: function(el){
				var parent = el.find(".jFiler-jProgressBar").parent();
				el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
				});
			},
			statusCode: null,
			onProgress: null,
			onComplete: null
		},
		onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
			var filerKit = inputEl.prop("jFiler");
			var picture_id = filerKit.files_list[id].file.id;
			if (picture_id) {
				$.get(Routing.generate('project_remove_picture', { id: $('.js-project-form').data('id'), picture_id: picture_id }));
			}
		},
		dialogs: {
			alert: function(text) {
				return alert(text);
			},
			confirm: function (text, callback) {
				swal({
					title: text,
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Oui, continuer !",
					cancelButtonText: "Annuler",
				}, function(result) {
					if (result) {
						callback();
					}
				});
			}
		},
		captions: {
			removeConfirmation: "Êtes-vous sûr de vouloir supprimer ce fichier ?",
			errors: {
				filesLimit: "Seuls les fichiers {{fi-limit}} peuvent être téléchargés.",
				filesType: "Seules les images sont autorisées à être téléchargées.",
				filesSize: "{{fi-name}} est trop grand ! Veuillez télécharger un fichier jusqu'à {{fi-maxSize}} MB.",
				filesSizeAll: "Les fichiers que vous avez choisis sont trop grands ! Veuillez télécharger des fichiers jusqu'à {{fi-maxSize}} MB."
			}
		},
		theme: "dragdropbox",
		templates: {
            box: '<ul class="jFiler-items-list jFiler-items-default"></ul>',
            item: '<li class="jFiler-item"><div class="jFiler-item-container"><div class="jFiler-item-inner"><div class="jFiler-item-icon pull-left">{{fi-image}}</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-title" title="{{fi-name}}">{{fi-name | limitTo:30}}</div><div class="jFiler-item-others"><span>size: {{fi-size2}}</span><span>type: {{fi-extension}}</span><span class="jFiler-item-status">{{fi-progressBar}}</span></div><div class="jFiler-item-assets"><ul class="list-inline"><li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li></ul></div></div></div></div></li>',
            itemAppend: '<li class="jFiler-item"><div class="jFiler-item-container"><div class="jFiler-item-inner"><div class="jFiler-item-icon pull-left">{{fi-image}}</div><div class="jFiler-item-info pull-left"><div class="jFiler-item-title">{{fi-name | limitTo:35}}</div><div class="jFiler-item-others"><span>size: {{fi-size2}}</span><span>type: {{fi-extension}}</span><span class="jFiler-item-status"></span></div><div class="jFiler-item-assets"><ul class="list-inline"><li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li></ul></div></div></div></div></li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: false,
            removeConfirmation: true,
            canvasImage: true,
            _selectors: {
                list: '.jFiler-items-list',
                item: '.jFiler-item',
                progressBar: '.bar',
                remove: '.jFiler-item-trash-action'
            }
        },
	});

	if ($('#js-project-pictures-add').length != 0) {
		$.get(Routing.generate('project_get_pictures', { id: $('.js-project-form').data('id') }), function(pictures) {
			var filerKit = $("#js-project-pictures-add").prop("jFiler");
			if ($('.js-project-pictures-preloader').length) {
				$('.js-project-pictures-preloader').remove();
			}
			$.each( pictures, function( index, picture ){
				filerKit.append(picture.upload);
			});
		});
	}
})
