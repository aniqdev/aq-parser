window._aa = { add_modal:$('#addModal'), data:{img_checker:[]} };


class AddButton extends React.Component {
	
	constructor(props) {
	    super(props);
	    this.handleClick = this.handleClick.bind(this);
	}

	handleClick(){
		if (!confirm("Баним эту игру?")) return false;
		$.post('ajax.php?action=ajax-woo',
			{action : 'banaddon',
			table: 'steam_blacklist',
			plati_id : this.props.plati_id,
			game_name : this.props.game_name,
			game_id : _aa.data.id,
		});
	}

	render() {
		return <div onClick={this.handleClick} className="btn btn-primary btn-xs">to Add-on</div>
	}
}


class AddModalBody extends React.Component {
	
	constructor(props) {
	    super(props);
	    _aa.this = this;
	    this.state = {value: '',
	    			title: '',
	    			title_length: '',
	    			addBtnDisabled: false,
	    			alertHtml:''};
	    this.addGameClick = this.addGameClick.bind(this);
	    this.reparseClick = this.reparseClick.bind(this);
	    this.priceInputChange = this.priceInputChange.bind(this);
	    this.titleInputChange = this.titleInputChange.bind(this);
	    this.choosePriceClick = this.choosePriceClick.bind(this);
	}

	reparseClick(){
		var steam_de_id = _aa.data.id;
		$.post( "ajax.php?action=getjson-steam-de",
			{getjson:steam_de_id, start:1, end:1, scan:'0'}, 
			function(data) {
		 	ajax_steam_modal_body(steam_de_id);
		});
	}

	priceInputChange(e) {
		this.setState({value: e.target.value});
	}

	titleInputChange(e) {
		if(e.target.value.length <= 80){
			this.setState({title: e.target.value});
			this.setState({title_length: e.target.value.length});
		}
	}

	addGameClick(){
		var price = parseFloat(this.state.value.toString().replace(',','.')).toFixed(2);
		var title = this.state.title;
		console.log( _aa.data.id);
		console.log(price);
		console.log(title);
		this.setState({addBtnDisabled: true});
		$.post('ajax.php?action=ajax-add-item',
			{sid:_aa.data.id, price:price, title: title, plati_id: _aa.plati_id},
			function(data) {
				var elink = ''
				if (data.resp && data.resp.ItemID) {
					elink = '<b>Success!</b><br><a target="_blank" href="http://www.ebay.de/itm/'+data.resp.ItemID+'">'+data.item.Title+'</a>';
				}else{
					elink = '<b>Fail! ('+data.resp+')</b>'
				}
				if(data.deuched) elink += '<br>Deutch only!!!';
				_aa.this.setState({alertHtml: elink});
				if(data.success){
					//_aa.add_modal.modal('hide');
					if(_aa.tr) _aa.tr.remove();
				}
			},'json');
	}

	componentWillReceiveProps(){
		this.setState({value: _aa.data.item1_recom});
		this.setState({title: _aa.data.title_long});
		this.setState({title_length: _aa.data.title_long.length});
		this.setState({addBtnDisabled: false});
		this.setState({alertHtml: ''});
		_aa.plati_id = _aa.data.item1_id;
	}

	choosePriceClick(e){
		this.setState({value: _aa.data['item'+e.target.title+'_recom']});
		_aa.plati_id = _aa.data['item'+e.target.title+'_id'];
	}

  render() {
  	var ebay_link = 'http://www.ebay.de/sch/i.html?_odkw=Rust+Steam&LH_PrefLoc=2&_sop=2&LH_BIN=1&_osacat=1249&_from=R40&_trksid=p2045573.m570.l1313.TR0.TRC0.H0.TRS0&_sacat=1249&_nkw='+_aa.data.title+' steam';
    //this.setState({value: _aa.data.item1_recom});
    return (
<div>
	<div>
		<table className="table table-striped"><tbody>
		  <tr>
		  	<td><a target="_blank" href={'https://www.plati.ru/itm/'+_aa.data.item1_id}>{_aa.data.item1_name}</a></td>
		  	<td><div onClick={this.choosePriceClick} title="1" className="btn btn-success btn-xs">{_aa.data.item1_price}</div></td>
		  	<td><AddButton plati_id={_aa.data.item1_id} game_name={_aa.data.item1_name} /></td>
		  </tr>
		  <tr>
		  	<td><a target="_blank" href={'https://www.plati.ru/itm/'+_aa.data.item2_id}>{_aa.data.item2_name}</a></td>
		  	<td><div onClick={this.choosePriceClick} title="2" className="btn btn-success btn-xs">{_aa.data.item2_price}</div></td>
		  	<td><AddButton plati_id={_aa.data.item2_id} game_name={_aa.data.item2_name} /></td>
		  </tr>
		  <tr>
		  	<td><a target="_blank" href={'https://www.plati.ru/itm/'+_aa.data.item3_id}>{_aa.data.item3_name}</a></td>
		  	<td><div onClick={this.choosePriceClick} title="3" className="btn btn-success btn-xs">{_aa.data.item3_price}</div></td>
		  	<td><AddButton plati_id={_aa.data.item3_id} game_name={_aa.data.item3_name} /></td>
		  </tr>
		</tbody></table>
	</div>
	<div className="btn-group">
	  <button onClick={this.reparseClick} type="button" className="btn btn-default">Re-parse</button>
	  <a href={ebay_link} className="btn btn-primary" target="_blank" title={_aa.data.title}>eBay link</a>
	  <a href={_aa.data.link} className="btn btn-primary" target="_blank" title={_aa.data.title}>Steam link</a>
	  <input type="text" value={this.state.value} onChange={this.priceInputChange} className="btn btn-default"/>
	  <button onClick={this.addGameClick} disabled={this.state.addBtnDisabled} type="button" className="btn btn-info">Add Game</button>
	  <div className="pull-right sl-img-checker">
	  	<small>dowloaded images ({_aa.data.img_checker.length}/9):</small>
	  	{_aa.data.img_checker.map((el,i)=>{
			return (<i className="glyphicon glyphicon-picture" title={el} key={i}></i>)
	  	})}
	  </div>
	</div>
	<br/><br/>
	<div className="input-group">
	  <input type="text" value={this.state.title} onChange={this.titleInputChange} className="form-control"/>
	  <span className="input-group-addon">{this.state.title_length}</span>
	</div>
	<br/>
	<div className="alert alert-info" role="alert" dangerouslySetInnerHTML={{__html: this.state.alertHtml}}></div>
	<div className="sysreq_contents">
		<div className="game_area_sys_req sysreq_content active clearfix">
			<div className="game_area_sys_req_leftCol">
					<ul className="bb_ul">
						<li><strong>ID:</strong>{_aa.data.id}<br/></li>
						<li><strong>type:</strong>{_aa.data.type}<br/></li>
						<li><strong>appid:</strong>{_aa.data.appid}<br/></li>
						<li><strong>title:</strong>{_aa.data.title}<br/></li>
						<li><strong>link:</strong>{_aa.data.link}<br/></li>
						<li><strong>genres:</strong>{_aa.data.genres}<br/></li>
						<li><strong>developer:</strong>{_aa.data.developer}<br/></li>
						<li><strong>publisher:</strong>{_aa.data.publisher}<br/></li>
						<li><strong>usk age:</strong>{_aa.data.usk_age}<br/></li>
						<li><strong>tags:</strong>{_aa.data.tags}<br/></li>
					</ul>
			</div>
			<div className="game_area_sys_req_rightCol">
					<ul className="bb_ul">
						<li><strong>now price:</strong>{_aa.data.reg_price}<br/></li>
						<li><strong>old price:</strong>{_aa.data.old_price}<br/></li>
						<li><strong>year:</strong>{_aa.data.year}<br/></li>
						<li><strong>release:</strong>{_aa.data.release}<br/></li>
						<li><strong>specs:</strong>{_aa.data.specs}<br/></li>
						<li><strong>os:</strong>{_aa.data.os}<br/></li>
						<li><strong>overall rating:</strong>{_aa.data.o_rating}<br/></li>
						<li><strong>overall reviews:</strong>{_aa.data.o_reviews}<br/></li>
						<li><strong>languages:</strong>{_aa.data.lang}<br/></li>
					</ul>
			</div>
		</div>
		<div className="game_area_description" dangerouslySetInnerHTML={{__html:_aa.data.desc}}></div>
	</div>
</div>
    );
  }
}

var modal_body = document.getElementById('modal_body');

ReactDOM.render(<AddModalBody />, modal_body);

$('#b888').on('click', function(e) {
	var sid = $('#i888').val();
	$.post('ajax.php?action=steam-list&steam-id='+sid, function(data) {
		_aa.data = data;
		ReactDOM.render(<AddModalBody />, modal_body);
	},'json');



});
//=================================================================

function ajax_steam_modal_body(sid) {
	$.ajax({
		url:'ajax.php?action=steam-list&steam-id='+sid,
		dataType: 'json'
	})
	  .done(function(data) {
		_aa.data = data;
		_aa.data.tags = _aa.data.tags.replace(/,/g,', ');
		_aa.data.specs = _aa.data.specs.replace(/,/g,', ');
		_aa.data.lang = _aa.data.lang.replace(/,/g,', ');
		ReactDOM.render(<AddModalBody />, modal_body);
	  })
	  .fail(function() {
		_aa.data = data;
		ReactDOM.render(<AddModalBody />, modal_body);
	  });
}


$('.js-tabledeligator').on('click', '.js-add', function(e) {
	_aa.tr = $(this).parent().parent();
	_aa.title1 = _aa.tr.find('.adb1').attr('title');
	_aa.title2 = _aa.tr.find('.adb2').attr('title');
	_aa.title3 = _aa.tr.find('.adb3').attr('title');
	var sid = _aa.tr.find('.sid').text();

	ajax_steam_modal_body(sid);

	_aa.add_modal.modal('show');
});	


$('#repInterval').on('click', function(e) {
	var $this = $(this);
	$this.attr('disabled', true);
	$.post( "ajax.php?action=getjson-steam-de",
		{getjson:'interval', start:1, end:100, scan:'0'}, 
		function(data) {
		$this.attr('disabled', false);
	 	document.location.reload(true);
	});
});	

var bundle_sid = 0;
var bundle_link_input = document.getElementById('bundle_link');
function getPlatiRu(sid) {
	$.post( "ajax.php?action=getjson-steam-de",
	 	{getjson:'one_game_parse', start:1, end:1, scan:0, sid:sid},
		function( data ) {
			document.getElementById('bundle_add').disabled = false;

		},'json');
}

$('#bundle_save').on('click', function(e) {
	$(bundle_link_input).parent().removeClass('has-error');
	var link = bundle_link_input.value;
	if(!link){
		$(bundle_link_input).parent().addClass('has-error');
		return false;
	}
	$.post('ajax.php?action=ajax-bundle',
		{action:'save',link:link},
		function function_name(data) {
			if (data.status = 'success') {
				bundle_sid = data.sids.steam_de;
				getPlatiRu(bundle_sid)
			}
		},'json');
});

$('#bundle_add').on('click', function(e) {
	if(!bundle_sid) return false;
	ajax_steam_modal_body(bundle_sid);
	_aa.add_modal.modal('show');
});