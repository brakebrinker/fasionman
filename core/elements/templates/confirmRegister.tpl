{extends 'file:templates/_base.tpl'}

{block 'content'}

{insert 'file:chunks/titlebar.tpl'}

<!-- Content
================================================== -->

<!-- Container -->
<div class="container">
	<div class="six columns centered">
  	[[!ConfirmRegister? &authenticate=`1` &errorPage=`268`]]
    <script type="text/javascript">
      setTimeout('document.getElementById("timer_inp").innerHTML = "через <b>4</b> секунды"', 1000);
      setTimeout('document.getElementById("timer_inp").innerHTML = "через <b>3</b> секунды"', 2000);
      setTimeout('document.getElementById("timer_inp").innerHTML = "через <b>2</b> секунды"', 3000);
      setTimeout('document.getElementById("timer_inp").innerHTML = "через <b>1</b> секунду"', 4000);
      setTimeout('document.getElementById("timer_inp").innerHTML = "<b>прямо сейчас</b>"', 5000);
      setTimeout('document.location.href="[[~268]]"', 5000);
    </script>

    <p>Страничка вашего <a href="[[~268]]">личного кабинета</a> откроется <span id="timer_inp">через <b>5</b> секунд</span></p>
	</div>
</div>
<!-- Container / End -->

<div class="margin-top-50"></div>
{/block}