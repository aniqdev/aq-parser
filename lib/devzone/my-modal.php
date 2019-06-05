<div class="container">
	<!-- Button trigger mordal -->
	<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModala">
	  Launch demo mordal
	</button>
	<button type="button" class="btn btn-primary btn-lg" id="show_mordal" data-target="#mymordal">
	  Launch my mordal
	</button>

<script>
document.addEventListener("DOMContentLoaded", function() {

	function myMordalOpen(e) {
		var mordalId = this.dataset.target
		var mordal = document.querySelector(mordalId)
		document.body.classList.add('mordal-open')
		mordal.classList.add('in')
		mordal.style.display = 'block'
		mordal.style.backgroundColor = 'rgba(0, 0, 0, 0.4)'
	}

	function myMordalClose(e) {
		var mordalId = this.dataset.target
		var mordal = document.querySelector(mordalId)
		document.body.classList.remove('mordal-open')
		mordal.classList.remove('in')
		mordal.style.display = 'none'
	}

	document.all.mordalDialog.addEventListener('click', function(e){e.stopPropagation()})

	document.all.show_mordal.addEventListener('click', myMordalOpen)

	document.all.mymordal.addEventListener('click', myMordalClose)

	document.all.mymordalClose.addEventListener('click', myMordalClose)

})
</script>

<style>
.mordal-open {
  overflow: hidden;
}
.mordal {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1050;
  display: none;
  overflow: hidden;
  -webkit-overflow-scrolling: touch;
  outline: 0;
  color: #666;
}
.mordal.fade .mordal-dialog {
  -webkit-transform: translate(0, -25%);
  -ms-transform: translate(0, -25%);
  -o-transform: translate(0, -25%);
  transform: translate(0, -25%);
  -webkit-transition: -webkit-transform 0.3s ease-out;
  -o-transition: -o-transform 0.3s ease-out;
  transition: transform 0.3s ease-out;
}
.mordal.in .mordal-dialog {
  -webkit-transform: translate(0, 0);
  -ms-transform: translate(0, 0);
  -o-transform: translate(0, 0);
  transform: translate(0, 0);
}
.mordal-open .mordal {
  overflow-x: hidden;
  overflow-y: auto;
}
.mordal-dialog {
  position: relative;
  width: auto;
  margin: 10px;
}
.mordal-content {
  position: relative;
  background-color: #ffffff;
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  border: 1px solid #999999;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 6px;
  -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  outline: 0;
    background: rgba(2,17,38,.9);
    border: 1px solid #25c99e;
    border-radius: 20px;
    color: #eaeaea;
}
.mordal-backdrop {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1040;
  background-color: #000000;
}
.mordal-backdrop.fade {
  filter: alpha(opacity=0);
  opacity: 0;
}
.mordal-backdrop.in {
  filter: alpha(opacity=50);
  opacity: 0.5;
}
.mordal-header {
  padding: 15px;
  border-bottom: 1px solid #e5e5e5;
}
.mordal-header .close {
  margin-top: -2px;
}
.mordal-title {
  margin: 0;
  line-height: 1.42857143;
}
.mordal-body {
  position: relative;
  padding: 15px;
}
.mordal-footer {
  padding: 15px;
  text-align: right;
  border-top: 1px solid #e5e5e5;
}
.mordal-footer .btn + .btn {
  margin-bottom: 0;
  margin-left: 5px;
}
.mordal-footer .btn-group .btn + .btn {
  margin-left: -1px;
}
.mordal-footer .btn-block + .btn-block {
  margin-left: 0;
}
.mordal-scrollbar-measure {
  position: absolute;
  top: -9999px;
  width: 50px;
  height: 50px;
  overflow: scroll;
}
@media (min-width: 768px) {
  .mordal-dialog {
    width: 600px;
    margin: 30px auto;
  }
  .mordal-content {
    -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
  }
  .mordal-sm {
    width: 300px;
  }
}
@media (min-width: 992px) {
  .mordal-lg {
    width: 900px;
  }
}
.clearfix:before,
.clearfix:after,
.mordal-header:before,
.mordal-header:after,
.mordal-footer:before,
.mordal-footer:after {
  display: table;
  content: " ";
}
.clearfix:after,
.mordal-header:after,
.mordal-footer:after {
  clear: both;
}
.mordal button.close {
	float: right;
    font-size: 21px;
    font-weight: 700;
    line-height: 1;
    color: #eaeaea;
    opacity: .7;
    -webkit-appearance: none;
    padding: 0;
    cursor: pointer;
    background: 0 0;
    border: 0;
}
</style>
	<!-- mordal -->
	<div class="mordal fade bs-example-mordal-sm" id="mymordal" tabindex="-1" role="dialog" aria-labelledby="mymordalLabel" data-target="#mymordal">
	  <div class="mordal-dialog" role="document" id="mordalDialog">
	    <div class="mordal-content">
	      <div class="mordal-header">
	        <button type="button" class="close" data-dismiss="mordal" aria-label="Close" id="mymordalClose" data-target="#mymordal"><span aria-hidden="true">&times;</span></button>
	        <h4 class="mordal-title" id="mymordalLabel">mordal title</h4>
	      </div>
	      <div class="mordal-body">
	        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea ut explicabo voluptate dignissimos illum quibusdam amet at nobis, consequatur quis accusamus odit totam, aspernatur omnis, soluta minima sit possimus saepe reiciendis dolorum repudiandae vero iure eveniet repellat fuga. Incidunt tenetur illo aliquid repellendus dolorum eos, iusto pariatur voluptates veritatis libero quisquam, aperiam natus odit cumque nesciunt perspiciatis. Nihil corporis unde voluptates facere soluta, possimus quis ullam numquam doloribus laboriosam, totam pariatur deserunt tenetur! Sequi nostrum quos repellat nulla consequuntur, quam consectetur id iure, eaque enim corrupti voluptatem iste est? Dolore esse, officiis quae accusamus aliquam dolores deleniti aspernatur, commodi iste ipsum, iusto voluptas aut exercitationem nobis. Officiis magnam commodi ipsum laudantium cupiditate, asperiores architecto ducimus excepturi qui ex saepe possimus distinctio consequuntur blanditiis, accusantium, voluptas eum. Laudantium quis atque quos sequi et quia optio, ad pariatur molestiae non voluptate. Cupiditate a veniam iusto incidunt similique molestiae neque facilis quam impedit autem ipsam rerum sed expedita nobis nemo, consequatur, architecto aliquam odit minus velit, quisquam quasi harum dolore accusantium! Fuga amet animi eveniet quo beatae minus vero nam incidunt quas quos voluptatibus, laboriosam ipsa, reiciendis, velit, earum ipsam consequuntur. Nam, modi porro consequatur quisquam sint officiis quaerat impedit voluptatibus perspiciatis aliquid, natus eligendfficia rerum deleniti illum esse possimus. Nemo consequuntur at culpa incidunt deleniti inventore placeat officia magni molestiae similique iusto, obcaecati, libero ab repellendus asperiores sed aliquam fugidit autem ipsam rerum sed expedita nobis nemo, consequatur, architecto aliquam odit minus velit, quisquam quasi harum dolore accusantium! Fuga amet animi eveniet quo beatae minus vero nam incidunt quas quos voluptatibus, laboriosam ipsa, reiciendis, velit, earum ipsam consequuntur. Nam, modi porro consequatur quisquam sint officiis quaerat impedit voluptatibus perspiciatis aliquid, natus eligendfficia rerum deleniti illum esse possimus. Nemo consequuntur at culpa incidunt deleniti inventore placeat officia magni molestiae similique iusto, obcaecati, libero ab repellendus asperiores sed aliquam fugiat velit! Dolores iste pariatur ex excepturi consequuntur dolorem dignissimos, laudantium cum vero sint maiores atque tempora officiis accusantium laboriosam exercitationem, deleniti, quas totam aspernatur nobis aliquid! Recusandae odit harum tenetur dolorem nemo possimus autem velit sapiente sit at repellendus, a beatae aperiam doloremque consectetur, totam in molestiae vero incidunt ratione. Enim rem accusamus quaerat debitis reprehenderit assumenda quam, dolores fuga saepe facilis molestiae rerum, nisi ex eum ratione, dignissimos itaque earum est laudantium excepturi dolorum! Deserunt id assumenda iure rem excepturi doloribus, eum itaque ducimus error voluptatum accusamus voluptas sequi earum ratione magni ipsum voluptatem dolorum tempora eos, ad et, adipisci.
	      </div>
	      <div class="mordal-footer">
	        <!-- <button type="button" class="btn btn-default" data-dismiss="mordal">Close</button> -->
	        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Modal -->
	<div class="modal fade bs-example-modal-sm" id="myModala" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-target="#myModala">
	  <div class="modal-dialog" role="document" id="qwe">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="asdd" data-target="#myModal"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="zxc">Modal title</h4>
	      </div>
	      <div class="modal-body">
	        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea ut explicabo voluptate dignissimos illum quibusdam amet at nobis, consequatur quis accusamus odit totam, aspernatur omnis, soluta minima sit possimus saepe reiciendis dolorum repudiandae vero iure eveniet repellat fuga. Incidunt tenetur illo aliquid repellendus dolorum eos, iusto pariatur voluptates veritatis libero quisquam, aperiam natus odit cumque nesciunt perspiciatis. Nihil corporis unde voluptates facere soluta, possimus quis ullam numquam doloribus laboriosam, totam pariatur deserunt tenetur! Sequi nostrum quos repellat nulla consequuntur, quam consectetur id iure, eaque enim corrupti voluptatem iste est? Dolore esse, officiis quae accusamus aliquam dolores deleniti aspernatur, commodi iste ipsum, iusto voluptas aut exercitationem nobis. Officiis magnam commodi ipsum laudantium cupiditate, asperiores architecto ducimus excepturi qui ex saepe possimus distinctio consequuntur blanditiis, accusantium, voluptas eum. Laudantium quis atque quos sequi et quia optio, ad pariatur molestiae non voluptate. Cupiditate a veniam iusto incidunt similique molestiae neque facilis quam impedit autem ipsam rerum sed expedita nobis nemo, consequatur, architecto aliquam odit minus velit, quisquam quasi harum dolore accusantium! Fuga amet animi eveniet quo beatae minus vero nam incidunt quas quos voluptatibus, laboriosam ipsa, reiciendis, velit, earum ipsam consequuntur. Nam, modi porro consequatur quisquam sint officiis quaerat impedit voluptatibus perspiciatis aliquid, natus eligendfficia rerum deleniti illum esse possimus. Nemo consequuntur at culpa incidunt deleniti inventore placeat officia magni molestiae similique iusto, obcaecati, libero ab repellendus asperiores sed aliquam fugidit autem ipsam rerum sed expedita nobis nemo, consequatur, architecto aliquam odit minus velit, quisquam quasi harum dolore accusantium! Fuga amet animi eveniet quo beatae minus vero nam incidunt quas quos voluptatibus, laboriosam ipsa, reiciendis, velit, earum ipsam consequuntur. Nam, modi porro consequatur quisquam sint officiis quaerat impedit voluptatibus perspiciatis aliquid, natus eligendfficia rerum deleniti illum esse possimus. Nemo consequuntur at culpa incidunt deleniti inventore placeat officia magni molestiae similique iusto, obcaecati, libero ab repellendus asperiores sed aliquam fugiat velit! Dolores iste pariatur ex excepturi consequuntur dolorem dignissimos, laudantium cum vero sint maiores atque tempora officiis accusantium laboriosam exercitationem, deleniti, quas totam aspernatur nobis aliquid! Recusandae odit harum tenetur dolorem nemo possimus autem velit sapiente sit at repellendus, a beatae aperiam doloremque consectetur, totam in molestiae vero incidunt ratione. Enim rem accusamus quaerat debitis reprehenderit assumenda quam, dolores fuga saepe facilis molestiae rerum, nisi ex eum ratione, dignissimos itaque earum est laudantium excepturi dolorum! Deserunt id assumenda iure rem excepturi doloribus, eum itaque ducimus error voluptatum accusamus voluptas sequi earum ratione magni ipsum voluptatem dolorum tempora eos, ad et, adipisci.
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>
</div>