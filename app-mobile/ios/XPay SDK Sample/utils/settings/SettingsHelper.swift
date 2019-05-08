//
//  SettingsHelper.swift
//  XPay SDK Sample
//
//  Created by Softeam Spa on 08/11/2018.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import Foundation
import XPaySDK

class SettingsHelper {
    struct SettingsBundleKeys {
        static let Alias = "alias_preference"
        static let SecretKey = "secret_key_preference"
        static let Environment = "environment_preference"
        static let MerchantId = "merchant_id_preference"
        static let DisplayName = "display_name_preference"
    }
    
    // Setup Alias and Secret Key
    class func setMerchantSettings() {
        XPayConstants.ALIAS = UserDefaults.standard.string(forKey: SettingsBundleKeys.Alias) ?? ""
        XPayConstants.SECRET_KEY = UserDefaults.standard.string(forKey: SettingsBundleKeys.SecretKey) ?? ""
    }
    
    // Setup global environment
    class func setEnvironment() {
        let environmentValue = UserDefaults.standard.integer(forKey: SettingsBundleKeys.Environment)
        XPayConstants.ENVIRONMENT = EnvironmentUtils.Environment.init(rawValue: environmentValue) ?? .test
    }
    
    // Setup Apple Pay fields
    class func setApplePay() {
        XPayConstants.Apple.MERCHANT_ID = UserDefaults.standard.string(forKey: SettingsBundleKeys.MerchantId) ?? "merchant.SofteamMerchant"
        XPayConstants.Apple.DISPLAY_NAME = UserDefaults.standard.string(forKey: SettingsBundleKeys.DisplayName) ?? "Nexi Payments Spa"
    }
}
