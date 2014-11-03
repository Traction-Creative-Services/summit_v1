$('.dropdown-menu').click(function(event){
     event.stopPropagation();
});
$(document).on( 'click', '.dropdown-menu', function(e) { searchModel.preventColapse(e) } );
$(document).on( 'keyup', '#agent-search', function(e) { searchModel.findAgent(e) } );
$(document).on( 'keyup', '#client-search', function(e) { searchModel.findClient(e) } );

var searchModel = {

	preventColapse: function(e) { 
		e.stopPropagation();
	},

	findAgent: function(e) {
		e.stopPropagation();
	},

	findClient: function(e) {
		e.stopPropagation
	}
}