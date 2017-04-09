<style type="text/css">
	body {
		background-color: #CC99FF;
	}

	.bloc_erreur {
		padding: 20px;
		
		color: white;
		
		background-color: #FF4A4A;
		font-weight: bold;
		
		width: 35%;
		text-align: center;
		margin: auto;
		margin-top: 10%;
		
		-moz-box-shadow: 0px 0px 5px 0px #656565;
		-webkit-box-shadow: 0px 0px 5px 0px #656565;
		-o-box-shadow: 0px 0px 5px 0px #656565;
		box-shadow: 0px 0px 5px 0px #656565;
		filter:progid:DXImageTransform.Microsoft.Shadow(color=#656565, Direction=NaN, Strength=5);
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		border-radius: 10px;
	}
</style>

<div class="bloc_erreur">
	Une erreur du serveur est survenue. Reesayez dans quelques minutes.<br />
	
	<small>Impossible d'acc&eacute;der &agrave; la base de donn&eacute;es</small>
</div>

<?php 
exit();