<?php
// cartTotal() {
//                 cartto = this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toLocaleString();

//                 return cartto;
//             }


// <!-- get base_discount() {
//                 if( 0 < this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toLocaleString() < 10000){
//                        return 'no discount'
//                 }else if( 10000<= this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toLocaleString()<10000){
//                     return 'me discount'

//                 }else if( this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toLocaleString() <10000){
//                     return 500

//                 }else {
//                     return 0
//                 } -->

$cartto = 10000;

// get base_discount(x){
//         if( 10000 < x < 20000){
//                        return 'no discount'
//                 }if( 20000 <= x < 30000){
//                     return 'me discount'

//                 }if( 30000 < x){
//                     return 'me discount 2'
//                 }else {
//                     return 0
//                 } 
//             }

$x=10000;


    switch($x){
        case $x < 10000:
            return 'no discount'; break;
        case $x < 20000:
        return '10%'; break;
        case $x < 30000:
        return '15%'; break;
        case $x > 30000:
        return '20%'; break;
    }


echo($x);

?>