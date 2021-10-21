
function addFriendship(id1, id2) {

		$.ajax({
			'url':'index.php?ajax=addFriendship',
			'dataType':'json',
			'type':'GET',
			'data':{
				'usr1Id': id1,
				'usr2Id': id2
			},
			'success':function(data) {
				if(data.result) {
					location.reload();
				} else {
				}
			},
			'error':function(request, error) {
				alert(error);
			}
		});	
	
}


function removeFriendship(id1, id2) {

		$.ajax({
			'url':'index.php?ajax=removeFriendship',
			'dataType':'json',
			'type':'GET',
			'data':{
				'usr1Id': id1,
				'usr2Id': id2
			},
			'success':function(data) {
				if(data.result) {
					location.reload();
				} else {
				}
			},
			'error':function(request, error) {
				alert(error);
			}
		});	
	
}