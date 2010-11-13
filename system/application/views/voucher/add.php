<script type="text/javascript">

$(document).ready(function() {
	/* Calculating Dr and Cr total */
	$('.dr-item').change(function() {
		var drTotal = 0;
		$("table tr .dr-item").each(function() {
			var curDr = $(this).attr('value');
			curDr = parseFloat(curDr);
			if (isNaN(curDr))
				curDr = 0;
			drTotal += curDr;
		});
		$("table tr #dr-total").text(drTotal);
		var crTotal = 0;
		$("table tr .cr-item").each(function() {
			var curCr = $(this).attr('value');
			curCr = parseFloat(curCr);
			if (isNaN(curCr))
				curCr = 0;
			crTotal += curCr;
		});
		$("table tr #cr-total").text(crTotal);

		if (drTotal == crTotal) {
			$("table tr #dr-total").css("background-color", "#FFFF99");
			$("table tr #cr-total").css("background-color", "#FFFF99");
			$("table tr #dr-diff").text("-");
			$("table tr #cr-diff").text("");
		} else {
			$("table tr #dr-total").css("background-color", "#FFE9E8");
			$("table tr #cr-total").css("background-color", "#FFE9E8");
			if (drTotal > crTotal) {
				$("table tr #dr-diff").text("");
				$("table tr #cr-diff").text(drTotal - crTotal);
			} else {
				$("table tr #dr-diff").text(crTotal - drTotal);
				$("table tr #cr-diff").text("");
			}
		}
	});

	$('.cr-item').change(function() {
		var drTotal = 0;
		$("table tr .dr-item").each(function() {
			var curDr = $(this).attr('value')
			curDr = parseFloat(curDr);
			if (isNaN(curDr))
				curDr = 0;
			drTotal += curDr;
		});
		$("table tr #dr-total").text(drTotal);
		var crTotal = 0;
		$("table tr .cr-item").each(function() {
			var curCr = $(this).attr('value')
			curCr = parseFloat(curCr);
			if (isNaN(curCr))
				curCr = 0;
			crTotal += curCr;
		});
		$("table tr #cr-total").text(crTotal);

		if (drTotal == crTotal) {
			$("table tr #dr-total").css("background-color", "#FFFF99");
			$("table tr #cr-total").css("background-color", "#FFFF99");
			$("table tr #dr-diff").text("-");
			$("table tr #cr-diff").text("");
		} else {
			$("table tr #dr-total").css("background-color", "#FFE9E8");
			$("table tr #cr-total").css("background-color", "#FFE9E8");
			if (drTotal > crTotal) {
				$("table tr #dr-diff").text("");
				$("table tr #cr-diff").text(drTotal - crTotal);
			} else {
				$("table tr #dr-diff").text(crTotal - drTotal);
				$("table tr #cr-diff").text("");
			}
		}
	});

	/* Dr - Cr dropdown changed */
	$('.dc-dropdown').change(function() {
		var drValue = $(this).parent().next().next().children().attr('value');
		var crValue = $(this).parent().next().next().next().children().attr('value');

		if ($(this).parent().next().children().val() == "0") {
			return;
		}

		drValue = parseFloat(drValue);
		if (isNaN(drValue))
			drValue = 0;

		crValue = parseFloat(crValue);
		if (isNaN(crValue))
			crValue = 0;

		if ($(this).attr('value') == "D") {
			if (drValue == 0 && crValue != 0) {
				$(this).parent().next().next().children().attr('value', crValue);
			}
			$(this).parent().next().next().next().children().attr('value', "");
			$(this).parent().next().next().next().children().attr('disabled', 'disabled');
			$(this).parent().next().next().children().attr('disabled', '');
		} else {
			if (crValue == 0 && drValue != 0) {
				$(this).parent().next().next().next().children().attr('value', drValue);
			}
			$(this).parent().next().next().children().attr('value', "");
			$(this).parent().next().next().children().attr('disabled', 'disabled');
			$(this).parent().next().next().next().children().attr('disabled', '');
		}
		$(this).parent().next().next().children().trigger('change');
		$(this).parent().next().next().next().children().trigger('change');
	});

	/* Ledger dropdown changed */
	$('.ledger-dropdown').change(function() {
		if ($(this).val() == "0") {
			$(this).parent().next().children().attr('value', "");
			$(this).parent().next().next().children().attr('value', "");
			$(this).parent().next().children().attr('disabled', 'disabled');
			$(this).parent().next().next().children().attr('disabled', 'disabled');
		} else {
			$(this).parent().next().children().attr('disabled', '');
			$(this).parent().next().next().children().attr('disabled', '');
			$(this).parent().prev().children().trigger('change');
		}
		$(this).parent().next().children().trigger('change');
		$(this).parent().next().next().children().trigger('change');

		var ledgerid = $(this).val();
		var rowid = $(this);
		$.ajax({
			url: <?php echo '\'' . site_url('ledger/balance') . '/\''; ?> + ledgerid,
			success: function(data) {
				var ledger_bal = parseFloat(data);
				if (isNaN(ledger_bal))
					ledger_bal = 0;
				if (ledger_bal == 0)
					rowid.parent().next().next().next().next().next().children().text("0");
				else if (ledger_bal < 0)
					rowid.parent().next().next().next().next().next().children().text("Cr " + -data);
				else
					rowid.parent().next().next().next().next().next().children().text("Dr " + data);
			}
		});
	});

	/* Recalculate Total */
	$('table td .recalculate').live('click', function() {
		$('.dr-item:first').trigger('change');
		$('.cr-item:first').trigger('change');
	});

	/* Delete ledger row */
	$('table td .deleterow').live('click', function() {
		$(this).parent().parent().remove();
	});

	/* Add ledger row */
	$('table td .addrow').live('click', function() {
		var cur_obj = this;
		$.ajax({
			url: <?php echo '\'' . site_url('voucher/addrow') . '\''; ?>,
			success: function(data) {
				$(cur_obj).parent().parent().after(data);
			}
		});
	});

	/* On page load initiate all triggers */
	$('.dc-dropdown').trigger('change');
	$('.ledger-dropdown').trigger('change');
	$('.dr-item:first').trigger('change');
	$('.cr-item:first').trigger('change');
});

</script>

<?php
	echo form_open('voucher/add/' . $voucher_type);
	echo "<p>";
	echo form_label('Voucher Number', 'voucher_number');
	echo " ";
	echo form_input($voucher_number);
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo form_label('Voucher Date', 'voucher_date');
	echo " ";
	echo form_input_date($voucher_date);
	echo "</p>";

	echo "<table class=\"vouchertable\">";
	echo "<thead><tr><th>Type</th><th>Ledger A/C</th><th>Dr Amount</th><th>Cr Amount</th><th colspan=2>Actions</th><th colspan=2>Cur Balance</th></tr></thead>";


	for ($i = 0; $i < 5; $i++)
	{
		$dr_amount = array(
			'name' => 'dr_amount[' . $i . ']',
			'id' => 'dr_amount[' . $i . ']',
			'maxlength' => '15',
			'size' => '15',
			'value' => ($_POST) ? $dr_amount_p[$i] : '',
			'class' => 'dr-item',
		);
		$cr_amount = array(
			'name' => 'cr_amount[' . $i . ']',
			'id' => 'cr_amount[' . $i . ']',
			'maxlength' => '15',
			'size' => '15',
			'value' => ($_POST) ? $cr_amount_p[$i] : '',
			'class' => 'cr-item',
		);
		echo "<tr>";

		if ($voucher_type != "payment" && $i == 0)
			echo "<td>" . form_dropdown_dc('ledger_dc[' . $i . ']', ($_POST) ? $ledger_dc_p[$i] : 'D') . "</td>";
		else if ($voucher_type != "payment" && $i != 0)
			echo "<td>" . form_dropdown_dc('ledger_dc[' . $i . ']', ($_POST) ? $ledger_dc_p[$i] : 'C') . "</td>";
		else if ($voucher_type == "payment" && $i == 0)
			echo "<td>" . form_dropdown_dc('ledger_dc[' . $i . ']', ($_POST) ? $ledger_dc_p[$i] : 'C') . "</td>";
		else if ($voucher_type == "payment" && $i != 0)
			echo "<td>" . form_dropdown_dc('ledger_dc[' . $i . ']', ($_POST) ? $ledger_dc_p[$i] : 'D') . "</td>";

		echo "<td>" . form_input_ledger('ledger_id[' . $i . ']', ($_POST) ? $ledger_id_p[$i] : '') . "</td>";
		echo "<td>" . form_input($dr_amount) . "</td>";
		echo "<td>" . form_input($cr_amount) . "</td>";

		echo "<td>" . img(array('src' => asset_url() . "images/icons/add.png", 'border' => '0', 'alt' => 'Add Ledger', 'class' => 'addrow')) . "</td>";
		echo "<td>" . img(array('src' => asset_url() . "images/icons/delete.png", 'border' => '0', 'alt' => 'Remove Ledger', 'class' => 'deleterow')) . "</td>";

		echo "<td class=\"ledger-balance\"><div></div></td>";

		echo "</tr>";
	}
	echo "<tr><td colspan=4><hr /></td><td></td></tr>";

	echo "<tr id=\"total\"><td colspan=2>TOTAL</td><td id=\"dr-total\">0</td><td id=\"cr-total\">0</td><td>" . img(array('src' => asset_url() . "images/icons/gear.png", 'border' => '0', 'alt' => 'Recalculate Total', 'class' => 'recalculate', 'title' => 'Recalculate Total')) . "</tr>";

	echo "<tr id=\"difference\"><td colspan=2>DIFFERENCE</td><td id=\"dr-diff\"></td><td id=\"cr-diff\"></td></tr>";
	echo "</table>";

	echo "<p>";
	echo form_label('Narration', 'voucher_narration');
	echo "<br />";
	echo form_textarea($voucher_narration);
	echo "</p>";

	echo "<p>";
	echo form_fieldset('Options', array('class' => "fieldset-auto-width"));
	echo form_checkbox('voucher_draft', 1, echo_value($voucher_draft, FALSE)) . "Draft";
	echo "<br /><br />";
	echo form_checkbox('voucher_print', 1, echo_value($voucher_print, FALSE)) . "Print";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo form_checkbox('voucher_email', 1, echo_value($voucher_email, FALSE)) . "Email";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo form_checkbox('voucher_pdf', 1, echo_value($voucher_pdf, FALSE)) . "Download PDF";
	echo form_fieldset_close();
	echo "</p>";
	echo "<br /><br />";
	echo form_submit('submit', 'Create');
	echo " ";
	echo anchor('voucher/show/' . $voucher_type, 'Back', array('title' => 'Back to ' . ucfirst($voucher_type) . ' Vouchers'));
	echo form_close();
?>
