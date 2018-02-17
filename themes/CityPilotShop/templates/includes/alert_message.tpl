 <!-- alert -->
     {blockposition name='top-center' assign="admin_msg"}
     {if !empty($admin_msg)}
    <div role="alert" class="alert alert-warning alert-dismissible fade in top-alert">
        <div class="container">
            <button aria-label="Close" data-dismiss="alert" class="close" type="button">
                <span aria-hidden="true">×</span>
            </button>
           {*<strong>I dag servicerer vi sitet fra kl. 12.00 til ca. kl. 14.00</strong> <br>
            Vi beklager de gener dette måtte medføre.
            Venlig hilsen
            Dit CityPilot Team*}
            {$admin_msg}
        </div>
    </div>
        {/if}
    <!-- alert end -->