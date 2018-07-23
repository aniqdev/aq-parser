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
			<textarea id="em_textarea" name="text" class="form-control" placeholder="Message..." rows="6" name="comment" required="" style="resize:vertical;"></textarea><br>
			<div class="checkbox pull-right" title="public answer at product listing">
			    <label style="user-select: none;">
			      <input class="public-chbx" type="checkbox" name="is_public" value="true" {{$data['disabled']}}">
			    </label>
			</div>
			<button type="submit" class="btn btn-primary">Send <span class="glyphicon glyphicon-send"></span></button>
			<br><br>
			<div class="form-inline">
				<select id="tpl_select" name="template" class="form-control" >
					<option value="">choose template</option>
					@foreach ($data['templates'] as $tpl)
					    <option value="{{htmlspecialchars($tpl['tpl_text'])}}">{{$tpl['tpl_name']}}</option>
					@endforeach
			    </select>
				<div class="form-group">
				    <input id="tpl_name" type="text" class="form-control" placeholder="template name">
				</div>
				<button id="tpl_save" type="button" class="btn btn-success">Save <i class="glyphicon glyphicon-floppy-save"></i></button>
			</div>
		</div>
	</div>
</form>