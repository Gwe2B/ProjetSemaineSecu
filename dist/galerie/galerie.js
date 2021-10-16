function DisplayImages(id) {
	let classephoto  = "photo" + id,
	    titregalerie = "titregal" + id,
		addPhoto = document.getElementById('btn_addImg');

	
	if (addPhoto) {
		addPhoto.href = "index.php?ajoutImage&gallerie="+id;
	    addPhoto.setAttribute('onclick', "return hs.htmlExpand(this, { objectType: 'iframe' } )");
	}

	$('.galerie').addClass('hide');

	$('.' + titregalerie).removeClass('hide');
	$('.' + titregalerie).addClass('show');

	$('.' + classephoto).removeClass('hide');
	$('.' + classephoto).addClass('show');
	
	if ($('.crumb'+id)) {
		$('.crumb'+id).removeClass('hide');
	    $('.crumb'+id).addClass('show');
	}
	
	if ($('.photoPlus')) {
		$('.photoPlus').removeClass('hide');
	    $('.photoPlus').addClass('show');
	}
	

	$('.photos').each(function () {
		if ($(this).hasClass("hide")) $(this).remove();
	});
}

function removeImg(id) {
	let continuer = confirm("Etes-vous sure de vouloir la supprimer ?");

	if(continuer) {
		$.ajax({
			'url':'index.php?ajax=removeImg',
			'dataType':'json',
			'type':'GET',
			'data':{
				'imgId': id
			},
			'success':function(data) {
				if(data.result) {
					document.getElementById(id).remove();
				} else {
					alert("Impossible de supprimer l'image.");
				}
			},
			'error':function(request, error) {
				alert(error);
			}
		});
	}
}

function removeGal(id) {
	let continuer = confirm("Etes-vous sûr de vouloir supprimer la galerie et toutes ses photos ?");

	if(continuer) {
		$.ajax({
			'url':'index.php?ajax=removeGal',
			'dataType':'json',
			'type':'GET',
			'data':{
				'galId': id
			},
			'success':function(data) {
				if(data.result) {
					document.getElementById(id).remove();
				} else {
					alert("Impossible de supprimer la galerie.");
				}
			},
			'error':function(request, error) {
				alert(error);
			}
		});
	}
	location.reload();
}