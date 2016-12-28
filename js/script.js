/**
 * Created by user on 27.12.2016.
 */
function inputize() {
    var input = $('<input>', {
        type:'text',
        name:'price[' + $(this).attr('data-price-id') +']',
        value: $(this).html(),
        class:'priceContainer'
    });
    $(this).html(input);
}
$(document).on('dblclick','.inputable',function(e){
    inputize.call(this);
    e.stopPropagation();
    return false;
});
$(document).ready(function() {
    $('.inputable.modified').each(function(){
        inputize.call(this);
    });
    $('.listForm').submit(function(){
        return confirm('Будет создан новый список цен, продолжить?');
    });
});