<!doctype html>
<html>
  <head>
    <title>Socket.IO chat</title>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font: 13px Helvetica, Arial; }
      form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
      form input { border: 0; padding: 10px; width: 90%; margin-right: .5%; }
      form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
      #messages { list-style-type: none; margin: 0; padding: 0; }
      #messages li { padding: 5px 10px; }
      #messages li:nth-child(odd) { background: #1c304e; }
    </style>
  </head>
  <body>
    <ul id="messages"></ul>
    <form action="">
      <input id="m" autocomplete="off" autofocus="autofocus" ><button>Send</button>
    </form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<script>
  $(function () {
    return;
    var socket = io('ws://localhost:3000');
    $('form').submit(function(e){
      e.preventDefault(); // prevents page reloading
      socket.emit('chat message', {msg:$('#m').val()});
      $('#m').val('');
      return false;
    });
    socket.on('chat message', function(data){
      $('#messages').append($('<li>').text(data.msg));
    });
  });

  $(function () {

var socket = new WebSocket("ws://localhost:3000");

socket.onopen = function(e) {
  console.log("[open] Соединение установлено");
  // socket.send("Меня зовут Джон");
  $('form').submit(function(e){
    e.preventDefault(); // prevents page reloading
    console.log("Отправляем данные на сервер");
    socket.send($('#m').val());
    $('#m').val('');
    return false;
  });
};

socket.onmessage = function(event) {
  console.log(`[message] Данные получены с сервера: ${event.data}`);
  $('#messages').append($('<li>').text(event.data));
};

socket.onclose = function(event) {
  if (event.wasClean) {
    console.log(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
  } else {
    // например, сервер убил процесс или сеть недоступна
    // обычно в этом случае event.code 1006
    console.log('[close] Соединение прервано');
  }
};

socket.onerror = function(error) {
  console.log(`[error] ${error.message}`);
};


  });
</script>

  </body>
</html>