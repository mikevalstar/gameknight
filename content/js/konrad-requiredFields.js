// Use: $('form').requiredFields()
!function ($) {

    "use strict"; // jshint ;_;
  
    /* Required field CLASS DEFINITION
     * Author: Mike Valstar
     * =============================== */

    var RequiredFields = function (element, options) {
        this.init(element, options)
    }

    RequiredFields.prototype = {
    
          constructor: RequiredFields
        
        , init: function (element, options) {
              var eventIn
                , eventOut
            
              this.$element = $(element)
              this.options = options
              this.enabled = true
              
              this.$element.on('submit', $.proxy(this.onSubmit, this));
        }
        
        , onSubmit: function (e){
            var fields = this.$element.find('input.required,input.validate_email,input.validate_phone,input.minlengeth')
              , isValid = true
              , that = this
            
            fields.each(function(key, val){
                var $val = $(val);
                var check = that.checkField($val)
                if(check === true){
                    that.removeWarning($val)
                }else{
                    that.displayWarning($val, check)
                    isValid = false;
                }
            });
            
            if(!isValid) e.preventDefault();
        }
        
        , checkField: function(field){
            // simple required field
            if(field.val() == ''){
                return "This field is required";
            }
            
            // email check
            if(field.hasClass('validate_email')){
                var re = /\S+@\S+\.\S+/;
                if(!re.test(field.val())){
                    return "This does not appear to be a valid email address.";
                }
            }
            
            // phone check (has at least 10 digits)
            if(field.hasClass('validate_phone')){
                if(field.val().replace(/[^0-9]/g, '').length < 10){
                    return "Phone numbers require at least 10 digits.";
                }
            }
            
            return true;
        }
        
        , displayWarning: function(field, warning){
            if(!field.parents('.control-group').hasClass('error')){
                field.parents('.control-group').addClass('error');
                field.parents('.controls').append('<span class="help-inline">'+warning+'</span>');
            }
        }
        
        , removeWarning: function(field){
            field.parents('.control-group').removeClass('error');
            field.parents('.controls').find('.help-inline').remove();
        }
    }


    /* Required field PLUGIN DEFINITION
     * ========================= */

    $.fn.requiredFields = function ( option ) {
        return this.each(function () {
            var $this = $(this)
                , data = $this.data('requiredFields')
                , options = typeof option == 'object' && option
            if (!data) $this.data('requiredFields', (data = new RequiredFields(this, options)))
        })
    }

    $.fn.requiredFields.Constructor = RequiredFields

    $.fn.requiredFields.defaults = {}

}(window.jQuery);