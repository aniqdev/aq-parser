var log = console.log

class AqsTaskManager extends React.Component {
	constructor(props) {
		super(props);
		this.addForm = this.addForm.bind(this);
		this.addTaskClick = this.addTaskClick.bind(this);
		this.inputChange = this.inputChange.bind(this);
		this.textareaChange = this.textareaChange.bind(this);
		this.changeTask = this.changeTask.bind(this);
		this.toggleAddForm = this.toggleAddForm.bind(this);
		this.changeStatus = this.changeStatus.bind(this);
		this.deleteTask = this.deleteTask.bind(this);

		this.state = {
			tasks: [],
			default_task: {
				id: '0',
				text1: '',
				text2: '',
				created_at: '',
				updated_at: '',
				done_at: '',
				done: false,
			},
			curr_task: {
				id: '0',
				text1: '',
				text2: '',
				created_at: '',
				updated_at: '25-01-17 16:50',
				done_at: '0',
				done: false,
			},
		};
	}

	componentDidMount () {
		$.get('csv/tasks.json', (data)=>{
			console.log(data)
			this.setState({tasks:data})
		},'json')
	}

	showAddForm () {
		$('.add-task-row').removeClass('aqs-hidden').addClass('aqs-shown')
		document.getElementById("add_input").focus();
	}

	hideAddForm () {
		$('.add-task-row').addClass('aqs-hidden').removeClass('aqs-shown')
	}

	toggleAddForm () {
		if ($('.add-task-row').hasClass('aqs-hidden')) {
			this.showAddForm()
			this.state.curr_task = $.extend({},this.state.default_task)
			this.setState(this.state)
		}else{
			this.hideAddForm()
		}
	}

	addTaskClick () {
		this.state.curr_task.created_at = this.now_time()
		this.state.tasks.unshift($.extend({},this.state.curr_task))
		this.setState(this.state)
		this.hideAddForm();
	}

	inputChange (e) {
		console.log('inputChange');
		this.state.curr_task.text1 = e.target.value;
		this.setState({curr_task:this.state.curr_task});
	}

	textareaChange (e) {
		this.state.curr_task.text2 = e.target.value;
		this.setState({curr_task:this.state.curr_task});
	}

	modalClick (e) {
		e.nativeEvent.stopPropagation();
		e.nativeEvent.stopImmediatePropagation();
	}

	changeTask (e) {
		this.state.curr_task = this.state.tasks[e.target.dataset.key];
		this.setState(this.state);
		this.showAddForm();
	}

	changeStatus (e) {
		var curr_task = this.state.tasks[e.target.dataset.key];
		if (curr_task.done) {
			curr_task.done = false;
			curr_task.done_at = '00';
		}else{
			curr_task.done = true;
			curr_task.done_at = this.now_time();
		}
		this.setState(this.state);
	}

	now_time () {
		return moment().format()
	}

	deleteTask (e) {
		delete this.state.tasks[e.target.dataset.key]
		this.setState(this.state);
	}

	addForm () {
		return(
			<div onClick={this.modalClick} className="col-xs-10 col-sm-8 col-md-5 row add-task-row aqs-hidden aqs-modal">
				<div className="col-xs-3 add-task-col">
					<button onClick={this.addTaskClick} type="button" className="btn btn-default add-task-button" title="Add task">Add task</button>
					<button onClick={this.hideAddForm} type="button" className="btn btn-default add-task-button" title="Cancel">Cancel</button>
				</div>
				<div className="col-xs-9 add-task-col">
					<input onChange={this.inputChange} value={this.state.curr_task.text1} className="add-input" id="add_input" type="text" placeholder="task"/>
					<textarea onChange={this.textareaChange} value={this.state.curr_task.text2} className="add-textarea" placeholder="comment"></textarea>
				</div>
			</div>
		)
	}

	getTaskRow = (task, i) => {
				var is_empty = (task.done) ? '' : '-empty';
				return(
<tr key={i} ref={'tr'+i}>
	<td>{task.text1}</td>
	<td>{task.text2}</td>
	<td>{task.created_at}</td>
	<td className="text-right aqs-options p0">
		<i onClick={this.changeTask} data-key={i} className="btn btn-default aqs-button glyphicon glyphicon-pencil" title="change task"></i>
		<i onClick={this.changeStatus} data-key={i} className={"btn btn-default aqs-button glyphicon glyphicon-star"+is_empty} title={task.done_at}></i>
		<i onClick={this.deleteTask} data-key={i} className="btn btn-default aqs-button glyphicon glyphicon-remove" title="delete task"></i>
	</td>
</tr> 
				)}

	render() {
		return (
<div className="panel panel-default task-panel"> 
	<div className="panel-heading">
		<div className="row pos-rel">
			<this.addForm/>
			<div className="col-xs-2 col-sm-6 add-form-wrapper">
				<button onClick={this.toggleAddForm} type="button" className="btn btn-default" title="Add task">+</button>
			</div>
			<div className="col-xs-10 col-sm-6">
				<div className="btn-group btn-group-justified" role="group" aria-label="...">
				  <div className="btn-group" role="group">
				    <button type="button" className="btn btn-default">Left</button>
				  </div>
				  <div className="btn-group" role="group">
				    <button type="button" className="btn btn-default">Middle</button>
				  </div>
				  <div className="btn-group" role="group">
				    <button type="button" className="btn btn-default">Right</button>
				  </div>
				</div>
			</div>
		</div>
	</div>
	<div className="panel-body"> 
		
	</div> 
	<table className="table"> 
		<thead> 
			<tr><th>Task</th><th>Comment</th><th>Done</th><th className="text-right">Options</th></tr>
		</thead> 
		<tbody> 
			{this.state.tasks.map(this.getTaskRow)}
		</tbody>
	</table> 
</div>
		);
	}
}


ReactDOM.render(<AqsTaskManager/>, document.getElementById('task_manager_root'))



// не работает так как в react js все события висят на document
// document.body.addEventListener('click', function(e) {
// 	$('.aqs-modal').addClass('aqs-hidden').removeClass('aqs-shown');
// 	log('body')
// });
// document.all.task_manager_root.addEventListener('click', function(e) {
// 	e.stopPropagation();
// });

// $('.aqs-modal').on('click', function(e) {
// 	e.stopPropagation();
// });


document.body.addEventListener('click', function(e) {
	log('body')
});
document.addEventListener('click', function(e) {
	log('document')
});