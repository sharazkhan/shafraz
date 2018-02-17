{pageaddvar name="jsgettext" value="zikula_js"}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}

{pageaddvar name='javascript' value='modules/ZMAP/javascript/projectscript.js'}
{pageaddvar name='javascript' value='modules/ZMAP/javascript/zmapscript.js'}


<div>
  <div>
     
    <select onChange='onProjectChange(this.value);'>
        <option value="">{gt text="Select projects to show"}</option>
         {foreach from=$projects  item='project'}
        <option value="{$project.pid}" {if $smarty.request.projectId eq $project.pid} selected="selected" {/if}>{$project.name|upper}</option>
        {/foreach}
    </select>
  </div>
    <div><b>{gt text="Name / Description"}</b></div>
    <div>
          <input type="text" id="projectname" name="projectname" value="{$projectitem.name}" onkeyup="copyProjectWords(this.value)">
          <input type="hidden" id="projectnameDB" name="projectnameDB" value="{$projectitem.name}">
    </div>
    <div>
          <textarea id="projectdescription" name="projectdescription" onkeyup="copyProjectText(this.value)">{$projectitem.description}</textarea>
    </div>
    <div>
       
        <form onsubmit="return validateSaveNewProject();" id="saveprojectas" name="saveprojectas"  action="{modurl modname='ZMAP' type='user' func='saveNewProject'}" method="post">
          <input type="submit" name="saveas" value="{gt text='Save As'}" onclick="return zmapPconfirm('saveNewProject');" title="{gt text='Save current project as new project'}">
          <input type="hidden" id="hprojectname" name="hprojectname" value="{$projectitem.name}" >
          <input type="hidden" id="projectId" name="projectId" value="{$smarty.request.projectId}">
          <textarea id="hprojectdescription" name="hprojectdescription" style="display:none;">{$projectitem.description}</textarea>
        <input type="hidden" name="saveasvalue" value="saveas">
        </form>
      
        <form onsubmit="return validateSaveProject();" id="saveproject" name="saveproject" action="{modurl modname='ZMAP' type='user' func='saveloadedproject'}" method="post">
         <input type="submit" name="saveprj" value="{gt text='Save'}" onclick="return zmapPconfirm('saveLoadedProject');" title="{gt text='Save changes to current project'}">
         <input type="hidden" id="aprojectname" name="aprojectname" value="{$projectitem.name}">
         <input type="hidden" id="projectId" name="projectId" value="{$smarty.request.projectId}">
         <textarea id="aprojectdescription" name="aprojectdescription" style="display:none;" >{$projectitem.description}</textarea>
        </form>
        
         <form id="deleteproject" name="deleteproject"  action="{modurl modname='ZMAP' type='user' func='deleteProject'}" method="post">
             <input type="hidden" id="projectId" name="projectId" value="{$smarty.request.projectId}">
             <input type="submit" name="deleteproject" value="{gt text='Delete'}" onclick="return zmapPconfirm('deleteProject');" title="{gt text='Delete current project'}">
         </form>
        
    </div>
         <!--   <div><input type="checkbox" id="savemode" name="savemode" value="">{gt text="Drag and save"}</div>-->
            
</div>
        <!--
        {php}
          echo "<pre>"; print_r($_SERVER); echo "</pre>";
          echo $_SERVER['REQUEST_SCHEME'] .  "://"  . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        {/php}
        -->