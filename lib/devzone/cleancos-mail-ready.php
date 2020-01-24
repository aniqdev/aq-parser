<meta charset="UTF-8" />
<div style="height: auto; margin: 0 !important; padding: 0 !important; max-width: 9000px !important; font-family: arial, verdana, sans-serif; background-color: #e5e5e5;">
<div style="margin-left: auto; margin-right: auto; width: 600px; padding-bottom: 5px; padding-top: 5px; font-size: 12px; color: #999999; text-align: right;">&nbsp;</div>

<div style="margin-left: auto; margin-right: auto; width: 600px; padding: 25px 45px 25px; background-color: white; margin-top: 15px; margin-bottom: 15px; display: block;">
<table style="width: 100%; overflow: hidden;">
	<tbody>
		<tr>
			<td colspan="2" style="text-align: right;">{$gm_logo_mail}</td>
		</tr>
		<tr>
			<td>
				<p><span style="font-size:12px;"><span style="font-family: verdana,geneva,sans-serif;">{$address_label_payment}</span></span></p>

				<p><span style="font-size:12px;"><span style="font-family: verdana,geneva,sans-serif;">
					{if $PAYMENT_METHOD}Zahlung: {$PAYMENT_METHOD}<br />{/if}
				Bestellnummer: {$oID}<br />
				Bestelldatum: {$DATE}<br />
				{if $csID}Kundennummer: {$csID}{/if}</span></span></p>
			</td>
			<td style="text-align: right;"><span style="font-size:12px;"><span style="font-family: verdana,geneva,sans-serif">
			CleanCos Beauty Group<br />
			Inh. Raymond G. Zino<br />
			Feringastr. 10 b<br />
			D-85774 Unterf&ouml;hring<br />
			Tel.: 089 / 20 00 14 40<br />
			Fax: 089 / 20 00 14 420<br />
			Email: info@cleancos.de<br />
			UStID: DE252149040<br /></span></span></td>
		</tr>
	</tbody>
</table>

<table border="0" style="border-top:solid #eee 1px ; margin-top:5px;padding:10px" width="100%">
	<tbody>
		<tr>
			<td style="vertical-align: top" width="50%"><span style="font-size:12px;"><span style="font-family: verdana,geneva,sans-serif;"><strong>Rechnungsadresse</strong><br />
			{$address_label_payment} </span> </span></td>
			<td style="vertical-align: top" width="50%"><span style="font-size:12px;"><span style="font-family: verdana,geneva,sans-serif;"><strong>Lieferadresse</strong><br />
			{$address_label_shipping} </span> </span></td>
		</tr>
		<tr style="border-top:solid #eee 1px">
			<td colspan="2">
			<h1 style="font-weight: normal;">{if $NAME|trim == ''}<span style="font-size:12px;"><span style="font-family: verdana,geneva,sans-serif;">Sehr geehrte Damen und Herren,</span></span> {else} <span style="font-size:12px;"><span style="font-family: verdana,geneva,sans-serif;">Sehr {if $GENDER == 'm'}geehrter Herr {elseif $GENDER == 'f'}geehrte Frau {else}geehrte(r) {/if}{$NAME} ,</span></span> {/if}</h1>
			<p><span style="font-size:12px;"><span style="font-family: verdana,geneva,sans-serif;">vielen Dank f&uuml;r Ihre Bestellung in unserem Online-Shop!</span></span></p>
			</td>
		</tr>
	</tbody>
</table>

<table cellspacing="0" style="width: 100%; font-size: 12px; padding: 0;">
	<tbody>
		<tr>
			<th style="font-weight: normal; border-bottom: 2px solid #000; padding: 3px 0 3px 3px; font-size: 13px; text-align: left;"><span style="font-size:13px;"><span style="font-family: verdana,geneva,sans-serif;">Anzahl</span></span></th>
			<th style="font-weight: normal; border-bottom: 2px solid #000; padding: 3px 0 3px 3px; font-size: 13px; text-align: left;"><span style="font-size:13px;"><span style="font-family: verdana,geneva,sans-serif;">Artikel</span></span></th>
			<th style="width: 67px; font-weight: normal; border-bottom: 2px solid #000; padding: 3px 0 3px 3px; font-size: 13px; text-align: left;"><span style="font-size:13px;"><span style="font-family: verdana,geneva,sans-serif;">Art.-Nr.</span></span></th>
			<th style="font-weight: normal; border-bottom: 2px solid #000; padding: 3px 4px 3px 3px; font-size: 13px; text-align: right;"><span style="font-size:13px;"><span style="font-family: verdana,geneva,sans-serif;">Einzelpreis</span></span></th>
			<th style="font-weight: normal; border-bottom: 2px solid #000; padding: 3px 4px 3px 3px; font-size: 13px; text-align: right;"><span style="font-size:13px;"><span style="font-family: verdana,geneva,sans-serif;">Gesamtpreis</span></span></th>
		</tr>
		<!--{foreach name=aussen item=order_values from=$order_data}-->
		<tr>
			<td style="border-bottom: 1px solid #ddd; padding: 5px 4px; vertical-align: top"><span style="font-size:13px;"><span style="font-family: verdana,geneva,sans-serif;">{$order_values.PRODUCTS_QTY}{if $order_values.UNIT} {$order_values.UNIT}{else}x{/if}</span></span></td>
			<td style="border-bottom: 1px solid #ddd; padding: 5px 4px; vertical-align: top">
				<span style="font-size:13px;">
					<span style="font-family: verdana,geneva,sans-serif;">
						{$order_values.PRODUCTS_NAME}
					</span>
				</span>
			</td>
			<td style="border-bottom: 1px solid #ddd; padding: 5px 4px; vertical-align: top">
				<span style="font-size:13px;">
					<span style="font-family: verdana,geneva,sans-serif;">
						{$order_values.PRODUCTS_MODEL}<br />
						<em>{$order_values.PRODUCTS_ATTRIBUTES_MODEL}</em> 
					</span>
				</span>
			</td>
			<td style="border-bottom: 1px solid #ddd; padding: 5px 4px; text-align: right; vertical-align: top"><span style="font-size:13px;"><span style="font-family: verdana,geneva,sans-serif;">{$order_values.PRODUCTS_SINGLE_PRICE|replace:'EUR':'€'}</span></span></td>
			<td style="border-bottom: 1px solid #ddd; padding: 5px 4px; text-align: right; vertical-align: top"><span style="font-size:13px;"><span style="font-family: verdana,geneva,sans-serif;">{$order_values.PRODUCTS_PRICE|replace:'EUR':'€'}</span></span></td>
		</tr>
		<!--{/foreach}-->
	</tbody>
</table>

<div style="float: right; text-align: right">
	{foreach name=aussen item=order_total_values from=$order_total}<span style="font-size:12px"><span style="font-family: verdana,geneva,sans-serif;">{$order_total_values.TITLE|trim} {$order_total_values.TEXT|trim}</span></span><br />
	{/foreach}
</div>

</div>
</div>