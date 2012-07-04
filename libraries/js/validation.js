$(document)
		.ready(
				function() {
					$('.validate-length').keydown(
							function() {
								var maximumLength = $(this).attr('MaxLength');
								var currentLength = $(this).val().length + 1;
								var infoNode = $(this).parent( ).children( '.info' ); 
								
								if( infoNode != null )
								{
									infoNode.text( (maximumLength - currentLength ) + " characters remaining");																
								} 
							});

					$('.validate-length')
							.focusout(
									function() {
										var maximumLength = $(this).attr(
												'MaxLength');
										var currentLength = $(this).val().length + 1;

										if (currentLength > maximumLength) {
											alert($(this).attr("name")
													+ " can contain a maximum of "
													+ maximumLength
													+ " characters, it currently contains "
													+ currentLength
													+ " characters.");
											$(this).focus();
											$(':input[type=submit]').attr(
													'disabled', true);
										} else {
											if ($(':input[type=submit]').attr(
													'disabled')) {
												$(':input[type=submit]')
														.removeAttr('disabled');
											}
										}
									});

					$('.validate-email')
							.change(
									function() {
										$email = $(this).val();

										if (!/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/
												.test($email)) {
											alert("Invalid email address");
											$(this).focus();
											$(this).select();
										}
									});
				});