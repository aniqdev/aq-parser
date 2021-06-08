<html>
<body>

<table cellpadding="0" cellspacing="0">
    <tr>
        <th class="title-td">
            <h1 class="title">Gutschein</h1>
        </td>
        <td class="logo-td">
            <img class="logo" src="https://funkyimg.com/i/39nuY.png" alt="VulcanoVet">
        </td>
    </tr>
</table>
<div>
    <img class="banner" src="https://funkyimg.com/i/39nmM.jpg" alt="">
</div>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th>Name des Beschenkten:</th>
        <td>{if $personalization}{$personalization->getPresenteeName()}{else}John Doe{/if}</td>
    </tr>
    <tr>
        <th>Grußbotschaft:</th>
        <td>{if $personalization}{$personalization->getPresenteeMessage()}{else}John Doe{/if}</td>
    </tr>
    <tr>
        <th>Name des Schenkers:</th>
        <td>{if $personalization}{$personalization->getDonorName()}{else}John Doe{/if}</td>
    </tr>
    <tr>
        <th>Gutscheincode:</th>
        <td>{$code}</td>
    </tr>
    <tr>
        <th>Wert:</th>
        <td>{if $percental}{$value}%{else}{$value} €{/if}</td>
    </tr>
</table>

</body>
</html>
