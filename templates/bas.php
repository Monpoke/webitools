	<?php 
		$rand = rand(0, 100);
		$pub=false;
		
		
		
		/*if(getParam(-1)!='index' && getParam(-1)!='admin' && $rand<70){
			$pub=true;
			
			$sql="SELECT * FROM publicites WHERE debut_campagne<='".time()."' and fin_campagne>='".time()."' and emplacement='1' ORDER BY rand() LIMIT 1";
			$publicite = mysql_fetch_array(Query($sql));
		
		?>
			<span id="pub_content">
				<span class="pub_afficher" title="Votre pub ici ?">?</span>
				<span class="pub_copy"><a href="/services/pub">Votre pub ici ?</a></span>
				
				<?php 
				echo $publicite['nom'];?>
				
				<script type="text/javascript">
					$(function(){
						$(".pub_copy").hide();
					
					});
					
					var afficher_copy = false;
					
					
					$(".pub_afficher").click(function(){
						
						$(".pub_copy").fadeToggle();
					});
					
					
					
					
					
				</script>
			</span>
		<?php } */?>
	
 <!-- END SERVICES -->
  </div>
      <!-- END CONTENT -->
  <div class="clear"></div>
     <!-- END SHADOW -->
</div>
<!-- FOOTER -->
<div id="footer">
  <p>WebiTools &copy;2012 I <a href="/contact">Contact</a></p>
</div>
<!-- END FOOTER -->
</div>
	<div id="lien_forum">
		<a href="/votresite">Votre publicité ?</a>
	</div>
	
		
	<?php 
	/*if(getParam(-1)=='index' or !$pub){ 
	
		$sql="SELECT * FROM publicites WHERE debut_campagne<='".time()."' and fin_campagne>='".time()."' and emplacement='2' ORDER BY rand() LIMIT 1";
		$publicite = mysql_fetch_array(Query($sql));
		
	
	?>
		<div id="pub_bas">
			
			<!-- EMPLACEMENT 2 -->
			
			TEST
			
			
			
			
			<span class="pub_afficher" title="Votre pub ici ?">?</span>
			<span class="pub_copy"><a href="/services/pub">Votre pub ici ?</a></span>
			
			<script type="text/javascript">
				$(function(){
					$(".pub_copy").hide();
				
				});
				
				var afficher_copy = false;
				
				
				$(".pub_afficher").click(function(){
					
					$(".pub_copy").fadeToggle();
				});
				
				
				
				
				
			</script>
		</div>
	<?php } */?>
	
	
	<div id="footer_terms">
		<h1><strong>kits graphiques gratuits</strong> sur <a href="http://www.kitsgratuits.com/"><strong>kits graphiques gratuits</strong></a></h1>
	</div>
<!-- END HOLDER -->
</body>
</html>
