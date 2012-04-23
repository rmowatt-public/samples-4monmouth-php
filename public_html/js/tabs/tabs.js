/**
 * @author      Filipe Araujo
 * @version     1.0
 * @since       1.0
 * 
 * @lastChange  new
 */

FM.Tabs = (function(){    
    /**
     * activate the selected style on tabs
     * @memberOf FM.Tabs
     * @private
     * @param {Object} el link element passed through click
     */ 
    var activateTab = function(el){
        jQuery(".tab.selected").removeClass("selected");
        jQuery(el).parent().addClass("selected");
    },
    /**
     * binds click events
     * @memberOf FM.Tabs
     * @private
     */ 
    buildTabs = function(){
        jQuery('#tabGroup .tab a').click(showTab);
        jQuery(".toggle").click(toggleAdminTabs);
        jQuery(".collapse").click(closeAllAdminTabs);
        jQuery(".explode").click(openAllAdminTabs);        
    },
    /**
     * close all admin tabs
     * @memberOf FM.Tabs
     * @private
     */
    closeAllAdminTabs = function(){
        jQuery(".module").hide();
        _switchAdminIcon('close');
    }
    /**
     * close all admin tabs
     * @memberOf FM.Tabs
     * @private
     */
    openAllAdminTabs = function(){
        jQuery(".module").show();
        _switchAdminIcon('open');
    }
    /**
     * setup datepicker in calendar tab
     * @memberOf FM.Tabs
     * @private
     */
    setupDatePicker = function(){
        jQuery("#datepicker_input").datepicker({
            altField: '#datetag',
            altFormat: 'yymmdd',
            showOn: 'button',
            buttonImage: '/images/icons/calendar.png',
            buttonImageOnly: true
        });
    }
    
     /**
     * setup datepicker in calendar tab
     * @memberOf FM.Tabs
     * @private
     */
    setupCouponDatePicker = function(){
        jQuery("#cdatepicker_input").datepicker({
            altField: '#valid',
            altFormat: 'yymmdd',
            showOn: 'button',
            buttonImage: '/images/icons/calendar.png',
            buttonImageOnly: true
        });
    }
    
    /**
     * show current tab_content and hide all others
     * @memberOf FM.Tabs
     * @private
     */ 
    showTab = function(){
        activateTab(this);
                
        var id = this.id.split("tab_")[1],
            el = jQuery("#content #"+id),
            effect = "slide";
            
        if(this.id == "tab_admin"){
            effect = null;
        }
        
        jQuery("#content").queue(function(){
            jQuery("#content .tab_content").hide();
            el.show(effect, {
                direction : "up"
            }, el.height()* 1.66)
            jQuery(this).dequeue();
        });
        
    },
    /**
     * activate the admin modules next to toggle links
     * @memberOf FM.Tabs
     * @private
     */ 
    toggleAdminTabs = function(){
      _switchAdminIcon(this);
      /*jQuery(this).next(".module").toggle("slide",{
          direction : "up"
      });*/
      jQuery(this).next(".module").toggle();
    }
    /**
     * switch open and close icon
     * @memberOf FM.Tabs
     * @private
     * @param {Object} el element ()
     */
    _switchAdminIcon = function(el){
        if(typeof el == "string"){
            (el == "close") ? jQuery(".toggle").removeClass("close") : jQuery(".toggle").addClass("close");
        }
        else {            
           jQuery(el).toggleClass("close")
        }
    }
        
    jQuery(function(){
        buildTabs();
        setupDatePicker();
        setupCouponDatePicker();
    })
    
    return {        
    }
})();
