<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('presence/choose') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <table>
    <tfoot>
      <tr>
      <tr>
        <td colspan="2" align="center">
          <input type="submit" value="Saisir" />
        </td>
      </tr>
    </tfoot>
    <tbody>
     <tr>
        <th><?php echo $form['group']->renderLabel() ?></th>

            <th><?php echo $form['sequence']->renderLabel() ?></th>

      </tr>
      <tr>
              <td>
          <?php echo $form['group']->render(array('size'=>count($groups))) ?>
        </td>
                <td>
          <?php echo $form['sequence']->render(array('size'=>count($sequences))) ?>
        </td>
        </tr>
    <tbody>
  </table>
</form>
