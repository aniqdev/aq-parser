var PlatiComponent = React.createClass({displayName: "PlatiComponent",

  toggleLiked: function() {
    this.setState({
      liked: !this.state.liked
    });
  },

  getInitialState: function() {
    return {
      liked: false
    }
  },

  render: function() {
    var buttonClass = this.state.liked ? 'active' : '';

    return (
      React.createElement("h2", {className: "photo"}, 
        "Plati.ru item"
      )
    )
  }
});