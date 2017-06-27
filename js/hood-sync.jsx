
function syncronize(frm, to) {
	var el = $('.hs-tr');
	var ebay_id = el[frm-1].id;
	var title = el[frm-1].title;
	$.post('/ajax.php?action=devzone/hood-sync',{ebay_id:ebay_id}, function(data){
		console.log(data);
		if(data.ans[ebay_id].status === 'success') el.eq(frm-1).find('.timestamp').text(data.date);
		if(frm < to) syncronize(frm+1, to);
	},'json');
}

class HsForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {inp_from:1,inp_to:3,
    			  btnDisabled:false,
    			  cons_arr: []};

	this.fromChange = this.fromChange.bind(this);
	this.toChange = this.toChange.bind(this);
	this.formSubmit = this.formSubmit.bind(this);
	this.syncronize = this.syncronize.bind(this);
  }

  fromChange(e){this.setState({inp_from:+e.target.value});}
  toChange(e){this.setState({inp_to:+e.target.value});}

  formSubmit(e){
  	e.preventDefault();
	this.setState({btnDisabled:true});
	this.syncronize(this.state.inp_from, this.state.inp_to);
  }

  syncronize(frm, to) {
	var el = $('.hs-tr');
	var ebay_id = el[frm-1].id;
	var title = el[frm-1].title;
	var $this = this;
	$.post('/ajax.php?action=hood-sync',{ebay_id:ebay_id}, function(data){
		// console.log(data.ans[ebay_id]);
		// console.log($this.state.cons_arr);
		if(data.ans) $this.state.cons_arr.unshift(data.ans[ebay_id]);
		else $this.state.cons_arr.unshift({status:'null'});
		if(data.ans && data.ans[ebay_id].status === 'success') el.eq(frm-1).find('.timestamp').text(data.date);
		if(frm < to) $this.syncronize(frm+1, to);
		else $this.state.cons_arr.unshift({status:'Done!'});
		$this.setState({cons_arr:$this.state.cons_arr});
	},'json');
  }

  render() {
   return (
	<form onSubmit={this.formSubmit} className="form-inline">
	  <div className="form-group">
	    <label for="exampleInputName2">from</label>&nbsp;
	    <input onChange={this.fromChange} value={this.state.inp_from} type="number" className="form-control" id="exampleInputName2"/>
	  </div>&nbsp;
	  <div className="form-group">
	    <label for="exampleInputEmail2">to</label>&nbsp;
	    <input onChange={this.toChange} value={this.state.inp_to} type="number" className="form-control" id="exampleInputEmail2"/>
	  </div>&nbsp;
	  <button disabled={this.state.btnDisabled} type="submit" className="btn btn-default">Syncronize</button>
	  <br/><br/>
	  <ul className="hs-list">
		{this.state.cons_arr.map((el,i)=>{
			return (<li>{el.idAuction} | {el.status} | {el.error}</li>)
		})}
	  </ul>
	</form>
   );
  }
}

ReactDOM.render(
  <HsForm/>,
  document.getElementById('form_here')
);
