<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('listpresence/display') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
    <tfoot>
      <tr>
      <tr>
        <td colspan="4">
          <input type="submit" value="Choix des séquences et groupes" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     <tr>
        <th><?php echo $form['group']->renderLabel() ?></th>
        <td>
          <?php echo $form['group']->render(array('size'=>count($groups))) ?>
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
