/**
 * Upsell notice for theme
 */
( function( $ ) {
 // Add Upgrade Message
 if ('undefined' !== typeof prefixL12n) {
 upsell3 = $('<a class="prefix-upsell-link3"></a>')
 .attr('href', prefixL12n.prefixURL3)
 .attr('target', '_blank')
 .text(prefixL12n.prefixLabel3)
 .css({
 'display' : 'inline-block',
 'color' : 'rgb(84, 84, 84)',
 'text-transform' : 'uppercase',
 'margin-top' : '6px',
 'margin-bottom' : '6px',
 'padding' : '6px 6px',
 'font-size': '14px',
 'letter-spacing': '1px',
 'line-height': '1.5',
 'clear' : 'both',
 'width' : '100%',
 'text-align' : 'center',
 'border' : '1px solid #D03232'
 
 })
 ;
 setTimeout(function () {
 $('#customize-info 	').append(upsell3);
 }, 200);
 // Remove accordion click event
 $('.prefix-upsell-link2').on('click', function(e) {
 e.stopPropagation();
 });
 }
} )( jQuery );


