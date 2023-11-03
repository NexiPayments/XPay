//
//  XPayConstants.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import XPaySDK

///
/// Replace these constants
/// with the values available in the Test Area (https://ecommerce.nexi.it/area-test)
/// or use the default iOS settings panel
///
struct XPayConstants {
    static var ALIAS = ""
    static var SECRET_KEY = ""
    static var ENVIRONMENT = EnvironmentUtils.Environment.test
    
    struct Apple {
        static var MERCHANT_ID = ""
        static var DISPLAY_NAME = ""
    }
}
