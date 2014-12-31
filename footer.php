    <div data-role="navbar" data-iconpos="left">
        <ul>
            <?php
				if($_SESSION['Clearance']==1){ ?>
			<li><a href="#trash" data-icon="protocollo-trash">Cestino</a></li>
			<li><a href="#email-out" data-icon="protocollo-inviate">Email in uscita</a></li>
			<li><a href="#autorize-mail" data-icon="protocollo-protocollare">Email in uscita da autorizzare</a></li>
			<?php } ?>
            <li><a href="#new_mail" data-icon="protocollo-nuova">Nuova Email</a></li>
            <li><a href="logout.php" data-icon="protocollo-logout" rel="external">Logout</a></li>
        </ul>
	</div>