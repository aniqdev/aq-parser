
// var Route = window.ReactRouterDOM.Route;
// var Router = window.ReactRouterDOM.Router;
// var BrowserRouter = window.ReactRouterDOM.BrowserRouter;
// var Switch = window.ReactRouterDOM.Switch;
// var Link = window.ReactRouterDOM.Link;

$('#ho_js_deligator').on('click', '.ho-modal-show', function() {
	$('#js_ho_modal').modal('show');

	var gig_hood_order_id = this.id;
	$.post('ajax.php?action=hood-orders',
		{action:'get_order_info', gig_hood_order_id:gig_hood_order_id},
		function(data) {
			ReactDOM.render(
			  React.createElement(ModalContent, {order_info: data}),
			  document.getElementById('react_ho_modal'));
		},'json');
});

// $('#js_ho_modal').on('shown.bs.modal', function () {
// 	console.log('first render');
// });

function is_active(key, val) {
	if(key === val) return 'active';
}


class ModalContent extends React.Component {
  constructor(props) {
    super(props);
    this.default_state = {
    	modal_title:this.props.order_info.br_firstName+
    	' '+this.props.order_info.br_lastName+
    	' ('+this.props.order_info.br_email+')',
		route: window.location.hash.substr(1)};
    this.state = this.default_state;
	// console.log('constructor');
	this.componentWillReceiveProps = this.componentWillReceiveProps.bind(this);
  }

  componentWillReceiveProps(nextProps){
	// console.log('componentWillReceiveProps');
  	this.setState({
  		modal_title:nextProps.order_info.br_firstName+
    	' '+nextProps.order_info.br_lastName+
    	' ('+nextProps.order_info.br_email+')'
  	});
  }

  modalClose(){$('#js_ho_modal').modal('hide');}

  componentDidMount() {
    window.addEventListener('hashchange', () => {
      this.setState({
        route: window.location.hash.substr(1)
      });
      // console.log(window.location.hash.substr(1));
    });
  }

  render() {
  	var Child;
    switch (this.state.route) {
      case 'chat': Child = ChatComponent; break;
      // case 'plati': Child = PlatiComponent; break;
      default: Child = OrderInfoComponent;
    }
   return (
React.createElement("div", {className: "op-main-modal ho-modal"}, 
	React.createElement("div", {className: "op-modal-header"}, 
		this.state.modal_title, 
		React.createElement("div", {className: "op-modal-tabs pull-right"}, 
            React.createElement("a", {href: '#info', className: 'op-modal-tab '+is_active(this.state.route, 'info')}, "Info"), 
            React.createElement("a", {href: '#chat', className: 'op-modal-tab '+is_active(this.state.route, 'chat')}, "Chat")
		)
	), 
	React.createElement("div", {className: "op-modal-body"}, 
		React.createElement(Child, {order_info: this.props.order_info})
  	), 
	React.createElement("div", {className: "op-modal-footer"}, 
		React.createElement("a", {onClick: this.modalClose, className: "op-modal-btn"}, "close")
	)
)
   );
  }
}





