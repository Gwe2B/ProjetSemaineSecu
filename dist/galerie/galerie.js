

function DisplayImages(id){
	
		var classephoto="photo"+id;
		var titregalerie="titregal"+id;
	
	    $('.galerie').addClass('hide');

		$('.'+titregalerie).removeClass('hide');
		$('.'+titregalerie).addClass('show');
	   
	    $('.'+classephoto).removeClass('hide');
		$('.'+classephoto).addClass('show');
		
		
		$('.photos').each(function() {
			
			if($(this).hasClass("hide")) $(this).remove();
		});
			
		}