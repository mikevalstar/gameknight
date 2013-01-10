$(function(){

    $('.linkrow,.linkcol').click(function(e){
        e.preventDefault();
        window.location = $(this).attr('data-link');
    });

    var simplefilter = function(){
        var target = $( $(this).attr('data-target') );
        if($(this).val() == ''){
            target.find('tbody tr').show();
        }else{
            target.find('tbody tr').hide();
            target.find('tbody tr:icontains("' + $(this).val() + '")').show();
        }
    };
    $('.simplefiltertable').keyup(simplefilter).change(simplefilter);
    $('.datepicker').datepicker({format: 'yyyy-mm-dd'});
    
    // Submit for another form
    $("button[data-submitfor],input[data-submitfor],a[data-submitfor]").click(function(e){
        e.preventDefault();
        $($(this).attr('data-submitfor')).submit();
    });
    
    // Form required fields
    $('form').requiredFields();
    
    // Postback
    $('select.postback').change(function(){
        $(this).parents('form').first().submit();
    });
    
    // tooltips
    $('.tt').tooltip();
    
    // User type ahead
    $('.filluseremail').attr('autocomplete', 'off').typeahead({source: function(typeahead, search){
            $.ajax({
                url: '/users',
                data: {filter: search, rows: 20, emailonly: true},
                success: function(data){
                    var options = [];
                    $(data.results).each(function(key, itm){
                        options.push({'id':itm.user_pk, 'value': itm.email});
                    });
                    typeahead.process(options);
                },
                accepts: {json: "application/json"},
                dataType: "json"
            });
        }
        , onselect: function(obj) {
            if(this.$element.attr('data-idfill')){
                $(this.$element.attr('data-idfill')).val(obj.id);
            }
        }
        , minLength: 2
        , items: 12 });
        
    $('.fillgamename')
        .change(function(e){ $($(this).attr('data-idfill')).val('') })
        .attr('autocomplete', 'off')
        .typeahead({source: function(typeahead, search){
            $.ajax({
                url: '/games',
                data: {filter: search, rows: 20},
                success: function(data){
                    var options = [];
                    $(data.results).each(function(key, itm){
                        options.push({'id':itm.game_pk, 'value': itm.game_name});
                    });
                    typeahead.process(options);
                },
                accepts: {json: "application/json"},
                dataType: "json"
            });
        }
        , onselect: function(obj) {
            if(this.$element.attr('data-idfill')){
                $(this.$element.attr('data-idfill')).val(obj.id);
            }
        }
        , minLength: 2
        , items: 12 });
        
  // konami code
  var state = 0, konami = [38,38,40,40,37,39,37,39,66,65];
  $(window).keydown(function(e){
    if ( e.keyCode == konami[state] ) state++;
      else state = 0;  
      if ( state == 10 ) {
        $('.konami').toggle();
      }
  });
  $('.datepicker').datepicker();
  $('.timepicker').timepicker({minuteStep: 5, defaultTime: 'value'});

  // if the hash is set select the tab
  if (window.location.hash.indexOf('tab') != -1) {
    $('a[href="'+window.location.hash.replace('tab','')+'"]') .click();
  }
  // Add hash when tab is selected
  $('a[data-toggle]').on('click', function(){
    window.location.hash = "tab" + $(this).attr('href').replace('#','');
  });
});

// icontains insensitive contains function
jQuery.expr[":"].icontains = jQuery.expr.createPseudo(function(arg) {
    return function( elem ) {
        return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});