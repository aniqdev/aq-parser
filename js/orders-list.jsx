var _aa = { order_modal:$('#orderModal'), 
			modal_body:document.getElementById('order_modal_body'),
			data:{} };

function draw_ebay_info() {
	return(
			<div className="ol-ebay-prices">
				<a className="order-modal-link" href={"http://www.ebay.de/sch/i.html?LH_PrefLoc=2&_sop=2&LH_BIN=1&_osacat=1249&_from=R40&_trksid=p2045573.m570.l1313.TR0.TRC0.H0.TRS0&_sacat=1249&_nkw="+this.props.data.plati_info.name} target="_blank">
					<table className="wbtable">
						<tbody>
							<tr>
								<td className={this.state.ebay_info.gigs[0]+' '+this.state.ebay_info.wls[0]} title={this.state.ebay_info.title1}>{this.state.ebay_info.price1}</td>
								<td className={this.state.ebay_info.gigs[1]+' '+this.state.ebay_info.wls[1]} title={this.state.ebay_info.title2}>{this.state.ebay_info.price2}</td>
								<td className={this.state.ebay_info.gigs[2]+' '+this.state.ebay_info.wls[2]} title={this.state.ebay_info.title3}>{this.state.ebay_info.price3}</td>
								<td className={this.state.ebay_info.gigs[3]+' '+this.state.ebay_info.wls[3]} title={this.state.ebay_info.title4}>{this.state.ebay_info.price4}</td>
								<td className={this.state.ebay_info.gigs[4]+' '+this.state.ebay_info.wls[4]} title={this.state.ebay_info.title5}>{this.state.ebay_info.price5}</td>
							</tr>
						</tbody>
					</table>
				</a><br/>
				<div className="ol-ebay-prices-names">
					<button onClick={this.reparsClick.bind(this,this.props.data.ebay_info.game_id)}>repars</button>
					<div className={this.state.ebay_info.gigs[0]+' '+this.state.ebay_info.wls[0]}><i onClick={this.addToWhiteClick.bind(this,'white','1')} className="ok glyphicon glyphicon-ok-circle" title="add to white list"></i> <i onClick={this.addToWhiteClick.bind(this,'black','1')} className="rem glyphicon glyphicon-remove-circle" title="add to black list"></i> <div className="clip">{this.state.ebay_info.title1}</div> <b>{this.state.ebay_info.price1}</b></div>
					<div className={this.state.ebay_info.gigs[1]+' '+this.state.ebay_info.wls[1]}><i onClick={this.addToWhiteClick.bind(this,'white','2')} className="ok glyphicon glyphicon-ok-circle" title="add to white list"></i> <i onClick={this.addToWhiteClick.bind(this,'black','2')} className="rem glyphicon glyphicon-remove-circle" title="add to black list"></i> <div className="clip">{this.state.ebay_info.title2}</div> <b>{this.state.ebay_info.price2}</b></div>
					<div className={this.state.ebay_info.gigs[2]+' '+this.state.ebay_info.wls[2]}><i onClick={this.addToWhiteClick.bind(this,'white','3')} className="ok glyphicon glyphicon-ok-circle" title="add to white list"></i> <i onClick={this.addToWhiteClick.bind(this,'black','3')} className="rem glyphicon glyphicon-remove-circle" title="add to black list"></i> <div className="clip">{this.state.ebay_info.title3}</div> <b>{this.state.ebay_info.price3}</b></div>
					<div className={this.state.ebay_info.gigs[3]+' '+this.state.ebay_info.wls[3]}><i onClick={this.addToWhiteClick.bind(this,'white','4')} className="ok glyphicon glyphicon-ok-circle" title="add to white list"></i> <i onClick={this.addToWhiteClick.bind(this,'black','4')} className="rem glyphicon glyphicon-remove-circle" title="add to black list"></i> <div className="clip">{this.state.ebay_info.title4}</div> <b>{this.state.ebay_info.price4}</b></div>
					<div className={this.state.ebay_info.gigs[4]+' '+this.state.ebay_info.wls[4]}><i onClick={this.addToWhiteClick.bind(this,'white','5')} className="ok glyphicon glyphicon-ok-circle" title="add to white list"></i> <i onClick={this.addToWhiteClick.bind(this,'black','5')} className="rem glyphicon glyphicon-remove-circle" title="add to black list"></i> <div className="clip">{this.state.ebay_info.title5}</div> <b>{this.state.ebay_info.price5}</b></div>
				</div>
			</div>
		);
}

class OrderModalBody extends React.Component {
	
	constructor(props) {
		console.log('constructor');
		super(props);
		this.buyBtnClick = this.buyBtnClick.bind(this);
		this.state = {tripleBtnIndex: 1,
			chosen_title:this.props.data.plati_info.item1_name,
			chosen_plati_id:this.props.data.plati_info.item1_id,
			answer_templates:this.props.data.answer_templates,
			inputs:{
				mail_title:this.props.data.msg_subject,
				mail_body:this.props.data.msg_email,
				ebay_title:this.props.data.msg_subject,
				ebay_body:this.props.data.msg_ebay,
				new_price:this.props.data.plati_info.item1_recom
			},
			data:this.props.data, emaild: '', ebaid: '',
			ebay_info:this.props.data.ebay_info};
		this.mailTitleInputChange = this.mailTitleInputChange.bind(this);
		this.mailBodyInputChange = this.mailBodyInputChange.bind(this);
		this.ebayTitleInputChange = this.ebayTitleInputChange.bind(this);
		this.ebayBodyInputChange = this.ebayBodyInputChange.bind(this);
		this.newPriceInputChange = this.newPriceInputChange.bind(this);
		this.sendAllClick = this.sendAllClick.bind(this);
		this.sendEmailClick = this.sendEmailClick.bind(this);
		this.sendEbayClick = this.sendEbayClick.bind(this);
		this.changePriceClick = this.changePriceClick.bind(this);
		this.toAddonClick = this.toAddonClick.bind(this);
		this.toBlacklistClick = this.toBlacklistClick.bind(this);
		this.itemRemoveClick = this.itemRemoveClick.bind(this);
		this.answerTemplateSelectChange = this.answerTemplateSelectChange.bind(this);
	}

	componentWillReceiveProps(nextProps){
		console.log('componentWillReceiveProps');
		if (nextProps.reset) {
			this.setState({answer_templates: nextProps.data.answer_templates});
			this.setState({tripleBtnIndex: nextProps.index});
			this.setState({chosen_title:nextProps.data.plati_info.item1_name});
			this.setState({chosen_plati_id:nextProps.data.plati_info.item1_id});
			this.setState({inputs:{
				mail_title:'',mail_body:'',ebay_title:'',ebay_body:'',
				new_price:nextProps.data.plati_info.item1_recom
			}, emaild: '', ebaid: '',
			ebay_info:nextProps.data.ebay_info});
			console.info('if');
		}else{
			console.info('else');
			this.setState({inputs:{
				mail_title:nextProps.data.msg_subject,
				mail_body:nextProps.data.msg_email,
				ebay_title:nextProps.data.msg_subject,
				ebay_body:nextProps.data.msg_ebay,
				new_price:nextProps.data.plati_info.item1_recom,
				ebay_info:nextProps.data.ebay_info
			}});
		}
	}

	tripleBtnClick(index){
		console.log('tripleBtnClick');
		_aa.chosen_plati_id = _aa.data.plati_info['item'+index+'_id'];
		this.setState({tripleBtnIndex: index,
			chosen_title:this.props.data.plati_info['item'+index+'_name'],
			chosen_plati_id:this.props.data.plati_info['item'+index+'_id']
		});
		this.setState({inputs:{new_price:this.props.data.plati_info['item'+index+'_recom']}});
		console.log(this.props.data.plati_info['item'+index+'_id']);
	}

	buyBtnClick(){
		console.log(_aa.ids);
		console.log(_aa.chosen_plati_id);
		ajax_buy(_aa.ids, _aa.chosen_plati_id);
	}

	sendAllClick(){
		var $this = this;
		$.post('/ajax.php?action=ajax-invoice-sender',
			{
			sendemail:1,
				ebay_orderid:this.props.data.order_info.order_id,
				user_email:this.props.data.order_info.BuyerEmail,
				email_subject:this.state.inputs.mail_title,
				email_body:this.state.inputs.mail_body,
				ebay_order_item_id: _aa.ids.split('-')[1],
			sendebay:1,
				ebay_user:this.props.data.order_info.BuyerUserID,
				ebay_item:this.props.data.good_info.ebay_id,
				ebay_subject:this.state.inputs.ebay_title,
				ebay_body:this.state.inputs.ebay_body,
			secret_hash:this.props.data.secret_hash,
			country_alias:this.props.data.order_info.ShippingAddress.Country,
			},
			function(data) {
				if(data.sendemail_ans !== 'no'){
					$this.setState({emaild: 'glyphicon-ok'});
					remove_order();
				} 
				else $this.setState({emaild: 'glyphicon-ban-circle'});
				if(data.sendebay_ans !== 'no') $this.setState({ebaid: 'glyphicon-ok'});
				else $this.setState({ebaid: 'glyphicon-ban-circle'});
			},'JSON');
	}

	sendEmailClick(){
		var $this = this;
		$.post('/ajax.php?action=ajax-invoice-sender',
			{sendemail:1,
			ebay_orderid:this.props.data.order_info.order_id,
			user_email:this.props.data.order_info.BuyerEmail,
			email_subject:this.state.inputs.mail_title,
			email_body:this.state.inputs.mail_body,
			ebay_order_item_id: _aa.ids.split('-')[1],
			secret_hash:this.props.data.secret_hash,
			country_alias:this.props.data.order_info.ShippingAddress.Country,
		},function(data) {
			if(data.sendemail_ans !== 'no'){
				$this.setState({emaild: 'glyphicon-ok'});
				remove_order();
			}
			else $this.setState({emaild: 'glyphicon-ban-circle'});
		},'JSON');
	}

	sendEbayClick(){
		var $this = this;
		$.post('/ajax.php?action=ajax-invoice-sender',
			{sendebay:1,
			ebay_user:this.props.data.order_info.BuyerUserID,
			ebay_item:this.props.data.good_info.ebay_id,
			ebay_subject:this.state.inputs.ebay_title,
			ebay_body:this.state.inputs.ebay_body,
			secret_hash:this.props.data.secret_hash,
			country_alias:this.props.data.order_info.ShippingAddress.Country,
		},function(data) { 
				if(data.sendebay_ans !== 'no') $this.setState({ebaid: 'glyphicon-ok'});
				else $this.setState({ebaid: 'glyphicon-ban-circle'});
		},'JSON');
	}

	changePriceClick(e) {
		console.log(this.props.data.good_info.ebay_id);
		$.post('ajax.php?action=ajax-ebay-api-price-changer',
			{action:'change',
			ebayId:this.props.data.good_info.ebay_id,
			price:this.state.inputs.new_price},
			function(data) { },'JSON');

		$.post('ajax.php?action=ajax-hood',
			{ hood_change_price_no_id: 'true',
			ebayId:this.props.data.good_info.ebay_id,
			newPrice: round_hood_price(this.state.inputs.new_price) },
			function (data) { }, 'json');
	}

	toAddonClick(e){
		console.log('toAddonClick');
		if (!confirm("Баним эту игру?")) return false;
		var game_id = this.props.data.plati_info.game_id;
		$.post('ajax.php?action=ajax-woo',
			{action:'banaddon',
			plati_id:this.state.chosen_plati_id,
			game_name:this.state.chosen_title,
			game_id:this.props.data.plati_info.game_id},
			function(data) { 
				console.info('toAddon done');
				ajax_reparse_one(game_id,()=>{console.log('callbacknulo');})

			});
	}

	toBlacklistClick(e){
		console.log('toBlacklistClick');
		if (!confirm("Баним эту игру?")) return false;
		$.post('ajax.php?action=ajax-woo',
			{action:'ban',
			plati_id:this.state.chosen_plati_id},
			function(data) {  },'JSON');
	}

	itemRemoveClick(e){
		console.log('itemRemoveClick');
		$.post('ajax.php?action=ajax-ebay-api-price-changer',
			{action:'remove',
			ebayId:this.props.data.good_info.ebay_id},
			function(data) {  },'JSON');

		$.post('ajax.php?action=ajax-hood',
			{ hood_remove_no_id: 'true',
			ebayId:this.props.data.good_info.ebay_id },
			function (data) { }, 'json');
	}

	mailTitleInputChange(e){this.setState({inputs:{mail_title:e.target.value}})}
	mailBodyInputChange(e){this.setState({inputs:{mail_body:e.target.value}})}
	ebayTitleInputChange(e){this.setState({inputs:{ebay_title:e.target.value}})}
	ebayBodyInputChange(e){this.setState({inputs:{ebay_body:e.target.value}})}
	newPriceInputChange(e){this.setState({inputs:{new_price:e.target.value}})}

	answerTemplateSelectChange(e){
		this.setState({inputs:{ebay_body:e.target.value}});
	}

	reparsClick(game_id){
		var $this = this;
		$.post('/ajax-controller.php',
			{function:'ebay_reparse_one',game_id:game_id},
		  function(data) {
			$this.setState({ebay_info:data.ebay_info});
		  },'json');
	}

	addToWhiteClick(black_white,game_num){	
		$.post('/ajax-controller.php',
			{function:'black_white_list',
			game_id:this.state.ebay_info.game_id,
			ebay_id:this.state.ebay_info['itemid'+game_num], 
			category:black_white, 
			title:this.state.ebay_info['title'+game_num]},
		  function(data) {});
	}

	render() {
		console.log('render OrderModalBody');
		if (this.state.ebay_info.id) {
			var ebay_info_html = draw_ebay_info.call(this);
		}else{
			var ebay_info_html = <div className="alert alert-danger text-center" role="alert">there is no ebay info</div>;
		}
		return (
		<div className="container-fluid"><div className="row"><div className="col-xs-12">

			<h4 title={this.props.data.good_info.title} className="clip">
				<a className="order-modal-link"
					target="_blank" 
					href={'http://www.ebay.de/itm/'+this.props.data.good_info.ebay_id}>
					{this.props.data.good_info.title}
				</a>
			</h4>

			{ebay_info_html}

			<div className="btn-group btn-group-justified" role="group" aria-label="...">
			  <div className="btn-group" role="group">
				<button type="button" 
					onClick={this.tripleBtnClick.bind(this,1)} 
					className={this.state.tripleBtnIndex===1?'btn btn-success':'btn btn-default'}
					title={this.props.data.plati_info['item1_price']+' rur'}
					>{this.props.data.plati_info['item1_recom']}</button>
			  </div>
			  <div className="btn-group" role="group">
				<button type="button" 
					onClick={this.tripleBtnClick.bind(this,2)} 
					className={this.state.tripleBtnIndex===2?'btn btn-success':'btn btn-default'}
					title={this.props.data.plati_info['item2_price']+' rur'}
					>{this.props.data.plati_info['item2_recom']}</button>
			  </div>
			  <div className="btn-group" role="group">
				<button type="button" 
					onClick={this.tripleBtnClick.bind(this,3)} 
					className={this.state.tripleBtnIndex===3?'btn btn-success':'btn btn-default'}
					title={this.props.data.plati_info['item3_price']+' rur'}
					>{this.props.data.plati_info['item3_recom']}</button>
			  </div>
			</div>
			<h5><a href={'https://www.plati.ru/itm/'+this.state.chosen_plati_id+'?ai=163508'}
				target="_blank"
				className="order-modal-link"
				>{this.state.chosen_title}</a>
				<b className="pull-right" title="parse date">{this.props.data.plati_info.date}</b></h5>

			<div className="row">
				<div className="col-sm-6">
					<button onClick={this.buyBtnClick} type="button" className="btn btn-primary">Buy</button>
					<b className="pull-right"> paid: {this.props.data.good_info.price} | 
					current: {this.props.data.curr_price}</b><br/><br/>
				</div>
				<div className="col-sm-6">
					<div className="input-group">
					  <input onChange={this.newPriceInputChange} value={this.state.inputs.new_price} title="current price" type="text" className="form-control" placeholder="Current price"/>
					  <span className="input-group-btn">
						<button onClick={this.changePriceClick} className="btn btn-primary" type="button">Change Price</button>
					  </span>
					</div>
				</div>
			</div><br/>


			<div className="row"><div className="col-sm-12">
				<div className="btn-group btn-group-justified">
				  <div className="btn-group" role="group">
					<button onClick={this.toAddonClick} type="button" className="btn btn-info">to Add-on</button>
				  </div>
				  <div className="btn-group" role="group">
					<button onClick={this.toBlacklistClick} type="button" className="btn btn-warning">to Blacklist</button>
				  </div>
				  <div className="btn-group" role="group">
					<button onClick={this.itemRemoveClick} type="button" className="btn btn-danger">Remove from Sale</button>
				  </div>
				</div>
			</div></div>

			<hr/>
			{this.props.data.errors.map(function(error, i) {
					return(<div key={i} className="alert alert-danger" role="alert">
							  <span className="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							  <span className="sr-only">Error:</span>
							  {error}
							</div>)
				})}
			<div className="bs-callout bs-callout-info" id="callout-type-b-i-elems">
				<h4>Товар:</h4> 
				<p>{this.props.data.product}</p> 
			</div>

			<div className="row">
				<div className="col-sm-6">
					<input onChange={this.mailTitleInputChange} value={this.state.inputs.mail_title} type="text" className="form-control"/><br/>
					<textarea onChange={this.mailBodyInputChange} value={this.state.inputs.mail_body} className="form-control" id="editor1" cols="30" rows="11" resize="both">
						
					</textarea><br/>
					<button onClick={this.sendAllClick} className="op-modal-btn" type="submit"><i className="glyphicon"></i>Send All</button>
					<button onClick={this.sendEmailClick} className="op-modal-btn pull-right" type="button"><i className={'glyphicon '+this.state.emaild}></i>Send Email</button><br/><br/>
				</div>

				<div className="col-sm-6">
					<select onChange={this.answerTemplateSelectChange} name="ebay_or_mail" className="form-control">
						<option value="">choose template</option>
						{this.state.answer_templates.map(function(el, i) {
								return(<option value={el.tpl_text}>{el.tpl_name}</option>)
						})}
				    </select><br/>
					<input onChange={this.ebayTitleInputChange} value={this.state.inputs.ebay_title} type="text" className="form-control"/><br/>
					<textarea onChange={this.ebayBodyInputChange} value={this.state.inputs.ebay_body} className="form-control" cols="30" rows="11">
						
					</textarea><br/>
					<button onClick={this.sendEbayClick} className="op-modal-btn pull-right" type="button"><i className={'glyphicon '+this.state.ebaid}></i>Send Message</button><br/>
				</div>
			</div><hr/>

			<h5>Product frame:</h5>
			<iframe className="invoice-iframe"
				src={this.props.data.frame_link?this.props.data.frame_link+'&oper=checkpay':''}>
				Ваш браузер не поддерживает плавающие фреймы!
			</iframe>

		</div></div></div>
		)
	}
}


function ajax_reparse_one(game_id, callback) {
	$.post('ajax.php?action=getjson-multi3',
		{getjson: game_id, start: 1, end: 1, scan: 0},
		function(data) { 
			ajax_order_modal_body(_aa.ids);
		},'JSON');
}


function ajax_order_modal_body(ids) {
	$.ajax({
		url:'ajax.php?action=ajax-orders-list',
		method: 'POST',
		data: {operation:'info',ids:ids},
		dataType: 'json'
	})
	  .done(function(data) {
		_aa.chosen_plati_id = data.plati_info.item1_id;
		_aa.data = data;
		ReactDOM.render(<OrderModalBody data={data} index={1} reset={true}/>, _aa.modal_body);
	  })
	  .fail(function() {
		_aa.data = {};
		ReactDOM.render(<OrderModalBody data={null} index={1} reset={true} />, _aa.modal_body);
	  });
}


function ajax_buy(ids, plati_id) {
	$.ajax({
		url:'ajax.php?action=ajax-orders-list',
		method: 'POST',
		data: {operation:'buy',ids:ids, plati_id:plati_id},
		dataType: 'json'
	})
	  .done(function(data) {
		_aa.data = data;
		ReactDOM.render(<OrderModalBody data={data}/>, _aa.modal_body);
		if(data.is_order_blocked === false) check_tem_on_plati_and_up_to_three(plati_id, data.good_info.ebay_id);
		_aa.chosen_plati_id = data.plati_info.item1_id;
	  })
	  .fail(function() {
		_aa.data = {};
		ReactDOM.render(<OrderModalBody data={null}/>, _aa.modal_body);
	  });
}


function remove_order() {
	$('#id'+_aa.ids).remove();
}


function check_tem_on_plati_and_up_to_three(plati_id, ebay_id) {
		$.post('ajax.php?action=ajax-orders-list',
			{check_tem_on_plati_and_up_to_three:'true',
			plati_id:plati_id,
			ebay_id:ebay_id},
			function(data) {  },'JSON');
}


$('#js-listdeligator').on('click', '.js-checkout', function(e) {

	_aa.ids = this.title;
	ajax_order_modal_body(this.title);

	//ReactDOM.render(<OrderModalBody />, _aa.modal_body);
	_aa.order_modal.modal('show');
});	


$('#js-listdeligator').on('click', '.orl-mas', (e) => {
	if (!confirm("Уверены?")) return false;
	var ids=e.target.id.replace('ids','');
	var ebay_order_item_id = ids.split('-')[1];
	var ebay_order_id = e.target.lang;
	if(!ebay_order_id) return false;
	$.post('/ajax.php?action=ajax-mark-orders',
		{mark_as_shipped:'true',
			ebay_order_id:ebay_order_id,			
			ebay_order_item_id:ebay_order_item_id},
		function(data) {
			if(data.Ack == 'Success'){
				$('#id'+ids).remove();
			}
		},'JSON');
});




