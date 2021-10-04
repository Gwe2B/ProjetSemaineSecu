function DisplayImages(id) {
	let classephoto  = "photo" + id,
	    titregalerie = "titregal" + id,
		addImg = document.createElement('a');

	addImg.href = "index.php?ajoutImage&gallerie="+id;
	addImg.innerText = "Ajouter une image";
	addImg.classList.add("ui","fluid","large","primary","submit","button");
	addImg.setAttribute('onclick', "return hs.htmlExpand(this, { objectType: 'iframe' } )");
	document.getElementById('display').appendChild(addImg);

	$('.galerie').addClass('hide');

	$('.' + titregalerie).removeClass('hide');
	$('.' + titregalerie).addClass('show');

	$('.' + classephoto).removeClass('hide');
	$('.' + classephoto).addClass('show');

	$('.photos').each(function () {
		if ($(this).hasClass("hide")) $(this).remove();
	});
}