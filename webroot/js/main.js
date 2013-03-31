jQuery(function($) {

	var debug = true;
	var defaultCss = {};
	function log(message) {
		if(debug) {
			console.log(message);
		}
	}

	log('Debug mode !');

	/* all link with confirm class will ask if sure ! */
	$('a.confirm').click(function(event) {
		event.preventDefault();
		log("Confirmation");
		return confirm('Êtes-vous sûr ?');
	});

	/* all link with disabled class won't work */
	$('a.disabled').click(function(event) {
		event.preventDefault();
		log('Le lien ' + this + ' est désactivé');
		return false;
	});

	/* Min height en fonction de la taille du viewer */
	var minHeight = $('aside').height()
		+ $('nav').height()
		+ parseInt($('.content').css('margin-top'))
		+ parseInt($('aside').css('padding-top'))
		+ parseInt($('aside').css('padding-bottom'))
		+ parseInt($('footer').css('margin-top'));
	$('.content').css('min-height', minHeight);
	log('.content has now min-height of ' + minHeight + 'px');

	$('nav .expand').attr('unselectable', 'on')
		.css('user-select', 'none')
		.on('selectstart', false)
		.click(function(e) {
			$('nav ul li').stop().slideToggle(300);
		});

	log('Parsley loaded');
	$('form.parsley').parsley({
		successClass: 'success',
		errorClass: 'warning',
		errors: {
			errorsWrapper: '<span></span>',
			errorElem: '<span class="help-block"></span>',
			classHandler: function (elem) {
				return $(elem).parent();
			}
		},
		messages: {
			// parsley //////////////////////////////////////
			defaultMessage: "Cette valeur semble non valide.",
			type: {
				email:      "Cette valeur n'est pas une adresse email valide.",
				url:        "Cette valeur n'est pas une URL valide.",
				urlstrict:  "Cette valeur n'est pas une URL valide.",
				number:     "Cette valeur doit être un nombre.",
				digits:     "Cette valeur doit être numérique." ,
				dateIso:    "Cette valeur n'est pas une date valide (YYYY-MM-DD).",
				alphanum:   "Cette valeur doit être alphanumérique."
			}
			, notnull:        "Cette valeur ne peut pas être nulle."
			, notblank:       "Cette valeur ne peut pas être vide."
			, required:       "Ce champ est requis."
			, regexp:         "Cette valeur semble non valide."
			, min:            "Cette valeur ne doit pas être inféreure à %s."
			, max:            "Cette valeur ne doit pas excéder %s."
			, range:          "Cette valeur doit être comprise entre %s et %s."
			, minlength:      "Cette chaîne est trop courte. Elle doit avoir au minimum %s caractères."
			, maxlength:      "Cette chaîne est trop longue. Elle doit avoir au maximum %s caractères."
			, rangelength:    "Cette valeur doit contenir entre %s et %s caractères."
			, equalto:        "Cette valeur devrait être identique."

			// parsley.extend ///////////////////////////////
			, minwords:       "Cette valeur doit contenir plus de %s mots."
			, maxwords:       "Cette valeur ne peut pas dépasser %s mots."
			, rangewords:     "Cette valeur doit comprendre %s à %s mots."
			, greaterthan:    "Cette valeur doit être plus grande que %s."
			, lessthan:       "Cette valeur doit être plus petite que %s."
		}
	});
});