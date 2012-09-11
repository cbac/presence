<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('choicemodule/choose') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
    <tfoot>
      <tr>
      <tr>
        <td colspan="4">
          <input type="submit" value="Choix du module" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     <tr>
        <th><?php echo $form['modulelist']->renderLabel() ?></th>
        <td>
          <?php echo $form['modulelist']->render(array('size'=>count($modules))) ?>
        </td>
      </tr>
           <tr>
        <th><?php echo $form['listormodify']->renderLabel() ?></th>
        <td>
          <?php echo $form['listormodify']->render() ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
  </table>
</form>