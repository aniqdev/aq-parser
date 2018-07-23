

class MagicInput extends React.Component {
  constructor(props) {
    super(props);
    this.state = {input_val:'',	search_results:[], steam_de_id:'', steam_de_link:'', steam_de_title:''};

	this.addClick = this.addClick.bind(this);
	this.searchChange = this.searchChange.bind(this);
  }

  addClick(e){
  	console.log(magic_gameId);
  	if(!this.state.steam_de_id) return false;
	ajax_steam_modal_body(this.state.steam_de_id);
	_aa.add_modal.modal('show');
  }

  searchChange(e){
  	this.setState({input_val:e.target.value});
  	if(e.target.value.length < 3){
  		this.setState({search_results:[], steam_de_link:''});
  		return;
  	}
  	var M_this = this;
  	$.post('/ajax.php?action=ajax-table_changes',
  		{action:'get_steam_list', search_query:e.target.value},
  		function(data) {
  			M_this.setState({search_results:data});
  		},'json');
  }

  itemClick(steam_de_id, steam_de_link, steam_de_title){
	this.setState({input_val:steam_de_title, search_results:[], steam_de_id:steam_de_id,
		steam_de_link:steam_de_link, steam_de_title:steam_de_title});
	console.log(this.state);
  	$.post('/ajax.php?action=ajax-table_changes',
  		{action:'set_steam_link', game_id:magic_gameId, steam_link:steam_de_link},
  		function(data) {
  			
  		},'json');
  }

  render() {
   return (
	<div className="tca-input-wrapper">
	  <button onClick={this.addClick} className="tca-add-btn">add</button>
	  <b>{this.state.steam_de_title}</b><br/>
	  <input onChange={this.searchChange} value={this.state.input_val} className="tca-input" tabindex="11" aria-autocomplete="list" placeholder="start typing game name"/>
	  <ul className="tca-dropdown-list">
		{this.state.search_results.map((el,i)=>{
			return (
				<li><a onClick={this.itemClick.bind(this, el.id, el.link, el.title)} tabindex={i+12} role="option">{el.title}</a></li>
			);
		})}
	  </ul>
	</div>
   );
  }
}


ReactDOM.render(
  <MagicInput/>,
  document.getElementById('magic_input')
);