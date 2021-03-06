<table class="table table-hover table-sm table-striped">
    <thead>
    <tr>
        <th>Position #</th>
        <th>Closed</th>
        <th>Username</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$dataTable item="row" name="topcreators"}
        <tr {*if $row.username == $currentUser->getUsername()}class="info"{/if*}>
            <td>{$smarty.foreach.topcreators.iteration}</td>
            <td>{$row.count}</td>
            <td>
                <a href="#"
                   {if $row.status == 'Suspended'}class="text-muted"
                   {elseif $row.status == 'Admin'}class="text-success"{/if}>
                    {$row.username|escape}
                </a>
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
