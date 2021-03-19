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
            <th data-field="tipo" data-sortable="true" data-filter-control="select" data-visible="true">Tipo Istanza</th>
            <th data-field="data_istanza" data-sortable="true" data-filter-control="input" data-visible="true">Data Istanza</th>
            <th data-field="terreni" data-sortable="true" data-filter-control="input" data-visible="true">Terreni</th>
            <th data-field="file_s" data-sortable="false" data-formatter="nameFormatterFile1" data-visible="true">Diritti Istruttori</th>
            <th data-field="file_bi" data-sortable="false" data-formatter="nameFormatterFile2" data-visible="true">Bollo Istanza</th>
            <th data-field="n_bolli" data-sortable="false" data-filter-control="input" data-visible="true">N. Bolli CDU</th>
            <th data-field="file_bc" data-sortable="false" data-formatter="nameFormatterFile3" data-visible="true">Bollo CDU</th>
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
	if (row.tipo == 'Visura'){
    if (row.file_s != null){
      return' <a id="myLink" type="button" class="btn btn-info" href="invia_istanza.php?idi='+row.id_istanza+'&idu=<?php echo $usr_id; ?>"><i class="fas fa-play-circle"></i></a>';
    }else{
      return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
    }
  }else{
    if (row.file_s == null || row.file_bi == null || row.file_bc == null){
      return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
    }else{
      if (row.inviato != 't'){
        return' <a id="myLink" type="button" class="btn btn-info" href="invia_istanza.php?idi='+row.id_istanza+'&idu=<?php echo $usr_id; ?>"><i class="fas fa-play-circle"></i></a>';
      }else{
        return' <a id="myLink" type="button" class="btn btn-info" style="background-color: lightgrey; border-color: lightgrey;"><i class="fas fa-play-circle"></i></a>';
      }
    }
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

// funzione per visualizzare/caricare autocertificazione diritti istruttori apre modal che richiama upload.php
function nameFormatterFile1(value, row) {
	//var test_id= row.id;
	//return' <a type="button" class="btn btn-info" href="remove_ist.php?idi='+row.id_istanza+'"><i class="fas fa-file-upload"></i></a>'
	if (row.file_s == null){
	return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal'+row.id_istanza+'" title="Carica pagamento diritti segreteria"><i class="fas fa-file-upload"></i></button>\
    <div class="modal fade" id="myModal'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
  <div class="modal-dialog modal-dialog-centered" role="document">\
    <div class="modal-content">\
      <div class="modal-header">\
        <h5 class="modal-title" id="exampleModalLabel'+row.id_istanza+'">Diritti di Segreteria</h5>\
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
          <span aria-hidden="true">&times;</span>\
        </button>\
      </div>\
      <div class="modal-body">\
	  <form action="upload.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
	  <div class="form-group">\
  		Seleziona la ricevuta di pagamento:<br><br>\
      <input type="hidden" name="user" id="user'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
  		<input type="file" name="fileToUpload" id="fileToUpload'+row.id_istanza+'" required><br><br>\
      <label>Estremi versamento</label>\
      <input type="text" name="estremi_s" id="estremi_s'+row.id_istanza+'" required><br><br>\
  		<input type="submit" value="Invia" name="submitfile">\
		  </div>\
		</form>\
      </div>\
      <div class="modal-footer">\
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
        <!--button type="button" class="btn btn-primary">Save changes</button-->\
      </div>\
    </div>\
  </div>\
</div>' ;
} else{
  if (row.inviato != 't'){
    return' <span><a href="../isernia_upload/segreteria/'+ row.file_s.split("/").pop() +'" target="_blank">Vedi file</a></span><br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal'+row.id_istanza+'" title="Modifica file pagamento diritti segreteria"><i class="fas fa-file-upload"></i></button>\
      <div class="modal fade" id="myModal'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
    <div class="modal-dialog modal-dialog-centered" role="document">\
      <div class="modal-content">\
        <div class="modal-header">\
          <h5 class="modal-title" id="exampleModalLabel'+row.id_istanza+'">Diritti di Segreteria</h5>\
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
            <span aria-hidden="true">&times;</span>\
          </button>\
        </div>\
        <div class="modal-body">\
      <form action="upload.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
      <div class="form-group">\
        Seleziona la ricevuta di pagamento:<br><br>\
        <input type="hidden" name="user" id="user'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
        <input type="file" name="fileToUpload" id="fileToUpload'+row.id_istanza+'" required><br><br>\
        <label>Estremi versamento</label>\
        <input type="text" name="estremi_s" id="estremi_s'+row.id_istanza+'" required><br><br>\
  		  <input type="submit" value="Invia" name="submitfile">\
        </div>\
      </form>\
        </div>\
        <div class="modal-footer">\
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
          <!--button type="button" class="btn btn-primary">Save changes</button-->\
        </div>\
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
	if (row.tipo == 'Visura'){
    return' <span> - </span>';
  }else{
    if (row.file_bi == null){
    return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBi'+row.id_istanza+'" title="Carica pagamento Bollo per l\'Istanza"><i class="fas fa-file-upload"></i></button>\
      <div class="modal fade" id="myModalBi'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
    <div class="modal-dialog modal-dialog-centered" role="document">\
      <div class="modal-content">\
        <div class="modal-header">\
          <h5 class="modal-title" id="exampleModalLabelBi'+row.id_istanza+'">Bollo Istanza</h5>\
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
            <span aria-hidden="true">&times;</span>\
          </button>\
        </div>\
        <div class="modal-body">\
      <form action="upload_bi.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
      <div class="form-group">\
        Seleziona la ricevuta di pagamento:<br><br>\
        <input type="hidden" name="userBi" id="userBi'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
        <input type="file" name="fileToUploadBi" id="fileToUploadBi'+row.id_istanza+'" required><br><br>\
        <label>Identificativo bollo (14 cifre)</label>\
        <input type="text" name="estremi_bi" id="estremi_bi'+row.id_istanza+'" minlength="14" maxlength="14" required><br><br>\
        <input type="submit" value="Invia" name="submitfile">\
        </div>\
      </form>\
        </div>\
        <div class="modal-footer">\
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
          <!--button type="button" class="btn btn-primary">Save changes</button-->\
        </div>\
      </div>\
    </div>\
  </div>' ;
  } else{
    if (row.inviato != 't'){
      return' <span><a href="../isernia_upload/bollo_istanza/'+ row.file_bi.split("/").pop() +'" target="_blank">Vedi file</a></span><br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBi'+row.id_istanza+'" title="Modifica file pagamento Bollo per l\'Istanza"><i class="fas fa-file-upload"></i></button>\
        <div class="modal fade" id="myModalBi'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" id="exampleModalLabelBi'+row.id_istanza+'">Bollo Istanza</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
        <form action="upload_bi.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
        <div class="form-group">\
          Seleziona la ricevuta di pagamento:<br><br>\
          <input type="hidden" name="userBi" id="userBi'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
          <input type="file" name="fileToUploadBi" id="fileToUploadBi'+row.id_istanza+'" required><br><br>\
          <label>Identificativo bollo (14 cifre)</label>\
          <input type="text" name="estremi_bi" id="estremi_bi'+row.id_istanza+'" minlength="14" maxlength="14" required><br><br>\
          <input type="submit" value="Invia" name="submitfile">\
          </div>\
        </form>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div>\
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
	if (row.tipo == 'Visura'){
    return' <span> - </span>';
  }else{
    if (row.file_bc == null){
      return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBc'+row.id_istanza+'" title="Carica pagamento Bollo per CDU"><i class="fas fa-file-upload"></i></button>\
        <div class="modal fade" id="myModalBc'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
      <div class="modal-dialog modal-dialog-centered" role="document">\
        <div class="modal-content">\
          <div class="modal-header">\
            <h5 class="modal-title" id="exampleModalLabelBc'+row.id_istanza+'">Bollo CDU</h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
              <span aria-hidden="true">&times;</span>\
            </button>\
          </div>\
          <div class="modal-body">\
        <form action="upload_bc.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
        <div class="form-group">\
          Seleziona la ricevuta di pagamento:<br><br>\
          <input type="hidden" name="userBc" id="userBc'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
          <input type="file" name="fileToUploadBc" id="fileToUploadBc'+row.id_istanza+'" required><br><br>\
          <label>Identificativo bollo (14 cifre)</label>\
          <input type="text" name="estremi_bc" id="estremi_bc'+row.id_istanza+'" minlength="14" maxlength="14" required><br><br>\
          <input type="submit" value="Invia" name="submitfile">\
          </div>\
        </form>\
          </div>\
          <div class="modal-footer">\
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
            <!--button type="button" class="btn btn-primary">Save changes</button-->\
          </div>\
        </div>\
      </div>\
    </div>' ;
    } else{
      if (row.inviato != 't'){
        return' <span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc.split("/").pop() +'" target="_blank">Vedi file</a></span><br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBc'+row.id_istanza+'" title="Modifica file pagamento Bollo per CDU"><i class="fas fa-file-upload"></i></button>\
          <div class="modal fade" id="myModalBc'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
        <div class="modal-dialog modal-dialog-centered" role="document">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h5 class="modal-title" id="exampleModalLabelBc'+row.id_istanza+'">Bollo CDU</h5>\
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
              </button>\
            </div>\
            <div class="modal-body">\
          <form action="upload_bc.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
          <div class="form-group">\
            Seleziona la ricevuta di pagamento:<br><br>\
            <input type="hidden" name="userBc" id="userBc'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
            <input type="file" name="fileToUploadBc" id="fileToUploadBc'+row.id_istanza+'" required><br><br>\
            <label>Identificativo bollo (14 cifre)</label>\
            <input type="text" name="estremi_bc" id="estremi_bc'+row.id_istanza+'" minlength="14" maxlength="14" required><br><br>\
            <input type="submit" value="Invia" name="submitfile">\
            </div>\
          </form>\
            </div>\
            <div class="modal-footer">\
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
              <!--button type="button" class="btn btn-primary">Save changes</button-->\
            </div>\
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
	if (row.tipo == 'Visura'){
    return' <span> - </span>';
  }else{
    if(row.n_bolli != 1 && row.inviato == 't'){
      if (row.file_bc_integr == null){
        return' <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBci'+row.id_istanza+'" title="Carica pagamento Bollo per CDU"><i class="fas fa-file-upload"></i></button>\
          <div class="modal fade" id="myModalBci'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
        <div class="modal-dialog modal-dialog-centered" role="document">\
          <div class="modal-content">\
            <div class="modal-header">\
              <h5 class="modal-title" id="exampleModalLabelBci'+row.id_istanza+'">Bollo CDU</h5>\
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
              </button>\
            </div>\
            <div class="modal-body">\
          <form action="upload_bci.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
          <div class="form-group">\
            Seleziona la ricevuta di pagamento:<br><br>\
            <input type="hidden" name="userBci" id="userBci'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
            <input type="file" name="fileToUploadBci" id="fileToUploadBci'+row.id_istanza+'" required><br><br>\
            <label>Identificativo bollo</label>\
            <input type="text" name="estremi_bci" id="estremi_bci'+row.id_istanza+'" required><br><br>\
            <input type="submit" value="Invia" name="submitfile">\
            </div>\
          </form>\
            </div>\
            <div class="modal-footer">\
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
              <!--button type="button" class="btn btn-primary">Save changes</button-->\
            </div>\
          </div>\
        </div>\
      </div>' ;
      } else{
        if (row.terminato != 't'){
          return' <span><a href="../isernia_upload/bollo_cdu/'+ row.file_bc_integr.split("/").pop() +'" target="_blank">Vedi file</a></span><br><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalBci'+row.id_istanza+'" title="Modifica file pagamento Bollo per CDU"><i class="fas fa-file-upload"></i></button>\
            <div class="modal fade" id="myModalBci'+row.id_istanza+'" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
          <div class="modal-dialog modal-dialog-centered" role="document">\
            <div class="modal-content">\
              <div class="modal-header">\
                <h5 class="modal-title" id="exampleModalLabelBci'+row.id_istanza+'">Bollo CDU</h5>\
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                  <span aria-hidden="true">&times;</span>\
                </button>\
              </div>\
              <div class="modal-body">\
            <form action="upload_bci.php?idi='+row.id_istanza+'" method="post" enctype="multipart/form-data">\
            <div class="form-group">\
              Seleziona la ricevuta di pagamento:<br><br>\
              <input type="hidden" name="userBci" id="userBci'+row.id_istanza+'" value="<?php echo $_SESSION['user']; ?>">\
              <input type="file" name="fileToUploadBci" id="fileToUploadBci'+row.id_istanza+'" required><br><br>\
              <label>Identificativo bollo</label>\
              <input type="text" name="estremi_bci" id="estremi_bci'+row.id_istanza+'" required><br><br>\
              <input type="submit" value="Invia" name="submitfile">\
              </div>\
            </form>\
              </div>\
              <div class="modal-footer">\
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
                <!--button type="button" class="btn btn-primary">Save changes</button-->\
              </div>\
            </div>\
          </div>\
        </div>' ;
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