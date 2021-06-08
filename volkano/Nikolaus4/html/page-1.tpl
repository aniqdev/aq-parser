<html>
    <body>
        <div class="parent code">{$code}</div>
        <div class="parent value">
            {if $percental}
                {$value}%
            {else}
                {$value|currency}
            {/if}
        </div>
        <div class="parent shopName">{config name=shopName}</div>
        <div class="parent shopUrl">{$shopUrl}</div>
    </body>
</html>
