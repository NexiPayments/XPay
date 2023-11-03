//
//  ConfirmViewProtocol.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import XPaySDK

protocol ConfirmViewProtocol {
    func displaySimpleAlert(title: String, message: String)
    func goToResult(codTrans: String, amount: Int)
}
