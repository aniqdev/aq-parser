<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">New message to <b>{{$_GET['user_name']}}</b></div>
        <div class="panel-body">
            <form action="{{$_SERVER['REQUEST_URI']}}" method="POST">
                <textarea class="form-control" name="text" rows="3"></textarea><br>
                <button class="btn btn-primary" name="send" type="submit">Send</button>
            </form>
        </div>
    </div>
</div>