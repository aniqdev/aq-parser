


class SpecsFormInner extends React.Component {
	
	constructor(props) {
	    super(props);

	    this.state = {
	    	left_col_vals: {},
	    	midl_col_vals: {},
	    	right_col_vals: {},
	    }
	    this.props.a_specs.forEach((a_spec,i)=>{
			i += 100;
			var old_val = this.props.specs_keys[a_spec.Name] ? this.props.specs_keys[a_spec.Name].Value.toString() : '';
			this.state.left_col_vals['elem'+i] = old_val;
			this.state.midl_col_vals['elem'+i] = old_val;
			this.state.right_col_vals['elem'+i] = '';
	  	});

	    this.leftKeyUp = this.leftKeyUp.bind(this);
	    this.updateMultiselect = this.updateMultiselect.bind(this);
	    this.writeMidlColValue = this.writeMidlColValue.bind(this);
	}

	writeMidlColValue(elem,right_col_str){
		// if (this.state.left_col_vals[elem] && right_col_str) {
		// 	this.state.midl_col_vals[elem] = this.state.left_col_vals[elem]+','+this.state.right_col_vals[elem];
		// }else if (this.state.left_col_vals[elem]) {
		// 	this.state.midl_col_vals[elem] = this.state.left_col_vals[elem];
		// }else if (right_col_str) {
		// 	this.state.midl_col_vals[elem] = right_col_str;
		// }else{
		// 	this.state.midl_col_vals[elem] = '';
		// }
		if (this.state.left_col_vals[elem] && this.state.right_col_vals[elem]) {
			this.state.midl_col_vals[elem] = this.state.left_col_vals[elem]+','+this.state.right_col_vals[elem];
		}else if (this.state.left_col_vals[elem]) {
			this.state.midl_col_vals[elem] = this.state.left_col_vals[elem];
		}else if (this.state.right_col_vals[elem]) {
			this.state.midl_col_vals[elem] = this.state.right_col_vals[elem];
		}else{
			this.state.midl_col_vals[elem] = '';
		}
		this.setState(this.state);
	}

	leftKeyUp(e){
		this.state.left_col_vals[e.target.name] = e.target.value;
		// this.setState(this.state);
		this.writeMidlColValue(e.target.name,false)
	}

	updateMultiselect() {
		console.log('updateMultiselect');
		var _class = this;
		$('.js-multiselect').multiselect({
			buttonWidth : '100%',
			onChange: function(element, checked) {
		        var right_col_str = this.$select.val()?this.$select.val()+'':'';
		        var elem = this.$select[0].name.replace('multiselect','elem');
				_class.state.right_col_vals[elem] = right_col_str;
				// _class.setState(_class.state);
		        // var elem = this.$select[0].name.replace('multiselect','elem');
		        _class.writeMidlColValue('elem100');
	        },
	  //       buttonText: function(options, select) {
		 //        console.log(this);
		 //        // var elem = this.$select[0].name.replace('multiselect','elem');
		 //        _class.writeMidlColValue('elem100');
		 //        if (options.length == 0) {
		 //          return this.nonSelectedText;
		 //        } else {
		 //          if (options.length > this.numberDisplayed) {
		 //            return options.length + ' ' + this.nSelectedText;
		 //          } else {
		 //            var selected = '';
		 //            options.each(function() {
		 //              var label = ($(this).attr('label') !== undefined) ? $(this).attr('label') : $(this).html();
		 //              selected += label + ', ';
		 //            });
		 //            return selected.substr(0, selected.length - 2);
		 //          }
		 //        }
		 //    }
		});
	}

	componentDidMount() { this.updateMultiselect(); }

	// componentDidUpdate() { this.updateMultiselect(); }

	render() {
		// console.log(this.props);
		return <div>
			{this.props.specs.map((spec,i)=>{
				if(!this.props.a_specs_keys[spec.Name]) var third_btn = '';
				else var third_btn = <button type="button" className="btn btn-success" name={"#spec_row"+i}><i className="glyphicon glyphicon-open"></i></button>
				return (<div className="form-group" id={"spec_row"+i} key={i}>
				  	<label for={"ge"+i} className="col-sm-2 control-label">{spec.Name}</label>
				  	<div className={"col-xs-11 col-sm-9 js-holder-spec_add"+i}>
				  		{spec.Value.map((value,j)=>{
				  			return(<input name={"specs["+spec.Name+"][]"} type="text" className="form-control spec-input" value={value} id={"ge"+i}/>)
				  		})}
				  	</div>
				  	<div className="col-xs-1 last-col">
				  		<button type="button" className="btn btn-info js-spec-add" name="USK-Einstufung" id={"spec_add"+i}>
				  			<i className="glyphicon glyphicon-plus"></i>
				  		</button>
				  		<button type="button" className="btn btn-danger js-spec-remove" name={"#spec_row"+i}>
				  			<i className="glyphicon glyphicon-trash"></i>
				  		</button>
				  		{third_btn}
				  	</div>
				  </div>)
		  	})}
		  	<div class="row"><div class="col-sm-offset-2 col-sm-10">
				<div class="ge-subtitle">Additional specifics:</div><hr/>
			</div></div>
			{this.props.a_specs.map((a_spec,i)=>{
				i += 100;
				return (<div class="form-group" id={"a_spec_row"+i} key={i}>
					<label for={"ge"+i} class="col-sm-2 control-label">{a_spec.Name}</label>
					<div class="col-sm-3">
						<input onChange={this.leftKeyUp} name={'elem'+i} type="text" class="form-control spec-input" value={this.state.left_col_vals['elem'+i]} id={"ge"+i}/>
					</div>
					<div class="col-sm-4">
						<input name={"specs["+a_spec.Name+"][]"} type="text" class="form-control spec-input" value={this.state.midl_col_vals['elem'+i]} readOnly/>
					</div>
					<div class="col-sm-3">
						<select value="not-existed-value" className="form-control js-multiselect" name={"multiselect"+i} multiple="multiple" size="1">
					  		{a_spec.Value.map((value,j)=>{
					  			return(<option key={j+1} type="text" value={value}>{value}</option>)
					  		})}
						</select>
					</div>
				</div>)
		  	})}
		</div>
	}
}




jsxOnload(SpecsFormInner);