//
//  CardInputPresenterProtocol.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import XPaySDK

protocol CardInputPresenterProtocol {
    func goToConfirm(_ view: ConfirmViewProtocol, card: CardFormMulti, amount: Int, codTrans: String)
}
