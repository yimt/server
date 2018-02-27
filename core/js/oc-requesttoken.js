/* global OC, _ */

(function($, OC, document) {

	function addCSRFToken(elm, xhr, settings) {
		if (settings.crossDomain === false) {
			xhr.setRequestHeader('requesttoken', oc_requesttoken);
			xhr.setRequestHeader('OCS-APIREQUEST', 'true');
		}
	}

	function handleExpiredCSRFToken(elm, xhr, settings) {
		if (xhr.status !== 412 || settings.retried) {
			// Ignore this error
			return;
		}

		settings.retried = true;
		var url = OC.generateUrl('/csrftoken');
		$.ajax(url, {
			success: function(resp) {
				oc_requesttoken = resp.token;
				OC.requestToken = resp.token;

				// Retry request with same settings
				$.ajax(settings);
			},
			// Call original error handler if possible
			error: settings.error
		});
	}

	$(document).ajaxSend(addCSRFToken);
	$(document).ajaxError(handleExpiredCSRFToken);

})($, OC, document);
