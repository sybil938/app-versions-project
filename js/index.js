(function($){

	$(function(){

		//ADDED
		$( '#add-row-added' ).on('click', function() {
		    var rowA = $( '.empty-row-added.screen-reader-text' ).clone(true);
		    rowA.removeClass( 'empty-row-added screen-reader-text' );
		    rowA.insertBefore( '#repeatable-fieldset-one-added tbody>tr:last' );
		    return false;
		});

		$( '.remove-row-added' ).on('click', function() {
		    $(this).parents('tr').remove();
		    return false;
		});

		//REMOVED
		$( '#add-row-removed' ).on('click', function() {
		    var rowR = $( '.empty-row-removed.screen-reader-text' ).clone(true);
		    rowR.removeClass( 'empty-row-removed screen-reader-text' );
		    rowR.insertBefore( '#repeatable-fieldset-one-removed tbody>tr:last' );
		    return false;
		});

		$( '.remove-row-removed' ).on('click', function() {
		    $(this).parents('tr').remove();
		    return false;
		});

		//CHANGED
		$( '#add-row-changed' ).on('click', function() {
		    var rowC = $( '.empty-row-changed.screen-reader-text' ).clone(true);
		    rowC.removeClass( 'empty-row-changed screen-reader-text' );
		    rowC.insertBefore( '#repeatable-fieldset-one-changed tbody>tr:last' );
		    return false;
		});

		$( '.remove-row-changed' ).on('click', function() {
		    $(this).parents('tr').remove();
		    return false;
		});

		//FIXED
		$( '#add-row-fixed' ).on('click', function() {
		    var rowF = $( '.empty-row-fixed.screen-reader-text' ).clone(true);
		    rowF.removeClass( 'empty-row-fixed screen-reader-text' );
		    rowF.insertBefore( '#repeatable-fieldset-one-fixed tbody>tr:last' );
		    return false;
		});

		$( '.remove-row-fixed' ).on('click', function() {
		    $(this).parents('tr').remove();
		    return false;
		});   

	}); 

})(jQuery);