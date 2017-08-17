var ChatComponent = React.createClass({displayName: "ChatComponent",

  getInitialState: function() {
    return {
      liked: false,
      email_adress: this.props.order_info.br_email,
      email_subject: this.props.order_info.json_orderItems[0].prodName['@cdata'],
      email_body: '',
      msg_body: '',
      add_class: '',
      user_id: '',
    }
  },

  fillEmail: function (e) {this.setState({email_adress: e.target.value});},
  fillSubj: function (e) {this.setState({email_subject: e.target.value});},
  fillMessageEmail: function (e) {this.setState({email_body: e.target.value});},
  fillMessageHood: function (e) {this.setState({msg_body: e.target.value});},
  fillUserId: function (e) {this.setState({user_id: e.target.value});},

  sendEmail: function (e) {
    e.preventDefault();
    var $this = this, state = this.state;
    $.post('/ajax.php?action=ajax-invoice-sender',
      {sendemail:'1',
      user_email:state.email_adress,
      email_subject:state.email_subject,
      email_body:state.email_body},
      function(data) {
        if (data.sendemail_ans) $this.setState({add_class: 'glyphicon-ok'});
        else $this.setState({add_class: 'glyphicon-warning-sign'});
      },'json');
  },

  sendHood: function (e) {
    e.preventDefault();
    var $this = this, state = this.state;
    $.post('/ajax.php?action=hood-messages',
      {send:'ajax',
      user_id:state.user_id,
      text:state.msg_body},
      function(data) {

      },'json');
  },

  render: function() {
    return (
React.createElement("div", {className: "container-fluid"}, 
    React.createElement("form", {className: "row", method: "POST", id: "js-inv-sendemail-form"}, 

      React.createElement("div", {className: "col-sm-6"}, 
        React.createElement("input", {type: "hidden", name: "sendemail"}), 
        React.createElement("input", {onChange: this.fillEmail, type: "text", className: "form-control", name: "user_email", value: this.state.email_adress}), React.createElement("br", null), 
        React.createElement("input", {onChange: this.fillSubj, type: "text", className: "form-control", name: "email_subject", value: this.state.email_subject}), React.createElement("br", null), 
        React.createElement("textarea", {onChange: this.fillMessageEmail, className: "form-control", name: "email_body", id: "editor1", cols: "30", rows: "11"}, 
          this.state.email_body
        ), React.createElement("br", null), 
        React.createElement("button", {onClick: this.sendEmail, className: "glyphicon op-modal-btn pull-right "+this.state.add_class, type: "button"}, "Send Email")
      ), 

      React.createElement("div", {className: "col-sm-6"}, 
        React.createElement("input", {type: "hidden", name: "action", value: "send_hood"}), 
        React.createElement("input", {onChange: this.fillUserId, type: "text", className: "form-control", name: "user_id", value: this.state.user_id, placeholder: "user id"}), React.createElement("br", null), 
        React.createElement("textarea", {onChange: this.fillMessageHood, className: "form-control", name: "hood_body", cols: "30", rows: "11"}, 
          this.state.msg_body
        ), React.createElement("br", null), 
        React.createElement("button", {onClick: this.sendHood, className: "op-modal-btn glyphicon pull-right", id: "js-inv-sendebay", type: "button"}, "Send Message")
      )

    )
  )
    )
  }
});