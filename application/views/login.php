<?php include 'header.php' ?>

<script>
$(document).ready(function() {
$( "#criarconta" ).click(function() {
   $('#criarcontaform').css('display','inline').animate({
        opacity: 1
    }, 5000 );
	$('#criarconta').hide();
});
$( "#closeform" ).click(function() {
	$('#criarconta').show();
	 $('#criarcontaform').hide();
});

});
</script>

  <div class="container">

    <div class="row">
      <div class="span4 offset4 well" style="margin-top:40px;text-align: center;">
		
    <legend><strong>NearFriends</strong></legend>

        <?php if (isset($error) && $error): ?>
          <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">×</a>Nickname ou password incorrectos!
          </div>
        <?php endif; ?>
		<?php if (isset($newusersuccess) && $newusersuccess==1): ?>
          <div class="alert alert">
            <a class="close" data-dismiss="alert" href="#">×</a>Conta criada com sucesso!
          </div>
        <?php endif; ?>
		<?php if (isset($newusersuccess) && $newusersuccess == 0): ?>
          <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">×</a>Erro ao criar conta!
          </div>
        <?php endif; ?>
		<?php if (isset($newusersuccess) && $newusersuccess == 2): ?>
          <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">×</a>Nickname ou email já existentes!
          </div>
        <?php endif; ?>

        <?php echo form_open('login/login_user') ?>

        <input type="text" id="nickname" class="span4" name="nickname" placeholder="Nickname">
        <input type="password" id="password" class="span4" name="password" placeholder="Password">

        <!--<label class="checkbox">
          <input type="checkbox" name="remember" value="1"> Remember Me
        </label>-->

        <button type="submit" name="submit" class="btn btn-info btn-block">Entrar</button>

        </form>
		 <button id = "criarconta" name="criarconta" class="btn btn-info btn-block">Criar conta</button>
		
		<?php 
		$attributes = array('id' => 'criarcontaform');
		echo form_open('login/criar_conta', $attributes) ;
		?>
		<input pattern=".{4,10}" required title="4 to 10 characters" type="text" id="nickname" class="span4" name="nickname" placeholder="Nickname (4 - 10 characters)">
        <input type="email" id="email" class="span4" name="email" placeholder="Email">
        <input pattern=".{4,10}" required title="4 to 10 characters" type="password" id="password" class="span4" name="password" placeholder="Password (4 - 10 characters)">
        <!--<label class="checkbox">
          <input type="checkbox" name="remember" value="1"> Remember Me
        </label>-->

        <button type="submit" name="submit" class="btn btn-info btn-block">Criar conta</button>
		<a id="closeform" class="close" >×</a>
        </form>
		  
  </div>
    </div>
  </div>

<?php include 'footer.php' ?>