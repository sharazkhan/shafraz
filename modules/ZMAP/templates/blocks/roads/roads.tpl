{pageaddvar name="jsgettext" value="zikula_js"}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}

{pageaddvar name='javascript' value='modules/ZMAP/javascript/roadscript.js'}
{pageaddvar name='javascript' value='modules/ZMAP/javascript/zmapscript.js'}  

<div>
  <div>
  <form  id="loadroads" name="loadroads"   action="{modurl modname='ZMAP' type='user' func='loadroad'}" method="post">
    <select name="rid" id="allroads" onchange="loadroad(this.value)">
        <option value="">{gt text='Select From All Roads'}</option>
          {foreach from=$allProjectRoads  item='allprojectroad'}
        <option value="{$allprojectroad.rid}" >{$allprojectroad.name|upper}</option>
        {/foreach}
    </select>
     <input type="hidden" id="uri" name="uri" value="{$uri}">
    </form
  ></div>
   <div>
         
        
       <select id="projectroads" onchange="centerDirection1(this.value);getRoad(this.value)">
        <option value="">{gt text='Select Project Roads'}</option>
        {foreach from=$projectroads  item='projectroad' key='k'}
         <option value="{$projectroad.rid}+directionsDisplay{$k}.setMap(map)">{$projectroad.name|upper}</option>
        {/foreach}
    </select>
  </div>
    <form  id="roadsave" name="roadsave"   action="{modurl modname='ZMAP' type='user' func='roads'}" method="post">
    <div><b>{gt text='Road Name / Description'}</b></div>
    <div>
        <input type="hidden" id="ridedit" name="ridedit" value="">
        <input type="text" id="roadname" name="roadname" value="">
        <input type="hidden" id="projectId" name="projectId" value="{$smarty.request.projectId}">
        <input type="hidden" id="uri" name="uri" value="{$uri}">
        
       <input type="hidden" id="rmve" name="rmve" value="">
    </div>
    <div>
          <textarea name="roaddescription" id="roaddescription"></textarea>
    </div>
    
    <div><b>{gt text='Start Point / Description'}</b></div>
    <div>
        <input type="text" id="startgps" name="start" size="40" value=""> <input type="button" name="save" value="{gt text='GPS'}" onclick="getGps('startgps');" title="{gt text='Insert current GPS position in field'}">
    </div>
    <div>
          <textarea id="startdescription" name="startdescription"></textarea>
    </div>
     
     <div><b>End Point / Description</b></div>
    <div>
        <input type="text" id="endgps" name="end" size="40" value=""> <input type="button" name="save" value="{gt text='GPS'}" onclick="getGps('endgps');" title="{gt text='Insert current GPS position in field'}">
    </div>
    <div>
          <textarea id="enddescription" name="enddescription" class="zinput"></textarea>
    </div>
    <div>
        <input type="submit" name="saveroad" value="{gt text='Save'}" class="zbutton" title="{gt text='Save changes made to current road'}">
        <input type="submit" name="saveroadas" value="{gt text='Set Road'}" onclick="return validateRoad();" title="{gt text='Set road on map (not save)'}">
        <input type="button" name="remove" value="{gt text='Remove'}" onclick="removeMap()" title="{gt text='Remove road from map (not delete)'}">
        <input type="submit" name="deleteroad" value="{gt text='Delete'}" onclick="return zmapRconfirm('deleteRoad')" title="{gt text='Delete road from map and project'}">
        <input type="submit" name="removesetroads" value="{gt text='Remove Set Roads'}" title="{gt text='Remove all set roads from map (not saved roads)'}">
      </div> 
          </form>
   

</div>