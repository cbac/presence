


<h1>Pr&eacute;sence des &eacute;l&egrave;ves en CSC4002</h1>

<table border="1">
	<thead>
		<th>Nom</th>
		<th>Prenom</th>
		<th>Groupe</th>
		
		
		
		
		<?php 
			foreach($sequences as $seq) {
				echo '<th>'.$seq.'</th>';
			}		
		?>

	</thead>
	<form action="<?php echo url_for('presence/enterAlpha') ?>"
		method="post" enctype="multipart/form-data">

		<tfoot>
			<tr>
				<td align="center" colspan="3"><input type="submit" value="Modifier" />
				</td>
			</tr>
		</tfoot>
		<tbody>
			
			

		<?php
		$lineCount = 0;
		foreach($etudiants as $key => $etudiant){

			echo '<tr>';
			echo '<td>'.$etudiant->getLastname().'</td>';
			echo '<td>'.$etudiant->getFirstname().'</td>';
			echo '<td align="center">'.$etudiant->getGroupe().'</td>';
			$uid = $etudiant->getId();
			foreach($sequences as $seq){
				$seqid = $seq->getId();
				if(isset($presences[$uid]) && isset($presences[$uid][$seqid])){
					if(isset($added[$uid]) && isset($added[$uid][$seqid])){
						echo '<td bgcolor="#33FF33">';
					}else{
						echo '<td>';
					}
					echo '<input type="checkbox" name="presence['.$uid.']['.$seqid.']" checked="checked" /> </td>';
				}else {
					if(isset($removed[$uid]) && isset($removed[$uid][$seqid])){
						echo '<td bgcolor="#FFFF33">';
					}else{
						echo '<td>';
					}
					echo '<input type="checkbox" name="presence['.$uid.']['.$seqid.']" /> </td>';
				}
			}
			echo '</tr>';
			echo '</tr>';
			$lineCount++;
			if(($lineCount % 30)==0){
				// repeat header
				echo '<tr>	<th>Nom</th> <th>Prenom</th> <th>Groupe</th>';
				foreach($sequences as $seq) {
					echo '<th>'.$seq.'</th>';
				}
				echo '</tr>'	;
			}
		}
		?>
		</tbody>

</table>

<br />
<br />
<a href="<?php echo url_for('presence') ?>">retour au menu saisie</a>
<br />
<br />
<a href="<?php echo url_for('listepresence/index') ?>">vers la liste des
	présences</a>
