<style>
  #noMoreBox {
    position: fixed;
    z-index: 40;
    padding-top: 3%;
    margin-top: 10%;
    background-color: #57B9FF; 
    margin-left:200px;
    width: 400px;
    height: 280px;
    border: solid 2px #499BD6;
    text-align: center;
    border-radius: 25px;
  }
  #clickOut {
    position: fixed;
    z-index: 20;
    width: 200%;
    height: 100%;
    text-align: center;
    font-size: 1.2em;
    margin-left: -50%;
    background-color: rgba(38, 12, 12, 0.50);
    font-weight:bold;
  }
  hr {
    border: 0;
    height: 0; /* Firefox... */
    box-shadow: 0 0 10px 1px #AAA;
  }
  hr:after {  
      content: "\00a0";  
  }
	</style>
  <!--     width: 800px; -->
<div id="clickOut" style="display:none;"></div>
<div id="noMoreBox" style="display:none;">
  <p style="margin-bottom:50px;">Cannot select more in this category</p>
  <hr>
  <p style="margin-top:50px;">Tap anywhere to continue</p>
  <p><i class="fa fa-hand-o-up" aria-hidden="true"></i></p>
</div>