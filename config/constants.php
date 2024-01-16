<?php

/**
 * Constant file
 *
 * @author
 */
return [
    //Staff Role
    'ADMIN' => 1,
    'COMPANY_ADMIN' => 2,
    'SHOP_ADMIN' => 3,
    //'WAREHOUSE_STAFF' => 4,
    //'SHOP_STAFF' => 4,
    'CASHIER_STAFF' => 4,
    'KITCHEN_STAFF' => 5,
    'WAITER_STAFF' => 6,
    'SALE_STAFF' => 7,

    //Staff Type
    'FULL_TIME' => 1,
    'PART_TIME' => 2,

    //Position
    //'CASHIER' => 1,
    'SYSTEM_ADMIN' => 1,
    'OWNER' => 2,
    'MANAGER' => 3,
    'OPERATION_STAFF' => 4,


    //Gender
    'MALE' => 1,
    'FEMALE' => 2,
    'OTHER' => 3,

    //Matrial Status
    'SINGLE' => 1,
    'MARRIED' => 2,

    //Company License Type
    'STANDALONE_POS' => 1,
    'STANDALONE_POS_INVENTORY' => 2,
    'MULTI_POS' => 3,
    'MULTI_POS_INVENTORY' => 4,

    'DISCOUNT_AMOUNT' => 0,

    //Company License Status Type
    'COMPANY_LICENSE_INACTIVE' => 1,
    'COMPANY_LICENSE_ACTIVE' => 2,
    'COMPANY_LICENSE_EXPIRE' => 3,
    'COMPANY_LICENSE_BLOCK' => 4,

    //Staff Status
    'ACTIVE' => 1,
    'IN_ACTIVE' => 2,
    'DELETED' => 3,

    // //Company License Status
    // 'COMPANY_LICENSE_ACTIVE' => 1,
    // 'COMPANY_LICENSE_EXPIRE' => 2,

    //Shop Type
    'RETAILS_SHOP' => 1,
    'RESTAURANT_SHOP' => 2,

    //PRODUCT Status
    'PRODUCT_ACTIVE' => 1,
    'PRODUCT_IN_ACTIVE' => 2,
    'PRODUCT_DELETED' => 3,
    //pagination
    'WAREHOUSE_PAGINATION' => 10,
    'SHOP_PAGINATION' => 10,
    'TERMINAL_PAGINATION' => 10,
    'CATEGORY_PAGINATION' => 10,
    'PRODUCT_PAGINATION' => 10,
    'STOCK_PAGINATION' => 10,
    'SALE_PAGINATION' => 10,
    'SALE_RETURN_PAGINATION' => 10,
    'DAMAGE_LOSS_PAGINATION' => 10,
    'STAFF_PAGINATION' => 10,
    'REPORT_PAGINATION' => 10,
    'COMPANY_PAGINATION' => 10,
    'COMPANY_LICENSE_PAGINATION' => 10,
    'ORDER_PAGINATION' => 10,

    //invoice status for sales
    'INVOICE_PENDING' => 1,
    'INVOICE_CONFIRM' => 2,
    'INVOICE_CANCELLED' => 3,

    //order status for order

    //'ORDER_ACCEPT' => 1,  // cashier
    'ORDER_CREATE' => 1, // waiter
    'ORDER_CONFIRM' => 2, // kitchen
    'ORDER_INVOICE' => 3, // cashier

    //country code for product
    'COUNTRY_CODE' => 883,

    //'BASE_PATH' => 'C:\Program Files',
    //'BASE_PATH' => 'D:\POS_Images',

    // 'BASE_STORAGE_HOST' => 'http://storage.local/',

    //staff path for saving image
    'STAFF_PATH' => '/uploads/staffs',

    //product path for saving image
    'PRODUCT_PATH' => 'uploads/products',
    'COMPANY_PATH' => '/uploads/company',

    //stock inout_flg
    'STOCK_IN_FLG' => 1,
    'STOCK_OUT_FLG' => 2,

    //Active On/Off
    'ACTIVE_FLG_ON' => '1',
    'ACTIVE_FLG_OFF' => '0',

    //DelFlgON/OFF
    'DEL_FLG_ON' => '1',
    'DEL_FLG_OFF' => '0',

    'FRM_SALE_RETURN' => '1',
    'FRM_DAMAGE_LOSS' => '2',

    //Damage or Loss
    'DAMAGE' => '1',
    'LOSS' => '2',

    'DAMAGE_CHECK_FLG_ON' => 'on',

    'DAMAGE_LOSS_SEARCH_SHOP' => 1,
    'DAMAGE_LOSS_SEARCH_WAREHOUSE' => 2,

    'RESTAURANT_TABLE_FREE' => 1,
    'RESTAURANT_TABLE_ORDER' => 2,


    'ORDER_DETAILS_CREATE' =>1, //Waiter Create
    'ORDER_DETAILS_CONFIRM' => 2,  // Kitchen Confirm

    'NOTIFICATION_LOW_STOCK' => 1,
    'NOTIFICATION_DAMAGE_LOSS' => 2,
    'NOTIFICATION_SALE_RETURN' => 3,
    'NOTIFICATION_NEW_PRODUCTS' => 4,
    'NOTIFICATION_COMPANY_LICENSE_EXPIRE' => 5


];
