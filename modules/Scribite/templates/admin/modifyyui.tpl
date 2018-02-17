{adminheader}<div class="z-admin-content-pagetitle">    {img src='yui.png' modname='Scribite' height='22'}    <h3>{gt text='YUI Rich Text Editor configuration'}</h3></div><form class="z-form" action="{modurl modname="Scribite" type="admin" func="updateyui"}" method="post" enctype="application/x-www-form-urlencoded">    <div>        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />        <fieldset>            <legend>{gt text='Settings'}</legend>            <div class="z-formrow">                <label for="yui_type">{gt text="Toolbar"}</label>                <select id="yui_type" name="yui_type">                    {html_options options=$yui_types selected=$yui_type}                </select>            </div>            <div class="z-formrow">                <label>{gt text="Editor width and height"}</label>                <div>                    <input id="yui_width" type="text" name="yui_width" size="5" maxlength="6" value="{$yui_width|safetext}" />                    <label for="yui_width">{gt text="px/(auto)"}</label>                    <input id="yui_height" type="text" name="yui_height" size="5" maxlength="6" value="{$yui_height|safetext}" />                    <label for="yui_height">{gt text="px/(auto)"}</label>                </div>            </div>            <div class="z-formrow">                <label for="yui_dombar">{gt text="Statusbar"}</label>                <input type="checkbox" id="yui_dombar" name="yui_dombar" value="1"{if $yui_dombar eq "1"} checked="checked"{/if} />            </div>            <div class="z-formrow">                <label for="yui_animate">{gt text="Animation"}</label>                <input type="checkbox" id="yui_animate" name="yui_animate" value="1"{if $yui_animate eq "1"} checked="checked"{/if} />            </div>            <div class="z-formrow">                <label for="yui_collapse">{gt text="Collapsable"}</label>                <input type="checkbox" id="yui_collapse" name="yui_collapse" value="1"{if $yui_collapse eq "1"} checked="checked"{/if} />            </div>        </fieldset>        <div class="z-buttons z-formbuttons">            {button src='button_ok.png' set='icons/extrasmall' __alt="Save" __title="Save" __text="Save"}            <a href="{modurl modname='Scribite' type='admin' func='modules'}">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>        </div>    </div></form>{adminfooter}