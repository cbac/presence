<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('listepresence/parGroupe') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
    <tfoot>
      <tr>
      <tr>
        <td colspan="4">
          <input type="submit" value="Pr&eacute;sences par s&eacute;quence et groupe" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     <tr>
        <th><?php echo $form['groupe']->renderLabel() ?></th>
        <td>
          <?php echo $form['groupe']->render(array('size'=>count($groupes))) ?>
        </td>
            <th><?php echo $form['sequence']->renderLabel() ?></th>
        <td>
          <?php echo $form['sequence']->render(array('size'=>count($sequences))) ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
  </table>
</form>
