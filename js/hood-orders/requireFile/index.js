var React = requireFile('react');
var ReactDOM = requireFile('react-dom');

$('#ho_js_deligator').on('click', function() {
	$('#js_ho_modal').modal('show');
});

$('#js_ho_modal').on('shown.bs.modal', function () {
	console.log('first render');
	$.post('ajax.php?action=hood-orders',
		{},
		function(data) {
			ReactDOM.render(React.createElement(ModalContent, {data: data}),document.getElementById('react_ho_modal'));
		});
});


class ModalContent extends React.Component {
  constructor(props) {
    super(props);
    this.default_state = {modal_title:'Modal title'};
    this.state = default_state;
	console.log('constructor');

  }

  modalClose(){$('#js_ho_modal').modal('hide');}


  render() {
   return (
React.createElement("div", {className: "op-main-modal ho-modal"}, 
	React.createElement("div", {className: "op-modal-header"}, 
		this.state.modal_title, 
		React.createElement("div", {className: "op-modal-tabs pull-right"}, 
			React.createElement("a", {href: "#", className: "op-modal-tab"}, "order info"), 
			React.createElement("a", {href: "#", className: "op-modal-tab"}, "chat"), 
			React.createElement("a", {href: "#", className: "op-modal-tab active"}, "plati")
		)
	), 
	React.createElement("div", {className: "op-modal-body"}, 
		React.createElement("a", {className: "op_modal_game_link", href: "#"}, "Test Drive Unlimited 2 PC spiel Steam Download Digital Link DE/EU/USA Key Code"), React.createElement("br", null)
	), 
	React.createElement("div", {className: "op-modal-footer"}, 
		React.createElement("a", {onClick: this.modalClose, className: "op-modal-btn"}, "close")
	)
)
   );
  }
}






