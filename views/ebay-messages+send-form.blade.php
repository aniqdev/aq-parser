<h3>{{$data['game_name']}}</h3>
<form class="well form-horizontal" style="background: #191919;" id="ebay-msg-answer-form">
	<input type="hidden" name="sendanswer" value="true">
	<input type="hidden" name="correspondent" value="{{$data['correspondent']}}">
	<input type="hidden" name="message_id" value="{{$data['message_id']}}">
	<div class="form-group has-feedback">
	  <label class="col-md-4 control-label">Answer:</label>
	  <div class="col-md-4">
	    <div class="input-group">
	        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
	        <textarea class="form-control" name="text" placeholder="Message text"></textarea>
	  	</div>
	  </div>
	</div>
	<div class="form-group">
	  <label class="col-md-4 control-label"></label>
	  <div class="col-md-4">
	    <button type="submit" class="btn btn-warning">Send <span class="glyphicon glyphicon-send"></span></button>
	  </div>
	</div>
</form>