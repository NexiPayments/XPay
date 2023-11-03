//
//  CardInputPresenter.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import XPaySDK

class CardInputPresenter: CardInputPresenterProtocol {
    
    private var view: CardInputViewProtocol?
    
    init(_ view: CardInputViewProtocol?) {
        self.view = view
    }
    
    // Perform the segue with details
    func goToConfirm(_ view: ConfirmViewProtocol, card: CardFormMulti, amount: Int, codTrans: String) {
        if let vc = view as? ConfirmViewController {
            vc.card = card
            vc.amount = amount
            vc.codTrans = codTrans
        }
    }
}
