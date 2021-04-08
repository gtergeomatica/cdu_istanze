<?php

?>
<div>
	<h2>Dati utente</h2>
<?php
  //query sul DB per recuperare dati utente e stamparli nella dasboard
  //session_start();
	$query = "SELECT * FROM utenti.utenti where  usr_login=$1";
	$result = pg_prepare($conn_isernia, "myquery0", $query);
    $result = pg_execute($conn_isernia, "myquery0", array($_SESSION['user']));
	//echo $query;
	//exit;
	//$rows = array();
	while($r = pg_fetch_assoc($result)) {
    		//$rows[] = $r;
			$usr_id=$r["id"];
      $data_exp=$r["doc_exp"];
			echo "<b>User</b>: ".$r["usr_login"];
			echo "<br><b>Nome e Cognome</b>: ".$r["firstname"]. " " .$r["lastname"];
			echo "<br><b>Codice Fiscale</b>: ".$r["cf"];
			echo "<br><b>Documento identità</b>: ".$r["doc_id"];
      echo "<br><b>Data Scadenza Documento identità</b>: ".$r["doc_exp"];
      //check su scadenza del documento
      if($r["doc_exp"] < date("Y-m-d")){
        echo "<br><br><b style='color: yellow;'>ATTENZIONE il Documento è SCADUTO!<br> Se non modifichi il documento non potrai richiedere il CDU</b><br>";
      }
			echo "<br><b>Indirizzo</b>: ".$r["street"]. " - " .$r["postcode"]. ", " .$r["city"];
			echo "<br><b>E-mail</b>: ".$r["usr_email"];
			echo "<br><b>Telefono</b>: ".$r["phonenumber"];
	//echo $usr_id;
?>

<!-- link a form per cambiare i dati personali tranne username e pwd richiama modifica_dati.php -->
<br><br><a class="btn btn-light btn-sm" href="modifica_dati.php?u=<?php echo $r["id"]; ?>&user=<?php echo $r["usr_login"]; ?>">Modifica i tuoi dati</a>
<hr class="light">
<!-- link a form per aggiungere istanza richiama form_istanza_cdu.php -->
<div class="mytip" id="mytip">
	<a id="rcdu" class="btn btn-light btn-xl" href="form_istanza_cdu.php?u=<?php echo $r["id"]; ?>&user=<?php echo $r["usr_login"]; ?>">Richiedi CDU/Visura</a>
  <span id="spantip"></span>
</div>
<hr class="light">


</div>
<!-- tabella istanze aggiunte dall'utente griglia_richieste.php per i dati -->
<div>
    <h2> <i class="fas fa-copy" style="color:white;"></i> Istanze di <?php echo $r["usr_login"]; ?></h2>
	<!--div id="toolbar2">
            <select class="form-control">
                <option value="">Esporta i dati visualizzati</option>
                <option value="all">Esporta tutto (lento)</option>
                <option value="selected">Esporta solo selezionati</option>
            </select>
        </div-->
	<div style="overflow-x:auto;">
    <table style="background-color:white;" id="log" class="table-hover" data-toggle="table" data-filter-control="true" 
  data-show-search-clear-button="true" data-page-size="25" 
  data-url="griglia_richieste.php?u=<?php echo $r["id"]; ?>" 
	data-show-export="false" data-search="true" data-click-to-select="true" data-pagination="true" 
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar2" data-locale="it-IT" data-row-style="rowStyle">
<thead>
<!--data-field="colonna DB" data-formatter="nome funzione" -->
 <tr>
            <!--th data-field="state" data-checkbox="true"></th-->
			<th data-field="id" data-sortable="false" data-formatter="nameFormatterRemove" data-visible="true">Rimuovi</th>
			<th data-field="id_istanza" data-sortable="false" data-formatter="nameFormatterSend" data-visible="true">Invia</th>
            <th data-field="tipo" data-sortable="true" data-filter-control="select" data-formatter="nameFormatterTipo" data-visible="true">Tipo Istanza</th>
            <th data-field="data_istanza" data-sortable="true" data-filter-control="input" data-visible="true">Data Istanza</th>
            <th data-field="terreni" data-sortable="true" data-filter-control="input" data-visible="true">Terreni</th>
            <th data-field="file_s" data-sortable="false" data-formatter="nameFormatterFile1" data-visible="true">Diritti Istruttori</th>
            <th data-field="file_bi" data-sortable="false" data-formatter="nameFormatterFile2" data-visible="true">Bollo Istanza</th>
            <th data-field="file_bc" data-sortable="false" data-formatter="nameFormatterFile3" data-visible="true">Bollo CDU</th>
            <th data-field="n_bolli" data-sortable="false" data-filter-control="input" data-visible="true">N. Altri Bolli CDU</th>
            <th data-field="file_bc_integr" data-sortable="false" data-formatter="nameFormatterFile5" data-visible="true">Altri bolli CDU</th>
            <th data-field="file_cdu" data-sortable="false" data-formatter="nameFormatterFile4" data-visible="true">CDU/Visura</th>
        </tr>
</thead>

</table>

</div>	


</div>
<!-- Script con tutte le funzioni richiamate dalla tabella istanze -->
<script>
// funzione sul pulsante invia istanza richiama file invia_istanza.php
function nameFormatterSend(value, row) {
  if (row.inviato != 't'){
    if (row.tipo == 'Visura' || row.motivo == 'Successione ereditaria' || row.motivo == 'Esproprio'){
      if (row.file_s != null){
        return' <a id="myLink" type="button" class="btn btn-info" href="invia_istanza.php?idi='+row.id_istanza+'&idu=<?php echo $usr_id; ?>"><i class="fas fa-play-circle"></i></a>';
      }else{
        return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
      }
    }else{
        if (row.file_s == null || row.file_bi == null || row.file_bc == null){
          return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
        }else{
            return' <a id="myLink" type="button" class="btn btn-info" href="invia_istanza.php?idi='+row.id_istanza+'&idu=<?php echo $usr_id; ?>"><i class="fas fa-play-circle"></i></a>';
        }
    }
  }else{
    return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
  }
}

// funzione sul pulsante rimuovi istanza richiama file remove_ist.php
function nameFormatterRemove(value, row) {
	//var test_id= row.id;
	//return' <button type="button" class="btn btn-info" data-target="remove_ist.php?idu='+row.id_istanza+'"><i class="fas fa-trash-alt"></i></button>';
  if (row.inviato != 't'){
	  return' <a type="button" class="btn btn-info" href="remove_ist.php?idi='+row.id_istanza+'"><i class="fas fa-trash-alt"></i></a>';
  }else{
    return' <a type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-trash-alt"></i></a>';
  }
}

// funzione per visualizzare tipo e motivo dell'istanza
function nameFormatterTipo(value, row) {
  if(row.tipo == 'Visura'){
    return row.tipo
  }else{
    if(row.motivo == 'Successione ereditaria' || row.motivo == 'Esproprio'){
      return row.tipo + ' (' + row.motivo + ')';
    }else{
      return row.tipo
    }
  }
}

// funzione per visualizzare/caricare autocertificazione diritti istruttori apre modal che richiama upload.php
function nameFormatterFile1(value, row) {
	//var test_id= row.id;
	//return' <a type="button" class="btn btn-info" href="remove_ist.php?idi='+row.id_istanza+'"><i class="fas fa-file-upload"></i></a>'
	if (row.file_s == null && row.estremi_s == null){
	return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal'+row.id_istanza+'" title="Carica dettagli pagamento diritti segreteria"><i class="fas fa-file-upload"></i></button>\
    <div class="modal fade" id="myModal'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
  <div class="modal-dialog modal-dialog-centered" role="document">\
    <div class="modal-content">\
      <div class="modal-header">\
        <h5 class="modal-title" id="exampleModalLabel'+row.id_istanza+'">Dettagli Pagamento Diritti Istruttori</h5>\
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
          <span aria-hidden="true">&times;</span>\
        </button>\
      </div>\
      <div class="modal-body">\
	  <form action="upload.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
	  <div class="form-group">\
  		Seleziona la ricevuta di pagamento:<br><br>\
  		<input type="file" name="fileToUpload" id="fileToUpload'+row.id_istanza+'" required><br><br>\
      <label>Estremi versamento</label>\
      <input type="text" name="estremi_s" id="estremi_s'+row.id_istanza+'" required><br><br>\
  		<input type="submit" value="Invia" name="submitfile">\
		  </div>\
		</form>\
      </div>\
      <div class="modal-footer">\
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
        <!--button type="button" class="btn btn-primary">Save changes</button-->\
      </div>\
    </div>\
  </div>\
</div>' ;
} else{
  if (row.inviato != 't'){
    return ' <!--span><a href="../isernia_upload/segreteria/'+ row.file_s.split("/").pop() +'" target="_blank">Vedi file</a><a style="margin-left: 10px;" href="remove_s.php?idi='+row.id_istanza+'" title="Rimuovi file pagamento diritti segreteria"><i class="fas fa-trash"></i></a></span><br-->\
    <button style="background-color: #3be23b; border-color: #3be23b;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal'+row.id_istanza+'" title="Visualizza dettagli pagamento diritti segreteria"><i class="fas fa-info-circle"></i></button>\
      <div class="modal fade" id="myModal'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
    <div class="modal-dialog modal-dialog-centered" role="document">\
      <div class="modal-content">\
        <div class="modal-header">\
          <h5 class="modal-title" id="exampleModalLabel'+row.id_istanza+'">Dettagli Pagamento Diritti Istruttori</h5>\
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
            <span aria-hidden="true">&times;</span>\
          </button>\
        </div>\
        <div class="modal-body">\
      <!--form action="upload.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data"-->\
      <div class="form-group">\
      <span>File selezionato: <a href="../isernia_upload/segreteria/'+ row.file_s.split("/").pop() +'" target="_blank">Vedi file</a></span><br><br>\
      <label>Estremi versamento</label>\
      <input type="text" name="estremi_s" id="estremi_s'+row.id_istanza+'" value="'+row.estremi_s+'" readonly><br><br><hr>\
      <span>Per modificare i dettagli di pagamento è necessario rimuoverli.</span><br>\
      <span>Rimuovere i dettagli del pagamento?<br><br>\
      <a type="button" class="btn btn-info" href="remove_s.php?idi='+row.id_istanza+'" title="Rimuovi dettagli pagamento diritti segreteria"><i class="fas fa-trash"></i></a>\
      <a type="button" class="btn btn-info" data-dismiss="modal" title="Torna alla pagina principale">Chiudi</a>\
      </span>\
        <!--input type="file" name="fileToUpload" id="fileToUpload'+row.id_istanza+'"><br><br>\
        <label>Estremi versamento</label>\
        <input type="text" name="estremi_s" id="estremi_s'+row.id_istanza+'" value="'+row.estremi_s+'" required><br><br>\
  		  <input type="submit" value="Invia" name="submitfile">\
        </div>\
      </form>\
        </div>\
        <div class="modal-footer">\
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
          <!--button type="button" class="btn btn-primary">Save changes</button-->\
        </div-->\
      </div>\
    </div>\
  </div>' ;
  }else{
    return' <span><a href="../isernia_upload/segreteria/'+ row.file_s.split("/").pop() +'" target="_blank">Vedi file</a></span>';
  }
}
}

// funzione per visualizzare/caricare autocertificazione bollo istanza apre modal che richiama upload_bi.php
function nameFormatterFile2(value, row) {
	if (row.tipo == 'Visura' || row.motivo == 'Successione ereditaria' || row.motivo == 'Esproprio'){
    return' <span> - </span>';
  }else{
    if (row.file_bi == null){
    return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBi'+row.id_istanza+'" title="Carica dettagli pagamento Bollo per l\'Istanza"><i class="fas fa-file-upload"></i></button>\
      <div class="modal fade" id="myModalBi'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
    <div class="modal-dialog modal-dialog-centered" role="document">\
      <div class="modal-content">\
        <div class="modal-header">\
          <h5 class="modal-title" id="exampleModalLabelBi'+row.id_istanza+'">Dettagli Pagamento Bollo Istanza</h5>\
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
            <span aria-hidden="true">&times;</span>\
          </button>\
        </div>\
        <div class="modal-body">\
      <form action="upload_bi.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
      <div class="form-group">\
        Seleziona la ricevuta di pagamento:<br><br>\
        <input type="file" name="fileToUploadBi" id="fileToUploadBi'+row.id_istanza+'" required><br><br>\
        <label>Identificativo bollo (14 cifre)</label>\
        <input type="text" name="estremi_bi" id="estremi_bi'+row.id_istanza+'" minlength="14" maxlength="14" required><br><br>\
        <input type="submit" value="Invia" name="submitfile">\
        </div>\
      </form>\
        </div>\
        <div class="modal-footer">\
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
          <!--button type="button" class="btn btn-primary">Save changes</button-->\
        </div>\
      </div>\
    </div>\
  </div>' ;
  } else{
    if (row.inviato != 't'){
      return' <!--span><a href="../isernia_upload/bollo_istanza/'+ row.file_bi.split("/").pop() +'" target="_blank">Vedi file</a></span><br!-->\
      <button style="background-color: #3be23b; border-color: #3be23b;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBi'+row.id_istanza+'" title="Visualizza dettagli pagamento Bollo per l\'Istanza"><i class="fas fa-info-circle"></i></button>\
        <div class="modal fade" id="myModalBi'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" id="exampleModalLabelBi'+row.id_istanza+'">Dettagli Pagamento Bollo Istanza</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
        <!--form action="upload_bi.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data"-->\
        <div class="form-group">\
          <span>File selezionato: <a href="../isernia_upload/bollo_istanza/'+ row.file_bi.split("/").pop() +'" target="_blank">Vedi file</a></span><br><br>\
          <label>Numero Identificativo Bollo</label>\
          <input type="text" name="estremi_bi" id="estremi_bi'+row.id_istanza+'" value="'+row.estremi_bi+'" readonly><br><br><hr>\
          <span>Per modificare i dettagli di pagamento è necessario rimuoverli.</span><br>\
          <span>Rimuovere i dettagli del pagamento?<br><br>\
          <a type="button" class="btn btn-info" href="remove_bi.php?idi='+row.id_istanza+'" title="Rimuovi dettagli pagamento bollo istanza"><i class="fas fa-trash"></i></a>\
          <a type="button" class="btn btn-info" data-dismiss="modal" title="Torna alla pagina principale">Chiudi</a>\
          </span>\
          <!--input type="file" name="fileToUploadBi" id="fileToUploadBi'+row.id_istanza+'" required><br><br>\
          <label>Identificativo bollo (14 cifre)</label>\
          <input type="text" name="estremi_bi" id="estremi_bi'+row.id_istanza+'" minlength="14" maxlength="14" required><br><br>\
          <input type="submit" value="Invia" name="submitfile">\
          </div>\
        </form>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div-->\
        </div>\
      </div>\
    </div>' ;
    }else{
      return' <span><a href="../isernia_upload/bollo_istanza/'+ row.file_bi.split("/").pop() +'" target="_blank">Vedi file</a></span>';
    }
  }
}
}

// funzione per visualizzare/caricare autocertificazione bolli cdu apre modal che richiama upload_bc.php
function nameFormatterFile3(value, row) {
	if (row.tipo == 'Visura' || row.motivo == 'Successione ereditaria' || row.motivo == 'Esproprio'){
    return' <span> - </span>';
  }else{
    if (row.file_bc == null){
      return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBc'+row.id_istanza+'" title="Carica dettagli pagamento Bollo per CDU"><i class="fas fa-file-upload"></i></button>\
        <div class="modal fade" id="myModalBc'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" id="exampleModalLabelBc'+row.id_istanza+'">Dettagli Pagamento Bollo CDU</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
        <form action="upload_bc.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
        <div class="form-group">\
          Seleziona la ricevuta di pagamento:<br><br>\
          <input type="file" name="fileToUploadBc" id="fileToUploadBc'+row.id_istanza+'" required><br><br>\
          <label>Identificativo bollo (14 cifre)</label>\
          <input type="text" name="estremi_bc" id="estremi_bc'+row.id_istanza+'" minlength="14" maxlength="14" required><br><br>\
          <input type="submit" value="Invia" name="submitfile">\
          </div>\
        </form>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div>\
        </div>\
      </div>\
    </div>' ;
    } else{
      if (row.inviato != 't'){
        return' <!--span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc.split("/").pop() +'" target="_blank">Vedi file</a></span><br-->\
        <button style="background-color: #3be23b; border-color: #3be23b;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBc'+row.id_istanza+'" title="Visualizza dettagli pagamento Bollo per CDU"><i class="fas fa-info-circle"></i></button>\
          <div class="modal fade" id="myModalBc'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
        <div class="modal-dialog modal-dialog-centered" role="document">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h5 class="modal-title" id="exampleModalLabelBc'+row.id_istanza+'">Dettagli Pagamento Bollo CDU</h5>\
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
              </button>\
            </div>\
            <div class="modal-body">\
          <!--form action="upload_bc.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data"-->\
          <div class="form-group">\
            <span>File selezionato: <a href="../isernia_upload/bollo_cdu/'+ row.file_bc.split("/").pop() +'" target="_blank">Vedi file</a></span><br><br>\
            <input type="text" name="estremi_bc" id="estremi_bc'+row.id_istanza+'" value="'+row.estremi_bc+'" readonly><br><br><hr>\
            <span>Per modificare i dettagli di pagamento è necessario rimuoverli.</span><br>\
            <span>Rimuovere i dettagli del pagamento?<br><br>\
            <a type="button" class="btn btn-info" href="remove_bc.php?idi='+row.id_istanza+'" title="Rimuovi dettagli pagamento bollo CDU"><i class="fas fa-trash"></i></a>\
            <a type="button" class="btn btn-info" data-dismiss="modal" title="Torna alla pagina principale">Chiudi</a>\
            </span>\
            <!--input type="file" name="fileToUploadBc" id="fileToUploadBc'+row.id_istanza+'" required><br><br>\
            <label>Identificativo bollo (14 cifre)</label>\
            <input type="text" name="estremi_bc" id="estremi_bc'+row.id_istanza+'" minlength="14" maxlength="14" required><br><br>\
            <input type="submit" value="Invia" name="submitfile">\
            </div>\
          </form>\
            </div>\
            <div class="modal-footer">\
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
              <!--button type="button" class="btn btn-primary">Save changes</button-->\
            </div-->\
          </div>\
        </div>\
      </div>' ;
      }else{
          return' <span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc.split("/").pop() +'" target="_blank">Vedi file</a></span>';
      }
    }
  }
}

// funzione per visualizzare/caricare autocertificazione bolli cdu integrativi apre modal che richiama upload_bci.php
function nameFormatterFile5(value, row) {
	if (row.tipo == 'Visura' || row.motivo == 'Successione ereditaria' || row.motivo == 'Esproprio'){
    return' <span> - </span>';
  }else{
    if(row.n_bolli != null && row.inviato == 't'){
      if (row.file_bc_integr == null){
        return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBci'+row.id_istanza+'" title="Carica file pagamento Bollo per CDU" onclick="checkVal('+row.id_istanza+')"><i class="fas fa-file-upload"></i></button>\
            <div class="myclass modal fade" id="myModalBci'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
          <div class="modal-dialog modal-dialog-centered" role="document">\
            <div class="modal-content">\
              <div class="modal-header">\
                <h5 class="modal-title" id="exampleModalLabelBci'+row.id_istanza+'">Dettagli pagamento Bolli integrativi CDU</h5>\
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                  <span aria-hidden="true">&times;</span>\
                </button>\
              </div>\
              <div class="modal-body">\
            <!--form id="formBCI'+row.id_istanza+'" action="upload_bci.php?idi='+row.id_istanza+'&nb='+row.n_bolli+'" method="post" enctype="multipart/form-data"-->\
            <form id="formBCI'+row.id_istanza+'" action="upload_bci.php?idi='+row.id_istanza+'&nb='+row.n_bolli+'" method="post" enctype="multipart/form-data">\
            <div class="form-group">\
              Seleziona la ricevuta di pagamento:<br><br>\
              <input type="file" class="form-control" name="fileToUploadBci" id="fileToUploadBci'+row.id_istanza+'" style="height: auto;"><br>\
              <div class="help-block with-errors"></div>\
              </div>\
              <label>Identificativo bollo<br><small>(in caso di più bolli, gli identificativi di 14 cifre devono essere separati da virgola)</small></label>\
              <div class="form-group">\
              <input type="text" class="form-control" name="estremi_bci" id="estremi_bci'+row.id_istanza+'" data-customcheck="'+row.n_bolli+'"data-validate="true" required><br>\
              <div class="help-block with-errors"></div>\
              </div>\
              <div class="form-group">\
              <input type="submit" value="Invia" name="submitfile">\
              </div>\
            </form>\
              </div>\
              <div class="modal-footer">\
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
                <!--button type="button" class="btn btn-primary">Save changes</button-->\
              </div>\
            </div>\
          </div>\
        </div>' ;
      } else{
        if (row.terminato != 't'){
          /* if (row.errore == 1){
            string = '<div class="mytip" id="mytip"><i class="fas fa-exclamation-triangle"></i><span class="tooltiptext">Gli estremi devono essere separati da virgola</span></div>'
          }else if(row.errore == 2){
            string = '<div class="mytip" id="mytip"><i class="fas fa-exclamation-triangle"></i><span class="tooltiptext">Il numero di identificativi inseriti non corrisponde al numero di bolli dovuti</span></div>'
          }else if(row.errore == 3){
            string = '<div class="mytip" id="mytip"><i class="fas fa-exclamation-triangle"></i><span class="tooltiptext">I numeri identificativi devono essere di 14 cifre</span></div>'
          } 
          return string+' <span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc_integr.split("/").pop() +'" target="_blank">Vedi file</a></span><br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBci'+row.id_istanza+'" title="Modifica file pagamento Bollo per CDU" onclick="checkVal('+row.id_istanza+')"><i class="fas fa-file-upload"></i></button>\*/
          return ' <!--span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc_integr.split("/").pop() +'" target="_blank">Vedi file</a></span><br-->\
          <button style="background-color: #3be23b; border-color: #3be23b;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBci'+row.id_istanza+'" title="Modifica dettagli pagamento Bolli integrativi per CDU" onclick="checkVal('+row.id_istanza+')"><i class="fas fa-edit"></i></button>\
            <div class="myclass modal fade" id="myModalBci'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
          <div class="modal-dialog modal-dialog-centered" role="document">\
            <div class="modal-content">\
              <div class="modal-header">\
                <h5 class="modal-title" id="exampleModalLabelBci'+row.id_istanza+'">Dettagli pagamento Bolli integrativi CDU</h5>\
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                  <span aria-hidden="true">&times;</span>\
                </button>\
              </div>\
              <div class="modal-body">\
            <!--form id="formBCI'+row.id_istanza+'" action="upload_bci.php?idi='+row.id_istanza+'&nb='+row.n_bolli+'" method="post" enctype="multipart/form-data"-->\
            <span>File precedentemente caricato: <a href="../isernia_upload/bollo_cdu/'+ row.file_bc_integr.split("/").pop() +'" target="_blank">Vedi file</a></span><br><hr>\
            <span>Vuoi modificare il file caricato?</span>\
            <form id="formBCI'+row.id_istanza+'" action="upload_bci.php?idi='+row.id_istanza+'&nb='+row.n_bolli+'" method="post" enctype="multipart/form-data">\
            <div class="form-group">\
              Seleziona la nuova ricevuta di pagamento:<br><br>\
              <input type="file" class="form-control" name="fileToUploadBci" id="fileToUploadBci'+row.id_istanza+'" style="height: auto;"><br>\
              <div class="help-block with-errors"></div>\
              </div>\
              <span>Vuoi modificare i numeri identificativi dei bolli?</span>\
              <label>Modifica Identificativi bolli<br><small>(in caso di più bolli, gli identificativi di 14 cifre devono essere separati da virgola)</small></label>\
              <div class="form-group">\
              <input type="text" class="form-control" name="estremi_bci" id="estremi_bci'+row.id_istanza+'" data-customcheck="'+row.n_bolli+'"data-validate="true" value="'+row.estremi_bc_integr+'" required><br>\
              <div class="help-block with-errors"></div>\
              </div>\
              <div class="form-group">\
              <input type="submit" value="Invia" name="submitfile">\
              </div>\
            </form>\
              </div>\
              <div class="modal-footer">\
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>\
                <!--button type="button" class="btn btn-primary">Save changes</button-->\
              </div>\
            </div>\
          </div>\
        </div>\
        <!--script>\
            document.getElementById("estremi_bci'+row.id_istanza+'").addEventListener("input", function (e) {\
            var target = e.target, position = target.selectionEnd, length = target.value.length;\
            console.log(target);\
            console.log(position);\
            console.log(length);\
            target.value = target.value.replace(/[^\\da-zA-Z]/g, "").replace(/(.{14})/g, "$1 ").trim();\
            target.selectionEnd = position += ((target.value.charAt(position - 1) === " " && target.value.charAt(length - 1) === " " && length !== target.value.length) ? 1 : 0);\
          });\
        </scr'+'ipt-->' ;
        }else{
            return' <span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc_integr.split("/").pop() +'" target="_blank">Vedi file</a></span>';
        }
      }
  }else{
    return' <span> - </span>';
  }
  }
}

// funzione per visualizzare/scaricare file cdu richiama download_cdu.php
function nameFormatterFile4(value, row) {
  if(row.terminato == 't' && row.file_cdu != null){
    if (row.tipo == 'Visura'){
      return' <span><a href="../isernia_upload/cdu/'+ row.file_cdu.split("/").pop() +'" target="_blank">Vedi visura</a></span><br><a class="btn btn-primary" href="./download_cdu.php?f='+ row.file_cdu.split("/").pop() +'"><i class="fas fa-file-download"></i></a>';
    }else{
      return' <span><a href="../isernia_upload/cdu/'+ row.file_cdu.split("/").pop() +'" target="_blank">Vedi CDU</a></span><br><a class="btn btn-primary" href="./download_cdu.php?f='+ row.file_cdu.split("/").pop() +'"><i class="fas fa-file-download"></i></a>';
    }
  }else{
    return' <span> - </span>';
  }
}

// funzione per evidenziare le istanze a seconda che sia terminata o inviata (viene richiamata nella tabel con data-row-style="rowStyle")
function rowStyle(row, index) {
  //console.log(row.doc_exp)
    if (row.inviato == 't' && row.terminato != 't') {
      return {
        css: {
          'background-color': 'bisque'
        }
      }
    }else if (row.inviato == 't' && row.terminato == 't'){
      return {
        css: {
          'background-color': 'lightgreen'
        }
      }
    }
    return {
      css: {
        'background-color': 'white'
      }
    }
}
</script>


<!-- Script per disattivare il bottone richiedi cdu se documento è scaduto -->
<script>
  var data_exp = "<?php echo $data_exp; ?>";
  //console.log(data_exp);
  var today = new Date().toISOString().substring(0,10);
  //console.log(today);
  if (data_exp < today){
    console.log("documento scaduto");
    $('#rcdu').removeAttr('href');
    $('#rcdu').css('opacity', '0.65');
    $('#spantip').attr('class', 'tooltiptext');
    $('#spantip').html('impossibile richiedere CDU')
  }
</script>
<?php
}
?>