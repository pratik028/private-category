jQuery(document).ready(function($){
    
    var struct = $('body').find('#pc-make-primary-wrapper').html(); // Mark Primary/Primary text
        
    // Event fired when taxonomy checkbox is checked/un-checked
    $(document).find('.postbox .categorydiv input[type="checkbox"]').change(function(){

        if(this.checked) {
            if($(this).parents('li').find('.pc-make-primary-term').length <= 0) {
                $(this).parents('li').append(struct);
                if($(this).parents('ul').find('input:checkbox:checked').length == 1) {
                    var value = $(this).val();
                    $(this).parents('li').find('.pc-make-primary-term').addClass('hidden');
                    $(this).parents('li').find('.pc-primary-term').removeClass('hidden').addClass('active');
                    $(this).parents('.categorydiv').find('input.pc-primary-term').val(value);
                }
            }
        } else {
            if($(this).parents('li').find('.pc-make-primary-term').length > 0) {
                $(this).parents('li').find('.pc-make-primary-term').remove();
                $(this).parents('li').find('.pc-primary-term').remove();
            }
        }

    });

    // Evend fired when taxonomy term is marked as Primary
    $(document).on('click', 'a.pc-make-primary-term', function(e) {

        e.preventDefault();
        $(this).parents('ul').find('.pc-primary-term.active').removeClass('active').addClass('hidden').prev('a.pc-make-primary-term.hidden').removeClass('hidden');
        $(this).addClass('hidden').parent().find('.pc-primary-term').removeClass('hidden').addClass('active');
        var primary_term_id = $(this).parents('li').find('input[type="checkbox"]').val();
        $(this).parents('.categorydiv').find('.pc-primary-term').val(primary_term_id);
        return false;

    });
    
    $('.categorydiv').each( function(){
        
        var this_id = $(this).attr('id'), taxonomyParts, taxonomy;
        taxonomyParts = this_id.split('-');
        taxonomyParts.shift();
        taxonomy = taxonomyParts.join('-'); //get taxonomy name
        
        // Add Input hidden field below taxonomy boxes
        var hidden_fields = jQuery('#pc-primary-'+taxonomy+'-input').html();
        $(this).append(hidden_fields);
        
        // Check if primary term is set, if yes make Primary text visible
        var selected_primary_id = jQuery(this).find('#pc-primary-'+taxonomy+'-term').val();
        $(this).find('input:checkbox:checked').each(function(){
                var value = $(this).val();
                if(selected_primary_id === value) {
                    $(this).parents('li').append(struct)
                        .find('.pc-make-primary-term').addClass('hidden')
                        .next('.pc-primary-term.hidden').removeClass('hidden').addClass('active');
                } else {
                    $(this).parents('li').append(struct);
                }

        });

    });
    
});