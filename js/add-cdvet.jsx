var addModal = $('#addModal');
var current_btn;
$('.js-cdadd').on('click', function(e) {
	// console.log(this.lang);
	current_btn = this;
	var row = this.lang;
	$('#rrow').text(row);
	var chosen_cat_id = this.name;
	$('input[type=checkbox]').prop('checked', false);

	$.post('ajax.php'+location.search,
		{action:'get_xcel_info',
		row:row, chosen_cat_id:chosen_cat_id},
		function(data) {
			ReactDOM.render(<App info={data}/>, document.getElementById('modal_body'));
			addModal.modal('show');
		},'json');

	
});


function galert(type,text) {
	return ('<div class="alert alert-'+type+' alert-dismissible height-anim" role="alert">'+
  '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+text+'</div>');
}

var Aca = {
	btns_arr : $('.js-cdadd.not_added'),
	curr_index : -1,
	next : function() {
	    this.curr_index++;
		if(!this.btns_arr[this.curr_index] || this.curr_index >= 10) return;
		var row = this.btns_arr[this.curr_index].lang;
		var chosen_cat_id = this.btns_arr[this.curr_index].name;
	    this.add_item(row,chosen_cat_id);
	},
	add_item: function(row,chosen_cat_id) {
	    console.log( row + " | chosen_cat_id: " + chosen_cat_id );

		$.post('ajax.php'+location.search,
			{action:'get_xcel_info',
			row:row, chosen_cat_id:chosen_cat_id},
			function(info) {
				
				$.post('ajax.php'+location.search,
					{action:'add_item',item:info},
					function(data) {
						if (data.resp.Ack && data.resp.Ack !== 'Failure') {
							var item_link = '<a target="_blank" href="http://www.ebay.de/itm/'+data.resp.ItemID+'">'+data.resp.ItemID+'</a><br/>';
							$(Aca.btns_arr[Aca.curr_index]).append('<i class="glyphicon glyphicon-ok"></i>');
							$('#report_screen').append(galert('success','<b>Success!</b> '+item_link));
						}else{
							$('#report_screen').append(galert('danger','<b>Fail!</b> '));
						}
	    				Aca.next();
					},'json');

			},'json');

	}
};


$('#add_all').on('click', function() {
	$(this).attr('disabled','true');
	Aca.next();
})




class App extends React.Component {
	constructor(props) {
		super(props);
		this.state = this.props.info;

		this.send = this.send.bind(this)
	}

	componentWillReceiveProps(nextProps){
		this.setState(nextProps.info);
	}

	uni_handler(state_name, e){
		this.state[state_name] = e.target.value
		if (state_name === 'main_pic1') {
			this.state['show_picture'] = e.target.value
		}
		this.setState(this.state);
	}

	uni_arr_handler(state_name, j, e){
		this.state[state_name][j] = e.target.value;
		this.setState(this.state);
	}

	chbx_handler(i){
		if(this.state.chosen_desc_pics[i]) delete this.state.chosen_desc_pics[i];
		else this.state.chosen_desc_pics[i] = this.state.desc_pics[i];
		this.setState(this.state);
		log(this.state.chosen_desc_pics);
	}

	titleInputChange(e) {
		if(e.target.value.length <= 80){
			this.setState({title: e.target.value});
			this.setState({title_length: e.target.value.length});
		}
	}

	mouseEnterHandler(e){this.setState({show_picture: e.target.value});}
	mouseLeaveHandler(e){this.setState({show_picture: this.state.main_pic1});}

	send(){
		// this.state['chosen_cat'] = this.state['cat_ids'][main_cat_id];
		// log(s);
		$.post('ajax.php'+location.search,
			{action:'add_item',item:this.state},
			(data) => {
				var item_link = '';
				if (data.resp.Ack && data.resp.Ack !== 'Failure') {
					var item_link = '<a target="_blank" href="http://www.ebay.de/itm/'+data.resp.ItemID+'">'+this.state.title+'</a><br/>';
					$(current_btn).append('<i class="glyphicon glyphicon-ok"></i>');
				}
				this.setState({alertHtml: item_link+data.text_resp});
			},'json');
	}

	render() {
		// log(this.props.info.specifics);
		var specs = this.state.specifics;
    	return (
<div>
  	<button 
  			onClick={this.send.bind(this)}
  			type="button" 
  			className="btn btn-primary m010100" 
  			>Add product to: <b>{this.state.chosen_cat[0].main_cat_name}</b></button>
  	<br/>
	<div className="alert alert-info" role="alert" dangerouslySetInnerHTML={{__html: this.state.alertHtml}}></div>
	<div className="row">
		<div className="col-sm-6">
		  <label for="inp1" className="title-label">Title ({this.state.configuratorOptions}):</label>
		  <div className="input-group">
		    <input onChange={this.titleInputChange.bind(this)} value={this.state.title} type="text" className="form-control" id="inp1" placeholder="Title"/>
		    <span className="input-group-addon">{this.state.title_length}</span>
		  </div>
		  <div className="form-group">
		    <label for="inp2">Price:</label>
		    <input onChange={this.uni_handler.bind(this,'price')} value={this.state.price}  type="text" className="form-control" id="inp2" placeholder="price"/>
		  </div>
		  <div className="form-group">
		    <label for="inp31">Main picture url 1:</label>
		    <input onChange={this.uni_handler.bind(this,'main_pic1')}
	      	       onMouseEnter={this.mouseEnterHandler.bind(this)}
			       onMouseLeave={this.mouseLeaveHandler.bind(this)} 
			       value={this.state.main_pic1}  type="text" className="form-control picture-input" id="inp31" placeholder="Picture 1"/>
		  </div>
		  <div className="form-group">
		    <label for="inp32">Main picture url 2:</label>
		    <input onChange={this.uni_handler.bind(this,'main_pic2')}
	      	       onMouseEnter={this.mouseEnterHandler.bind(this)}
			       onMouseLeave={this.mouseLeaveHandler.bind(this)} 
			       value={this.state.main_pic2}  type="text" className="form-control picture-input" id="inp32" placeholder="Picture 2"/>
		  </div>
		  <div className="form-group">
		    <label for="inp33">Main picture url 3:</label>
		    <input onChange={this.uni_handler.bind(this,'main_pic3')}
	      	       onMouseEnter={this.mouseEnterHandler.bind(this)}
			       onMouseLeave={this.mouseLeaveHandler.bind(this)} 
			       value={this.state.main_pic3}  type="text" className="form-control picture-input" id="inp33" placeholder="Picture 3"/>
		  </div>
		  	<hr/>
		  	<h4>Description pictures:</h4>
		  	{this.state.desc_pics.map((el,i) => {
		  		return(<div className="form-group" key={i+1}>
					    <label for="inp3">Picture {i+1}</label>
						<div className="input-group">
					      <span className="input-group-addon">
					      	<input onChange={this.chbx_handler.bind(this, i)} type="checkbox" checked/>
					      </span>
					      <input onChange={this.uni_arr_handler.bind(this, 'desc_pics', i)}
					      	     onMouseEnter={this.mouseEnterHandler.bind(this)}
							     onMouseLeave={this.mouseLeaveHandler.bind(this)}
							     value={el} type="text" className="form-control picture-input" placeholder="Picture 1"/>
					    </div>
					  </div>);
		  	})}
		  	<hr/>
		  	<h4>Specifications:</h4>
		  	{Object.keys(specs).map((j) => {
				return(
				  <div className="form-group" key={j+1}>
				    <label for="">&nbsp;{j}:&nbsp;</label>
				    <input onChange={this.uni_arr_handler.bind(this, 'specifics', j)} value={specs[j]} type="text" className="form-control" id="" placeholder="value"/>
				  </div>
				);
		  	})}
		  	<hr/>
		    <div className="form-group">
		      <label htmlFor="inp5">Description title:</label>
		      <input onChange={this.uni_handler.bind(this,'desc_title')} value={this.state.desc_title}  type="text" className="form-control" id="inp5" placeholder="Picture 1"/>
		    </div>
  		</div>
		<div className="col-sm-6">
		  <img className="add-cdvet-pic1" src={this.state.show_picture}/>
  		</div>
  	</div>
	<h4>Description top:</h4>
  	<textarea onChange={this.uni_handler.bind(this,'desc_top')} className="form-control" rows="5" value={this.state.desc_top}/>
  	<br/>
  	<div className="add-cdvet-textfield" dangerouslySetInnerHTML={{__html: this.state.desc_top}}></div>
	<h4>Description bottom:</h4>
  	<textarea onChange={this.uni_handler.bind(this,'desc_bot')} className="form-control" rows="5" value={this.state.desc_bot}/>
  	<br/>
  	<div className="add-cdvet-textfield" dangerouslySetInnerHTML={{__html: this.state.desc_bot}}></div>
  	<br/>
</div>
    	)
	}
}