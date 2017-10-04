var addModal = $('#addModal');
$('.js-cdadd').on('click', function(e) {
	console.log(this.lang);
	var row = this.lang;

	$.post('ajax.php?action=add-cdvet',
		{action:'get_xcel_info',
		row:row},
		function(data) {
			ReactDOM.render(<App info={data}/>, document.getElementById('modal_body'));
			addModal.modal('show');
		},'json');

	
});




class App extends React.Component {
	constructor(props) {
		super(props);
		this.max_len = 18;
		this.state = {
			desc_top: this.props.info.desc_top,
			desc_bot: this.props.info.desc_bot,
		};

		this.change_desc_top = this.change_desc_top.bind(this);
		this.change_desc_bot = this.change_desc_bot.bind(this);
	}

	componentWillReceiveProps(nextProps){
		console.log('componentWillReceiveProps');
		this.setState({desc_top: nextProps.info.desc_top, desc_bot: nextProps.info.desc_bot});
	}

	change_desc_top(e){
		this.setState({desc_top: e.target.value});
	}

	change_desc_bot(e){
		this.setState({desc_bot: e.target.value});
	}

	render() {
    	return (
    		<div>
<form>
	<div className="row">
		<div className="col-sm-6">
		  <div className="form-group">
		    <label for="inp1">Title ({this.props.info['N']})</label>
		    <input value={this.props.info['C']} type="text" className="form-control" id="inp1" placeholder="Title"/>
		  </div>
		  <div className="form-group">
		    <label for="inp2">Price</label>
		    <input value={this.props.info['G']}  type="text" className="form-control" id="inp2" placeholder="price"/>
		  </div>
		  <div className="form-group">
		    <label for="inp3">Main picture</label>
		    <input value={this.props.info['PictureURL']}  type="text" className="form-control" id="inp3" placeholder="Picture 1"/>
		  </div>
		  	<hr/>
		  	<h4>Description pictures:</h4>
		  	{this.props.info.desc_pics.map(function(el,i) {
		  		return(<div className="form-group">
					    <label for="inp3">Picture {i+1}</label>
						<div className="input-group">
					      <span className="input-group-addon"><input type="checkbox" aria-label="..."/></span>
					      <input value={el} type="text" className="form-control" placeholder="Picture 1"/>
					    </div>
					  </div>);
		  	})}
		  	<hr/>
		  	<h4>Specifications:</h4>
			<div className="form-inline">
			  <div className="form-group">
			    <label for="exampleInputName2">name</label>
			    <input type="text" className="form-control" id="exampleInputName2" placeholder="Jane Doe"/>
			  </div>
			  <div className="form-group">
			    <label for="exampleInputEmail2"> value</label>
			    <input type="email" className="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com"/>
			  </div>
			</div><br/>
			<div className="form-inline">
			  <div className="form-group">
			    <label for="exampleInputName2">name</label>
			    <input type="text" className="form-control" id="exampleInputName2" placeholder="Jane Doe"/>
			  </div>
			  <div className="form-group">
			    <label for="exampleInputEmail2"> value</label>
			    <input type="email" className="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com"/>
			  </div>
			</div><br/>
		  	<hr/>
  		</div>
		<div className="col-sm-6">
		  <img className="add-cdvet-pic1" src={this.props.info['PictureURL']}/>
  		</div>
  	</div>
	<h4>Description top:</h4>
  	<textarea onChange={this.change_desc_top} className="form-control" rows="5" value={this.state.desc_top}/>
  	<br/>
  	<div className="add-cdvet-textfield" dangerouslySetInnerHTML={{__html: this.state.desc_top}}></div>
	<h4>Description bottom:</h4>
  	<textarea onChange={this.change_desc_bot} className="form-control" rows="5" value={this.state.desc_bot}/>
  	<br/>
  	<div className="add-cdvet-textfield" dangerouslySetInnerHTML={{__html: this.state.desc_bot}}></div>
  	<br/>
    <button type="submit" className="btn btn-primary">Add product</button>
</form>
    		</div>
    	)
	}
}