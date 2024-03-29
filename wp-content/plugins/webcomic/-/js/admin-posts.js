/** Update meta data values in the quick edit box for webcomic posts. */
function webcomic_quick_edit( url ) {
	jQuery( function( $ ) {
		$( document ).on( 'click', 'a.editinline', function() {
			$.getJSON( url, {
				post: $( this ).parents( 'tr' ).attr( 'id' ).substr( 5, $( this ).parents( 'tr' ).attr( 'id' ).length ),
				webcomic_admin_ajax: 'WebcomicPosts::ajax_quick_edit'
			}, function ( data ) {
				$( '#webcomic_prints' ).attr( 'checked', data.prints ).attr( 'disabled', data.prints_disabled );
				$( '#webcomic_original' ).attr( 'checked', data.original );
				$( '#webcomic_transcripts' ).attr( 'checked', data.transcripts );
				
				// select page collection
			} );
		} );
	} );
}

/** Save meta data values from the quick edit box for webcomic posts. */
function webcomic_quick_save( url ) {
	jQuery( function( $ ) {
		$( '.save' ).on( 'click', function() {
			$.get( url, {
				post: $( this ).parents( 'tr' ).attr( 'id' ).substr( 5, $( this ).parents( 'tr' ).attr( 'id' ).length ),
				prints: $( '#webcomic_prints:checked' ).val() ? 1 : 0,
				original: $( '#webcomic_original:checked' ).val() ? 1 : 0,
				transcripts: $( '#webcomic_transcripts:checked' ).val() ? 1 : 0,
				webcomic_inline_save: $( '#webcomic_inline_save' ).val(),
				webcomic_admin_ajax: 'WebcomicPosts::ajax_quick_save'
			} );
		} );
	} );
}