<?php

/**
 * To define Message
 */
return [
    'I0001' => ":tbl_name is inserted.", //display after insert
    'I0002' => ":tbl_name has been updated.", //display after update
    'I0003' => ":tbl_name has been deleted.", //display after delete
    'I0004' => ":object_name has been cancelled.",
    'I0005' => ":tbl_name is retrieved.", //display after retrieved

    'W0001' => "This terminal is current used in sales.", //delete terminal when sales status is still pending.
    'W0002' => "This category is current used in sales and child category .", //delete terminal when sales status is still pending.
    'W0003' => "This product is current used in saledetails and damageAndLoss .", //delete terminal when sales status is still pending.

    'W0004' => "There is stock in  this warehouse .So currently can not delete.", //
    'W0007' => "Invoice Number exists in Sale Return.So currently can not cancel this invoice.", //
    'W0005' => "Not Enough Transfer Amount", //Transfer from warehouse to other
    'W0006' => ":product_name is not stored any warehouse",

    'E0001' => "An error occurred during database processing. Please contact your system administrator.", //something db error occured
    'E0002' => "User number does not exist.",//login user id not found in db
    'E0003' => "User number and password do not match.",//login password is wrong
    'E0004' => "Cashier staff can't use this system.",
    'E0005' => "Kitchen staff can't use this system.",
    'E0006' => "Waiter staff can't use this system.",
    
    'E0007' => "required pwd",
    'E0008' => "Please fill password with one digit and one special character.",
    
    'A0009' => "Are you sure to delete?",
    'E00010' => "Sale staff can't use this system.",
    'E00011' => "Sorry, your license is expired, you can't use now.",
    'E00012' => "This company has no company admin role to login.",
    'E00013' => "Unauthorized"


];
