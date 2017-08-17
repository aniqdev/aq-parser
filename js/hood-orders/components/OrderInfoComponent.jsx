var OrderInfoComponent = React.createClass({

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
      <pre>{this.props.order_info.info}</pre>
    )
  }
});