"use strict";
jQuery(function ($) {
	var calendar = $('#calendar').fullCalendar({
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