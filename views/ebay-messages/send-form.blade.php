<h3>{{$data['game_name']}}</h3>

<form class="well" style="background: #191919;" id="ebay-msg-answer-form">
	<div class="row">
		<div class="col-sm-3">
			<h4 class="pull-right">Answer message:</h4>
		</div>
		<div class="col-sm-6">
			<input type="hidden" name="sendanswer" value="true">
			<input type="hidden" name="correspondent" value="{{$data['correspondent']}}">
			<input type="hidden" name="message_id" value="{{$data['message_id']}}">
			<textarea name="text" class="form-control" placeholder="Message..." rows="6" name="comment" required="" style="resize:vertical;"></textarea><br>
			<div class="checkbox pull-right" title="public answer at product listing">
			    <label style="user-select: none;">
			      <input class="public-chbx" type="checkbox" name="is_public" value="true" {{$data['disabled']}}">
			    </label>
			</div>
			<button type="submit" class="btn btn-primary">Send <span class="glyphicon glyphicon-send"></span></button>
		</div>
	</div>
</form>