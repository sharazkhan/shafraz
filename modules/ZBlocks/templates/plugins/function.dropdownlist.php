<?php

function smarty_function_dropdownlist($args, &$smarty) {
    // js files
    PageUtil::addVar('javascript', 'modules/ZBlocks/javascript/dropdownlist/jquery-1.6.1.min.js');
    PageUtil::addVar('javascript', 'modules/ZBlocks/javascript/dropdownlist/jquery-ui-1.8.13.custom.min.js');
    PageUtil::addVar('javascript', 'modules/ZBlocks/javascript/dropdownlist/ui.dropdownchecklist.js');


    // css files
    PageUtil::addVar('stylesheet', 'modules/ZBlocks/javascript/dropdownlist/jquery-ui-1.8.13.custom.css');
    PageUtil::addVar('stylesheet', 'modules/ZBlocks/javascript/dropdownlist/ui.dropdownchecklist.themeroller.css');
    /* echo "<style>
      .z-form div.z-formrow > span, .z-form div.z-formrow > div {

      padding-bottom: 2.3em !important;

      }
      .ui-dropdownchecklist-selector{width: 170px !important}
      </style>";
     */
}
