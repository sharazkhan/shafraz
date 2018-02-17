<style>
  .Grade{
    display: none;
  }
 .CenterSec {
    float: left;
    font-family: Arial,Helvetica,sans-serif;
    min-height:230px;
    height: auto;
    margin-left: 5px;
    padding-bottom: 15px;
    width: 200px;
    }
</style>


<div class="Grade"></div>
{foreach from=$stories item=story}
<div class="CenterSec">
   {$story}
</div> 
{/foreach}