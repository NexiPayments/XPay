//
//  ResultViewProtocol.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit

protocol ResultViewProtocol {
    /// Handlers
    typealias AlertHandler = ((UIAlertAction) -> Void)?
    
    func goToRoot()
    func showAlert(title: String, message: String, handler: AlertHandler)
}
