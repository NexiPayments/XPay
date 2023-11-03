//
//  HomeViewProtocol.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import XPaySDK

protocol HomeViewProtocol {
    
    /// Handlers
    typealias FrontOfficeHandler = () -> ()
    
    func refreshProductList(products: [Product])
    func refreshTotalAmount(amount: Int)
    func displaySimpleAlert(title: String, message: String)
    func payWithApple(request: XPaySDK.ApplePayRequest, handler completion: @escaping XPaySDK.ApplePayViewController.ApplePayHandler) throws
    func goToResult(codTrans: String, amount: Int)
    func showPaymentOptionDialog(title: String, message: String)
}
