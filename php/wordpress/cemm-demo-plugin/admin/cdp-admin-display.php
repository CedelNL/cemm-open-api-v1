<div class="wrap">
	<h1><span class="dashicons dashicons-cloud"></span> CEMM Open API</h1>

	<p>De CEMM Demo plugin maakt gebruik van de CEMM Open API om informatie van een CEMM in een widget op je website te tonen. <br> De widget geeft een overzicht van  de zon opbrengst van de afgelopen periode. De widget kan geactiveerd worden op de <a href="<?php echo admin_url('widgets.php'); ?>">widgets pagina</a></p>

	<h2><span class="dashicons dashicons-lock"></span> Koppeling maken</h2>

	<p>Koppel een CEMM aan de widget door een API key op te geven en de juiste CEMM uit de lijst te selecteren.</p>

	<form method="post" action="options.php" novalidate="novalidate">

		<?php
	        //Grab all options
	        $options = get_option($this->plugin_name);

	        $api_key 	= (isset($options['api_key'])) ? $options["api_key"] : '';
	        $cemm 		= (isset($options['cemm'])) ? $options["cemm"] : '';
	    ?>

	    <?php
	        settings_fields($this->plugin_name);
	        do_settings_sections($this->plugin_name);
    	?>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="api_key">Open API key</label></th>
					<td>
						<input type="text" id="<?php echo $this->plugin_name; ?>-api_key" name="<?php echo $this->plugin_name; ?>[api_key]" value="<?php echo $api_key; ?>" size="64">
						<p class="description">Ga naar <a href="http://developer.cemm.nl">developer.cemm.nl</a> om een CEMM Open API key aan te vragen.</p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="cemm">CEMM</label></th>
					<td>
						<select id="<?php echo $this->plugin_name; ?>-cemm" disabled name="<?php echo $this->plugin_name; ?>[cemm]" data-value="<?php echo $cemm; ?>"></select>

						<?php if( ! empty($api_key) ){ ?>
							<span class="spinner" style="float: none;"></span>
						<?php } else { ?>
							<p class="description">Voeg een API key toe om een koppeling te maken met de CEMM Open API.</p>
						<?php } ?>
					</td>
				</tr>

			</tbody>
		</table>


	<?php submit_button('Wijzigingen opslaan', 'primary','submit', TRUE); ?>

	</form>

</div>