<h1>Liste de présence des élèves en CSC4002</h1>

 
<?php include_partial('formGroup', array('form' => $form,'groupes'=>$groupes,'sequences'=>$sequences)) ?>
 
<br /> 

<br /> 
  <a href="<?php echo url_for('listepresence/alpha') ?>">liste alphabetique</a>
  <br /> 
  <br /> 
  <a href="<?php echo url_for('presence') ?>">vers la saisie</a>