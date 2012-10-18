<h1>Sélection des groupes et séquences </h1>
<h1>pour le module <?php echo $moduleens->getName()?></h1>


<?php include_partial('formGroup', array('form' => $form,'groups'=>$groups,'sequences'=>$sequences)) ?>

<br />   <br />  
  <a href="<?php echo url_for('presence/index') ?>">vers la saisie des pr&eacute;sences</a>
  <br />   <br />  
  <a href="<?php echo url_for('choicemodule/index') ?>">vers le choix du module</a>