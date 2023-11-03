//
//  CardInputViewController.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit
import XPaySDK

class CardInputViewController: UITableViewController, CardInputViewProtocol {
    
    private var presenter: CardInputPresenterProtocol?
    
    @IBOutlet weak var cardForm: CardFormMulti!
    
    var amount: Int?
    var codTrans: String?
    
    override func viewDidLoad() {
        presenter = CardInputPresenter(self)
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if segue.identifier == "doneInputSegue" {
            if let confirmController = segue.destination as? ConfirmViewProtocol {
                // Go to confirm page passing the payment details
                presenter?.goToConfirm(confirmController, card: cardForm, amount: amount ?? 0, codTrans: codTrans ?? "")
            }
        }
    }
}
