<?php 
header("Content-Type: text/html; charset=$tilim_thetexts[encode]");
header("Content-Language: $tilim_thetexts[lang]");
echo '<?xml version="1.0" encoding="' . $tilim_thetexts['encode'] . '"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $tilim_thetexts['lang']; ?>" lang="<?php echo $tilim_thetexts['lang']; ?>" dir="ltr">
	<head>
		<title><?php echo $tilim_thetexts['pagetitle']; ?></title>
		<meta http-equiv="content-type" content="text/html;charset=<?php echo strtolower($tilim_thetexts['encode']); ?>" />
		<meta http-equiv="content-language" content="<?php echo $tilim_thetexts['lang']; ?>" />
		<meta http-equiv="cache-control" content="no-cache" />
		<script type="text/javascript">
			<!--
			//<![CDATA[
			var on_off = true;
			function tilim_radio(id) {
				var allradios = document.getElementById(id + "_h").value.split('/');
				for (var a = 0; a < allradios.length; ++a) {
					var myradios = allradios[a].split('-');
					for (var b = 0; b < myradios.length; ++b) {
						document.getElementById('born_' + myradios[b]).disabled = a > 0;
					}
				}
			}
			function showhidecap(num) {
				var a = document.getElementById('tilim_' + num).style;
				a.display = a.display == 'none' ? '' : 'none';
			}
			function showhideall() {
				for (num = 0; a = document.getElementById('tilim_' + num); ++num) {
					a.style.display = on_off ? '' : 'none';
				}
				document.getElementById('tilim_btn').value = on_off ? '<?php echo $tilim_thetexts['off']; ?>' : '<?php echo $tilim_thetexts['on']; ?>';
				on_off = !(on_off);
			}
			function newWindowLinks() {
				if (!document.getElementsByTagName) return;
				var anchors = document.getElementsByTagName("a");
				for (var i = 0; i < anchors.length; ++i) {
					var anchor = anchors[i];
					if (anchor.getAttribute("href") && anchor.getAttribute("rel") && anchor.getAttribute("rel").search("xternal")) {
						anchor.target = "_blank";
					}
				}
			}
			function selectInfo(name) {
				document.getElementById('capname').value = name;
				<?php if (empty($tilim_select['cap'])) { ?>
				document.getElementById('cap_born_born_day').click();
				tilim_radio('cap_born_born_day');
				<?php } ?>
				<?php foreach (array('day', 'month', 'year', 'cap') as $variable) if (isset($tilim_select[$variable])) { ?> 
				document.getElementById("born_<?php echo $variable; ?>").value = '<?php echo $tilim_select[$variable]; ?>';
				<?php }	?>
			}
			window.onload = function() {
				newWindowLinks();
				if (document.getElementById('tilim_btn')) {
					showhideall();
					document.getElementById('tilim_btn').onclick = function() { showhideall(); };
				}
				<?php if (isset($tilim_select['name'])) { ?>
				selectInfo('<?php echo $tilim_select['name']; ?>');
				<?php } ?>
			}
			//]]>
			-->
		</script>
		<style type="text/css">
			<!--
			a img { border:0px; }
			-->
		</style>
	</head>
	<body dir="<?php echo $tilim_thetexts['dir']; ?>">
	<?php if (isset($tilim_user['username'])) { ?>
		<p>
			<?php echo $tilim_user['username']; ?> 
			<a href="<?php echo $tilim_thetexts['PHP_SELF']; ?>&amp;logout=1"><?php echo $tilim_thetexts['logout']; ?></a>
		</p>
	<?php } ?>
	<?php if (isset($debuger)) { ?>
		<p>
			<a href="http://<?php echo $debuger . $tilim_thetexts['PHP_SELF']; ?>" rel="external"><?php echo $debuger; ?></a>
		</p>
	<?php } ?>
	<?php foreach($tilim_errors as $variable) { ?>
		<p><?php echo $variable; ?></p>
	<?php } ?>
	<?php if (!empty($tilim_caps)) { ?>
		<div>
			<p>
				<input type='button' id='tilim_btn' name='tilim_btn' />
				<a href='<?php echo $tilim_thetexts['invert_nn'];?>'>
					<?php echo $tilim_thetexts['nikud'][$tilim_nn]; ?>
				</a> 
			</p>
	<?php foreach($tilim_caps as $id => $cap) { ?>
			<p>
				<small>
				<?php foreach (array('id', 'learning') as $variable) if (isset($cap[$variable])) { ?> 
					<?php foreach ($variable == 'id' ? array('edit' => '#add_cap_form', 'moveup' => '', 'del' => '') : array('moveup' => '', 'del' => '') 
					as $type => $anchor) { ?>
					<a href="<?php echo $tilim_thetexts['PHP_SELF'] . '&amp;' . $type . 'cap=' . $cap[$variable] . $anchor; ?>">
						<?php echo $tilim_thetexts[$type . ($variable == 'id' ? 'cap' : $variable)]; ?>
					</a>
					<?php } ?>
				<?php } ?>
				</small>
				<?php echo $cap['name']; ?>
				<big><a href='javascript:showhidecap(<?php echo $id; ?>)'><?php echo $cap['capnum']; ?></a></big>
			</p>
			<p id='tilim_<?php echo $id; ?>' dir='rtl'><?php echo nl2br($cap['cap']); ?></p>
	<?php } ?>
		</div>
	<?php } ?>
	<?php foreach ($tilim_forms as $form) { ?>
		<form action="<?php echo $tilim_thetexts['PHP_SELF']; ?>" id="<?php echo $form['id']; ?>" method="post"><fieldset>
			<legend><?php echo $form['legend']; ?></legend>
				<?php if (isset($form['msg'])) echo '<p>' . $form['msg'] . '</p>'; ?>
				<?php if (isset($form['msgS'])) echo '<p><small>' . $form['msgS'] . '</small></p>'; ?>
				<?php foreach ($form['inputtag'] as $inputtag) { ?>
				<p>
					<?php if (isset($inputtag['label'])) { ?>
					<label for='<?php echo $inputtag['name']; ?>'><?php echo $inputtag['label']; ?></label>
					<?php } ?>
					<input type='<?php echo $inputtag['type']; ?>' id='<?php echo $inputtag['name']; ?>' name='<?php echo $inputtag['name']; ?>' value="<?php echo $inputtag['value']; ?>" <?php echo $inputtag['others']; ?>/>
				</p>
				<?php } ?>
				<?php if (!empty($form['selecttag'])) foreach ($form['selecttag'] as $selecttag) { ?>
					<?php if (!empty($selecttag['open'])) echo '<' . $selecttag['open'] . '>'; ?>
					<?php if (!empty($selecttag['radio']['name'])) { ?> 
					<?php $radio_id = $selecttag['radio']['name'] . '_' . $selecttag['name']; ?>
					<input type="radio" id="<?php echo $radio_id; ?>" name="<?php echo $selecttag['radio']['name']; ?>" onchange="tilim_radio(this.id);"
						title="<?php echo $selecttag['radio']['title']; ?>" value="<?php echo $selecttag['radio']['value']; ?>" <?php echo $selecttag['radio']['others']; ?>/>
					<label for='<?php echo $radio_id; ?>'><?php echo $selecttag['radio']['label']; ?></label>
					<input type="hidden" id="<?php echo $radio_id; ?>_h" name="<?php echo $radio_id; ?>_h" 
						value="<?php echo $selecttag['radio']['hvalue']; ?>" <?php echo $selecttag['radio']['hothers']; ?>/>
					<?php } ?>
					<?php if (!empty($selecttag['label'])) { ?> 
					<label for='<?php echo $selecttag['name']; ?>'><?php echo $selecttag['label']; ?></label>
					<?php } ?>
					<select id='<?php echo $selecttag['name']; ?>' name='<?php echo $selecttag['name']; ?>' <?php echo $selecttag['others']; ?>>
						<?php foreach ($selecttag['optgrouptag'] as $optgrouptag) { ?>
						<optgroup label="<?php echo $optgrouptag['name']; ?>">
							<?php foreach ($optgrouptag['optiontag'] as $optiontag) { ?>
							<option value="<?php echo $optiontag['value']; ?>" id="<?php echo $optiontag['id']; ?>"><?php echo $optiontag['text']; ?></option>
							<?php } ?>
						</optgroup>
						<?php } ?>
					</select>
					<?php if (!empty($selecttag['close'])) echo '</' . $selecttag['close'] . '>'; ?>
				<?php } ?>
				<p>
				<?php if (!empty($form['hiddentag'])) foreach ($form['hiddentag'] as $hiddentag) { ?>
					<input type='hidden' id='<?php echo $hiddentag['name']; ?>' name='<?php echo $hiddentag['name']; ?>' value="<?php echo $hiddentag['value']; ?>" <?php echo $hiddentag['others']; ?>/>
				<?php } ?>
				<?php foreach ($form['submits'] as $submits) { ?>
					<br />
					<input type='submit' id='<?php echo $submits['name']; ?>' name='<?php echo $submits['name']; ?>' value="<?php echo $submits['value']; ?>" <?php echo $submits['others']; ?>/>
				<?php } ?>
				</p>
		</fieldset></form>
	<?php } ?>
		<p><a href="http://users.skynet.be/mgueury/mozilla/">
			<img src="http://users.skynet.be/mgueury/mozilla/serial_16.gif" alt="Validated by HTML Validator" height="16" width="39" />
		</a></p>
	</body>
</html>
<?php
