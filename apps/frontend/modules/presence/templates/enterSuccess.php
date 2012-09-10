


<h1>
	Pr&eacute;sence du module CSC4002 des &eacute;l&egrave;ves
	<?php
	if(count($gids)){
		if(count($gids) == 1){
			echo ' groupe ' . $listGroups;
		}else {
			echo ' groupes ' . $listGroups;
		}
	}

	if(count($seqids)){
		if(count($seqids) == 1){
			echo '<br /> &agrave; la s&eacute;ance ' . $listSeqs;
		}else {
			echo '<br />aux s&eacute;quences ' . $listSeqs;
		}
	}
	?>
	<br />
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
	<form action="<?php echo url_for('presence/enter') ?>" method="post"
		enctype="multipart/form-data">

		<tfoot>
			<tr>
				<td align="center" colspan="3"><input type="submit" value="Modifier" />
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$curGid = -1; $nblines=0;
			foreach($etudiants as $etudiant){
				if(count($gids)>0){
					if($curGid != -1){
						if($etudiant->getGid() != $curGid) {
							// repeat header
							echo '<tr>	<th>Nom</th> <th>Prenom</th> <th>Groupe</th>';
							foreach($seqids as $key => $seqid) {
								echo '<th>'.$sequences[$seqid].'</th>';
							}
							echo '</tr>'	;
						}
					}
					$curGid = $etudiant->getGid();
				}else{
					$nblines++;
					if(($nblines % 30) == 0){
						echo '<tr>	<th>Nom</th> <th>Prenom</th> <th>Groupe</th>';
						foreach($seqids as $key => $seqid) {
							echo '<th>'.$sequences[$seqid].'</th>';
						}
						echo '</tr>';
					}
				}
				echo '<tr>';
				$uid = $etudiant->getId();
				echo '<td>'.$etudiant->getLastname().'</td>';
				echo '<td>'.$etudiant->getFirstname().'</td>';
				echo '<td align="center">'.$groupes[$etudiant->getGid()].'</td>';

				foreach($seqids as $seqid){
					$td = '<td>';
					$tdcontent = '&nbsp;';
					if(isset($presences[$uid]) && isset($presences[$uid][$seqid])){
						if(isset($added[$uid]) && isset($added[$uid][$seqid])){
							//  has been added => green color
							$td = '<td bgcolor="#33FF33">';
						}else {
							if(isset($removed[$uid]) && isset($removed[$uid][$seqid])){
								//has been removed => orange
								$td = '<td bgcolor="#FFFF33">';
							}
							if($sequences[$seqid]->getNote()){
								// this sequence requires a mark
								$tdcontent = $presences[$uid][$seqid]->getNote();
							} else {
								if($presences[$uid][$seqid]->getNote() != 0){
									$tdcontent = 'X';
								}
							}
						}
					}
					echo $td.$tdcontent.'</td>';
				}
				echo '</tr>';
			}
			?>
		</tbody>

</table>

<br />
<br />
<a href="<?php echo url_for('presence/index') ?>">retour au menu saisie</a>
<br />
<br />
<a href="<?php echo url_for('listpresence/index') ?>">vers la liste des
	présences</a>
