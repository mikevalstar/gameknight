<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            {if $removeowner}<th></th>{/if}
        </tr>
    </thead>
    <tbody>
        {foreach from=$results item=row}
        <tr>
            <td>{$row['name_first']|x} {$row['name_last']|x}</td>
            {if $removeowner}
            <td>
                <form method="post" class="form-inline">
                    <input type="hidden" name="action" value="remove_owner" />
                    <input type="hidden" name="owner_pk" value="{$row['owner_pk']}" />
                    <div class="btn-group pull-right">
                        <input type="submit" class="btn btn-mini btn-danger" type="button" value="Remove" />
                    </div>
                </form>
            </td>
            {/if}
        </tr>
        {/foreach}
    </tbody>
    {if $addowner}
    <tfoot>
        <tr>
            <td colspan="4">
                <form method="post">
                    <input type="hidden" name="action" value="add_owner" />
                    <input type="text" name="email" class="filluseremail" value="" placeholder="Email" autocomplete="off" />
                    <input type="submit" class="btn" value="Add" />
                    <p>Enter email name to add.</p>
                </form>
            </td>
        </tr>
    </tfoot>
    {/if}
</table>
