var OrderInfoComponent = React.createClass({displayName: "OrderInfoComponent",

  getInitialState: function() {
    return {
      liked: false
    }
  },

  toggleLiked: function() {
    this.setState({
      liked: !this.state.liked
    });
  },

  render: function() {
    return (
      React.createElement("pre", null, this.props.order_info.info)
    )
  }
});