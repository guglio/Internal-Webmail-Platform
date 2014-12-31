<?php
session_start();
if($_SESSION['status'] == 1){
include('lib/admin/config.php');
	$con = mysqli_connect($url_db,$db_user,$db_pwd,$db_name);
	if (!$con){
		echo ("Errore di connessione al DB");
	}
	else{
?>


<!DOCTYPE html>

<html lang="it">
<head>
    <meta charset="utf-8">

    <title>Webmail C.I.A.M. e S.I.C.A.M.</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="">
    <link href="lib/css/main.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="lib/css/main.css">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    <script src="lib/js/validate_new_mail.js"></script>
    <script src="lib/js/printThis.js"></script>
    <script>
		$(document).ready(function() {
	        $(".button_print").click(function () {
	            var id = $(this).parents('div').filter('.popup_panel').attr('id');
	            $("#"+id).printThis({
		            importCSS: false,
		            loadCSS: "lib/css/print.css",
					pageTitle: "Email in Uscita da autorizzare"
	            });
	        });
	        $(".button_print_sent").click(function () {
	            var id = $(this).parents('div').filter('.popup_panel').attr('id');
	            $("#"+id).printThis({
		            importCSS: false,
		            loadCSS: "lib/css/print.css",
					pageTitle: "Email in Uscita"
	            });
	        });
		    $('#select_all').click(function() {
		    	$('.invia_selected').attr('checked', true);
		        $('.invia_selected').each(function() { this.checked = true; }).checkboxradio("refresh");
		    });
		    $('#select_none').click(function() {
		    	$('.invia_selected').attr('checked', false);
		        $('.invia_selected').each(function() { this.checked = false; }).checkboxradio("refresh");
		    });
		    $('.refresh_mail').click(function() {
		    	location.reload();
			});
		})
    </script>
</head>

<body>
    <div data-role="page" id="new_mail">
            <div data-role="header" data-position="fixed" data-theme="b"  data-id="header">
				<h1>Scrivi nuova Email</h1>
            </div>
            <div data-role="content" class="page_wrapper">
                <form method="post" action="sendmail.php" enctype="multipart/form-data" data-ajax="false" name="new_email" onsubmit="return validate_new_mail()" id="new_email_form">
                	<div data-role="fieldcontain">
                		<label for="from">Da</label>
                			<select name="from" id="from"><?php
		                		$email_array = explode(",", $_SESSION['email']);
		                		for($i=0;$i<count($email_array);$i++){?>
		                		<option value="<?php echo $email_array[$i]; ?>"><?php echo $email_array[$i]; ?></option>
		                		<?php } ?>
	                		</select>
					</div><div data-role="fieldcontain" >
		                <label for="to">A</label><input type="email" id="to" name="to" data-clear-btn="true" required></div><div data-role="fieldcontain" >
		                <label for="cc">CC</label><input type="email" id="cc" name="cc" data-clear-btn="true"></div><div data-role="fieldcontain" >
		                <label for="ccn">CCN</label><input type="email" id="ccn" name="ccn" data-clear-btn="true"></div><div data-role="fieldcontain" >
		                <label for="subject">Oggetto</label><input type="text" id="subject" name="subject" data-clear-btn="true" required>
					</div>
					<div data-role="fieldcontain">
		                <label for="attachment">Allegati</label><input id="attachment" type="file" name="attachment[]" data-clear-btn="true" multiple></div><div data-role="fieldcontain" >
		                <label for="message">Messaggio</label><textarea id="message" name="message"  data-clear-btn="true" required></textarea>
					</div>
	                <button type="submit" data-inline="true">Invia</button>
	                <button type="reset" data-inline="true">Cancella</button>
                </form>
                <a href="#popupError" style="display:none;visibility:hidden;" id="error_popup" data-rel="popup" data-role="button" data-inline="true" data-transition="slideup" data-position-to="window"></a>
                <div data-role="popup" id="popupError">
					<div data-role="header" data-theme="a" class="ui-corner-top">
			        	<h1>Errore durante l'invio della mail</h1>
					</div>
					<div data-role="content" data-theme="d" class="ui-corner-bottom ui-content" id="popupErrorContent">
					</div>
                </div>
            </div>
            <div data-role="footer" data-position="fixed">
			<?php
			include('footer.php');
			?>
            </div>
    </div>
    <?php
if($_SESSION['Clearance']){ ?>
    <div data-role="page" id="autorize-mail">
    	<div data-role="header" data-position="fixed" data-theme="b">
			<h1>Elenco email in uscita da autorizzare</h1>
			<button data-icon="refresh" data-theme="a" data-iconpos="notext" data-iconshadow="false" class="ui-icon-nodisc ui-btn-right refresh_mail">Aggiorna le email</button>
		</div>
		<div data-role="content" class="list-wrapper">
			<ul data-role="listview" data-filter="true" data-split-icon="info" data-split-theme="d">
			<?php
				$result = mysqli_query($con,"SELECT * FROM $email_out_table WHERE Status=0 and Trash=0 ORDER BY ID_mail_out DESC");
				while($row = mysqli_fetch_array($result)){
			?>
				<li>
		            <a href="#<?php echo $row['ID_mail_out'];?>" data-rel="popup" data-transition="pop" data-position-to="window">
		            	<h2><?php echo $row['Da']; ?></h2>
						<p><strong><?php echo $row['Oggetto']; ?></strong></p>
						<p><?php echo substr($row['Messaggio'],0,255); ?></p>
						<p><fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
							<label><input type="checkbox" name="invia_selected[]" class="invia_selected" value="<?php echo $row['ID_mail_out'];?>" form="form_invia">Invia</label><label><input type="checkbox" name="cancel_selected[]" class="cancel_selected" value="<?php echo $row['ID_mail_out'];?>" form="form_invia">Cancella</label>
					</fieldset></p>
					<span style="visibility:hidden;display:none"><?php echo $row['A']; echo $row['Messaggio']; ?></span>
		            <p class="ui-li-aside"><strong><?php echo date('d/m/Y H:i', strtotime($row['Data'])); ?></strong></p></a>
		            <?php
						if($row['Allegati']!=''){ ?>
							<a href="#attachment-<?php echo $row['ID_mail_out'];?>" data-rel="popup" data-transition="slide" data-theme="e">Allegati</a>
					<?php } ?>
		        </li>
		        <?php
		        }
		        ?>
		    </ul>
	    </div>
    <?php 
    $result = mysqli_query($con,"SELECT * FROM $email_out_table ORDER BY ID_mail_out DESC");
	while($row = mysqli_fetch_array($result)){
	    ?>
		 <div data-role="popup" id="<?php echo $row['ID_mail_out'];?>" class="popup_panel">
		 	<div data-role="header" class="row-header">
		 		<p class="popup-from">Da: <?php echo $row['Da']; echo " &lt; ".$row['NomeCognome']." &gt;"; ?><span class="date_right"><?php echo date('d/m/Y H:i', strtotime($row['Data'])); ?></span></p>
		 		<p class="popup-to">A: <?php echo $row['A']; ?></p>
		 		<?php if($row['CC']!="") {?>
		 		<p class="popup-to">CC: <?php echo $row['CC']; ?></p>
		 		<?php } ?>
		 		<?php if($row['CCN']!="") {?>
		 		<p class="popup-to">CCN: <?php echo $row['CCN']; ?></p>
		 		<?php } ?>
		 		<p class="popup-subject">Oggetto: <?php echo $row['Oggetto']; ?></p>
		 	</div>
		 	<p class="popup-message"><?php echo $row['Messaggio']; ?></p>
		 	<button data-inline="true" class="button_print">Stampa</button> 
		 </div>  
		  <?php
		  if($row['Allegati']!=''){
		  ?>
			  <div data-role="popup" id="attachment-<?php echo $row['ID_mail_out'];?>" class="ui-content" data-theme="e">
			  	<?php 
			  	$allegato = explode($scape_char, $row['Allegati']);
			  	for($i=0;$i<count($allegato);$i++){?>
				  	<p><a href="<?php echo $allegato[$i]; ?>" target="new"><?php echo substr($allegato[$i],strlen("http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$row['ID_mail_out']."/"),strlen($allegato[$i])-strlen("http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$row['ID_mail_out']."/")); ?></a></p>	
			  		<?php
			  		}
			  	?>
			  </div>	
		 <?php   
	    }}
    ?>

    <div data-role="footer" data-position="fixed">
		<form id="form_invia" method="post" action="mail.php" data-ajax="false">
			<input type="submit" value="Invia/cancella email selezionate" data-inline="true" data-theme="c" data-icon="check">
        </form>
    	<fieldset data-role="controlgroup" data-type="horizontal" class="ui-btn-right"> 
			<button id="select_none" data-theme="c" data-icon="delete">Reset</button>
			<button id="select_all"  data-theme="c" data-icon="grid" data-iconpos="right">Select All</button>
		</fieldset>
	    <?php
			include('footer.php');
			?>
	</div>
</div>

<div data-role="page" id="email-out">
	<div data-role="header" data-position="fixed" data-theme="b">
		<h1>Email in uscita</h1>
    <button data-icon="refresh" data-theme="a" data-iconpos="notext" data-iconshadow="false" class="ui-icon-nodisc ui-btn-right refresh_mail">Aggiorna le email</button>
	</div>
	<div data-role="content" class="list-wrapper">
			<ul data-role="listview" data-filter="true" data-role="header" data-split-icon="info" data-split-theme="d">
			<?php
				$result = mysqli_query($con,"SELECT * FROM $email_out_table WHERE Status=1 ORDER BY Autorizzata_il DESC");
				while($row = mysqli_fetch_array($result)){
			?>
				<li>
		            <a href="#sent-<?php echo $row['ID_mail_out'];?>" data-rel="popup" data-transition="pop" data-position-to="window">
		            	<h2><?php echo $row['Da']; ?></h2>
						<p><strong><?php echo $row['Oggetto']; ?></strong></p>
						<p><?php echo $row['Messaggio']; ?></p>
					<span style="visibility:hidden;display:none"><?php echo $row['A']; ?></span>
		            <p class="ui-li-aside"><strong>Autorizzata il <?php echo date('d/m/Y H:i', strtotime($row['Autorizzata_il'])); ?></strong><br>Da <?php echo $row['Autorizzata_da']?></p></a>
		            <?php
						if($row['Allegati']!=''){ ?>
							<a href="#attachment-<?php echo $row['ID_mail_out'];?>" data-rel="popup" data-transition="slide" data-theme="e">Allegati</a>
					<?php } ?>
		        </li>
		        <?php
		        }
		        ?>
		    </ul>
	    </div>
    <?php 
    $result = mysqli_query($con,"SELECT * FROM $email_out_table WHERE Status=1 ORDER BY Autorizzata_il DESC");
	while($row = mysqli_fetch_array($result)){
	    ?>
		 <div data-role="popup" id="sent-<?php echo $row['ID_mail_out'];?>" class="popup_panel_sent">
		 	<div data-role="header" class="row-header">
		 		<p class="popup-from">Da: <?php echo $row['Da']; echo " &lt; ".$row['NomeCognome']." &gt;"; ?><span class="date_right">Inviata il <?php echo date('d/m/Y H:i', strtotime($row['Data'])); ?><br>Autorizzata il <?php echo date('d/m/Y H:i', strtotime($row['Autorizzata_il'])); ?><br>Da <?php echo $row['Autorizzata_da']?></span></p>
		 		<p class="popup-to">A: <?php echo $row['A']; ?></p>
		 		<?php if($row['CC']!="") {?>
		 		<p class="popup-to">CC: <?php echo $row['CC']; ?></p>
		 		<?php } ?>
		 		<?php if($row['CCN']!="") {?>
		 		<p class="popup-to">CCN: <?php echo $row['CCN']; ?></p>
		 		<?php } ?>
		 		<p class="popup-subject">Oggetto: <?php echo $row['Oggetto']; ?></p>
		 	</div>
		 	<p class="popup-message"><?php echo $row['Messaggio']; ?></p>
		 	<button data-inline="true" class="button_print_sent">Stampa</button> 
		 </div>  
		  <?php
		  if($row['Allegati']!=''){
		  ?>
			  <div data-role="popup" id="attachment-<?php echo $row['ID_mail_out'];?>" class="ui-content" data-theme="e">
			  	<?php 
			  	$allegato = explode($scape_char, $row['Allegati']);
			  	for($i=0;$i<count($allegato);$i++){?>
				  	<p><a href="<?php echo $allegato[$i]; ?>" target="new"><?php echo substr($allegato[$i],strlen("http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$row['ID_mail_out']."/"),strlen($allegato[$i])-strlen("http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$row['ID_mail_out']."/")); ?></a></p>	
			  		<?php
			  		}
			  	?>
			  </div>	
		 <?php   
	    }}
    ?>

	<div data-role="footer" data-position="fixed">
	    <?php
			include('footer.php');
			?>
	</div>
</div>
<div data-role="page" id="trash">
	<div data-role="header" data-position="fixed" data-theme="b">
		<h1>Cestino</h1>
    <button data-icon="refresh" data-theme="a" data-iconpos="notext" data-iconshadow="false" class="ui-icon-nodisc ui-btn-right refresh_mail">Aggiorna le email</button>
	</div>
	<div data-role="content" class="list-wrapper">
			<ul data-role="listview" data-filter="true" data-role="header" data-split-icon="info" data-split-theme="d">
			<?php
				$result = mysqli_query($con,"SELECT * FROM $email_out_table WHERE Trash=1 ORDER BY ID_mail_out DESC");
				while($row = mysqli_fetch_array($result)){
			?>
				<li>
		            <a href="#sent-<?php echo $row['ID_mail_out'];?>" data-rel="popup" data-transition="pop" data-position-to="window">
		            	<h2><?php echo $row['Da']; ?></h2>
						<p><strong><?php echo $row['Oggetto']; ?></strong></p>
						<p><?php echo $row['Messaggio']; ?></p>
					<span style="visibility:hidden;display:none"><?php echo $row['A']; ?></span>
		            <p class="ui-li-aside"><strong>Cancellata il <?php echo date('d/m/Y H:i', strtotime($row['Autorizzata_il'])); ?></strong><br>Da <?php echo $row['Autorizzata_da']?></p></a>
		            <?php
						if($row['Allegati']!=''){ ?>
							<a href="#attachment-<?php echo $row['ID_mail_out'];?>" data-rel="popup" data-transition="slide" data-theme="e">Allegati</a>
					<?php } ?>
		        </li>
		        <?php
		        }
		        ?>
		    </ul>
	    </div>
    <?php 
    $result = mysqli_query($con,"SELECT * FROM $email_out_table WHERE Trash=1 ORDER BY ID_mail_out DESC");
	while($row = mysqli_fetch_array($result)){
	    ?>
		 <div data-role="popup" id="sent-<?php echo $row['ID_mail_out'];?>" class="popup_panel_sent">
		 	<div data-role="header" class="row-header">
		 		<p class="popup-from">Da: <?php echo $row['Da']; echo " &lt; ".$row['NomeCognome']." &gt;"; ?><span class="date_right">Inviata il <?php echo date('d/m/Y H:i', strtotime($row['Data'])); ?><br>Cancellata il <?php echo date('d/m/Y H:i', strtotime($row['Autorizzata_il'])); ?><br>Da <?php echo $row['Autorizzata_da']?></span></p>
		 		<p class="popup-to">A: <?php echo $row['A']; ?></p>
		 		<?php if($row['CC']!="") {?>
		 		<p class="popup-to">CC: <?php echo $row['CC']; ?></p>
		 		<?php } ?>
		 		<?php if($row['CCN']!="") {?>
		 		<p class="popup-to">CCN: <?php echo $row['CCN']; ?></p>
		 		<?php } ?>
		 		<p class="popup-subject">Oggetto: <?php echo $row['Oggetto']; ?></p>
		 	</div>
		 	<p class="popup-message"><?php echo $row['Messaggio']; ?></p>
		 	<button data-inline="true" class="button_print_sent">Stampa</button> 
		 </div>  
		  <?php
		  if($row['Allegati']!=''){
		  ?>
			  <div data-role="popup" id="attachment-<?php echo $row['ID_mail_out'];?>" class="ui-content" data-theme="e">
			  	<?php 
			  	$allegato = explode($scape_char, $row['Allegati']);
			  	for($i=0;$i<count($allegato);$i++){?>
				  	<p><a href="<?php echo $allegato[$i]; ?>" target="new"><?php echo substr($allegato[$i],strlen("http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$row['ID_mail_out']."/"),strlen($allegato[$i])-strlen("http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$row['ID_mail_out']."/")); ?></a></p>	
			  		<?php
			  		}
			  	?>
			  </div>	
		 <?php   
	    }}
    ?>

	<div data-role="footer" data-position="fixed">
	    <?php
			include('footer.php');
			?>
	</div>
</div>
<?php } ?>
</body>
</html>
<?php
}
    mysqli_close($con);
} else {
	session_destroy();
	header("Location: $url_protocollo");
	exit();
}?>