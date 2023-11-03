//
//  HomeInteractor.swift
//  XPay SDK Sample
//
//  Created by Softeam Spa on 11/10/2018.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import XPaySDK

class HomeInteractor: HomeInteractorProtocol {
    
    func getAppleRequest(amount: Int, codTrans: String) -> ApplePayRequest {
        let request = ApplePayRequest(merchantId: XPayConstants.Apple.MERCHANT_ID, secretKey: XPayConstants.SECRET_KEY, alias: XPayConstants.ALIAS, displayName: XPayConstants.Apple.DISPLAY_NAME, amount: amount, currency: "EUR", country: "IT", codTrans: codTrans)
        request.SelectedEnvironment = XPayConstants.ENVIRONMENT
        request.ShippingFields = true
        request.BillingFields = true
        return request
    }
}
