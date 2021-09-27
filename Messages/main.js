let chatBox, from, to, input;

function loader() {
    chatBox = document.getElementById('chat');
    from = document.getElementById('from').value;
    to = document.getElementById('to').value;
    input = document.getElementById('input');

    getMessages();
}

function getMessages() {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if(xhr.readyState === XMLHttpRequest.DONE) {
            if(xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                chatBox.innerHTML = "";
                
                for (const key in response) {
                    if (Object.hasOwnProperty.call(response, key)) {
                        const element = response[key];
                        
                        let bubble = document.createElement('li'),
                            classe = "other";

                        if(from == element['user']['id']) {
                            classe = "self";
                        }

                        bubble.classList.add(classe);
                        bubble.innerHTML = '<div class="avatar"><img src="https://i.imgur.com/HYcn9xO.png" draggable="false"/></div>\
                        <div class="msg">\
                        <p>'+element['content']+'</p>\
                        <time>'+element['date']+'</time>\
                        </div>';

                        chatBox.appendChild(bubble);
                    }
                }
            } else {
                alert("Unable to contact server.");
            }
        }
    };

    xhr.open('GET', "getMessages.php?fromUser="+from+"&toUser="+to);
    xhr.send();
}

function sendMessage() {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if(xhr.readyState === XMLHttpRequest.DONE) {
            if(xhr.status !== 200) {
                alert("Unable to contact server");
            }
        }
    };

    let bubble = document.createElement('li');

    bubble.classList.add("self");
    bubble.innerHTML = '<div class="avatar"><img src="https://i.imgur.com/HYcn9xO.png" draggable="false"/></div>\
    <div class="msg">\
    <p>'+(input.value)+'</p>\
    <time>now</time>\
    </div>';

    chatBox.appendChild(bubble);

    xhr.open('GET', "sendMessage.php?fromUser="+from+"&toUser="+to+"&message="+(input.value));
    xhr.send();
}

setInterval(getMessages, 2000);