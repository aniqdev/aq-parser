<form id="Formular" name="Formular">
	<p>
		<span lang="de" xml:lang="de" style="font-family: Verdana; font-size: 8pt;"></span>
		<span style="font-family: Verdana; font-size: 8pt;"></span></p>
		<span style="font-family: Verdana; font-size: 8pt;"></span>
		<p align="center">
			<span style="font-family: Verdana; font-size: 8pt;"></span></p>
			<left>
			<div>
				<table cellspacing="0" cellpadding="3" border="0" height="64" style="border-collapse: collapse">
					<tbody>
						<tr>
							<td width="91" height="23">Decktag</td>
							<td width="257" height="23">
								<div>
									<span style="font-family: Verdana; color: rgb(0, 0, 0);">
										<select size="1" name="Date">
											<option value="01" selected="selected">1. </option>
											<option value="02">2. </option>
											<option value="03">3. </option>
											<option value="04">4. </option>
											<option value="05">5. </option>
											<option value="06">6. </option>
											<option value="07">7. </option>
											<option value="08">8. </option>
											<option value="09">9. </option>
											<option value="10">10. </option>
											<option value="11">11. </option>
											<option value="12">12. </option>
											<option value="13">13. </option>
											<option value="14">14. </option>
											<option value="15">15. </option>
											<option value="16">16. </option>
											<option value="17">17. </option>
											<option value="18">18. </option>
											<option value="19">19. </option>
											<option value="20">20. </option>
											<option value="21">21. </option>
											<option value="22">22. </option>
											<option value="23">23. </option>
											<option value="24">24. </option>
											<option value="25">25. </option>
											<option value="26">26. </option>
											<option value="27">27. </option>
											<option value="28">28. </option>
											<option value="29">29. </option>
											<option value="30">30. </option>
											<option value="31">31. </option>
										</select></span>
										<span style="font-weight: bold; font-style: italic;">
											<span style="font-family: Verdana; color: rgb(0, 0, 0);">
												<select size="1" name="Month">
													<option value="0" selected="selected">Januar </option>
													<option value="1">Februar</option>
													<option value="2">März</option>
													<option value="3">April</option>
													<option value="4">Mai</option>
													<option value="5">Juni</option>
													<option value="6">Juli</option>
													<option value="7">August</option>
													<option value="8">September</option>
													<option value="9">Oktober</option>
													<option value="10">November</option>
													<option value="11">Dezember</option>
												</select></span>
												<span style="font-family: Verdana; color: rgb(0, 0, 0);">
													<select size="1" name="Year">
														<option value="2019">2019</option>
														<option value="2020">2020</option>
														<option value="2021" selected="selected">2021</option>
														<option value="2022">2022</option>
													</select></span></span></div></td>
												</tr>
												<tr>
													<td height="32">Tragezeit</td>
													<td height="32">
														<div>
															<select size="1" name="Tragzeit">
																<option value="70" selected="selected">70</option>
																<option value="69">69</option>
																<option value="68">68</option>
																<option value="67">67</option>
																<option value="66">66</option>
																<option value="65">65</option>
																<option value="64">64</option>
																<option value="63">63</option>
																<option value="62">62</option>
																<option value="61">61</option>
																<option value="60">60</option>
															</select>
															<span style="font-size: 12pt; color: rgb(0, 0, 0);"> Tage</span></div></td>
														</tr>
													</tbody>
												</table></div>
												<span style="font-family: Verdana; font-size: 8pt;">
													<p align="left">
														<span style="font-family: Verdana; color: rgb(133, 107, 92);">
															<input type="button" name="button" onclick="PregEvent(Formular)" value=" Wurftermin berechnen!"></span></p></span>
															<left>
															<div>
																<table cellspacing="0" cellpadding="3" border="0" height="32" style="border-collapse: collapse">
																	<tbody>
																		<tr>
																			<td width="68" height="30">Wurftermin</td>
																			<td width="119" height="30">
																				<div>
																					<span style="font-family: Verdana; color: rgb(102, 102, 102);">
																						<input type="text" name="Geburtstag" size="16">
																					</span>
																				</div>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</div>
															</left>
															</left>
														</form>
<script>
function machenArray(len){
for (var i = 0; i < len; i++) this[i]=null;
this.length = len;
}
function machenArray() {
    for (i = 0; i<machenArray.arguments.length; i++)
        this[i + 1] = machenArray.arguments[i];
}
var months = new machenArray('Januar','Februar','März','April',
                           'Mai','Juni','Juli','August','September',
                           'Oktober','November','Dezember');
function y2k(number) { return (number < 1000) ? number + 1900 : number; }
function PregEvent(theForm) {
if (Formular.Year.value  == "")  {
           alert ("Bitte eine Jahreszahl angeben!");
        return;
}
if (("" + parseInt(Formular.Year.value)) != Formular.Year.value)  {
           alert ("Bitte nur Zahlenwerte eingeben!");
        return;
}
if (Formular.Year.value < 1000){
           alert ("Bitte eine vierstellige Jahreszahl eingeben");
        return;
}
var v_Deckdatum = Date.UTC(Formular.Year.value, Formular.Month.options[Formular.Month.selectedIndex].value, Formular.Date.options[Formular.Date.selectedIndex].value);
var v_TagDerGeburt = v_Deckdatum + (Formular.Tragzeit.options[Formular.Tragzeit.selectedIndex].value *  86000000);
var v_TagDerGeburtFormatted = new Date(v_TagDerGeburt)
Formular.Geburtstag.value = " " + v_TagDerGeburtFormatted.getDate() + ". " + months[v_TagDerGeburtFormatted.getMonth() + 1]  + " " +  y2k(v_TagDerGeburtFormatted.getYear());
}
</script>