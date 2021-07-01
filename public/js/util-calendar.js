"use strict";
jQuery(function ($) {
	$('#external-events .fc-event.formations').each(function() {
		$(this).data('event', {
			title: $.trim($(this).text()),
			stick: true,
			borderColor: $(this).css("border-color"),
			backgroundColor: $(this).css("background-color"),
			textColor: $(this).css("color")
		});

		$(this).draggable({
			zIndex: 999,
			revert: true,
			revertDuration: 0
		});

	});

	var events = [];

	$.get(Routing.generate('calendar'), function(result) {
		events = result;

		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,listMonth'
			},
			locale: 'fr',
			navLinks: true,
			businessHours: true,
			editable: true,
			selectable: true,
			droppable: true,
			events: events,
			drop: function(date) {
				var id = $(this).data('id');
				$(this).closest('.js-events').find('.js-events-date').html(date.format("DD/MM/YYYY"));
				$(this).parent().html('<div class="fc-event formation-selected">' + $(this).text() + '</div>');
	
				$.ajax({
					type: 'PUT',
					url: Routing.generate('session_formation_edit', { 'id': id }),
					data: { 'date' : date.format() },
					success: function(result) {}
				});
			},
			eventDrop: function(event, delta, revertFunc) {
				$.ajax({
					type: 'PUT',
					url: Routing.generate('session_formation_edit', { 'id': event.id }),
					data: { 'date' : event.start.format() },
					success: function(result) {}
				});
			},
			dayClick: function (date, jsEvent, view) {
				Swal.fire({
					title: "Ajouter un nouvel évenement pour le " + date.format("DD/MM/YYYY"),
					input: 'select',
					inputPlaceholder: 'Sélectionez un type d\'évenement',
					inputOptions: {
						'formation': 'Formation',
						'certification': 'Certification',
						'entretien': 'Entretien'
					},
					showCancelButton: true,
					closeOnConfirm: false,
					showLoaderOnConfirm: true,
					preConfirm: (event) => {
						if (['formation', 'certification', 'entretien'].includes(event)) {
							return event;
						} else {
							Swal.showValidationMessage("Veuillez sélectionnez un type d'évenement")
						}
					},
					allowOutsideClick: () => !Swal.isLoading()
				}).then((result) => {
					if (result.isConfirmed) {
						var title = "Ajout d'un" + (['formation', 'certification'].includes(result.value) ? 'e ' : ' ') + result.value + " le " + date.format("DD/MM/YYYY")
	
						$.get(Routing.generate('calendar_event_add', { type : result.value }), function(html) {
							bootbox.dialog({
								title: title,
								message: html,
								size: "large",
								backdrop: 'static',
								buttons: {
									success: {
										label: '<span class="fa fa-check"></span> Sauvegarder',
										className: "btn-success btn-embossed",
										callback: function() {
											var scope = $(this);
							
											$.ajax({
												type: 'POST',
												url: Routing.generate('formation_add'),
												data: $('form', scope).serialize()
											})
											.done(function(response, status, xhr) {
												if (xhr.hasOwnProperty('responseJSON')) {
													$('#calendar').fullCalendar('renderEvent', {
														title: response.name,
														start: date,
														borderColor: response.borderColor,
														backgroundColor: response.backgroundColor,
														textColor: response.textColor
													});
													bootbox.hideAll()
												}
												else {
													$('.bootbox-body', scope).html(response);
												}	
											})
											.fail(function(xhr) {
												bootbox.hideAll()
												error("Impossible de sauvegarder");
											});
				
											return false;
										}
									},
								}
							}).init(function() {			
								$('.bootbox form').submit(function(e) { e.preventDefault(); return false; });
							});
						});
					}
				});
			}
		});
	});

});