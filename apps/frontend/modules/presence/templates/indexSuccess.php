<h1>
	Saisie des présences des élèves en CSC4002
</h1>
 
<?php include_partial('formGroup', array('form' => $form,'groupes'=>$groupes,'sequences'=>$sequences)) ?>
 
<br /> 
<br /> 
  <a href="<?php echo url_for('presence/saisieAlpha') ?>">saisie alphabétique</a>
<br />   <br />  
  <a href="<?php echo url_for('listepresence/index') ?>">vers la liste des présences</a>
