<?php

function smarty_function_browsersupport($args, &$smarty)
{
    $currentTheme = System::getVar('Default_Theme');
    $themePath    = pnGetBaseURL().'themes/'.$currentTheme;
    ?>

    <!--[if lt IE 9]>
      <script src="<?php echo $themePath ?>/js/html5shiv.min.js"></script>
      <script src="<?php echo $themePath ?>/js/respond.min.js"></script>
    <![endif]-->

    <?php
    //  $smarty->assign("perm", $perm);
}
?>
