//
//  ResultPresenter.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit

class ResultPresenter: ResultPresenterProtocol {
    
    private var view: ResultViewProtocol?
    
    private var paymentRepository: PaymentRepositoryProtocol?
    
    init(_ view: ResultViewProtocol, paymentRepository: PaymentRepositoryProtocol = PaymentRepository()) {
        self.view = view
        self.paymentRepository = paymentRepository
    }
    
    func donePayment() {
        view?.goToRoot()
    }
    
    func reportOrders() {
        // Orders date interval (from -> to)
        let from = Date()
        let to = Date()
        // Display an alert on API result
        paymentRepository?.reportOrders(from: from, to: to, handler: { (response,error) in
            var title = "Error Report Orders"
            var message: String?
            if error != nil {
                message = error!.Error.Message
                self.view?.showAlert(title: title, message: message!, handler: nil)
            } else {
                if response!.IsSuccess {
                    title = "Report orders successful"
                    // Join list of orders
                    message = "List of orders: \(response!.Reports!.map({$0.Name!}).joined(separator: "\n")))"
                    self.view?.showAlert(title: title, message: message!, handler: { action in
                        self.view?.goToRoot()
                    })
                } else {
                    message = response!.Error.Message
                    self.view?.showAlert(title: title, message: message!, handler: nil)
                }
            }
        })
    }
}
