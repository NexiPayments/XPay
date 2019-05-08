//
//  ConfirmPresenter.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit
import XPaySDK

class ConfirmPresenter: ConfirmPresenterProtocol {
    
    private var view: ConfirmViewProtocol?
    
    private var paymentRepo: PaymentRepositoryProtocol?
    
    init(_ view: ConfirmViewProtocol, paymentRepo: PaymentRepositoryProtocol = PaymentRepository()) {
        self.view = view
        self.paymentRepo = paymentRepo
    }
    
    // Create the Nonce using the PaymentRepository
    func payNow(_ parent: UIViewController) {
        if let vc = view as? ConfirmViewController {
            let title = "Error Nonce"
            var message: String?
            // Passing the payment details
            paymentRepo?.requestNonce(parent, codTrans: vc.codTrans!, amount: vc.amount!, card: vc.card!, handler: { (response, error) in
                if error != nil {
                    message = error!.Error.Message
                } else {
                    if let nonceResponse = response {
                        if nonceResponse.IsSuccess {
                            // If the Nonce was created go to result page
                            self.view?.goToResult(codTrans: vc.codTrans!, amount: vc.amount!)
                            ///
                            /// HERE IN YOUR APPLICATION
                            /// YOU MUST USE THE NONCE TO MAKE THE SERVER TO SERVER PAYMENT
                            ///
                        } else {
                            message = response!.Error.Message
                        }
                    } else {
                        // User has canceled the 3D Secure payment
                        message = "Payment canceled by user"
                    }
                }
                // Else display an alert with the error
                if message != nil {
                    self.view?.displaySimpleAlert(title: title, message: message!)
                }
            })
        }
        
    }
}
