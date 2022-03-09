//
//  ConfirmViewController.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit
import XPaySDK

class ConfirmViewController: UIViewController, ConfirmViewProtocol {
    
    var card: CardFormMulti?
    var amount: Int?
    var codTrans: String?
    
    private var presenter: ConfirmPresenterProtocol?
    
    @IBOutlet weak var labelAmount: UILabel!
    
    override func viewDidLoad() {
        presenter = ConfirmPresenter(self)
        // Update payment details
        labelAmount.text = amount?.parseAmount()
    }
    
    @IBAction func payNow(_ sender: UIButton) {
        presenter?.payNow(self)
    }
    
    // Display simple alert with custom title and message
    func displaySimpleAlert(title: String, message: String) {
        let alert = UIAlertController(title: title, message: message, preferredStyle: .alert)
        
        alert.addAction(UIAlertAction(title: "Ok", style: .cancel, handler: nil))
        
        DispatchQueue.main.sync {
            self.present(alert, animated: true)
        }
    }
    
    // Go to result page
    func goToResult(codTrans: String, amount: Int) {
        let resultController = storyboard?.instantiateViewController(withIdentifier: "resultView") as! ResultViewController
        resultController.amount = amount
        present(UINavigationController(rootViewController: resultController), animated: true, completion: {
            // After that pop to root the current navigation controller
            self.navigationController?.popToRootViewController(animated: true)
        })
    }
}
