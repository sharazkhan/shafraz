<?php /* Smarty version 2.6.28, created on 2018-02-03 06:44:17
         compiled from blocks/exclusiveevents/exclusive_events.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'blocks/exclusiveevents/exclusive_events.tpl', 3, false),)), $this); ?>
 
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/exclusive_events.js?v=1.1"), $this);?>

<section class="slider-wrapper">
        <div class="container">
            <div class="banner-slider">
                <ul class="bxslider clearfix"  id="slideshow">
                                        
                </ul>
            </div>
        </div>
    </section>


<input type="hidden" id="old_event_id" value="<?php echo $this->_tpl_vars['events']['old_event_id']; ?>
">
<input value="<?php echo $this->_tpl_vars['events']['event_count']; ?>
" type="hidden" id="event_count">
<input type="hidden" id="first_load" value="1">