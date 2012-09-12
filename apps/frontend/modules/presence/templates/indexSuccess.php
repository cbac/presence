<h1>
	Saisie des pr&eacute;sences des &eacute;l&egrave;ves en <?php echo $moduleens->getName()?>
</h1>
 
<?php include_partial('formGroup', array('form' => $form,'groups'=>$groups,'sequences'=>$sequences)) ?>
 
<br />   <br />  
  <a href="<?php echo url_for('listpresence/index') ?>">vers la liste des pr&eacute;sences</a>
  <br />   <br />  
  <a href="<?php echo url_for('choicemodule/index') ?>">vers le choix du module</a>