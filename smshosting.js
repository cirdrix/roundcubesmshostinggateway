var availableContacts = [];

$(document).ready(function()
{
	window.rcmail.addEventListener('plugin.smshosting_addressbook_callback', smshosting_addressbook_callback);
	
	function split( val ) {
		return val.split( /;\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}

	$("#_to")
		// don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 0,
			source: function( request, response ) {

				var lock = window.rcmail.set_busy(true, 'loading');
				window.rcmail.http_post('plugin.smshosting_addressbook_handler', { _uid: rcmail.env.uid, _search: extractLast(request.term) }, lock)
				
				response($.ui.autocomplete.filter(availableContacts, extractLast(request.term)));
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.mobile );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
				this.value = terms.join( "; " );
				return false;
			}
		});
	
	
});	

function smshosting_addressbook_callback(response)
{
    $.each(response,function(index,value){
        
        var findIt = false;
        $.each(availableContacts,function(key,val){
            if (value.name == val.value)
            {
                findIt = true;
                return;
            }
        });
        
        if (!findIt)
            availableContacts.push({ value: value.name, mobile: value.mobile});
    });
    return;
}     	


