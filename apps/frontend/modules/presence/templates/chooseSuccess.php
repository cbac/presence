


<h1>
	CSC4002 saisie des pr&eacute;sences des &eacute;l&egrave;ves <br />
	<?php
	if(count($gids)){
		if(count($gids) == 1){
			echo ' groupe ' . $listGroups;
		}else {
			echo ' groupes ' . $listGroups;
		}
	}


	?>
	<br />
	<?php
	if(count($seqids)){
		if(count($seqids) == 1){
			echo '&agrave; la s&eacute;quence ' . $listSeqs;
		}else {
			echo 'aux s&eacute;quences ' . $listSeqs;
		}
	}

	?>

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
	<form action="<?php echo url_for('presence/enter') ?>"
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
		$lineCount = 0;
		if(count($gids)){
			$curGid = -1;
			foreach($etudiants as $etudiant){
				if($curGid != -1){
					if($etudiant->getGid() != $curGid) {
						$curGid = $etudiant->getGid();
						// repeat header
						echo '<tr>	<th>Nom</th> <th>Prenom</th> <th>Groupe</th>';
						foreach($seqids as $key => $seqid) {
							echo '<th>'.$sequences[$seqid].'</th>';
						}
						echo '</tr>'	;
					}
				}else{
					$curGid = $etudiant->getGid();
				}
				echo '<tr>';
				$uid = $etudiant->getId();
				echo '<td>'.$etudiant->getLastname().'</td>';
				echo '<td>'.$etudiant->getFirstname().'</td>';
				echo '<td align="center">'.$groupes[$etudiant->getGid()].'</td>';

				foreach($seqids as $seqid){
					if(isset($presences[$uid]) && isset($presences[$uid][$seqid])){
						echo '<td><input type="checkbox" name="presence['.$uid.']['.$seqid.']" checked="checked" /> </td>';
					}else {
						echo '<td><input type="checkbox" name="presence['.$uid.']['.$seqid.']" /> </td>';
					}

				}
				echo '</tr>';
			}
		}else{
			foreach($etudiants as $key => $etudiant){
					
				echo '<tr>';
				echo '<td>'.$etudiant->getLastname().'</td>';
				echo '<td>'.$etudiant->getFirstname().'</td>';
				echo '<td align="center">'.$groupes[$etudiant->getGid()].'</td>';
				$uid = $etudiant->getId();
				foreach($seqids as $seqid){
					if(isset($presences[$uid]) && isset($presences[$uid][$seqid])){
						echo '<td><input type="checkbox" name="presence['.$uid.']['.$seqid.']" checked="checked" /> </td>';
					}else {
						echo '<td><input type="checkbox" name="presence['.$uid.']['.$seqid.']" /> </td>';
					}
				}
				echo '</tr>';
				echo '</tr>';
				$lineCount++;
				if(($lineCount % 30)==0){
					// repeat header
					echo '<tr>	<th>Nom</th> <th>Prenom</th> <th>Groupe</th>';
					foreach($seqids as $seqid) {
						echo '<th>'.$sequences[$seqid].'</th>';
					}
					echo '</tr>'	;
				}
			}
		}

		?>
		</tbody>

</table>

<br>
<a href="<?php echo url_for('presence') ?>">retour</a>
