
$(document).ready(function(){
	
	$('.utilisateur').hide();

     });


$('#recherche').keyup(function () {
   var recherche = $('#recherche').val().toLowerCase();
   recherche = $.trim(recherche); //insensible aux espaces avant/après
   recherche = recherche.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
 //  alert(recherche);
   
   $('#users').children().each(function () {

            var htxt = $(this).find('.description').text().toLowerCase(); //insensible à la casse
            var htxt1 = htxt.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); //insensible aux accents
			
		//	alert(htxt1);

            if (htxt.indexOf(recherche) > -1 || htxt1.indexOf(recherche) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
			
			if(recherche==="") 	$(this).hide();
        });		
	
});