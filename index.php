<!DOCTYPE html>
<html class="bg-black" dir="ltr" lang="pt-BR">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>W-Sage | Login</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <!-- Theme style -->
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="bg-black">
    	<div id="fundo"><img src="imagens/intro-bg.jpg"></div>
    	<div class="loginHeader"><h2>W-SAGE</h2></div>
    	<div id="login" class="dialog">
    		<form method="post" class="panel-body" action="login.php">
    			<input type="hidden" name="submitted" id="submitted" value="true" />
    			<fieldset>
    				<div class="field">
    					<label for="email">Email</label>
    					<input type="email" tabindex="1" placeholder="email" name="email" id="email" class="text">
    				</div>
    				<div class="field">
    					<label for="password">Senha</label>
    					<input type="password" tabindex="2" placeholder="senha" name="senha" id="password" class="text password">
    				</div>
				</fieldset>
				<fieldset class="submit">
					<p><a href="/account/password/reset">Resetar Senha</a></p>
					<input type="submit" value="Entrar" tabindex="3" name="commit" class="submit">
				</fieldset>
			</form>
		</div>
		<div class="margin text-center">
	        <span>Entrar através do: </span>
	        <br/>
	        <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>
	        <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>
	        <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>
	    </div>
        <script src="scripts/jquery-2.0.3.js" type="text/javascript"></script>
        <script src="scripts/bootstrap.min.js" type="text/javascript"></script>

    </body>
</html>