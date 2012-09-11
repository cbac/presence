<h1>Attendances Selection</h1> <?php $moduleens->getName()?>


<?php include_partial('formGroup', array('form' => $form,'groups'=>$groups,'sequences'=>$sequences)) ?>

<br />   <br />  
  <a href="<?php echo url_for('presence/index') ?>">vers la saisie des pr&eacute;sences</a>