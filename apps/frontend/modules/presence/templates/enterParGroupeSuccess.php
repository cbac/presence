


<h1>
	Présence modifié des élèves groupe
	<?php echo $listGroups ?>
	<br /> à la seance
	<?php echo $listSeqs ?>
	CSC4002
</h1>

<table border="1">
	<thead>
		<tr>

			<th>Nom</th>
			<th>Prenom</th>
			<th>Groupe</th>
			
			
			
			
		<?php 
		foreach($seqids as $key => $seqid) {
			echo '<th>'.$sequences[$seqid].'</th>';
		}
		?>


	</tr>
	</thead>
	<form action="<?php echo url_for('presence/enterParGroupe') ?>"
		method="post" enctype="multipart/form-data">

		<tfoot>
			<tr>
				<td align="center" colspan="3"><input type="submit" value="Modifier" />
				</td>
			</tr>
		</tfoot>
		<tbody>
			
			

		<?php
		foreach($gids as $key => $gid) {
			echo '<input type="hidden" name="gids['.$key.']" value="'.$gid.'" />';
		}
		foreach($seqids as $key => $seqid) {
			echo '<input type="hidden" name="seqids['.$key.']" value="'.$seqid.'" />';
		}
		$curGid = -1;
		foreach($etudiants as $etudiant){
			if($curGid != -1){
				if($etudiant->getGroupe()->getId() != $curGid) {
					$curGid = $etudiant->getGroupe()->getId();
					// repeat header
					echo '<tr>	<th>Nom</th> <th>Prenom</th> <th>Groupe</th>';
					foreach($seqids as $key => $seqid) {
						echo '<th>'.$sequences[$seqid].'</th>';
					}
					echo '</tr>'	;
				}
			}else{
				$curGid = $etudiant->getGroupe()->getId();
			}
			echo '<tr>';
			$uid = $etudiant->getId();
			echo '<td>'.$etudiant->getLastname().'</td>';
			echo '<td>'.$etudiant->getFirstname().'</td>';
			echo '<td align="center">'.$etudiant->getGroupe().'</td>';

			foreach($seqids as $seqid){
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
