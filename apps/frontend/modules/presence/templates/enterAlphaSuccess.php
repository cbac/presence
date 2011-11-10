


<h1>
	Présence modifiée des élèves en CSC4002
</h1>

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
				<td align="center" colspan="3"><input type="submit"
					value="Sauvegarder" />
				</td>
			</tr>
		</tfoot>
		<tbody>

		<?php

		foreach($etudiants as $key => $etudiant){

			echo '<tr>';
			echo '<td>'.$etudiant->getLastname().'</td>';
			echo '<td>'.$etudiant->getFirstname().'</td>';
			echo '<td align="center">'.$etudiant->getGroupe().'</td>';
			$uid = $etudiant->getId();
			foreach($sequences as $seq){
				$seqid = $seq->getId();
				if(isset($presences[$uid]) && isset($presences[$uid][$seqid])){
					echo '<td><input type="checkbox" name="presence['.$uid.']['.$seqid.']" checked="checked" /> </td>';
				}else {
					echo '<td><input type="checkbox" name="presence['.$uid.']['.$seqid.']" /> </td>';
				}
			}
			echo '</tr>';
		}
		?>
		</tbody>

</table>

<br>
<a href="<?php echo url_for('presence') ?>">retour</a>