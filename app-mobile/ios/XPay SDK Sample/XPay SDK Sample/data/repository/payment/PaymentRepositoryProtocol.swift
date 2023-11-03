//
//  PaymentRepositoryProtocol.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit
import XPaySDK

protocol PaymentRepositoryProtocol {
    
    /// Handlers
    typealias QPHandler = (ApiFrontOfficeQPResponse) -> ()
    typealias NonceHandler = (ApiCreaNonceResponse?, ApiErrorResponse?) -> ()
    typealias OrdersHandler = (ApiReportOrdiniResponse?, ApiErrorResponse?) -> ()
    
    /// Start create instace of XPay class
    func loadSettings()
    
    /// Payment operations methods
    func pay(_ parent: UIViewController, codTrans: String, amount: Int, handler: @escaping QPHandler)
    func paySafari(_ parent: UIViewController, codTrans: String, amount: Int, handler: @escaping QPHandler)
    func requestNonce(_ parent: UIViewController, codTrans: String, amount: Int, card: CardFormMulti, handler: @escaping NonceHandler)
    func reportOrders(from: Date, to: Date, handler: @escaping OrdersHandler)
}
