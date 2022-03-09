//
//  PaymentRepository.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

///
/// In the PaymentRepository invoke the XpaySDK methods
/// to pay or make others payment related operations
///

import XPaySDK

class PaymentRepository: PaymentRepositoryProtocol {

    private var xPay: XPay?
    
    func loadSettings() {
        // Init the XPay class with the provided API Secret Key
        do {
            xPay = try XPay(secretKey: XPayConstants.SECRET_KEY)
        } catch {
            print("Jailbroken Device")
        }
    }
    
    // Pay with front office page
    func pay(_ parent: UIViewController, codTrans: String, amount: Int, handler: @escaping PaymentRepositoryProtocol.QPHandler) {
        // Create the API request using the provided Alias
        let request = ApiFrontOfficeQPRequest(alias: XPayConstants.ALIAS, codTrans: codTrans, currency: CurrencyUtilsQP.EUR, amount: amount)
        // Set the environment
        xPay?._FrontOffice.SelectedEnvironment = XPayConstants.ENVIRONMENT
        xPay?._FrontOffice.paga(request, navigation: true, parentController: parent, completionHandler: handler)
    }
    
    // Pay with front office Safari View Controller
    func paySafari(_ parent: UIViewController, codTrans: String, amount: Int, handler: @escaping PaymentRepositoryProtocol.QPHandler) {
        // Create the API request using the provided Alias
        let request = ApiFrontOfficeQPRequest(alias: XPayConstants.ALIAS, codTrans: codTrans, currency: CurrencyUtilsQP.EUR, amount: amount)
        // Set the environment
        xPay?._FrontOffice.SelectedEnvironment = XPayConstants.ENVIRONMENT
        xPay?._FrontOffice.pagaSafari(request: request, parentController: parent) {(resp: ApiFrontOfficeQPResponse?) in
            handler(resp!)
        }
    }
    
    func requestNonce(_ parent: UIViewController, codTrans: String, amount: Int, card: CardFormMulti, handler: @escaping PaymentRepositoryProtocol.NonceHandler) {
        // Create Nonce using the card form component
        do {
            try card.createNonce(parent: parent, secretKey: XPayConstants.SECRET_KEY, alias: XPayConstants.ALIAS, environment: XPayConstants.ENVIRONMENT, amount: amount, currency: CurrencyUtils.EUR, codTrans: codTrans, handler: handler)
        } catch XPayError.JailbrokenDevice {
            print("Jailbroken Device")
        } catch CardException.INVALID_CARD {
            print("Invalid data")
        } catch let error {
            print(error)
        }
    }
    
    func reportOrders(from: Date, to: Date, handler: @escaping PaymentRepositoryProtocol.OrdersHandler) {
        // Create the API orders report request using Alias
        let ordersRequest = ApiReportOrdiniRequest(alias: XPayConstants.ALIAS, from: from, to: to, channel: .all, statuses: [.authorized, .unauthorized, .refunded, .canceled], codTrans: "")
        // Set the environment
        xPay?._BackOffice.SelectedEnvironment = XPayConstants.ENVIRONMENT
        xPay?._BackOffice.reportOrdini(ordersRequest, completionHandler: handler)
    }
}
