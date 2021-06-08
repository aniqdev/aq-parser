<html>
<body>

<table cellpadding="0" cellspacing="0">
    <tr>
        <td class="title-td">
            <h1 class="title">Gutschein</h1>
        </td>
        <td>
            <img class="logo" src="https://funkyimg.com/i/39nuY.png" alt="VulcanoVet">
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0">
    <tr>
        <td class="banner-td">
            <img class="banner" src="https://funkyimg.com/i/39nmM.jpg" alt="">
        </td>
    </tr>
</table>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th>Name des Beschenkten:</th>
        <td>{if $personalization}{$personalization->getPresenteeName()}{/if}</td>
    </tr>
    <tr>
        <th>Grußbotschaft:</th>
        <td>{if $personalization}{$personalization->getPresenteeMessage()}{/if}</td>
    </tr>
    <tr>
        <th>Name des Schenkers:</th>
        <td>{if $personalization}{$personalization->getDonorName()}{/if}</td>
    </tr>
    <tr>
        <th>Gutscheincode:</th>
        <td>{$code}</td>
    </tr>
    <tr>
        <th>Wert:</th>
        <td>{if $percental}{$value}%{else}{$value}{/if}</td>
    </tr>
</table>

</body>
</html>
