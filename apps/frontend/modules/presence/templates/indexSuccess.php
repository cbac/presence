<h1>
	Saisie des pr&eacute;sences des &eacute;l&egrave;ves en CSC4002
</h1>
 
<?php include_partial('formGroup', array('form' => $form,'groupes'=>$groupes,'sequences'=>$sequences)) ?>
 
<br /> 
<br /> 
  <a href="<?php echo url_for('presence/saisieAlpha') ?>">saisie alphab&egrave;tique</a>
<br />   <br />  
  <a href="<?php echo url_for('listepresence/index') ?>">vers la liste des pr&eacute;sences</a>
