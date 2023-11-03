//
//  HomeInteractorProtocol.swift
//  XPay SDK Sample
//
//  Created by Softeam Spa on 11/10/2018.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import XPaySDK

protocol HomeInteractorProtocol {
    func getAppleRequest(amount: Int, codTrans: String) -> ApplePayRequest
}
