/**
* NG Responsive Tables v1.0
* Inspiration: http://css-tricks.com/examples/ResponsiveTables/responsive.php
* Author: Tomislav Matijević
* List of functions:
*   - targetTable: Searches for each table row , find td and take its current index.
*      Apply to that index same index of table head or td in first table row ( in case there are no table header applied )
*   - checkForTableHead: If there is no table head defined, use td in first table row as table head (prevention mode)
* Config:
* - Adjust paddings
* - On each td there is class named "tdno[index]", so you can modify each td if you need custom padding
*/

;(function ( $ ) {
    $.fn.ngResponsiveTables = function(options) {
        var defaults = {
        smallPaddingCharNo: 5,
        mediumPaddingCharNo: 10,
        largePaddingCharNo: 15
        },
        $selElement = this,
        ngResponsiveTables = {
            opt: '',
            dataContent: '',
            globalWidth: 0,
        init: function(){
            this.opt = $.extend( defaults, options );
            ngResponsiveTables.targetTable();
        },
        targetTable: function(){
            var that = this;
            $selElement.find('tr').each(function(){
                $(this).find('td').each(function(i, v){
                    that.checkForTableHead( $(this), i );
                    $(this).addClass('tdno' + i);
                });
            });
        },
        checkForTableHead: function(element, index){
            if( $selElement.find('th').length ){
                this.dataContent = $selElement.find('th')[index].textContent;
            }else{
                this.dataContent = $selElement.find('tr:first td')[index].textContent;
            }
            // This padding is for large texts inside header of table
            // Use small, medium and large paddingMax values from defaults to set-up offsets for each class
            if( this.opt.smallPaddingCharNo > $.trim(this.dataContent).length ){
                element.addClass('small-padding');
            }else if( this.opt.mediumPaddingCharNo > $.trim(this.dataContent).length ){
                element.addClass('medium-padding');
            }else{
                element.addClass('large-padding');
            }
            element.attr('data-content', this.dataContent);
        }
    };

    $(function(){
        ngResponsiveTables.init();
    });
        return this;
    };

}( jQuery ));
